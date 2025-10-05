<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form id="productForm" action="saveproduct.php" method="post" onsubmit="return validateForm()">
	<center>
		<h4><i class="icon-plus-sign icon-large"></i> Add Product</h4>
	</center>
	<hr>
	<div id="ac">
		<span>Brand Name : </span><input type="text" style="width:265px; height:30px;" name="code" id="brandName" required /><br>
		<div id="brandNameError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Brand Name is required</div>

		<span>Generic Name : </span><input type="text" style="width:265px; height:30px;" name="gen" id="genericName" required /><br>
		<div id="genericNameError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Generic Name is required</div>

		<span>Description : </span><input type="text" style="width:265px; height:30px;" name="name" id="description" required /><br>
		<div id="descriptionError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Description is required</div>

		<span>Expiry Date : </span><input type="date" style="width:265px; height:30px;" name="exdate" id="expiryDate" required /><br>
		<div id="expiryDateError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Expiry Date is required</div>

		<span>Selling Price : </span><input type="text" id="txt1" style="width:265px; height:30px;" name="price" required /><br>
		<div id="sellingPriceError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Selling Price is required</div>

		<span>Original Price : </span><input type="text" id="txt2" style="width:265px; height:30px;" name="o_price" required /><br>
		<div id="originalPriceError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Original Price is required</div>
		<span>Supplier : </span>
		<select name="supplier" style="width:265px; height:30px;" id="supplier" required>
			<option value="">Select Supplier</option>
			<?php
			include('../connect.php');
			$result = $db->prepare("SELECT * FROM supliers");
			//$result->bindParam(':userid', $res);
			$result->execute();
			for ($i = 0; $row = $result->fetch(); $i++) {
			?>
				<option value="<?php echo $row['suplier_name']; ?>"><?php echo $row['suplier_name']; ?></option>
			<?php
			}
			?>
		</select><br>
		<div id="supplierError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Please select a supplier</div>

		<span>Quantity : </span><input type="number" style="width:265px; height:30px;" id="txt4" name="qty" required min="0" /><br>
		<div id="quantityError" style="color: red; font-size: 12px; margin-left: 95px; display: none;">Quantity is required and must be 0 or greater</div>

		<div style="float:right; margin-right:10px;">
			<button id="saveBtn" type="submit" class="btn btn-success btn-large" style="width:130px; margin-right:5px;"><i class="icon icon-save icon-large"></i> Save</button>
			<button type="button" class="btn btn-danger btn-large" style="width:130px;" onclick="window.location.href='products.php';"><i class="icon icon-remove icon-large"></i> Cancel</button>
		</div>
	</div>
</form>

<script>
	function validateForm() {
		let isValid = true;

		// Hide all error messages first
		hideAllErrors();

		// Brand Name validation
		const brandName = document.getElementById('brandName').value.trim();
		if (brandName === '') {
			showError('brandNameError');
			isValid = false;
		}

		// Generic Name validation
		const genericName = document.getElementById('genericName').value.trim();
		if (genericName === '') {
			showError('genericNameError');
			isValid = false;
		}

		// Description validation
		const description = document.getElementById('description').value.trim();
		if (description === '') {
			showError('descriptionError');
			isValid = false;
		}

		// Expiry Date validation
		const expiryDate = document.getElementById('expiryDate').value.trim();
		if (expiryDate === '') {
			showError('expiryDateError');
			isValid = false;
		}

		// Selling Price validation
		const sellingPrice = document.getElementById('txt1').value.trim();
		if (sellingPrice === '') {
			showError('sellingPriceError');
			isValid = false;
		}

		// Original Price validation
		const originalPrice = document.getElementById('txt2').value.trim();
		if (originalPrice === '') {
			showError('originalPriceError');
			isValid = false;
		}

		// Supplier validation
		const supplier = document.getElementById('supplier').value;
		if (supplier === '') {
			showError('supplierError');
			isValid = false;
		}

		// Quantity validation
		const quantity = document.getElementById('txt4').value.trim();
		if (quantity === '' || quantity < 0) {
			showError('quantityError');
			isValid = false;
		}

		return isValid;
	}

	function showError(errorId) {
		document.getElementById(errorId).style.display = 'block';
	}

	function hideAllErrors() {
		const errors = ['brandNameError', 'genericNameError', 'descriptionError', 'expiryDateError', 'sellingPriceError', 'originalPriceError', 'supplierError', 'quantityError'];
		errors.forEach(function(errorId) {
			document.getElementById(errorId).style.display = 'none';
		});
	}
</script>