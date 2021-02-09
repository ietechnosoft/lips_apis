<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$fees="fee_structure1";
	$gdcol="gdcol1";
	$receipt="receipt1";
	$recparti="recparti1";
	$transaction="transaction1";
	$led="ledger_accounts1";
	$name="lips";
	$college="1";
	
	if($_REQUEST['college_id'] == 2 )
	{
		$fees="fee_structure2";
		$gdcol="gdcol2";
		$receipt="receipt2";
		$recparti="recparti2";
		$transaction="transaction2";
		$led="ledger_accounts2";
		$name="lwttc";
		$college="2";
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
	<script type="text/javascript">
			function calc()
			{
				var idate = document.frm2.rdate.value;
				var duedate = document.frm2.nextduedate.value;
				t1=idate.replace("-","/");
				t1=t1.replace("-","/");
				m=duedate.replace("-","/");
				m=m.replace("-","/");
				var feefine = document.frm2.feefine.value;
				var fine=0;
				if(m!="0000/00/00" && t1>m)
				{
					var date2 = new Date(t1);
					var date1 = new Date(m);
					var timeDiff = Math.abs(date2.getTime() - date1.getTime());
					var days = Math.ceil(timeDiff / (1000 * 3600 * 24));
					fine = days*feefine;
				}
				document.getElementById("lfine").value=fine*1;
				$("#lfine1").html(fine*1);
				var amt = document.getElementsByName("amt[]");
				
				var amttot=0;
				for(i=0;i<amt.length;i++)
				{
					amttot += amt[i].value*1;					
				}
				amttot += document.getElementById("lfine").value*1;
				document.getElementById("amttot").value = (amttot*1);
				$("#amttot1").html("<i class='fa fa-rupee'></i>&nbsp;"+amttot*1+" /-");
				val2 = document.frm2.s_id.value;
				  $.ajax({
					   url : '../../genpayfees.php',
					   type : 'POST',
					   data : {total : amttot, s_id : val2},
					   success : ajaxSuccess,
					   error : ajaxError
					  });
			}
			function ajaxSuccess(response)
			{
					 $('#display').html(response);
			}
			function ajaxError()
			{
				alert("error");
			}
			function chk()
			{
				if(document.frm2.amount.value==0)
				{
					alert("You Have no Dues Left!!!");
					return false;
				}
				else
				return confirm('Are you Sure??');
			}
		</script>
    </head>
    <body class="x-dashboard">
        <!-- START PAGE CONTAINER -->
        <div class="page-container" >            
            <!-- PAGE CONTENT -->
			
            <div class="page-content">
                <!-- PAGE CONTENT WRAPPER -->
				 
                <div class="page-content-wrap ">
                    <div class="row  ">
					 <form class="form-horizontal  " name='frm2' method="post" action="../merchant/<?php echo $name; ?>/gateway_home.php?type=1" enctype="multipart/form-data" onsubmit="return chk();">
						<div class="panel panel-default  " style="padding: 0px !important;">
								<div class="panel-body  " style="padding: 0px !important;">
									<div class="row">
                                        <div class="col-md-12">
										<?php
											$p=mysqli_fetch_row(mysqli_query($con,"select name,fname,mname,sms,f_id,gender,admno,ledger_id,pic,nextduedate from $gdcol where s_id='$_REQUEST[s_id]'"));
											$fee=mysqli_fetch_row(mysqli_query($con1,"select feefine from otherfee"));
										?>
										 <input type='hidden' name='s_id' value="<?php echo $_REQUEST['s_id']; ?>"/>
										 <input type='hidden' name='admno' value="<?php echo $p[6]; ?>"/>
										 <input type='hidden' name='name' value="<?php echo $p[0]; ?>"/>
										 <input type='hidden' name='ledger_id' value="<?php echo $p[7]; ?>"/> 
										 <input type='hidden' name='f_id' value="<?php echo $p[4]; ?>"/>
										 <input type='hidden' name='nextduedate' value="<?php echo $p[9]; ?>"/>
										 <input type='hidden' name='feefine' value="<?php echo $fee[0]; ?>"/>										 
										 <input type="hidden" class="form-control" name="rdate" value="<?php echo date("Y-m-d"); ?>"/>
										<div class="table">
											<table class="table table-bordered table-striped table-actions">
												<tbody>         	
													<tr>
														<th>Name</th>
														<td><?php echo $p[0]; ?></td>
														<th>Course</th>
														<?php
															$f1=mysqli_query($con,"select course,batchyear,yearwise from $fees where f_id='$p[4]'");
															$f=mysqli_fetch_row($f1);
															echo "<td>$f[0] ($f[1]) - $f[2]</td>";
														?>
													</tr>
													<tr class='danger'>
														<th>Next Due Date</th>
														<td colspan='3'>
														<?php
															if($p[9]!="0000-00-00")
															{
																$date= DateTime::createFromFormat('Y-m-d', $p[9]);
																echo $date->format('d-m-Y')."Late Fees Applicable after this.";
															}
														?>
														</td>
													</tR>
													<tr>
														<td colspan='4'>
															<table class="table table-bordered table-striped table-actions">
																<tbody>
																	<tr>
																		<th>S. No.</th>
																		<th>Particulars</th>
																		<th>Dues</th>
																	</tr>
																	<?php
																		$j=1;
																		$k=0;
																		$due=0;
																		$l1=mysqli_query($con,"select name from $led where (ledger_id>=2 and ledger_id<=19) or name like '%CONTINOUS FORM%' order by ledger_id");
																		$f1=mysqli_query($con,"select f4,f5,f6,f7,f8,f9,f10,f11,f12,f13,f14,f15,f16,f17,f19,f20,f21,f22,f18 from $gdcol where s_id=$_REQUEST[s_id]");
																		$f=mysqli_fetch_row($f1);
																		while($l=mysqli_fetch_row($l1))
																		{
																			echo "<tr>";
																			echo "<td>$j</td>";
																			echo "<td>$l[0]</td>";
																			$due += $f[$k];
																		?>
																		<input type='hidden' name='parti[]' value='<?php echo $l[0]; ?>' />
																		<td>
																			<input type="hidden" class="form-control" name="amt[]" value='<?php echo $f[$k]; ?>'/>
																		<?php echo $f[$k]; ?></td>
																		
																		<?php
																			echo "</tr>";
																			$j++;
																			$k++;
																		}
																	?>
																	<tr>
																		<td><?php echo $j; ?></td>
																		<td>LATE FINE</td>
																		
																		<td>
																			<input type="hidden" class="form-control" name="lfine" id="lfine" value='0'/>
																			<div id="lfine1"></div>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										
										</div>
										<div class="col-md-12">
											<div style="border:2px solid green; padding-top:10px; padding-left:25px; padding-bottom:10px;">
												<H2>Total Amount Payable <br><br>
												<input type="hidden" class="form-control" name="amount" id="amttot"/>
												<div id="amttot1" style='color:green; font-weight:bold;'><i class='fa fa-rupee'></i> &nbsp;0.00 /-</div></h2>
												<button class="btn btn-primary" type="submit" name="s1">Pay Now</button>
											</div>
										</div>
									</div>
								</div>
								<div id='display'></div>
						</div>
						</form>
					</div>
					</div>
					<?php echo "<script>calc();</script>"; ?>
                    <!-- END WIDGETS --> 
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