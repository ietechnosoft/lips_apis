<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$fees="fee_structure1";		
	$timetable="timetable1";
	$ttdetails="ttdetails1";
	$college="1";
	$gdcol="gdcol1";

	$RESPONCE['result'] = "success";
	$RESPONCE['period_count'] =  0;
	$RESPONCE['time_table_day'] = array();
	$day_detail['day'] =  "";
	$day_detail['time_table_period'] = array();
	$time_table_period = array();

	$d1=mysqli_query($con,"select * from $timetable where f_id ='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and status=1");
	if($d=mysqli_fetch_row($d1)) {
		$f=mysqli_fetch_row(mysqli_query($con,"select batchyear,course,yearwise from $fees where f_id ='$d[1]'"));

		if ($d[3]==0) $e[0]="Super Admin";
		else $e=mysqli_fetch_row(mysqli_query($con1,"select empname from emp_details where emp_id=$d[3]"));
 
 		$ts1=mysqli_query($con,"select * from $ttdetails where t_id='$d[0]'");
		while($ts=mysqli_fetch_row($ts1)) {
			
			if($ts[1] == "timeslot") {
				$i=2;
				$period = 1;
				while($ts[$i]!=null)
				{
					$str=explode(",",$ts[$i]);
					$timeFrom= DateTime::createFromFormat('H:i', $str[0]);
					$timeTo= DateTime::createFromFormat('H:i', $str[1]);
					$period_detail['period_name'] = 'Period '.$period++;
					$period_detail['timeslot'] =  $timeFrom->format('h:i').'-'.$timeTo->format('h:i');
					array_push($time_table_period, $period_detail);
					$i++;
				}

				continue;
			}

				$day_detail['time_table_period'] = array();
			for($i=1, $j=2; $i<=$d[6]; $i++, $j++) {
							$period_detail['period_name'] = $time_table_period[$i-1]['period_name'];
							$period_detail['timeslot'] = $time_table_period[$i-1]['timeslot'];
				if($ts[$j] != null) {
					$str1 = explode(";",$ts[$j]); 
					for($k=0; $k<count($str1); $k++) {

							$str = explode("-",$str1[$k]);
							$period_detail['subject_name'] = mysqli_fetch_row(mysqli_query($con,"select abbr from subject_list where s_id='$str[0]'"))[0];
							$period_detail['faculty_name'] = mysqli_fetch_row(mysqli_query($con1,"select empname from emp_details where emp_id='$str[1]'"))[0];
							$period_detail['class_name'] = mysqli_fetch_row(mysqli_query($con1,"select lname from location where l_id='$str[2]'"))[0];
							array_push($day_detail['time_table_period'], $period_detail);
					}
				}
			}
			$RESPONCE['period_count'] = (int) $d[6];
			$day_detail['day'] =  $ts[1];
			array_push($RESPONCE['time_table_day'], $day_detail);	
		}

	}

	echo json_encode($RESPONCE);


?>