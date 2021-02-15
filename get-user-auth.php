<?php
	ob_start();
	session_start();
    include_once("../../../connect.php");


    $RESPONSE = array();
    $RESPONSE['result'] = "success";

    $d1=mysqli_query($con,"select device_id from app_user_auth where user_id='$_REQUEST[s_id]' and user_type='$_REQUEST[user_type] and college_id='$_REQUEST[college_id]' ");
    
    $d=mysqli_fetch_row($d1);
    $RESPONSE['device_id'] = $d[0];

    echo json_encode($RESPONSE);
?>