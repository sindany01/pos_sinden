<?php
require_once __DIR__ . '../../vendor/autoload.php';
include('../condb.php');
$order_id = mysqli_real_escape_string($condb,$_GET['order_id']);  
include('query_pdf.php');
	$tableh = '<style>
        body{
            font-family: "freemono"; //คือ TH salaban แปลงชื่อเนื่องจาก function เดิม ดักการเพิ่มของไฟล์ font ซึ่งแก้แล้วไม่ได้
        }
    </style>'.'<p style="text-align:center"><img src="../logo_fordev22_2.png" width="250px" /></p>'.
    '<h2 style="text-align:center"> เลขที่บิล '.$order_id.'<br>'.' ผู้ทำรายการขาย : '.$rowmember['mem_name'].
    '<br> สถานะ : '.$st.' วันที่ทำรายการ : '.date('d/m/Y',strtotime($rowmember['order_date'])).'</h2>'.

    '<table id="bg-table" width="100%" style="border-collapse: collapse;font-size:12pt;margin-top:8px;">
        <tr style="border:1px solid #000;padding:4px;">
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"   width="10%">ลำดับ</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">IMG</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="20%">สินค้า</td>
            <td  width="15%" style="border-right:1px solid #000;padding:4px;text-align:center;">&nbsp; ราคา/หน่วย </td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">จำนวน</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;" width="15%" align = "right">ราคารวม</td>
        </tr>
    </thead>';
    foreach($querypay as $rspay)
	{
		$i += 1;
	$tableb .= '<tr style="border:1px solid #000;padding:4px;">
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"width="10%">'. $i++ .'</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">'.
            '<img src="../p_img/'.$rspay['p_img'].'"width="20%">'.'
            </td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">'.$rspay["p_name"].'</td>
            <td  width="15%" style="border-right:1px solid #000;padding:4px;text-align:center;">'.$rspay["p_price"].'</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">'.$rspay["p_c_qty"].'</td>
            <td  style="border-right:1px solid #000;padding:4px;text-align:center;" width="15%" align = "right">'.$rspay["total"].'</td>
        </tr>';
	}

	$query_my_order = "SELECT * FROM tbl_order 
	WHERE order_id = $order_id" or die ("Error : ".mysqlierror($query_my_order));
	$rs_my_order = mysqli_query($condb, $query_my_order);
	$row=mysqli_fetch_array($rs_my_order);
	$row_tt = $row['pay_amount2'] - $row['pay_amount'];

	$tablef ='<tr style="border:1px solid #000;padding:4px;">
			<td colspan="5"  style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">ยอดรวม</td>
            <td   style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">'.$row['pay_amount'].'
            </td>
            </tr>
            <tr style="border:1px solid #000;padding:4px;">
			<td colspan="5"  style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">รับชำระ</td>
            <td style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">'.$row['pay_amount2'].'
            </td>
            </tr>
            <tr style="border:1px solid #000;padding:4px;">
			<td colspan="5"  style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">เงินทอน</td>
            <td   style="border-right:1px solid #000;padding:4px;text-align:right;"width="10%">'.number_format($row_tt,2).'
            </td>
            </tr>
	';
    $table = '</table>';
$mpdf = new mPDF();
$mpdf->WriteHTML($tableh);
$mpdf->WriteHTML($tableb);
$mpdf->WriteHTML($tablef);
$mpdf->WriteHTML($table);
$mpdf->Output();
?>