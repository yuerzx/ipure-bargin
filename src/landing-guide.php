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

$opp = array(1, 2, 3, 4, 5, 6, 2, 1, 4, 5, 6, 4, 3);
$rand = rand(0, 12);
//get the random page id
$land_page_id = $opp[$rand];
$get = FALSE;

$req_uri = explode('/',$_SERVER["REQUEST_URI"], -1);
$req_uri = implode('/', $req_uri);
//$jssdk = new JSSDK("wx2d39a6c422ad663c", "e339b975f47c4a16b2b4b41f10fb5ef1");

// 测试id
$jssdk = new JSSDK("wxae45c193de06d5a4", "f9a61bd7a83a5302a9960a84eb9e8ba3");
$user_info = new stdClass();


if(isset($_GET['code']) && isset($_GET['state']) && strlen($_GET['code']) == 32){
    $get = TRUE;
    $code = sanitize_text_field($_GET['code']);
    $state = sanitize_text_field($_GET['state']);
}

if(!empty($_COOKIE['openid'])){
    //if we are already get all the information, then time to retrive from database
    $user_info = new stdClass();
    $user_info-> nickname   = $_COOKIE['nickname'];
    $user_info-> user_id    = $_COOKIE['user_id'];
    $user_info-> openid     = $_COOKIE['openid'];
    $user_info-> country    = $_COOKIE['country'];
    $user_info-> city       = $_COOKIE['city'];
    $user_info-> headimgurl = $_COOKIE['headimgurl'];
    $string = $user_info->user_id."oneu";
    $ver_code = substr(md5($string),-9);
    if( $ver_code == $_COOKIE['ver_code_user_data']) {

        //if true then we do nothing about it
    }else{
        //if the cookies has been modified by user
        //we use blank information instead
        $user_info-> nickname   = "万友澳洲 测试账号";
        $user_info-> user_id    = 11;
        $user_info-> openid     = "00";
        $user_info-> country    = "澳大利亚";
        $user_info-> city       = "墨尔本";
        $user_info-> headimgurl = "https://oneu.me/wp-content/themes/OneUni/images/common/logo-withwords.png";
    }
}elseif($get && empty($_COOKIE['openid'])){
    //if customers are new, then we are ready for next step.
    $user_info = $jssdk->getPageUserInfo($code);
    if(!empty($user_info->openid)){
        // if successfully get the user information, then we are able to process.
        $cookies->set("nickname",   $user_info->nickname,30,"days");
        $cookies->set('user_id',    $user_info->user_id,30,"days" );
        $cookies->set('openid',     $user_info->openid, 30,"days" );
        $cookies->set('city',       $user_info->city,30,"days" );
        $cookies->set('country',    $user_info->country,30,"days" );
        $cookies->set('headimgurl', $user_info->headimgurl,30,"days" );
        $string = $user_info->user_id."oneu";
        $ver_code = substr(md5($string),-9);
        $cookies->set('ver_code_user_data', $ver_code, 30, "days");
    }else{
        //relocated to the login page, to get code again.
        wp_redirect("wechat-landing.php");
        exit;
    }
}else{
    //without get and without the session, logout
    $user_info = new stdClass();
    $user_info-> nickname   = "万友澳洲 测试账号";
    $user_info-> user_id    = 11;
    $user_info-> openid     = "00";
    $user_info-> country    = "澳大利亚";
    $user_info-> city       = "墨尔本";
    $user_info-> headimgurl = "https://oneu.me/wp-content/themes/OneUni/images/common/logo-withwords.png";
}


$time = system_time();
$gift_card_list = $jssdk->get_giftcard_by_id($user_info->user_id);
if($gift_card_list){
    //if there is a gift under user before
    $time_diff = $time - $gift_card_list[0]["time_stamp"];
    //if the last time is 8 hours before, then we choose to close up the shack
    if($time_diff <= 28800){
        //smaller than 8 hours
        $gift_id = $gift_card_list[0]["gift_id"];
        $time = $gift_card_list[0]['time_stamp'];
    }else{
        $gift_id = $jssdk->create_giftcard($user_info->user_id,$land_page_id,$time);
    }
}else{
    //if there is no giftcard under user before, then we create one for user
    $gift_id = $jssdk->create_giftcard($user_info->user_id,$land_page_id,$time);
}

$ver_code = substr(md5($gift_id.$time."oneu"),-6);
$url = "//".$_SERVER["HTTP_HOST"] . $req_uri."/gift-voucher.php?gift_id=".$gift_id."&t=".$time."&ver=".$ver_code;

//var_dump($url);
//var_dump($user_info);
wp_redirect( $url);
exit;


