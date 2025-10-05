<?php
include('../connect.php');
$id = $_GET['id'];
$result = $db->prepare("SELECT * FROM products WHERE product_id= :userid");
$result->bindParam(':userid', $id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
?>
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="module" src="sweet.js"></script>
	<form action="saveeditproduct.php" method="post">
		<center>
			<h4><i class="icon-edit icon-large"></i> Edit Product</h4>
		</center>
		<hr>
		<div id="ac">
			<input type="hidden" name="memi" value="<?php echo $id; ?>" />
			<span>Brand Name : </span><input type="text" style="width:265px; height:30px;" name="code" value="<?php echo $row['product_code']; ?>" /><br>
			<span>Generic Name : </span><input type="text" style="width:265px; height:30px;" name="gen" value="<?php echo $row['gen_name']; ?>" /><br>
			<span>Description : </span><input type="text" style="width:265px; height:30px;" name="name" value="<?php echo $row['product_name']; ?>" /><br>
			<span>Expiry Date : </span><input type="date" style="width:265px; height:30px;" name="exdate" value="<?php echo $row['expiry_date']; ?>" /><br>
			<span>Selling Price : </span><input type="text" style="width:265px; height:30px;" id="txt1" name="price" value="<?php echo $row['price']; ?>" Required /><br>
			<span>Original Price : </span><input type="text" style="width:265px; height:30px;" id="txt2" name="o_price" value="<?php echo $row['o_price']; ?>" Required /><br>
			<span>Supplier : </span>
			<select name="supplier" style="width:265px; height:30px; margin-left:-5px;">
				<option><?php echo $row['supplier']; ?></option>
				<?php
				$results = $db->prepare("SELECT * FROM supliers");

				$results->execute();
				for ($i = 0; $rows = $results->fetch(); $i++) {
				?>
					<option><?php echo $rows['suplier_name']; ?></option>
				<?php
				}
				?>
			</select><br>
			<span>Current Stock: </span><input type="text" style="width:265px; height:30px;" name="qty" id="current_qty" value="<?php echo $row['qty']; ?>" /><br>

			<!-- Add Stock Section -->
			<div style="background: #f0f8ff; padding: 15px; margin: 10px 0; border: 1px solid #007bff; border-radius: 5px;">
				<strong style="color: #007bff;">📦 Add Stock</strong><br><br>
				<span>Add Quantity: </span>
				<input type="number" style="width:100px; height:30px; margin-right: 10px;" id="add_qty" min="1" placeholder="0" />
				<button type="button" onclick="addToStock()" style="background: #28a745; color: white; padding: 5px 15px; border: none; border-radius: 3px; cursor: pointer;">
					➕ Add to Stock
				</button>
				<br><br>
				<small style="color: #666;">Enter quantity to add to current stock. This will update the Current Stock field above.</small>
			</div>

			<div style="float:right; margin-right:10px;">
				<a href="products.php"><button type="button" class="btn btn-danger btn-small" style="width:130px; margin-right:5px;"><i class="icon-remove icon-small"></i> Cancel</button></a>
				<button class="btn btn-success btn-small" style="width:130px;"><i class="icon-save icon-small"></i> Save Changes</button>
			</div>
		</div>
	</form>

	<script>
		function addToStock() {
			// Get current values
			var currentStock = parseInt(document.getElementById('current_qty').value) || 0;
			var addQuantity = parseInt(document.getElementById('add_qty').value) || 0;

			// Validate add quantity
			if (addQuantity <= 0) {
				Swal.fire({
					icon: 'warning',
					title: 'Invalid Quantity',
					text: 'Please enter a valid quantity to add (greater than 0)',
					confirmButtonColor: '#ffc107'
				});
				return;
			}

			// Calculate new stock
			var newStock = currentStock + addQuantity;

			// Confirm the addition with SweetAlert
			Swal.fire({
				title: 'Add Stock Confirmation',
				html: '<div style="text-align: left; font-size: 16px;">' +
					'<p><strong>Current Stock:</strong> <span style="color: #007bff;">' + currentStock + '</span></p>' +
					'<p><strong>Adding:</strong> <span style="color: #28a745;">+' + addQuantity + '</span></p>' +
					'<p><strong>New Stock:</strong> <span style="color: #dc3545;">' + newStock + '</span></p>' +
					'</div>',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: '✅ Add to Stock',
				cancelButtonText: '❌ Cancel',
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d'
			}).then((result) => {
				if (result.isConfirmed) {
					// Update the current stock field
					document.getElementById('current_qty').value = newStock;

					// Clear the add quantity field
					document.getElementById('add_qty').value = '';

					// Show success message
					Swal.fire({
						icon: 'success',
						title: 'Stock Updated Successfully!',
						html: '<div style="text-align: center; font-size: 16px;">' +
							'<p><strong>New Stock:</strong> <span style="color: #28a745; font-size: 18px;">' + newStock + '</span></p>' +
							'<hr>' +
							'<p style="color: #856404;">📝 <strong>Don\'t forget to click "Save Changes"<br>to save to database!</strong></p>' +
							'</div>',
						timer: 4000,
						showConfirmButton: true,
						confirmButtonText: 'OK',
						confirmButtonColor: '#007bff'
					});
				}
			});
		}

		// Allow Enter key to trigger add stock
		document.getElementById('add_qty').addEventListener('keypress', function(event) {
			if (event.key === 'Enter') {
				addToStock();
			}
		});
	</script>
<?php
}
?>