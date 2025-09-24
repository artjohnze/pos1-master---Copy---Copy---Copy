<?php
include('../connect.php');
$id = $_GET['id'];
$qty = $_GET['qty'];
$product_id = $_GET['product_id'];

// Update product quantities when deleting sale
$sql = "UPDATE products 
        SET qty=qty+?, qty_sold=qty_sold-?
		WHERE product_id=?";
$q = $db->prepare($sql);
$q->execute(array($qty, $qty, $product_id));

$result = $db->prepare("DELETE FROM sales_order WHERE transaction_id= :id");
$result->bindParam(':id', $id);
$result->execute();
header("location: sales_inventory.php");
