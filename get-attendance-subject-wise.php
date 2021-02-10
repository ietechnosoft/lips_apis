<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$fees="fee_structure1";		
	$college="1";
	$gdcol="gdcol1";

	if ($_REQUEST['college_id'] == "2") {
		$fees = "fee_structure2";
		$college = "2";
		$gdcol = "gdcol2";
	}
	

	$RESPONCE = array();
	$RESPONCE['result'] = "success";
	$RESPONCE['total_lecture_count'] = (int) 0;
	$RESPONCE['total_attendance_count'] = (int) 0;
	$RESPONCE['total_percentage'] = (double) 0.0;
	$attendance = array();
	$RESPONCE['attendance'] = array();
 
	$dfrom=$_REQUEST['dfrom'];
	$dto=$_REQUEST['dto'];
	$date= DateTime::createFromFormat('Y-m-d', $dfrom);
	$date1= DateTime::createFromFormat('Y-m-d', $dto);

	$stsb=mysqli_fetch_row(mysqli_query($con,"select TRIM(BOTH ',' FROM subjects) from $gdcol where s_id ='$_REQUEST[s_id]'"));


	// echo "select * from subject_list where college='$college' and f_id='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and status=1 order by s_id";
	$sub1=mysqli_query($con,"select * from subject_list where college='$college' and s_id in ($stsb[0]) and f_id='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and status=1 order by s_id");
	$totalLectureCount = 0;
	$totalAttendedCount = 0;
	while($sub=mysqli_fetch_row($sub1)) {
		$attendance['subject_name'] = $sub[8] . '/' . $sub[4];
		$lec = mysqli_fetch_row(mysqli_query($con,"select count(*) from attendance where college='$college' and f_id='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and subject='$sub[0]' and adate>='$dfrom' and adate<='$dto'"));
		$attendance['lecture'] = (int) $lec[0];
		$totalLectureCount += (int) $lec[0];

		$att = mysqli_fetch_row(mysqli_query($con,"select count(*) from attendance where college='$college' and f_id='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and subject='$sub[0]' and adate>='$dfrom' and adate<='$dto' and (present like '$_REQUEST[s_id],%' or present like '%,$_REQUEST[s_id],%' or present like '$_REQUEST[s_id]' or present like '%,$_REQUEST[s_id]')"));
		$attendance['attendance'] = (int) $att[0];
		$totalAttendedCount += (int) $att[0];

		if($lec[0] != 0) {
			$attendance['percentage']  = (double) round(($att[0]/$lec[0]*100),2);
		}
		else $attendance['percentage'] = (double) 0.0;

		array_push($RESPONCE['attendance'], $attendance);
	}
	$RESPONCE['total_lecture_count'] = $totalLectureCount;
	$RESPONCE['total_attendance_count'] = $totalAttendedCount;

	if($totalLectureCount != 0) {
		$RESPONCE['total_percentage']  = (double) round(($totalAttendedCount/$totalLectureCount*100),2);
	}
	else $RESPONCE['total_percentage'] = (double) 0.0;

	echo json_encode($RESPONCE);
?>