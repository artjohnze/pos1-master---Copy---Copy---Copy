<?php
include('../connect.php');
$id = $_GET['id'];
$result = $db->prepare("SELECT * FROM supliers WHERE suplier_id= :userid");
$result->bindParam(':userid', $id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
?>
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
	<form action="saveeditsupplier.php" method="post">
		<center>
			<h4><i class="icon-edit icon-large"></i> Edit Supplier</h4>
		</center>
		<hr>
		<div id="ac">
			<input type="hidden" name="memi" value="<?php echo $id; ?>" />
			<span>Supplier Name : </span><input type="text" style="width:265px; height:30px;" name="name" value="<?php echo $row['suplier_name']; ?>" /><br>
			<span>Address : </span><input type="text" style="width:265px; height:30px;" name="address" value="<?php echo $row['suplier_address']; ?>" /><br>
			<span>Contact No.: </span><input type="text" style="width:265px; height:30px;" name="contact" value="<?php echo $row['suplier_contact']; ?>" /><br>
			<span>Note : </span><textarea style="width:265px; height:80px;" name="note"><?php echo $row['note']; ?></textarea><br>
			<div style="float:right; margin-right:10px;">
				<button type="submit" class="btn btn-success btn-large" style="width:130px; margin-right:5px;"><i class="icon icon-save icon-small"></i> Save</button>
				<button type="button" class="btn btn-danger btn-large" style="width:130px;" onclick="window.location.href='supplier.php';"><i class="icon icon-remove icon-small"></i> Cancel</button>
			</div>
		</div>
	</form>
<?php
}
?>