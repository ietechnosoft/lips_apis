<?php
	ob_start();
	session_start();
    $_SESSION['cursession'] = $_REQUEST['college_session'];
    include_once("../../../connect.php");
    $gdcol="gdcol1";
    $fees = "fee_structure1";


    if ($_REQUEST['college_id'] == "2") {
        $gdcol = "gdcol2";
        $fees = "fee_structure2";
    }

    $RESPONSE = array();
    $RESPONSE['result'] = "success";

    $d1=mysqli_query($con,"select * from $gdcol where s_id='$_REQUEST[s_id]' limit 1");
    
    $d=mysqli_fetch_row($d1);
    $RESPONSE['name'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[1])));
    $RESPONSE['name_hindi'] = $d[62];
    $RESPONSE['f_name'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[11])));;
    $RESPONSE['f_name_hindi'] = $d[63];
    $RESPONSE['m_name'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[18])));;
    $RESPONSE['m_name_hindi'] = $d[64];
    $RESPONSE['pic'] = $d[53];
    $RESPONSE['admission_date'] = date_format(date_create($d[54]),'d-F-Y');
    $RESPONSE['admission_number'] = $d[2];
    $RESPONSE['dob'] = date_format(date_create($d[6]),'d-F-Y');
    $RESPONSE['address1'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[8])));;
    $RESPONSE['city'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[9])));;
    $RESPONSE['state'] = trim(str_replace('  ', ' ', str_replace('\t', '', $d[10])));
    $RESPONSE['mobile1'] = $d[27];
    $RESPONSE['mpbile2'] = $d[28];

    $f = mysqli_fetch_row(mysqli_query($con, "select batchyear,course,yearwise from $fees where f_id='$d[33]'"));
    $RESPONSE['batch'] = $f[0];
    $RESPONSE['course'] = $f[1];
    $RESPONSE['year'] = $f[2];

    echo json_encode($RESPONSE);
?>