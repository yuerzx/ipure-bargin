<?php
/**
 * Created by PhpStorm.
 * User: Han
 * Date: 1/02/2016
 * Time: 2:56 PM
 */

require_once "functions.php";
require_once "class/jssdk.php";

global $user_class;

echo "Total Discount";
$re = $user_class->get_total_discount(1);
var_dump($re);

echo "Product Price";
$re1 = $user_class->get_product_price(1);
var_dump($re1);

//echo "Insert Data";
//$re2 = $user_class->help_bargin(19, 1);
//var_dump($re2);

echo "Get User By Openid";
$re1 = $user_class->get_user_id_by_openid("oE4-SuFWt2QJAkqskpIuTLBNX8V0");
var_dump($re1);

//echo "Inser New Events";
//$re2 = $user_class->set_new_events(23,2,1);
//var_dump($re2);

echo "Get user starter";
$re3 = $user_class->get_starter_id_by_eventid(1);
var_dump($re3);

$jssdk = new JSSDK();

echo "Get ID";
$id = $jssdk->getUserByOpenID("oSHx9wpKK9IMZqu-oSE7PxZrASAk");
var_dump($id);

$user_info = '{"openid":"oE4-SuOXfupZGk1jWx8IddDUuHUI","nickname":"ðŸ’•MissSunshine","sex":2,"language":"zh_CN","city":"å¢¨å°”æœ¬","province":"ç»´å¤šåˆ©äºš","country":"æ¾³å¤§åˆ©äºš","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/gvt1hEl40ckMWA4wOHWLvHgrIM9iaJhj99lWs8IOLiaQVwUiaZLyPdicmfLQgjdLuW7PEwJicZric4bSIe99kWgFzR2DaPia9CRvwtw\/0","privilege":[]}';
$json = json_decode($user_info);
$token = "OezXcEiiBSKSxW0eoylIeGCkdfp-3f9QIi5IqeL1Tz5Bl3WRS0iXNdRBd7vCBcf8mWoL0KFyGloYiukO9UrkmQ1213-YNlx-lpY4ccYtwkYUiFFO0cG-5UrumQdtiKvhixU6uZOTZbmAgFNNfdzSOA";
$res = $jssdk->getUserInfoJson($token, "oE4-SuOXfupZGk1jWx8IddDUuHUI");
var_dump($res);
$json_res = json_decode($res);
//$res1 = $jssdk->updateUserInfo((string)$json->openid, $json_res);
//var_dump($res1);