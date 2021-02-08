<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../connect.php");
	$fees="fee_structure1";
	$college="1";
	$gdcol="gdcol1";
	
	$RESPONCE = array();
	$RESPONCE['result'] = "success";
	$unit_detail = array();
	$unit_detail['unit_name'] = "";
	$unit_detail['topic'] = array();
	$topic_detail['topic_name'] = "";
	$topic_detail['sub_topic'] = array();
	$RESPONCE['unit'] = array(); 


	$d=mysqli_fetch_row(mysqli_query($con,"select * from lecture_performa where p_id ='$_REQUEST[p_id]'"));
	$f=mysqli_fetch_row(mysqli_query($con,"select batchyear,course,yearwise from $fees where f_id ='$d[2]'"));
	$j=1;
	$a1=mysqli_query($con,"select unit from lec_topics where p_id='$_REQUEST[p_id]' group by unit" );
	while($unit=mysqli_fetch_row($a1)) {
	$unit_detail['topic'] = array();

		$unit_detail['unit_name'] = $unit[0];

		$a2=mysqli_query($con,"select maintopic from lec_topics where p_id='$_REQUEST[p_id]' and unit='$unit[0]' group by maintopic" );
		while($a=mysqli_fetch_row($a2)) {
			$topic_detail['sub_topic'] = array();
			$topic_detail['topic_name']= str_replace('  ',' ',str_replace("\t",'',trim($a[0])));
			$a3=mysqli_query($con,"select subtopic,lecture,completed from lec_topics where p_id='$_REQUEST[p_id]' and unit='$unit[0]' and maintopic='$a[0]'" );
			while($lec=mysqli_fetch_row($a3)) {
				$sub_topic_detail['sub_topic'] = str_replace('  ',' ',str_replace("\t",'',trim($lec[0])));
				$sub_topic_detail['lecture_count'] = (int) $lec[1];
				if($lec[2] != "0000-00-00") {
					$sub_topic_detail['completed'] = $lec[2];
				} else {
					$sub_topic_detail['completed'] = "";
				}
				array_push($topic_detail['sub_topic'], $sub_topic_detail);
			}
			array_push($unit_detail['topic'], $topic_detail);
		}
		array_push($RESPONCE['unit'], $unit_detail); 
	}

	echo json_encode($RESPONCE);
?>