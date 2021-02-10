<?php
	ob_start();
	session_start();
	$_SESSION['cursession'] = $_REQUEST['college_session'];
	include_once("../../../connect.php");
	$grpmas="group_master1";
	$leddet="ledger_details1";
	$payment="payment_transaction1";
	$fees="fee_structure1";
	$gdcol="gdcol1";
	$receipt="receipt1";
	$recparti="recparti1";
	$transaction="transaction1";
	$led="ledger_accounts1";
	$recname="LIPS";
	$msgapi=mysqli_fetch_row(mysqli_query($con1,"select * from msgpack where mid=1"));
	$college="1";
	$tempreceipt="tempreceipt1";

	if ($_REQUEST['college_id'] == "2") {
		$grpmas = "group_master2";
		$leddet = "ledger_details2";
		$payment = "payment_transaction2";
		$fees = "fee_structure2";
		$gdcol = "gdcol2";
		$receipt = "receipt2";
		$recparti = "recparti2";
		$transaction = "transaction2";
		$led = "ledger_accounts2";
		$recname = "LWTTC";
		$msgapi = mysqli_fetch_row(mysqli_query($con1, "select * from msgpack where mid=2"));
		$college = "2";
		$tempreceipt = "tempreceipt2";
	}

	$sId = $_GET['s_id'];
	
	$userid=$msgapi[1];
	$password=$msgapi[2];
	$chk1=mysqli_query($con,"select * from $payment where s_id='$sId' and order_status='Success' and receiptstatus='Pending' and type=''");
	while($chk=mysqli_fetch_row($chk1))
	{
		$temp1=mysqli_query($con,"select * from $tempreceipt where order_id='$chk[3]' and status='Pending'");
		if($temp=mysqli_fetch_row($temp1))
		{
			$_REQUEST['s_id']=$temp[1]; 
			$_REQUEST['rdate']=$temp[2];
			$_REQUEST['amount']=$temp[3];
			$_REQUEST['lfine']=$temp[4];
			$_REQUEST['f_id']=$temp[5];
			$_REQUEST['amt']=explode(";",$temp[6]);
			$_REQUEST['parti']=explode(";",$temp[7]);
			$_REQUEST['admno']=$temp[8];
			$_REQUEST['ledger_id']=$temp[9];
			mysqli_begin_transaction($con);
			$invno=mysqli_query($con,"select rrecno,recno from $receipt order by recno desc limit 1");
																
			if($i=mysqli_fetch_row($invno))
				$i[0]=explode("/",$i[0])[2];
			else $i[0]=$i[1]=0;
			$i[0]++;
			if($i[0]<10) $no=$recname."/".substr($_SESSION['cursession'],2)."/00".$i[0];
			else if($i[0]<100) $no=$recname."/".substr($_SESSION['cursession'],2)."/0".$i[0];
			else $no=$recname."/".substr($_SESSION['cursession'],2)."/".$i[0];
			$_REQUEST['recno']=++$i[1];
			$_REQUEST['rrecno']=$no;
			$rid="R".$_REQUEST['recno'];
			$sql=mysqli_query($con,"insert into $receipt set recno='$_REQUEST[recno]', rdate='$_REQUEST[rdate]', amount='$_REQUEST[amount]', s_id='$_REQUEST[s_id]', paidby='Online', bankname='$chk[10]', chequeno='$chk[2]', relatedwith='$rid', rrecno='$_REQUEST[rrecno]',  pby='', fine='$_REQUEST[lfine]', finedis='0', f_id='$_REQUEST[f_id]'");
			if($sql)
			{
				$cmp1=mysqli_query($con,"select max(trans_id) from $transaction");
				$cmp=mysqli_fetch_row($cmp1);
				$tid=$cmp[0]+1;
				$count=count($_REQUEST['amt']);
				for($i=0,$j=4;$i<$count;$i++,$j++)
				{
						if($j==18) $j++;
						else if($j==23) $j=18;
						$amt=$_REQUEST['amt'][$i];
						$parti=$_REQUEST['parti'][$i];
						if($amt!="" && $amt!=0 && $amt>0)
						{
							$f="f".$j;
							mysqli_query($con,"update $gdcol set $f=$f-$amt where s_id='$_REQUEST[s_id]'");
							mysqli_query($con,"insert into $recparti set recno='$_REQUEST[recno]', particulars='$parti', amt='$amt', colname='$f'");
							$a1=mysqli_query($con,"select ledger_id from $led where name='$parti'");
							$a=mysqli_fetch_row($a1);
							
							
							mysqli_query($con,"insert into $transaction set trans_id='".$tid."', tdate='".$_REQUEST['rdate']."', ledger_id='$a[0]', amount='".$amt."', particulars='$_REQUEST[admno] Rec No. $_REQUEST[rrecno], Paid By: Online, $chk[10] $chk[2]', type='Dr.', relatedto='$rid'");
							$tid++;
						}
				}
				if($_REQUEST['lfine']>0)
				{
					$a1=mysqli_query($con,"select ledger_id from $led where name='LATE FINE'");
					$a=mysqli_fetch_row($a1);
					
					
					mysqli_query($con,"insert into $transaction set trans_id='".$tid."', tdate='".$_REQUEST['rdate']."', ledger_id='$a[0]', amount='".$_REQUEST['lfine']."', particulars='$_REQUEST[admno] Rec No. $_REQUEST[rrecno], Paid By: Online, $chk[10] $chk[2]', type='Dr.', relatedto='$rid'");
					$tid++;
					mysqli_query($con,"insert into $transaction set trans_id='".$tid."', tdate='".$_REQUEST['rdate']."', ledger_id='$_REQUEST[ledger_id]', amount='".$_REQUEST['lfine']."', particulars='Late Fine', type='Cr.', relatedto='$rid'");
					$tid++;
				}
				mysqli_query($con,"insert into $transaction set trans_id='".$tid."', tdate='".$_REQUEST['rdate']."', ledger_id='$_REQUEST[ledger_id]', amount='".($_REQUEST['amount'])."', particulars='Rec No. $_REQUEST[rrecno], Paid By: Online, $chk[10] $chk[2]', type='Dr.', relatedto='$rid'");
				$tid++;
				$temp2=mysqli_fetch_row(mysqli_query($con,"select count(*) from $receipt where s_id='$_REQUEST[s_id]'"));
				if($temp2[0]==1) $text="Congratulation!! You have been promoted!!\n";
				else $text="";
				$s=mysqli_fetch_row(mysqli_query($con,"select name,fname,sms from $gdcol where s_id='$_REQUEST[s_id]'"));
				if($s[2]!="" || $s[2]!=0)
				{
					$date= DateTime::createFromFormat('Y-m-d', $_REQUEST['rdate']);
					$date= $date->format('d-m-Y');
					$text.="The Fees of $s[0] C/o $s[1] has been Submitted. Details are\nDate - $date\nRec No. - $_REQUEST[rrecno]\nAmount - Rs. $_REQUEST[amount]\nThank You.";
					$text=urlencode($text);
					$data="https://www.businesssms.co.in/sms.aspx?ID=$userid&Pwd=$password&PhNo=".$s[2]."&Text=".$text;
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_URL, $data);
					
					curl_exec($ch);
					curl_close($ch);
				}				
			}
		//	mysqli_query($con,"update $gdcol set nextduedate='0000-00-00' where s_id='$_REQUEST[s_id]'");
			mysqli_commit($con);
			mysqli_query($con,"update $payment set receiptstatus='Generated' where id='$chk[0]'");
			mysqli_query($con,"update $tempreceipt set status='Generated' where order_id='$chk[3]'");
		}
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
    </head>
    <body class="x-dashboard">
        <div class="page-container" >
            <div class="page-content">
                <div class="page-content-wrap">                                           
					<?php
						$f=mysqli_fetch_row(mysqli_query($con,"select ledger_id from $gdcol where s_id='$sId'"));
						$_REQUEST['ledger_id']=$f[0];

						$n=mysqli_fetch_row(mysqli_query($con,"select name,opening_bal,group_id,session from $led where ledger_id='$_REQUEST[ledger_id]'"));
					?>
						
                    <div class="row">
						<div class="panel panel-default">
                                <div class="panel-body" style="padding:0 !important; margin:0 !important;">
									 <div class="table">
                                        <table class="table table-bordered table-striped">
                                            <thead>
												<?php
													$s1=mysqli_query($con,"select name,fname,f_id,sem,pic,sms,admno,status,exstu from $gdcol where ledger_id='$_REQUEST[ledger_id]'");
													$s=mysqli_fetch_row($s1);
													
														$f=mysqli_fetch_row(mysqli_query($con,"select course,batchyear,yearwise from $fees where f_id='$s[2]'"));
											?>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Particulars</th>
													<th>Credit</th>
													<th>Debit</th>
													<th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
													$j=1;
													$bal=$n[1];													
												?>
											<tr id="<?php echo $j; $j++; ?>">
												<td>&nbsp;</td>
												<td>Opening Balance</td>
												<?php
													if($n[1]>0)
													{
														$n[1]=number_format($n[1],2);
														echo "<td style='text-align:right;'>&nbsp;</td>";		
														echo "<td style='text-align:right;'>$n[1]</td>";
													}
													else
													{
														$n[1]=$n[1]-($n[1]*2);
														$n[1]=number_format($n[1],2);
														echo "<td style='text-align:right;'>$n[1]</td>";
														echo "<td style='text-align:right;'>&nbsp;</td>";
													}
													echo "<td style='text-align:right;'>".number_format($bal,2)."</td>";
													echo "<td></td>";
 												?>
											</tr>
												<?php
													$str="";
													for($i=explode("-",$n[3])[0]; $i<=explode("-",$_SESSION['cursession'])[0]; $i++)
													{
														$val="luckycollegeerp".($i)."_".(substr($i,2)+1);
														$val1=($i)."_".(substr($i,2)+1);
														$str .= "SELECT *,'$val1' as `sess` FROM $val.$transaction where ledger_id='$_REQUEST[ledger_id]' union ";
													}
													$str=substr($str,0,strlen($str)-7);
													$str .= " order by tdate";
													
													$d1=mysqli_query($con,$str);
													while($d=mysqli_fetch_row($d1))
													{
												?>
                                                <tr id="<?php echo $j; ?>">
                                                    <td><?php $date= DateTime::createFromFormat('Y-m-d', $d[1]); echo $date->format('d-m Y'); ?></td>
                                                    <td><?php echo $d[4]; ?></td>
                                                    <?php
														if($d[5]=="Cr.")
														{
															$bal=$bal-$d[3];			
															$d[3]=number_format($d[3],2);				
															echo "<td style='text-align:right; color: red;'>$d[3]</td>";
															echo "<td>&nbsp;</td>";
														}
														else
														{
															$bal=$bal+$d[3];			
															$d[3]=number_format($d[3],2);								
															echo "<td>&nbsp;</td>";
															echo "<td style='text-align:right; color: green;'>$d[3]</td>";
														}
														if($bal >= 0) $color = "green"; else $color = "red";
														echo "<td  style='text-align:right; color: ".$color." '>".number_format($bal,2)."</td>";
 													?>
													
                                                </tr>
												<?php
													}
												?>
                                            </tbody>
                                        </table>                                    
                                    </div>
								</div>
						</div>
					</div>
					</div>
                    <!-- END WIDGETS --> 
				</div>
          </div>
        <!-- START PLUGINS -->
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