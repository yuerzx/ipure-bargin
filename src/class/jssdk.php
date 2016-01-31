<?php
class JSSDK {
  private $appId;
  private $appSecret;
  private $wpdb,$table_wechat_user, $table_wechat_giftcard;

  public function __construct($appId, $appSecret) {
    global $wpdb;
    global $table_wechat_user;
    global $table_wechat_products;
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->wpdb = &$wpdb;
    $this->table_wechat_user = &$table_wechat_user;
    $this->table_wechat_giftcard = &$table_wechat_products;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getJSAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?user_info=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&user_info=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  private function getJSAccessToken() {
    // user_info 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("user_info.json"));
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen("user_info.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

  public function getPageUserInfo($code){
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code={$code}&grant_type=authorization_code";
    $res = json_decode($this->httpGet($url));
    if(!empty($res->access_token) && isset($res->access_token)){
      if($this->checkUserExist($res->openid)){
        //check if the user is in database already
        //retreat from database
        $user_info = $this->getUserByOpenID( (string)$res->openid);
        $update_result = $this->updateUserInfo( (string)$res->openid, $res);
        return $res;
      }else{
        //not existed before, I need to creat something
        $this->insertNewUser($res);
        $lastid = $this->wpdb->insert_id;
        $user_info = $this->getUserInfoJson($res->access_token, (string)$res->openid);
        if(!empty($user_info)){
          $user_info->user_id = $lastid;
          $this->updateUserInfo($res->openid, $user_info);
          return $user_info;
        }
      };
    }else{
      return "error";
    }
  }

  private function getUserInfoJson($token,$openid){
    $token = (string)$token;
    $openid = (string)$openid;
    $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
    $res = json_decode($this->httpGet($url));
    return $res;
  }

  private function checkUserExist($openid){
    $query = $this->wpdb->prepare("
            SELECT user_id FROM $this->table_wechat_user WHERE openid = %s
        ",
        $openid);
    $result = $this->wpdb->get_var( $query );
    return $result;
  }

  public function insertNewUser($data){
    if(!isset($data->unionid)){
      $data->unionid = 0;
    }
    $result = $this->wpdb->insert(
        $this->table_wechat_user,
        array(
            'openid'            => $data->openid,
            'access_token'      => $data->access_token,
            'refresh_token'     => $data->refresh_token,
            'join_time_stamp'   => date("Y-m-d H:i:s"),
            'unionid'           => $data->unionid
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );
    $lastid = $this->wpdb->insert_id;
    return $lastid;
  }

  private function updateUserInfo($openid, $data){
    if(!isset($data->sex)) $data->sex = 3;
    if(!isset($data->city)) $data->city = 0;
    if(!isset($data->province)) $data->province = 3;
    if(!isset($data->country)) $data->country = 3;
    if(!isset($data->nickname)) $data->nickname = "";
    if(!isset($data->headimgurl)) $data->headimgurl = "";
    $result = $this->wpdb->update(
        $this->table_wechat_user,
        array(
            'sex' => $data->sex,	// integer (number)
            'nickname' => $data->nickname,
            'city' => $data->city,
            'province' => $data->province,
            'country'  => $data->country,
            'headimgurl'=> $data->headimgurl
        ),
        array( 'openid' => $openid )
    );
    return $result;
  }

  public function getUserByOpenID($openid){
    $query = $this->wpdb->prepare("
            SELECT * FROM $this->table_wechat_user WHERE openid = %s
        ",
        $openid);
    $result = $this->wpdb->get_row( $query, ARRAY_A );
    return $result;
  }

  public function create_giftcard($user_id, $gift_type,$time_stamp){
    $result = $this->wpdb->insert(
        $this->table_wechat_giftcard,
        array(
            'wechat_user_id'  => $user_id,
            'gift_type'       => $gift_type,
            'time_stamp'      => $time_stamp
        ),
        array(
            '%d',
            '%d',
            '%d'
        )
    );
    $lastid = $this->wpdb->insert_id;
    return $lastid;
  }

  public function get_giftcard_by_id($user_id){
    $user_id = intval($user_id);
    $result = $this->wpdb->get_results("
    SELECT gift_id,wechat_user_id,gift_type,time_stamp
    FROM $this->table_wechat_giftcard WHERE
    wechat_user_id = $user_id
    ORDER BY time_stamp DESC
    ", ARRAY_A);
    return $result;
  }

}

