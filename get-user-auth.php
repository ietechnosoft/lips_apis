<?php
	ob_start();
	session_start();
    include_once("../../../connect.php");


    $RESPONSE = array();

    $d1=mysqli_query($con1,"select device_id from app_user_auth where user_id='$_REQUEST[s_id]' and user_type='$_REQUEST[user_type]' and college_id='$_REQUEST[college_id]' ");
    
    if(mysqli_num_rows($d1) == 0) {
        $RESPONSE['result'] = "success";
    } else {
        $d=mysqli_fetch_row($d1);
        if($d[0] == $_REQUEST['device_id']) {
            $RESPONSE['result'] = "success";
        } else {
            $RESPONSE['result'] = "fail";
            $RESPONSE['message'] = "It's look like you have logged in on another device..";
        }
    }

    echo json_encode($RESPONSE);
?>