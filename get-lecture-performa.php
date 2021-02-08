<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$fees="fee_structure1";
	$college="1";
	$gdcol="gdcol1";

	$f_id = $_REQUEST['f_id'];
	$sem = $_REQUEST['sem'];
	
	$RESPONCE = array();
	$RESPONCE['result'] = "success";
	$lecture_performa = array();
	$RESPONCE['lecture_performa'] = array();

	$f_id=" and f_id='$f_id'"; 
	$sem=" and sem='$sem'"; 

	$sql = "SELECT * FROM lecture_performa where college='$college' and status='Approved' ".$f_id." ".$sem." order by p_id";
	$result = mysqli_query($con,$sql);
	$table ="";
	if(mysqli_num_rows($result) > 0 ) {
		if($d = mysqli_fetch_row($result)) {		
			$j=1;
			do {
				$lecture_performa['l_id'] = (int) $d[0];
				$sub=mysqli_fetch_row(mysqli_query($con,"select subject from subject_list where s_id='$d[4]'"));
				$lecture_performa['subject_name'] = $sub[0];
				$e=mysqli_fetch_row(mysqli_query($con1,"select empname from emp_details where emp_id='$d[5]'"));
				$lecture_performa['feculty_name'] = $e[0];
				$lecture_performa['total_lecture'] = $d[8];
				array_push($RESPONCE['lecture_performa'], $lecture_performa);
			} while ($d = mysqli_fetch_array($result));
		}
	}

	echo json_encode($RESPONCE);