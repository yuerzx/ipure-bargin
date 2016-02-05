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

$res = $user_class->get_most_discount_ranking();
var_dump($res);