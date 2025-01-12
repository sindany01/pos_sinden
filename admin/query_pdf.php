<?php
		$sqlpay = "SELECT d.* , p.* ,
				m.mem_name,o.order_date,o.order_status,o.pay_amount2
				FROM tbl_order_detail AS d
				INNER JOIN tbl_product AS p ON d.p_id=p.p_id
				INNER JOIN tbl_order AS o ON d.order_id=o.order_id
				INNER JOIN tbl_member as m ON o.mem_id=m.mem_id
				WHERE d.order_id=$order_id";

				$querypay = mysqli_query($condb, $sqlpay) 
				or die("Error : ".mysqli_error($sqlpay));

				$rowmember=mysqli_fetch_array($querypay);
				$st=$rowmember['order_status'];




?>