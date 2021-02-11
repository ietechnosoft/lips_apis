<?php
ob_start();
session_start();
$db = "luckycollegeerp" . $_REQUEST['sess'];
$con = mysqli_connect("localhost", "root", "", "$db");
$con1 = mysqli_connect("localhost", "root", "", "luckycollegeerp");
mysqli_set_charset($con, 'utf8');
mysqli_set_charset($con1, 'utf8');
date_default_timezone_set("Asia/Kolkata"); 

if ($_REQUEST['college_id'] == "1") {
    $fees = "fee_structure1";
    $gdcol = "gdcol1";
    $pros = "prosfee1";
    $img = "../../../assets/gdcol1.jpg";
} else if ($_REQUEST['college_id'] == "2") {
    $fees = "fee_structure2";
    $gdcol = "gdcol2";
    $pros = "prosfee2";
    $img = "../../../assets/gdcol2.jpg";
}

function no_to_words($no)
{
    $words = array('0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six', '7' => 'Seven', '8' => 'Eight', '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fouteen', '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen', '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty', '30' => 'Thirty', '40' => 'Fourty', '50' => 'Fifty', '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninty', '100' => 'Hundred', '1000' => 'Thousand', '100000' => 'Lakh', '10000000' => 'Crore');
    if ($no == 0)
        return ' ';
    else {
        $no = round($no, 0);
        $novalue = '';
        $highno = $no;
        $remainno = 0;
        $value = 100;
        $value1 = 1000;
        while ($no >= 100) {
            if (($value <= $no) && ($no  < $value1)) {
                $novalue = $words["$value"];
                $highno = (int)($no / $value);
                $remainno = $no % $value;
                break;
            }
            $value = $value1;
            $value1 = $value * 100;
        }
        if (array_key_exists("$highno", $words))
            return $words["$highno"] . " " . $novalue . " " . no_to_words($remainno);
        else {
            $unit = $highno % 10;
            $ten = (int)($highno / 10) * 10;
            return $words["$ten"] . " " . $words["$unit"] . " " . $novalue . " " . no_to_words($remainno);
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-language" content="hi" />

    <title>LUCKY INSTITUTE OF PROFESSIONAL STUDIES</title>
    <link rel="icon" href="../../../logo3.png" type="image/x-icon" />
    <script type="text/javascript">
        function printreceipt() {
            window.print()
        }
    </script>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }

        body {
            margin-top: 5.5mm;
            margin-bottom: 0.5mm;
            ;
            margin-right: 10.0mm;
            margin-left: 10.0mm;
        }
    </style>
    <style type="text/css">
        .style1 {
            font-size: 15px;
            font-weight: bold;
        }

        .style2 {
            font-size: 13px;
            font-weight: bold;
        }

        .style3 {
            font-size: 10px;
        }
    </style>
</head>

<!-- <body ONLOAD="printreceipt();"> -->
<body>
    <?php
    $d1 = mysqli_query($con, "select * from $pros where recno='" . $_REQUEST['recno'] . "'");
    $d = mysqli_fetch_row($d1);
    $p1 = mysqli_query($con, "select name,fname,mname,sms from $gdcol where s_id='$d[2]'");
    $p = mysqli_fetch_row($p1);
    $f1 = mysqli_query($con, "select course,batchyear,yearwise from $fees where f_id=$d[13]");
    $f = mysqli_fetch_row($f1);
    ?>
    <div align="justify">
        <table align="center" width="100%" style="text-align:justify; font-size:12px; border-collapse:collapse; line-height:12px;" cellpadding="3">
            <tr>
                <td colspan='4' align='center'><img src='<?php echo $img; ?>' height='82' width='95%' /></td>
            </tr>
            <tr>
                <td align="center" colspan="4" style="line-height:17px;" CLASS="style1">
                    <?php
                    $name = "LUCKY INSTITUTE OF PROFESSIONAL STUDIES";
                    ?>
                    PROSPECTUS RECEIPT
                </td>
            </tr>
            <tr style="border-bottom:1px solid black;">
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td width="17%">Rec. No.</td>
                <td width="51%"><strong>:
                        <?php echo $d[10]; ?>
                    </strong></td>
                <td width="11%">Date</td>
                <td width="21%"><strong>:
                        <?php $date = DateTime::createFromFormat('Y-m-d', $d[3]);
                        echo $date->format('d/m/Y'); ?>
                    </strong></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><strong>: <?php echo $p[0]; ?></strong></td>
                <td>Pros. No.</td>
                <td><strong>: </strong></td>
            </tr>
            <tr>
                <td>Course</td>
                <td><strong>: <?php echo "$f[0] ($f[1]) $f[2]"; ?></strong></td>
                <td>Roll No.</td>
                <td><strong>: &nbsp;</strong></td>
            </tr>
            <tr>
                <td>Father's Name</td>
                <td><strong>: <?php echo $p[1]; ?></strong></td>
                <td>Pan No.</td>
                <td><strong>: &nbsp;</strong></td>
            </tr>
            <tr>
                <td>Mother's Name</td>
                <td><strong>: <?php echo $p[2]; ?></strong></td>
                <td>Mobile No.</td>
                <td><strong>: <?php echo $p[3]; ?></strong></td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%" cellpadding="3" style="border-collapse:collapse; min-height:200px;" height="200px">
                        <tr style="border-bottom:1px solid black; border-top:1px solid black;" height="5px">
                            <th width="7%" align="center">S. No.</th>
                            <th width="77%" align="left">Particulars</th>
                            <th width="16%" align="right">Amount</th>
                        </tr>
                        <?php
                        echo "<tr height='5px' style='font-weight:bold;'>";
                        echo "<td align='center'>1</td>";
                        echo "<td align='left'>Prospectus Fees</td>";
                        echo "<td align='right'>" . number_format($d[4], 2) . "</td>";
                        echo "</tr>";
                        ?>
                        <tr style="border-bottom:1px solid black;">
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr style="border-bottom:1px solid black;" height="5px">
                            <td>&nbsp;</td>
                            <td><strong>TOTAL</strong></td>
                            <td align="right"><strong><?php echo number_format($d[4], 2); ?></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">Amt. in Words :<strong> INR <?php echo no_to_words($d[4]); ?> Only &nbsp; &nbsp; <span style="">Paid By: <?php echo $d[5];
                                                                                                                                            if ($d[5] != "Cash") echo "<br>Bank Name: $d[6] &nbsp; &nbsp; Cheque No./Transaction ID/Invoice No. : $d[7]"; ?></span></strong></td>
            </tr>
            <tr>
                <td colspan="4"><?php echo $d[8]; ?></td>
            </tr>
            <tr>
                <td colspan="2" style="line-height:15px;"><br>Prepared By : <?php if ($d[11] != 0) {
                                                                                $e = mysqli_fetch_row(mysqli_query($con1, "select empname from emp_details where emp_id='$d[11]'"));
                                                                            } else $e[0] = "Super Admin";
                                                                            echo $e[0]; ?></td>
                <td colspan="2" align="right" valign="top" style="line-height:15px;"><span class="style2">for <?php echo $name; ?></span><br /><br><br />Authorised Signatory</td>
            </tr>
            <tr style="border-bottom:1px solid black;">
                <td colspan="4"><span class="style3">NB</span></td>
            </tr>
            <tr>
                <td colspan="4" style="line-height:15px;"><span class="style3">&middot; SUBJECT TO JODHPUR JURISDICTION<BR />
                        &middot; SUBJECT TO REALIZATION OF CHEQUE / DEMAND DRAFT<BR>
                        &middot; FEE ONCE PAID WILL NOT BE REFUNDED OR TRANSFER IN ANY CASE<BR>
                        <?php $hj = mysqli_fetch_row(mysqli_query($con1, "select feefine from otherfee")); ?>
                        &middot; FINE OF RS <?php echo $hj[0]; ?>/- PER DAY WILL BE CHARGED AFTER THE DUE DATE</span><BR>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>