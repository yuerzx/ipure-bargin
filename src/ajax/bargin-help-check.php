<?php
/**
 * Created by PhpStorm.
 * User: Han
 * Date: 1/02/2016
 * Time: 10:46 AM
 */
require ("../functions.php");
$json_raw = file_get_contents('php://input');

if($json_raw){
    $json = json_decode($json_raw);
    $user_id = $json->userid;
    $events_id = $json->events;
    $code_hash = $json->code;
    $cert = $user_id*11 + $events_id*33;
    $code_check = code_check($cert, 8);
    //var_dump($code_check);
    if($code_hash == $code_check){
        // the is safe to process
        global $user_class;
        $result = $user_class->check_if_support_status($user_id, $events_id);
        if($result){
            //if the user has been register before
            echo json_encode(array('result' =>"existed"));
        }else{
            //if the user didnt been regsiter before
            $bargin_result = $user_class->help_bargin($user_id, $events_id);
            if($bargin_result['result']){
                echo json_encode(array("amount" => $bargin_result['amount'], "result"=>"succeful"));
            }
        }
    }else{
        // if there is a issue with the certificate
        echo json_encode(array('result' =>"cert"));
    }

}else{
    //if it was not a valuate json
    echo json_encode(array("results"=> "err", "details"=>"Wrong Format"));
}



function validate_json($str=NULL) {
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}

