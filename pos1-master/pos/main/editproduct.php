<?php
include('../connect.php');
$id = $_GET['id'];
$result = $db->prepare("SELECT * FROM products WHERE product_id= :userid");
$result->bindParam(':userid', $id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
?>
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
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
			<span>Selling Price : </span><input type="text" style="width:265px; height:30px;" id="txt1" name="price" value="<?php echo $row['price']; ?>" onkeyup="sum();" Required /><br>
			<span>Original Price : </span><input type="text" style="width:265px; height:30px;" id="txt2" name="o_price" value="<?php echo $row['o_price']; ?>" onkeyup="sum();" Required /><br>
			<span>Profit : </span><input type="text" style="width:265px; height:30px;" id="txt3" name="profit" value="<?php echo $row['profit']; ?>" disabled /><br>
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
			<span>Quantity: </span><input type="number" style="width:265px; height:30px;" name="qty" value="<?php echo $row['qty']; ?>" /><br>

			<div style="float:right; margin-right:10px;">
				<a href="products.php"><button type="button" class="btn btn-danger btn-small" style="width:130px; margin-right:5px;"><i class="icon-remove icon-small"></i> Cancel</button></a>
				<button class="btn btn-success btn-small" style="width:130px;"><i class="icon-save icon-small"></i> Save Changes</button>
			</div>
		</div>
	</form>
<?php
}
?>