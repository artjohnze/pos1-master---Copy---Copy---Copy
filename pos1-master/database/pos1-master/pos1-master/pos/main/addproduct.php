<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="saveproduct.php" method="post">
	<center>
		<h4><i class="icon-plus-sign icon-large"></i> Add Product</h4>
	</center>
	<hr>
	<div id="ac">
		<span>Brand Name : </span><input type="text" style="width:265px; height:30px;" name="code" required /><br>
		<span>Generic Name : </span><input type="text" style="width:265px; height:30px;" name="gen" required /><br>
		<span>Description : </span><input type="text" style="width:265px; height:30px;" name="name" required /><br>

		<span>Expiry Date : </span><input type="date" style="width:265px; height:30px;" name="exdate" required /><br>
		<span>Selling Price : </span><input type="text" id="txt1" style="width:265px; height:30px;" name="price" onkeyup="sum();" required /><br>
		<span>Original Price : </span><input type="text" id="txt2" style="width:265px; height:30px;" name="o_price" onkeyup="sum();" required /><br>
		<span>Profit : </span><input type="text" id="txt3" style="width:265px; height:30px;" name="profit" readonly><br>
		<span>Supplier : </span>
		<select name="supplier" style="width:265px; height:30px;" required>
			<option value="">Select Supplier</option>
			<?php
			include('../connect.php');
			$result = $db->prepare("SELECT * FROM supliers");
			//$result->bindParam(':userid', $res);
			$result->execute();
			for ($i = 0; $row = $result->fetch(); $i++) {
			?>
				<option><?php echo $row['suplier_name']; ?></option>
			<?php
			}
			?>
		</select><br>
		<span>Quantity : </span><input type="number" style="width:265px; height:30px;" id="txt4" name="qty" onkeyup="sum();" required min="0" /><br>
		<div style="float:right; margin-right:10px;">
			<a href="products.php"><button type="button" class="btn btn-danger btn-large" style="width:130px; margin-right:5px;"><i class="icon-remove icon-small"></i> Cancel</button></a>
			<button id="saveBtn" type="submit" class="btn btn-success btn-large" style="width:130px; opacity:0.5;" disabled><i class="icon-save icon-small"></i> Save</button>
		</div>
	</div>
</form>