<?php
	ob_start();
	session_start();
    $_SESSION['cursession'] = $_REQUEST['college_session'];
    include_once("../../../connect.php");
    $gdcol="gdcol1";
    $type="1";

    $RESPONSE = array();
    $RESPONSE['result'] = 'success';
    $RESPONSE['book_active'] = array();
    $RESPONSE['book_overdue'] = array();
    $RESPONSE['book_return'] = array();
    $libraryData = array();


    $chk1=mysqli_query($con1,"select * from book_issued where srno like 's$_REQUEST[s_id]' and college='$type'");
    if($row=mysqli_fetch_row($chk1)) {
    	$j=1;
    	do{
    		$a1=mysqli_query($con1,"select isbn_no,accessionno,bookname,author,edition,publisher from library where b_id='$row[0]'");
    		$a=mysqli_fetch_row($a1);
    		$libraryData['isbn_no'] = $a[0];;
    		$libraryData['acc_no'] = $a[1];
    		$libraryData['book_name'] = $a[2];
    		$libraryData['author'] = $a[3];
    		$libraryData['publisher'] = $a[5];

    		if($row[2]!="0000-00-00") {
    			$date= DateTime::createFromFormat('Y-m-d', $row[2]);
    			$libraryData['from_date'] = $date->format('d-m-Y');
    		} else {
    			$libraryData['from_date'] = "";
    		}

    		if($row[3]!="0000-00-00") {
    			$date= DateTime::createFromFormat('Y-m-d', $row[3]);
    			$libraryData['to_date'] = $date->format('d-m-Y');
    		} else {
    			$libraryData['to_date'] = "";
    		}

    		if($row[4]!="0000-00-00") {
    			$date= DateTime::createFromFormat('Y-m-d', $row[4]);
    			$libraryData['submission_date'] = $date->format('d-m-Y');
    		} else {
    			$libraryData['submission_date'] = "";
    		}

    		$libraryData['fine'] = $row[6];
    		$libraryData['other_fine'] = $row[7];


    		$date1 = new DateTime($row[3]);
    		$date2 = new DateTime("now");
    		if($date2>$date1 && $row[4] == "0000-00-00"){
    			array_push($RESPONSE['book_overdue'], $libraryData);
    		} else if($date2<=$date1 && $row[4] == "0000-00-00") {
    			array_push($RESPONSE['book_active'], $libraryData);
    		} else if($row[4] != "0000-00-00") {
    			array_push($RESPONSE['book_return'], $libraryData);
    		}


    		$j++;
    	} while ($row=mysqli_fetch_row($chk1));
    }
    echo json_encode($RESPONSE);
?>