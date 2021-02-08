<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$gdcol="gdcol1";
	$doc="document1";

	$RESPONSE = array();
	$RESPONSE['result'] = 'success';
	$RESPONSE['document'] = array();
	$documentdata = array();

	$sql="select * from luckycollegeerp.$doc where s_id='$_REQUEST[s_id]' and status not in ('Not Applicable', 'Permanently N/A') ";
	$result = mysqli_query($con,$sql);
	if(mysqli_num_rows($result) > 0 ) {
		if($d = mysqli_fetch_row($result)) {
			do {
				$documentdata['document_name'] = $d[1];
				$documentdata['file_path'] = $d[3];
				$documentdata['file_type'] = $d[4];
				$documentdata['file_status'] = $d[6];

				array_push($RESPONSE['document'], $documentdata);
			} while($d = mysqli_fetch_array($result));
		}
	}

	echo json_encode($RESPONSE);
?>