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
    $open_id = $json->openid;
    $events_id = $json->events;
    $work_type = $json->work_type;
    // the is safe to process
    global $user_class;
    $user_id = $user_class->get_user_id_by_openid($open_id);
    if($user_id && $work_type == "help"){
        $result = $user_class->check_if_support_status($user_id, $events_id);
        if($result){
            //if the user has been register before
            echo json_encode(array('result' =>"existed"));
        }else{
            //if the user didnt been regsiter before
            $bargin_result = $user_class->help_bargin($user_id, $events_id);
            if($bargin_result['result']){
                echo json_encode(array("amount" => $bargin_result['amount'], "result"=>"succ"));
            }
        }
    }elseif($user_id && $work_type == "create"){
        $product_id = $user_class->get_product_id_by_eventid($events_id);
        if($product_id){
            $check_existed = $user_class->check_events_status($user_id, $product_id);
            if($check_existed){
                //The code is existed
                echo json_encode(array('result' =>"existed"));
            }else{
                //The code is not existed before
                $insert_id = $user_class->set_new_events($user_id, $product_id);
                echo json_encode(array("result"=>"succ", "et"=>$insert_id));
            }
        }

    }else{
        echo json_encode(array('result' =>"no-existed"));
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

