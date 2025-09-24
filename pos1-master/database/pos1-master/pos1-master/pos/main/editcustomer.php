<?php
include('../connect.php');
$id = $_GET['id'];
$result = $db->prepare("SELECT * FROM customer WHERE customer_id= :userid");
$result->bindParam(':userid', $id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
?>
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
	<form action="saveeditcustomer.php" method="post">
		<center>
			<h4><i class="icon-edit icon-large"></i> Edit Customer</h4>
		</center>
		<hr>
		<div id="ac">
			<input type="hidden" name="memi" value="<?php echo $id; ?>" />
			<span>Full Name : </span><input type="text" style="width:265px; height:30px;" name="name" value="<?php echo $row['customer_name']; ?>" /><br>
			<span>Address : </span><input type="text" style="width:265px; height:30px;" name="address" value="<?php echo $row['address']; ?>" /><br>
			<span>Contact : </span><input type="text" style="width:265px; height:30px;" name="contact" value="<?php echo $row['contact']; ?>" /><br>
			<span>Product Name : </span>
			<select style="width:265px; height:30px;" name="prod_name" id="product_select" required onchange="updatePrice()">
				<option value="">Select Product</option>
				<?php
				include('../connect.php');
				$prod_result = $db->prepare("SELECT * FROM products ORDER BY product_code ASC");
				$prod_result->execute();
				while ($prod_row = $prod_result->fetch()) {
					$selected = ($prod_row['product_code'] == $row['prod_name']) ? 'selected' : '';
					echo '<option value="' . $prod_row['product_code'] . '" data-price="' . $prod_row['price'] . '" ' . $selected . '>' . $prod_row['product_code'] . '</option>';
				}
				?>
			</select><br>
			<span>Total : </span><input type="text" style="width:265px; height:30px;" name="memno" id="total_field" value="<?php echo $row['membership_number']; ?>" readonly /><br>
			<span>Note : </span><textarea style="height:60px; width:265px;" name="note"><?php echo $row['note']; ?></textarea><br>
			<span>Expected Date: </span><input type="date" style="width:265px; height:30px;" name="date" value="<?php echo $row['expected_date']; ?>" placeholder="Date" /><br>
			<div style="float:right; margin-right:10px;">

				<button class="btn btn-success btn-block btn-large" style="width:267px;"><i class="icon icon-save icon-large"></i> Save Changes</button>
			</div>
		</div>
	</form>

	<script type="text/javascript">
		function updatePrice() {
			var selectElement = document.getElementById('product_select');
			var totalField = document.getElementById('total_field');
			var selectedOption = selectElement.options[selectElement.selectedIndex];

			if (selectedOption.value !== '') {
				var price = selectedOption.getAttribute('data-price');
				totalField.value = 'P ' + parseFloat(price).toFixed(2);
			} else {
				totalField.value = '';
			}
		}
	</script>
<?php
}
?>