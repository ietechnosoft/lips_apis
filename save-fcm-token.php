<?php
	ob_start();
	session_start();
    include_once("../../../connect.php");


    $RESPONSE = array();
    $RESPONSE['result'] = "success";

    $d1=mysqli_query($con1,"select * from app_user_auth where user_id='$_REQUEST[s_id]' and user_type='$_REQUEST[user_type]' and college_id='$_REQUEST[college_id]' ");
    if(mysqli_num_rows($d1) == 0) {
        mysqli_query($con1, "insert into app_user_auth set user_id='$_REQUEST[s_id]', user_type='$_REQUEST[user_type]', college_id='$_REQUEST[college_id]', device_id='$_REQUEST[device_id]', fcm_token='$_REQUEST[fcm_token]' ");
    } else {
        mysqli_query($con1, "update app_user_auth set fcm_token='$_REQUEST[fcm_token]', device_id='$_REQUEST[device_id]' where user_id='$_REQUEST[s_id]' and user_type='$_REQUEST[user_type]' and college_id='$_REQUEST[college_id]' ");
    }

    echo json_encode($RESPONSE);
?>