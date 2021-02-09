<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$fees="fee_structure1";
	$notice="notice1";
	$gdcol="gdcol1";

if ($_REQUEST['college_id'] == "2") {
	$notice = "notice2";
	$fees = "fee_structure2";
	$gdcol = "gdcol2";
}

	$f[0] = $_REQUEST['f_id'];
	
	$RESPONCE = array();
	$RESPONCE['result'] = "success";
	$notice_board = array();
	$RESPONCE['notice_board'] = array();

	$student=" and (student='All' or (student like '$f[0],%' or student like '%,$f[0],%' or student like '%,$f[0]' or student like '$f[0]'))";

	$sql ="select * from $notice where true ".$student." and student!='' order by noticeno desc";
	$result = mysqli_query($con,$sql);

	if(mysqli_num_rows($result)>0 ) {
		if($v = mysqli_fetch_row($result)) {		
			$j=1;
			do {
				$notice_board['notice_id'] = (int) $v[0];
				$notice_board['notice_number'] = (int) $v[1];
				
				if($v[2]!="0000-00-00")
				{
					$notice_board['notice_date'] = $v[2];
				} else {
					$notice_board['notice_date'] = "";
				}
				$notice_board['notice_title'] = htmlspecialchars($v[3]);
				$notice_board['notice_order_by'] = htmlspecialchars($v[7]);
				array_push($RESPONCE['notice_board'], $notice_board);
			} while ($v = mysqli_fetch_array($result));
		}
	}


	echo json_encode($RESPONCE);
?>