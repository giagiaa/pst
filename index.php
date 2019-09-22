<?php 
    include 'connect.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>List Barang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    
	<script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tableHTMLExport.js"></script>
    <script src="js/jspdf.min.js"></script>
    <script src="js/jspdf.plugin.autotable.min.js"></script>
    <script src="js/jquery.session.js"></script>
    <script src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

</head>
<body>
    <?php
        function getAttributeData() {
            include 'connect.php';

            $commentTag = "SELECT DISTINCT Comment FROM item_attribute";
            $resultCommentTag = $conn->query($commentTag);

            if ($resultCommentTag->num_rows > 0) {
                while ($row = $resultCommentTag->fetch_assoc()) {
                    echo "<input type='submit' name='btnTag' class='btnTag' value='" . $row['Comment'] . "' /> &nbsp; &nbsp;";
                }
            } else {
                echo "<script type='text/javascript'>alert('Data Attribute Tidak Ditemukan');</script>";
            }

            $conn->close();
        }

        function getItemData($query) {
            include 'connect.php';

            $resultCount = $conn->query($query);

            if ($resultCount->num_rows > 0) {
                while ($row = $resultCount->fetch_assoc()) {
                    echo "<tr class='dataItem'>";
                    echo    "<td>";
					echo 		"<label class='lblPortalDescription'>" . $row["portal_description"] . "</label>";
					echo 		"<input type='hidden' class='hdnNo' value='" . $row["item_no"] . "' />";
					echo 	"</td>";
                    echo    "<td><label class='lblPortalPartNo'>" . $row["portal_part_no"] . "</label></td>";
                    echo    "<td><label class='lblManufacturerCode'>" . $row["manufacturer_code"] . "</label></td>";
                    echo    "<td align='right'><label class='lblUnitPrice' name='lblUnitPrice' id='lblUnitPrice" . $row["id"] . "'>" . $row["unit_price"] . "</label></td>";
                    echo    "<td>";
					echo 		"<input type='number' class='txtQtyBarang' name='txtQtyBarang' id='txtQtyBarang" . $row["id"] . "'>";
					echo 		"<input type='hidden' name='txtQtyBarang" . $row["id"] . "'>";
					echo 	"</td>";
                    echo    "<td align='right'><label class='lblTotalHarga' name='lblTotalHarga' id='lblTotalHarga" . $row["id"] . "'>0</label></td>";
                    echo "</tr>";  
                }
            } else {
                echo "<script type='text/javascript'>alert('Data Tidak Ditemukan');</script>";
            }

            $conn->close();
        }
    ?>
	
	<br>
	
	<div align="center">
		<input type="image" src="img/abadi_auto_logo.png" alt="Submit"> 
	</div>
		
    <br>

    <!-- search bar, TAG & Dropdown -->
    <form method="post">
		<!-- tag search -->
		<div id="divTag" class="searchTag" align="center">
			<?php getAttributeData(); ?>
		</div>
		
		<br>
		
        <div class="wrap">
            <div class="search" align="center">

                <!-- Dropdown -->
                <!-- <select name="dropdownSearch" class="browser-default custom-select" style="width:auto;">
                    <option value=""> - SELECT - </option>
                    <?php
                        // $queryGetDropdown = "SELECT * FROM search_dropdown";
                        // $resultCount = $conn->query($queryGetDropdown);

                        // if ($resultCount->num_rows > 0) {
                        //     while ($row = $resultCount->fetch_assoc()) {
                        //         echo "<option value='" . $row["sd_name"] . "'>" . $row["sd_description"] . "</option>";
                        //     }
                        // }

                        // $conn->close();
                    ?>
                </select> -->

                &nbsp; &nbsp; &nbsp; &nbsp;

                <!-- Search Bar -->
                <!-- <input type="text" class="searchTerm" name="txtSearch" id="txtSearch" placeholder="Ketik Barang yang Sedang Dicari">
                <button type="submit" name="btnSearchItem" id="btnSearchItem" class="searchButton">
                    <i class="fa fa-search"></i>
                </button> -->
                <!--<input type="submit" class="btn btn-primary" name="getNewData" id="getNewData" value="Tarik Data Baru" />-->
            </div>
        </div>
    </form>

    <br>

    <!-- Konfirmasi Barang Modal Bootstrap -->
    <div class="wrap" align="center">
        <input type="submit" class="btn btn-success" name="btnConfirmOrder" id="btnConfirmOrder" value="Konfirmasi Barang" />
    </div>

    <br>

    <!-- table content -->
    <div class="container">
        <table class="table table-bordered" id="tblData">
            <thead>
                <tr>
                    <th>Part Number (Vendor Item No)</th>
                    <th>NAMA BARANG</th>
                    <th>Merek</th>
                    <th>Unit Price</th>
                    <th>Jumlah Barang</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!isset($_POST['btnSearchItem'])) {
                        $querySelectItem = "SELECT * FROM item";
                        getItemData($querySelectItem);
                    }
                    
                    if (isset($_POST['getNewData'])) {
						include 'connect.php';
                        $truncateTableItem = "TRUNCATE TABLE item";
                        $truncateTableItemAttribute = "TRUNCATE TABLE item_attribute";
						
						$jsonPortalItemAttribute = file_get_contents('https://api.myjson.com/bins/xnbal');
						$jsonPortalItems = file_get_contents('https://api.myjson.com/bins/rrc4d');
						
						$jsonObjectPortalItemAttribute = json_decode($jsonPortalItemAttribute);
						$jsonObjectPortalItems = json_decode($jsonPortalItems);
						
						$arrayJSONPortalItemAttribute = json_encode($jsonObjectPortalItemAttribute->value, true);
						$arrayJSONPortalItems = json_encode($jsonObjectPortalItems->value, true);
						
						$arrayAttributeValue = json_decode($arrayJSONPortalItemAttribute, true);
						$arrayValue = json_decode($arrayJSONPortalItems, true);

                        $insertItem = "INSERT INTO item (item_no, 
                                                    full_search_description, 
                                                    unit_price, 
                                                    manufacturer_code, 
                                                    vendor_item_no, 
                                                    portal_part_no, 
                                                    portal_description, 
                                                    portal_item_category_code, 
                                                    total_sales_qty) VALUES ";
                                                    
                        $insertItemAttribute = "INSERT INTO item_attribute (table_name, 
                                                    no, 
                                                    code, 
                                                    comment, 
                                                    auxiliary_index_one) VALUES ";

                        mysqli_query($conn, $truncateTableItem);
                        mysqli_query($conn, $truncateTableItemAttribute);

                        if ((is_array($arrayValue) || is_object($arrayValue)) && is_array($arrayAttributeValue) || is_object($arrayAttributeValue)) {
                            foreach ($arrayValue as $key => $value) {
                                $insertItem .= "('" . $value["No"] . "', 
                                                '" . $value["Full_Search_Description"] . "',
                                                " . $value["Unit_Price"] . ", 
                                                '" . $value["Manufacturer_Code"] . "', 
                                                '" . $value["Vendor_Item_No"] . "',
                                                '" . $value["Portal_Part_No"] . "',
                                                '" . $value["Portal_Description"] . "', 
                                                '" . $value["Portal_Item_Category_Code"] . "', 
                                                " . $value["TotalSalesQty"] . "), ";
                            }

                            foreach ($arrayAttributeValue as $key => $value) {
                                $insertItemAttribute .= "('" . $value["Table_Name"] . "', 
                                                '" . $value["No"] . "',
                                                '" . $value["Code"] . "', 
                                                '" . $value["Comment"] . "',  
                                                " . $value["AuxiliaryIndex1"] . "), ";
                            }
                            
                            $insertItem = rtrim($insertItem, ', ');
                            $insertItemAttribute = rtrim($insertItemAttribute, ', ');
                        } else {
                            echo 'Maaf, terjadi kesalahan pada data, silahkan periksa data kembali';
                        }

                        if (mysqli_query($conn, $insertItem) === TRUE && mysqli_query($conn, $insertItemAttribute) === TRUE) {
                            echo "<p align='center' style='color:green'>Data Telah Diperbaharui</p>";
                        } else {
                            echo "Error: " . $insertItem . "<br>" . $conn->error . "<br><br><br>";
                            echo "Error: " . $insertItemAttribute . "<br>" . $conn->error;
                        }

                        $querySelectItem = "SELECT * FROM item";
                        getItemData($querySelectItem);
                    }

                    if (isset($_POST['btnSearchItem']) && (strlen($_POST['txtSearch']) >= 4 || strlen($_POST['txtSearch']) == 0)) {
						include 'connect.php';
						
						/*if (isset($_POST['btnTag'])) {
							if ($_POST['dropdownSearch'] != '') {
								$leftJoinItemAttribute = " LEFT JOIN item_attribute b ON a.item_no = b.no ";
								$whereComment = " AND b.comment = '" . $_POST['btnTag'] . "'";
							}
						}*/
						
						
						//$_SESSION['get'] = $_POST['txtQtyBarang'];
						//echo $_SESSION['get'];
						
						$queryPortalDesc = "";
						$queryFullSearchDesc = "";
						$valueSearch = trim($_POST['txtSearch'], " ");
						$explodeValueSearch = explode(" ",$valueSearch);
						
						$queryItemSelect = "";
						$queryItemSelect .= "SELECT a.* from item a WHERE ";
						
						foreach ($explodeValueSearch as $value) {
							$queryPortalDesc .= " OR a.portal_description LIKE '%" . $value . "%'";
							$queryFullSearchDesc .= " OR a.full_search_description LIKE '%" . $value . "%'";
						}
						
						$rtrimQPD = rtrim($queryPortalDesc, " OR");
						$ltrimQPD = ltrim($rtrimQPD, " OR");
						$rtrimQFSD = rtrim($queryFullSearchDesc, " OR");
						
						$fullQuery = $queryItemSelect . $ltrimQPD . $rtrimQFSD;
                        
						/*$resultQD = $conn->query($fullQuery);
						if ($resultQD->num_rows == 0) {
							$fullQuery = "SELECT a.* FROM item a WHERE full_search_description LIKE '%" . $valueSearch . "%'";
						}*/
                        
                        if (isset($_POST['dropdownSearch'])) {
                            if ($_POST['dropdownSearch'] != '') {
								$fullQuery = " SELECT a.* FROM item a WHERE a." . $_POST['dropdownSearch'] . " LIKE '%" . $valueSearch . "%'";
                            }
                        }
						
						getItemData($fullQuery);
						
						$conn->close();
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Dialog -->
    <div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
				<form method="post" id="insertIntoDB">
					<div class="modal-footer">
						<input type="submit" class="btn btn-success" name="btnPDF" id="btnPDF" value="Jadikan PDF" />
					</div>
					<div class="modal-body">
						<div class="form-inline">
							<input type="text" class="form-control" id="txtName" name="txtName" placeholder="Name" />&nbsp; &nbsp;
							<input type="text" class="form-control" id="txtContact" name="txtContact" placeholder="Contact" />&nbsp; &nbsp;
							<input type="text" class="form-control" id="txtAddress" name="txtAddress" placeholder="Address" />
						</div> <br>
						<div class="form-inline">
							<input type="text" class="form-control" id="txtAddress2" name="txtAddress2" placeholder="Address 2" />&nbsp; &nbsp;
							<input type="text" class="form-control" id="txtPhoneNo" name="txtPhoneNo" placeholder="Phone No" />&nbsp; &nbsp;
							<input type="text" class="form-control" id="txtQuotationNo" name="txtQuotationNo" placeholder="Quotation No" />
						</div> <br>
						
						<table id="tblSelectedData" class="table table-bordered">
							<thead>
								<tr>
									<th class="kepala">Part Number (Vendor Item No)</th>
									<th class="kepala">NAMA BARANG</th>
									<th class="kepala">Merek</th>
									<th class="kepala">Unit Price</th>
									<th class="kepala">Jumlah Barang</th>
									<th class="kepala">Total Harga</th>
								</tr>
							</thead>
							<tbody id="tbodyKonfirmasiData">
							</tbody>
						</table>
					</div>
				</form>
            </div>
        </div>
    </div>
</body>
</html>

<script>
	$(document).ready(function(){
		$('#insertIntoDB').on("submit", function(event){  
			event.preventDefault();
			
			var d = new Date();
			var month = d.getMonth() + 1;
			var day = d.getDate();

			var outputDate = d.getFullYear() + 
				((''+month).length<2 ? '0' : '') + month + 
				((''+day).length<2 ? '0' : '') + day;
			
			if($('#txtName').val() == "") {  
				alert("Name is required");  
			} else if ($('#txtContact').val() == "") {
				alert("Contact is required");
			} else if ($('#txtAddress').val() == "") {
				alert("Address is required");
			} else if ($('#txtAddress2').val() == "") {
				alert("Second Address is required");
			} else if ($('#txtPhoneNo').val() == "") {
				alert("Phone No is required");
			} else if ($('#txtQuotationNo').val() == "") {
				alert("Quotation No is required");
			} else {
				$.ajax({  
				url:"insertFinalData.php",  
				method:"POST",  
				data:$('#insertIntoDB').serialize(),  
				success:function(data) {
					$('#classModal').modal('hide');
					alert('Data Berhasil Disimpan!');
					$("#tblSelectedData").tableHTMLExport({type:'pdf',filename:$('#txtName').val() + "_" + outputDate + ".pdf"});
				}
				});
			}				
		});
	});

    const dataTable = $('#tblData').DataTable();

    $(document).on('click',"#btnConfirmOrder",function() {
        var tableData = "";
		var jumlahTotalHarga = 0;
        var count = 0;
		var i = 0;
        let data = []

        dataTable.$('tr.dataItem').each(function(i,item) {
            if($(this).find("input.txtQtyBarang").val() != ""){
                console.log(this)
            $('#trKonfirmasiData').remove();
            $('#sumTotalPrice').remove();
            var portalDescription = $(this).find("label.lblPortalDescription").text();
            var portalPartNo = $(this).find("label.lblPortalPartNo").text();
            var manufacturerCode = $(this).find("label.lblManufacturerCode").text();
            var unitPrice = $(this).find("label.lblUnitPrice").text();
            var qtyBarang = $(this).find("input.txtQtyBarang").val();
            var noItem = $(this).find("input.hdnNo").val();
            var totalHarga = $(this).find("label.lblTotalHarga").text();
			
            if (qtyBarang != "" && qtyBarang != "0") {
                $('#classModal').modal('show');
                count += 1;
				
				$.session.set("storeQtyValue", qtyBarang);
				alert($.session.get("storeQtyValue"));
				
				//calculating total price
				var find = ",";
				var re = new RegExp(find, "g");
				parsedTotalHarga = parseInt(totalHarga.replace("Rp ", "").replace(re, "").replace(".00", ""));
				jumlahTotalHarga = jumlahTotalHarga + parsedTotalHarga;

                tableData += "<tr id='trKonfirmasiData'>";
                tableData +=    "<td>";
                tableData +=    	portalDescription;
                tableData +=    	"<input type='hidden' name='hdnNoItem" + count + "' value='" + noItem + "' />";
                tableData +=    "</td>";
                tableData +=    "<td>" + portalPartNo + "</td>";
                tableData +=    "<td>" + manufacturerCode + "</td>";
                tableData +=    "<td>" + unitPrice + "</td>";
                tableData +=    "<td>";
                tableData +=    	qtyBarang;
                tableData +=    	"<input type='hidden' name='hdnQtyBarang" + count + "' value='" + qtyBarang + "' />";
                tableData +=    "</td>";
				tableData +=    "<td>";
                tableData +=    	totalHarga;
                tableData +=    	"<input type='hidden' name='hdnTotalHarga" + count + "' value='" + parsedTotalHarga + "' />";
                tableData +=    "</td>";
                tableData += "</tr>";
            }
            }
        })

        
        // data.each(function() {
        //     $('#trKonfirmasiData').remove();
        //     $('#sumTotalPrice').remove();
        //     var portalDescription = $(this).find("label.lblPortalDescription").text();
        //     var portalPartNo = $(this).find("label.lblPortalPartNo").text();
        //     var manufacturerCode = $(this).find("label.lblManufacturerCode").text();
        //     var unitPrice = $(this).find("label.lblUnitPrice").text();
        //     var qtyBarang = $(this).find("input.txtQtyBarang").val();
        //     var noItem = $(this).find("input.hdnNo").val();
        //     var totalHarga = $(this).find("label.lblTotalHarga").text();
			
        //     if (qtyBarang != "" && qtyBarang != "0") {
        //         $('#classModal').modal('show');
        //         count += 1;
				
		// 		$.session.set("storeQtyValue", qtyBarang);
		// 		alert($.session.get("storeQtyValue"));
				
		// 		//calculating total price
		// 		var find = ",";
		// 		var re = new RegExp(find, "g");
		// 		parsedTotalHarga = parseInt(totalHarga.replace("Rp ", "").replace(re, "").replace(".00", ""));
		// 		jumlahTotalHarga = jumlahTotalHarga + parsedTotalHarga;

        //         tableData += "<tr id='trKonfirmasiData'>";
        //         tableData +=    "<td>";
        //         tableData +=    	portalDescription;
        //         tableData +=    	"<input type='hidden' name='hdnNoItem" + count + "' value='" + noItem + "' />";
        //         tableData +=    "</td>";
        //         tableData +=    "<td>" + portalPartNo + "</td>";
        //         tableData +=    "<td>" + manufacturerCode + "</td>";
        //         tableData +=    "<td>" + unitPrice + "</td>";
        //         tableData +=    "<td>";
        //         tableData +=    	qtyBarang;
        //         tableData +=    	"<input type='hidden' name='hdnQtyBarang" + count + "' value='" + qtyBarang + "' />";
        //         tableData +=    "</td>";
		// 		tableData +=    "<td>";
        //         tableData +=    	totalHarga;
        //         tableData +=    	"<input type='hidden' name='hdnTotalHarga" + count + "' value='" + parsedTotalHarga + "' />";
        //         tableData +=    "</td>";
        //         tableData += "</tr>";
        //     }
        // });
		
		tableData += "<tr id='sumTotalPrice'>";
		tableData +=    "<td colspan='4'></td>";
		tableData +=    "<td>Total Price:</td>";
		tableData +=    "<td class='sumTotalPrice'>"; 
		tableData += 		"<b>" + formatCurrency(jumlahTotalHarga) + "</b>";
		tableData += 		"<input type='hidden' name='hdnTotalHarga' id='hdnTotalHarga' value='" + jumlahTotalHarga + "'/>";
		tableData += 		"<input type='hidden' name='hdnCount' id='hdnCount' value='" + count + "'/>";
		tableData += 	"</td>";
		tableData += "</tr>";

        if (count == 0) {
            alert('Anda belum memilih Barang');
        }

        $('#tbodyKonfirmasiData').append(tableData);
    });

    function formatCurrency(total) {
        var neg = false;
        if(total < 0) {
            neg = true;
            total = Math.abs(total);
        }
        return (neg ? '-Rp ' : 'Rp ') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
    }

    /*$('.lblUnitPrice').ready(function(){
        var getID = $('.txtQtyBarang').attr('id').replace('txtQtyBarang', '');
        var getUnitPrice = $('#lblUnitPrice' + getID).text();
        //alert(getUnitPrice);
        $('#lblUnitPrice' + getID).text(formatCurrency(getUnitPrice));
    });*/


    $('#btnSearchItem').click(function () {
        var lengthTxtSearch = $("#txtSearch").val().length;
        if (lengthTxtSearch <= 3 && lengthTxtSearch != 0) {
            alert('Masukkan Minimal 4 Karakter');
            return false;
        }
    });

    dataTable.$('.txtQtyBarang').bind('keyup mouseup', function () {
        console.log(this)
            var getID = $(this).attr('id').replace('txtQtyBarang', '');
        var textboxValue = $('#txtQtyBarang' + getID).val();
        var getUnitPrice = $('#lblUnitPrice' + getID).text();
        var totalPrice = textboxValue * getUnitPrice; 

        if (textboxValue == '' || textboxValue == null) {
            $('#txtQtyBarang' + getID).text('0');
            $('#lblTotalHarga' + getID).text('0');
        } else {
            $('#lblTotalHarga' + getID).text(formatCurrency(totalPrice));
        }
    });
</script>
