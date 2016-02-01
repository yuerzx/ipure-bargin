<?php
/**
 * Created by PhpStorm.
 * User: Han
 * Date: 1/02/2016
 * Time: 2:56 PM
 */

require_once "functions.php";

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