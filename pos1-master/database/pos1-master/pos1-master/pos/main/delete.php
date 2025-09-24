<?php
include('../connect.php');

// Get parameters with validation
$id = intval($_GET['id']); // transaction_id of specific item to delete
$c = $_GET['invoice'];
$sdsd = $_GET['dle'];
$qty = intval($_GET['qty']);
$wapak = $_GET['code'];

// Validate parameters
if ($id <= 0 || $qty <= 0 || empty($c) || empty($wapak)) {
	header("location: sales.php?id=$sdsd&invoice=$c");
	exit;
}

try {
	// Begin transaction for data integrity
	$db->beginTransaction();

	// First, restore the product quantity
	$sql = "UPDATE products 
			SET qty=qty+?, qty_sold=qty_sold-?
			WHERE product_id=?";
	$q = $db->prepare($sql);
	$q->execute(array($qty, $qty, $wapak));

	// Then delete ONLY the specific sales_order item by transaction_id
	$result = $db->prepare("DELETE FROM sales_order WHERE transaction_id= :memid");
	$result->bindParam(':memid', $id);
	$result->execute();

	// Commit the transaction
	$db->commit();
} catch (Exception $e) {
	// Rollback on error
	$db->rollback();
}

header("location: sales.php?id=$sdsd&invoice=$c");
exit;
