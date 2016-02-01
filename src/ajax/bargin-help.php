<?php
/**
 * Created by PhpStorm.
 * User: Han
 * Date: 1/02/2016
 * Time: 10:46 AM
 */

$json_raw = file_get_contents('php://input');

if($json_raw){
    $json = json_decode($json_raw);
    echo $json->openid;

}else{
    //if it was not a valuate json
    echo json_encode(array("results"=> false));
}




function validate_json($str=NULL) {
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}