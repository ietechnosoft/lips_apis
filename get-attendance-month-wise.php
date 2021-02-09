<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
 
	$fees="fee_structure1";
	$college="1";
	$gdcol="gdcol1";
	
	if($_REQUEST['college_id']== "2" ){
		$fees="fee_structure2";
		$college="2";
		$gdcol="gdcol2";
	}
	

	$RESPONCE = array();
	$RESPONCE['result'] = "success";
	$attendance = array();
	$RESPONCE['attendance'] = array();
 
	$dfrom=$_REQUEST['dfrom'];
	$dto=$_REQUEST['dto'];
	$date= DateTime::createFromFormat('Y-m-d', $dfrom);
	$date1= DateTime::createFromFormat('Y-m-d', $dto);

	$d=explode("-",$_REQUEST['dfrom']);
	$f_d_t = mktime(0,0,0,$d[1],1,$d[0]);
	$no_days=date("t",$f_d_t);
	$dfrom=$d[0]."-".$d[1]."-01";
	$dto=$d[0]."-".$d[1]."-".$no_days;
	$date= DateTime::createFromFormat('m-Y', "$d[1]-$d[0]");
	$student=mysqli_fetch_row(mysqli_query($con,"select name,fname,mname,sms,admno from $gdcol where s_id='$_REQUEST[s_id]'"));
	$f=mysqli_fetch_row(mysqli_query($con,"select batchyear,course,yearwise from $fees where f_id ='$_REQUEST[f_id]'"));
	$sid=$_REQUEST['s_id'];
	do {
		$attendance['month_name'] = $date->format('M - Y');
		$stu=mysqli_fetch_row(mysqli_query($con,"select count(*) from attendance where f_id='$_REQUEST[f_id]' and college='$college' and sem='$_REQUEST[sem]' and adate>='$dfrom' and adate<='$dto' and (present like '$sid,%' or present like '%,$sid,%' or present like '$sid' or present like '%,$sid')"));
		$attendance['attendance'] = (int) $stu[0];
		$tot=mysqli_fetch_row(mysqli_query($con,"select count(*) from attendance where f_id='$_REQUEST[f_id]' and college='$college' and sem='$_REQUEST[sem]' and adate>='$dfrom' and adate<='$dto'"));
		$attendance['lecture'] = (int) $tot[0];

		if($tot[0]!=0)
			$attendance['percentage'] = (double) round(($stu[0]/$tot[0]*100),2);
		else $attendance['percentage'] = (double) 0;

		if($d[1]=="12"){ $d[1]="01"; $d[0]=$d[0]+1;}
		else if($d[1]=="09" || $d[1]=="10" || $d[1]=="11") $d[1]=$d[1]+1;
		else {
			$tmp=explode("0",$d[1]);
			$d[1]="0".($tmp[1]+1);
		}
		$f_d_t = mktime(0,0,0,$d[1],1,$d[0]);
		$no_days=date("t",$f_d_t);
		$dfrom=$d[0]."-".$d[1]."-01";
		$dto=$d[0]."-".$d[1]."-".$no_days;
		$date= DateTime::createFromFormat('m-Y', "$d[1]-$d[0]");

		array_push($RESPONCE['attendance'], $attendance);
	}while($dfrom<=$_REQUEST['dto']);

	echo json_encode($RESPONCE);
?>