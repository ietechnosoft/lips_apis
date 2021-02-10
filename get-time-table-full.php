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


	if ($_REQUEST['college_id'] == "2") {
		$fees = "fee_structure2";
		$timetable = "timetable2";
		$ttdetails = "ttdetails2";
		$college = "2";
		$gdcol = "gdcol2";
	}
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>        
        <!-- META SECTION -->
        <title>LUCKY INSTITUTE OF PROFESSIONAL STUDIES</title>           
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="logo3.png" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="../../css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                 
		<script src="../../js/jquery.min.js"></script>
	<script src="../../js/custom.js"></script>			
	<link href="../../css/grafikon.css" rel="stylesheet">
	<script type="text/javascript" src="../../js/grafikon.min.js"></script>
	<style type="text/css">
<!--
.style2 {
	font-size: 30px;
	font-weight: bold;
}
.style3 {
	font-size: 24px;
	font-weight: bold;
}
.style4 {
	font-size: 16px;
	font-weight: bold;
}
.style7 {font-size: 14px; font-weight: bold; }
-->
</style>
    </head>
    <body class="x-dashboard">
        <div class="container-fluid" >
        	<?php
        		$f=mysqli_fetch_row(mysqli_query($con,"select f_id,sem,sem2 from $gdcol where s_id='$_REQUEST[s_id]'"));
				$_REQUEST['f_id']=$f[0];
				if($f[2]!="") $_REQUEST['sem']=$f[2];
				else $_REQUEST['sem']=$f[1];
				$d1=mysqli_query($con,"select * from $timetable where f_id ='$_REQUEST[f_id]' and sem='$_REQUEST[sem]' and status=1");
				if($d=mysqli_fetch_row($d1)) {
					$f=mysqli_fetch_row(mysqli_query($con,"select batchyear,course,yearwise from $fees where f_id ='$d[1]'"));
			?>
					<div class="row"> 
						<div class="col-md-12">
						  	<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th rowspan='2'>Day</th>
										<?php
											for($i=1;$i<=$d[6];$i++)
											{
												echo "<th>Period $i</th>";
											}
										?>
									</tr>
									<tr>
										<?php
											// echo "select * from $ttdetails where t_id='$d[0]' and day='timeslot'";
											$ts=mysqli_fetch_row(mysqli_query($con,"select * from $ttdetails where t_id='$d[0]' and day='timeslot'"));
											
											$i=2;
											while($ts[$i]!=null)
											{
												$str=explode(",",$ts[$i]);
												$time= DateTime::createFromFormat('H:i', $str[0]);
												echo "<th>".$time->format('h:i');
												$time= DateTime::createFromFormat('H:i', $str[1]);
												echo " To <br>".$time->format('h:i')."</th>";					
												$i++;
											}
										?>
									</tr>
								</thead>
								<tbody>    
									<?php
										$ts1=mysqli_query($con,"select * from $ttdetails where t_id='$d[0]' and day!='timeslot'");
										while($ts=mysqli_fetch_row($ts1))
										{
									?>
										<tr>
											<td><?php echo $ts[1]; ?></td>
											<?php
												for($i=1,$j=2;$i<=$d[6];$i++,$j++)
												{
													if($ts[$j]!=null)
													{
														$str1=explode(";",$ts[$j]);
														echo "<td>";
														for($k=0;$k<count($str1);$k++)
														{
															$str=explode("-",$str1[$k]);
															$e=mysqli_fetch_row(mysqli_query($con,"select abbr from subject_list where s_id='$str[0]'"));
															echo $e[0]."<br>";
															$e=mysqli_fetch_row(mysqli_query($con1,"select empname from emp_details where emp_id='$str[1]'"));
															echo $e[0]."<br>";
															$e=mysqli_fetch_row(mysqli_query($con1,"select lname from location where l_id='$str[2]'"));
															echo $e[0];
															if(count($str1)>1 && $k!=count($str1)-1)
															{
																echo "<hr width='100%' style='border-top: 1px dashed #8c8b8b; margin:0px;'>";
															}
														}
														echo "</td>";
													}
													else
													{
														echo "<td></td>";
													}
												}
											?>
										</tr>
									<?php
										}
									?>
								</tbody>
							</table>
                        </div>                                    
					</div>
			<?php
				} else { echo "No Time Table Available!!!"; }
			?>
		</div>
	</div>
        <script type="text/javascript" src="../../js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='../../js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="../../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/scrolltotop/scrolltopcontrol.js"></script>
        
        <script type="text/javascript" src="../../js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="../../js/plugins/morris/morris.min.js"></script>       
        <script type="text/javascript" src="../../js/plugins/rickshaw/d3.v3.js"></script>
        <script type="text/javascript" src="../../js/plugins/rickshaw/rickshaw.min.js"></script>
        <script type='text/javascript' src='../../js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
        <script type='text/javascript' src='../../js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>                
        <script type='text/javascript' src='../../js/plugins/bootstrap/bootstrap-datepicker.js'></script>                
        <script type="text/javascript" src="../../js/plugins/owl/owl.carousel.min.js"></script>                 
        
        <script type="text/javascript" src="../../js/plugins/moment.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- END THIS PAGE PLUGINS-->        

        <!-- START TEMPLATE -->
        <script type="text/javascript" src="../../js/plugins.js"></script>        
        <script type="text/javascript" src="../../js/actions.js"></script>
        <script type="text/javascript" src="../../js/demo_dashboard_x.js"></script>
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','../../../../../../www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-36783416-1', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter25836617 = new Ya.Metrika({
                        id:25836617,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "../../../../../../mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/25836617" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->    
    </body>
</html>