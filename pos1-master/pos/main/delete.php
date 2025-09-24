<?php
include('../connect.php');

// Get parameters
$id = $_GET['id'];
$c = $_GET['invoice'];
$sdsd = $_GET['dle'];
$qty = $_GET['qty'];
$wapak = $_GET['code'];

// Restore product quantity first
$sql = "UPDATE products SET qty=qty+:qty WHERE product_id=:wapak";
$q = $db->prepare($sql);
$q->bindParam(':qty', $qty);
$q->bindParam(':wapak', $wapak);
$q->execute();

// Delete the item from sales_order
$result = $db->prepare("DELETE FROM sales_order WHERE transaction_id= :memid");
$result->bindParam(':memid', $id);
$result->execute();

// Redirect back to sales page
header("location: sales.php?id=$sdsd&invoice=$c&success=item_removed");
exit;
