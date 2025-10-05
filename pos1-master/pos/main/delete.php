<?php
include('../connect.php');

// Get parameters
$id = $_GET['id'];
$c = $_GET['invoice'];
$sdsd = $_GET['dle'];
$qty = $_GET['qty'];
$wapak = $_GET['code'];

// Delete the item from temporary_cart (no need to restore inventory since it wasn't deducted yet)
$result = $db->prepare("DELETE FROM temporary_cart WHERE transaction_id= :memid");
$result->bindParam(':memid', $id);
$result->execute();

// Redirect back to sales page
header("location: sales.php?id=$sdsd&invoice=$c&success=item_removed");
exit;
