<?php
//This is the main landing page for No.1 system

//we are now redirecting to a authorize page


include("functions.php");
if(isset($_COOKIE['openid']) && !empty($_COOKIE['openid'])){
    $url = "landing-guide.php";
}else {
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx2d39a6c422ad663c&redirect_uri=https%3A%2F%2Foneu.me%2Fevents%2Fwechat-shack%2Flanding-guide.php&response_type=code&scope=snsapi_userinfo&state=jianfeng#wechat_redirect";
}
wp_redirect($url);
exit;


