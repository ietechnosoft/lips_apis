<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../connect.php");
	$fees="fee_structure1";
	$notice="notice1";
	$col="LIPS";


	$p1=mysqli_query($con,"select * from $notice where n_id='$_REQUEST[n_id]'");
	$n=mysqli_fetch_row($p1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
<meta http-equiv="content-language" content="hi" />

<title><?php echo $n[3]?></title>
<link rel="icon" href="logo3.png" type="image/x-icon" />

<style type="text/css" media="print">

@page{
	size: potrait;
}
body
{
	margin-top: 5.5mm;
    margin-bottom: 0.5mm;;
    margin-right: 0.0mm;
    margin-left: 0.0mm;
}
</style>
<style type="text/css">

.style1 {
	font-size: 22px;
	font-weight: bold;
}
</style>
</head>
<body>
		<div align="justify">
		<table width='100%' cellpadding='5'>
			<tr>
				<td style='padding-left:25px;'><b>Notice No. - <?php echo $col."/".$n[1]; ?></b></td>
				<td align='right' style='padding-right:25px;'><b>Date - <?php  $date= DateTime::createFromFormat('Y-m-d', $n[2]); echo $date->format('d/m/Y'); ?></b></td>
			</tr>
			<tr>
				<td colspan='2' align='center' style='font-weight:bold; font-size:26px; text-transform:uppercase;'><u><?php echo $n[5]; ?></u></td>
			</tr>
			<?php
				$str=explode(";",$n[4]);
				for($i=1;$i<count($str)-1;$i++)
				{
					// $str[$i]=str_replace("assets","assets_parents",$str[$i]);
			?>
			<tr>
				<td colspan='2'><img src='<?php echo '../../'.$str[$i]; ?>' width='100%' style='max-height:800px;'/></td>
			</tr>
			<?php
				}
			?>
		</table>
</div>
</body>
</html>
