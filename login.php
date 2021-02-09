<?php
	ob_start();
	session_start();
    
    if(date("m")=="01" || date("m")=="02" || date("m")=="03" || date("m")=="04" || date("m")=="05" || date("m")=="06" || date("m")=="07" || date("m")=="08" || date("m")=="09")
	    $Y=date("Y")-1;
	else $Y=date("Y");
	$_SESSION['cursession'] =	$Y."_".(substr($Y,2)+1);
					
    include_once("../../../connect.php");
    $gdcol="gdcol1";
    $_REQUEST['college'] = "gdcol1";

    $username = md5($_REQUEST['username']);
    $password = md5($_REQUEST['password']);

    $RESPONSE = array();
    $RESPONSE['result'] = "success";
    $RESPONSE['data'] = array();
    $login = array();

    $dbname="luckycollegeerp". $_SESSION['cursession'];
    // echo "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
    $chk1=mysqli_query($con,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    //'luckycollegeerp2020_21'
    
    if($chk=mysqli_fetch_row($chk1)) {
        // echo "select s_id,name,f_id,sem,sem2,pic from $dbname.$_REQUEST[college] where uname='" . $username . "' and password='" . $password . "' and status=1";
        $l1=mysqli_query($con,"select s_id,name,f_id,sem,sem2,pic from $dbname.$_REQUEST[college] where uname='".$username."' and password='".$password."' and status=1");
        if($l=mysqli_fetch_row($l1)) {
            $str = explode("_", $_SESSION['cursession']);
            $str[0]++;
            $str[1]++;
            $_REQUEST['cursession1'] = $str[0]."_".$str[1];
            $dbname = "luckycollegeerp".$_REQUEST['cursession1'];
            $chk1=mysqli_query($con,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
            if($chk=mysqli_fetch_row($chk1)) {
                $l2=mysqli_query($con,"select s_id,name,f_id,sem,sem2,pic from $dbname.$_REQUEST[college] where uname='" . $username . "' and password='" . $password . "' and status=1 ");
                if($l3=mysqli_fetch_row($l2)) {
                    $login['college_session'] = str_replace("_","-", $_SESSION['cursession1']);
                    $login['user_id']=$l3[0];
                    $login['name'] = $l3[1];
                    $login['f_id'] = $l3[2];
                    $login['sem'] = $l3[3];
                    $login['sem1'] = $l3[4];
                    $login['pic'] = $l3[5];
                    $login['user_type'] = "1";
                } else {
                    $login['college_session'] = str_replace("_", "-", $_SESSION['cursession']);
                    $login['user_id'] = $l[0];
                    $login['name'] = $l[1];
                    $login['f_id'] = $l[2];
                    $login['sem1'] = $l[3];
                    $login['sem2'] = $l[4];
                    $login['pic'] = $l[5];
                    $login['user_type'] = "1";
                }
            } else {
                $login['college_session'] = str_replace("_", "-", $_SESSION['cursession']);
                $login['user_id'] = $l[0];
                $login['name'] = $l[1];
                $login['f_id'] = $l[2];
                $login['sem1'] = $l[3];
                $login['sem2'] = $l[4];
                $login['pic'] = $l[5];
                $login['user_type'] = "1";
            }
        } else {
            $str=explode("_", $_SESSION['cursession']);
            $str[0]++;
            $str[1]++;
            $_SESSION['cursession']=$str[0]."_".$str[1];
            $dbname="luckycollegeerp". $_SESSION['cursession'];
            // echo "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
            $chk1=mysqli_query($con,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");		
            
            if($chk=mysqli_fetch_row($chk1)) {
                $l1=mysqli_query($con,"select s_id,name,f_id,sem,sem2,pic from $dbname.$_REQUEST[college] where  uname='" . $username . "' and password='" . $password . "'  and status=1");
                
                if($l=mysqli_fetch_row($l1)) {

                    $login['college_session'] = str_replace("_", "-", $_REQUEST['cursession']);
                    $login['user_id'] = $l[0];
                    $login['name'] = $l[1];
                    $login['f_id'] = $l[2];
                    $login['sem1'] = $l[3];
                    $login['sem2'] = $l[4];
                    $login['pic'] = $l[5];
                    $login['user_type'] = "1";
                } else {
                    $RESPONSE['result'] = "fail";
                    $RESPONSE['message'] = "Invalid Username or Password!!!";

                    $login = null;
                }
            } else {
                $RESPONSE['result'] = "fail";
                $RESPONSE['message'] = "Invalid Username or Password!!!";

                $login = null;
            }
        }
    } else {
        $RESPONSE['result'] = "fail";
        $RESPONSE['message'] = "Session not created yet";

        $login = null;
    }

    $RESPONSE['data'] = $login;

    echo json_encode($RESPONSE);
?>