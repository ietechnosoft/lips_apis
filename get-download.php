<?php
	ob_start();
	session_start();
    $_SESSION['cursession'] = $_REQUEST['college_session'];
    include_once("../../../connect.php");
    $gdcol="gdcol1";
    $download="download1";

    $RESPONSE = array();
    $RESPONSE['result'] = "success";
    $RESPONSE['download'] = array();
    $downloadFile = array();

    $d1=mysqli_query($con,"select * from $download where f_id like '$_REQUEST[f_id],%' or f_id like '%,$$_REQUEST[f_id],%' or f_id like '%,$_REQUEST[f_id]' or f_id like '$_REQUEST[f_id]'  order by d_id desc");
    
    while($d=mysqli_fetch_row($d1)) {
        $downloadFile['file_name'] = $d[1];
        $downloadFile['file_path'] = $d[2];
        $downloadFile['subject'] = $d[4];

        array_push($RESPONSE['download'], $downloadFile);
    }



    echo json_encode($RESPONSE);
?>