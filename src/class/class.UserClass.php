<?php

/**
 * Created by PhpStorm.
 * User: Han
 * Date: 29/06/2015
 * Time: 9:41 AM
 */
class Game_Class
{
    private $wpdb, $table_wechat_user_db;

    public function __construct()
    {
        global $wpdb;
        global $table_wechat_user;
        global $table_wechat_bargin_events;
        global $table_wechat_products;
        //$this->mail = new PHPMailer();
        $this->wpdb = &$wpdb;
        $this->table_wechat_user_db = &$table_wechat_user;
        $this->table_wechat_bargin_events = &$table_wechat_bargin_events;
        $this->table_wechat_products = &$table_wechat_products;
        $this->table_friends_help = $wpdb->prefix.'oneuni_wechat_friends_help';
    }


    public function get_user_info_by_id($id)
    {
        $id = intval($id);
        $query = $this->wpdb->prepare("
            SELECT * FROM $this->table_wechat_user_db WHERE id = %s
        ",
            $id);
        $result = $this->wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_user_info_by_email($email)
    {
        $email = esc_attr($email);
        $query = $this->wpdb->prepare("
            SELECT * FROM $this->table_wechat_user_db WHERE sEmail = %s
        ",
            $email);
        $result = $this->wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_user_game_info_by_phone($phone)
    {
        $phone = esc_attr($phone);
        $query = $this->wpdb->prepare("
            SELECT user_times_total, user_times_played, user_id, user_phone, user_start, user_end, user_score  FROM $this->table_wechat_user_db WHERE user_phone = %s
        ",
            $phone);
        $result = $this->wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_user_id_by_phone($phone)
    {
        $query = $this->wpdb->prepare("
            SELECT id FROM $this->table_wechat_user_db WHERE sPhone = %s
        ",
            $phone);
        $result = $this->wpdb->get_var($query);
        return $result;
    }

    public function get_bargin_events_product_by_eventid($id){
        $query = $this->wpdb->prepare("
            SELECT *
            FROM $this->table_wechat_bargin_events
            JOIN $this->table_wechat_products
            ON $this->table_wechat_bargin_events.`product_id` = $this->table_wechat_products.`p_id`
            JOIN $this->table_wechat_user_db
            ON  $this->table_wechat_bargin_events.`starter_id` = $this->table_wechat_user_db.`user_id`
            WHERE $this->table_wechat_bargin_events.`events_id` = %d
        ",
            $id);
        $result = $this->wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_friends_support_by_eventid($id){
        $id = intval($id);
        $query = $this->wpdb->prepare("
        SELECT *
        FROM $this->table_friends_help
        JOIN $this->table_wechat_user_db
        ON $this->table_friends_help.`user_id` = $this->table_wechat_user_db.`user_id`
        WHERE $this->table_friends_help.`events_id` = %d
        ORDER BY `time_stamp`
        ",
            $id);
        $result = $this->wpdb->get_results($query, ARRAY_A);
        return $result;
    }

    public function send_welcome_email($name, $email)
    {
        //email section
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = "base64";
        $this->mail->isSMTP();                                      // Set mailer to use SMTP
        //$this->mail->Host = '127.0.0.42';  // Specify main and backup SMTP servers
        $this->mail->Host = 'smtpcorp.com';
        $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mail->Username = 'hansun@1230.me';                 // SMTP username
        $this->mail->Password = '998877ccdscas';                           // SMTP password
        $this->mail->SMTPSecure = 'none';                            // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 2525;                                    // TCP port to connect to
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->From = 'info@oneuni.com.au';
        $this->mail->FromName = '万友澳洲';
        $this->mail->addReplyTo('info@oneuni.com.au', '万友澳洲');
        $this->mail->createHeader("\n" . 'MIME-Version: 1.0' . "\n" . 'Content-Type: text/html; charset="UTF-8";' . "\n" . 'Content-Transfer-Encoding: 7bit');
        $this->mail->isHTML(true);
        $link = " https://www.oneuni.com.au/wenbo-ielts-speaking-video/?user=" . $email;
        $this->mail->addAddress($email, $name);     // Add a recipient
        $this->mail->Subject = '文波雅思视频领取地址';
        $this->mail->Body = '<!DOCTYPE Html><meta charset="UTF-8">' . $name . ':<br>欢迎参加<b>文波雅思</b>雅思口语材料大放送！ 您的用户名是：' . $email .
            '<br>视频链接： <a href=' . $link . '>' . $link . '</a> <br> 只需要轻松输入登记的邮箱即可查看，如果有任何疑问，请联系文波雅思官方个人微信： wenbo_tv<br><br><br>全程技术支持，万友澳洲';
        $this->mail->AltBody = $name . ': 欢迎参加<b>文波雅思</b>雅思口语材料大放送 <br>用户名是: ' . $email . '<br>视频地址是:' . $link;

        if (!$this->mail->send()) {
            $error = $this->mail->ErrorInfo;
            $em_ans = array('status' => 'failed', 'error' => $error);
        } else {
            $em_ans = array('status' => 'ok', 'error' => 'none');
        }

        return $em_ans;
    }

}
