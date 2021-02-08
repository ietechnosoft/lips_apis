<?php
	ob_start();
	session_start();
    $_SESSION['cursession'] = $_REQUEST['college_session'];
    include_once("../../connect.php"); 
?>
<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style type="text/css">
            html,body{
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <iframe src='<?php echo '../../'.$_REQUEST['doc']; ?>' height='100%' width='100%'></iframe>
    </body>
</html>
