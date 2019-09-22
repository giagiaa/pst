<?php 
	include 'connect.php';
	if(!empty($_POST)) {
		$name = mysqli_real_escape_string($conn, $_POST["txtName"]);  
		$contact = mysqli_real_escape_string($conn, $_POST["txtContact"]);  
		$address = mysqli_real_escape_string($conn, $_POST["txtAddress"]);  
		$address2 = mysqli_real_escape_string($conn, $_POST["txtAddress2"]);  
		$phoneNo = mysqli_real_escape_string($conn, $_POST["txtPhoneNo"]);  
		$quotationNo = mysqli_real_escape_string($conn, $_POST["txtQuotationNo"]);  
		$totalHarga = mysqli_real_escape_string($conn, $_POST["hdnTotalHarga"]);
		$count = mysqli_real_escape_string($conn, $_POST["hdnCount"]);
		$intCount = (int)$count;
		$date = date("Y-m-d");
		
		$queryInsertCustomer = "INSERT INTO customer (customer_name, 
													customer_contact,
													customer_address,
													customer_address_2,
													customer_phone_no) VALUES 
													('" . $name . "',
													'" . $contact . "',
													'" . $address . "',
													'" . $address2 . "',
													'" . $phoneNo . "'
													)";
		
		$queryInsertQH = "INSERT INTO quotation_header (qh_no, 
														qh_customer_phone_no,
														qh_date,
														qh_total_amount) VALUES 
														('" . $quotationNo . "',
														'" . $phoneNo . "',
														'" . $date . "',
														'" . $totalHarga . "'
														)";
														
		$queryInsertQD = "INSERT INTO quotation_detail (qd_no, 
														qd_item_no, 
														qd_quantity, 
														qd_discount_percentage,
														qd_discount_rupiah,
														qd_line_amount) VALUES ";
														
		
		for($i=1;$i<=$intCount;$i++) {
			$noItem = mysqli_real_escape_string($conn, $_POST["hdnNoItem" . $i]);
			$qtyBarang = mysqli_real_escape_string($conn, $_POST["hdnQtyBarang" . $i]);
			$totalHarga = mysqli_real_escape_string($conn, $_POST["hdnTotalHarga" . $i]);
			
			$queryInsertQD .= "('" . $quotationNo . "', 
                            '" . $noItem . "', 
                            " . $qtyBarang . ", 
                            0.0, 
                            0.0, 
							" . $totalHarga . "), ";
		}
		
		$queryInsertQD = rtrim($queryInsertQD, ', ');
																					
		mysqli_query($conn, $queryInsertCustomer);
		mysqli_query($conn, $queryInsertQH);
		mysqli_query($conn, $queryInsertQD);
	}
?>
