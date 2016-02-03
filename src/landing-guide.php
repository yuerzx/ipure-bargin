<?php
/**
 * Created by PhpStorm.
 * User: Han
 * Date: 14/10/2015
 * Time: 1:59 PM
 */

include("functions.php");
require('class/jssdk.php');
global $cookies;

$get = FALSE;

$req_uri = explode('/',$_SERVER["REQUEST_URI"], -1);
$req_uri = implode('/', $req_uri);
$jssdk = new JSSDK("wx2d39a6c422ad663c", "e339b975f47c4a16b2b4b41f10fb5ef1");

// 测试id
//$jssdk = new JSSDK("wxae45c193de06d5a4", "f9a61bd7a83a5302a9960a84eb9e8ba3");
global $user_class;
$user_info = new stdClass();


if(isset($_GET['code']) && isset($_GET['state']) && strlen($_GET['code']) == 32){
    $get = TRUE;
    $code = sanitize_text_field($_GET['code']);
    $state = sanitize_text_field($_GET['state']);
}

if($get){
    //As long as we get here, there is no need to check
    $user_info = $jssdk->getPageUserInfo($code);
    if(!empty($user_info->openid)){
        // if successfully get the user information, then we are able to process.
        $cookies->set("nickname",   $user_info->nickname,30,"days");
        $cookies->set('user_id',    $user_info->user_id,30,"days" );
        $cookies->set('openid',     $user_info->openid, 30,"days" );
        $cookies->set('city',       $user_info->city,30,"days" );
        $cookies->set('country',    $user_info->country,30,"days" );
        $cookies->set('headimgurl', $user_info->headimgurl,30,"days" );
        $cookies->set('new_login',  1);
        $string = $user_info->open_id."oneu";
        $ver_code = substr(md5($string),-9);
        $cookies->set('ver_code_user_data', $ver_code, 30, "days");

        $res = $user_class->set_user_info($user_info->openid, $user_info);
    }else{
        //relocated to the login page, to get code again.
        $add = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
            //+ "wxae45c193de06d5a4"
            . "wx2d39a6c422ad663c"
            . "&redirect_uri="
            //+ "http%3A%2F%2F127.0.0.1%2FAusway%2Fapp%2Fipure-bargin%2Fsrc%2Flanding-guide.php"
            . "https%3A%2F%2Foneu.me%2Fapp%2Fipure-bargin%2Flanding-guide.php&response_type=code&scope=snsapi_userinfo&state="
            .$events_id
            ."#wechat_redirect";
        wp_redirect($add);
        exit;
    }
}


$time = system_time();


$ver_code = substr(md5($gift_id.$time."oneu"),-6);
$url = "//".$_SERVER["HTTP_HOST"] . $req_uri."/index.php?et=".$state."&ver=".$ver_code;

//var_dump($url);
//var_dump($user_info);
wp_redirect( $url);
exit;


