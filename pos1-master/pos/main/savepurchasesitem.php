<?php
session_start();
include('../connect.php');

// Validate POST data
if (!isset($_POST['invoice']) || !isset($_POST['product']) || !isset($_POST['qty'])) {
    die('Error: Missing required data');
}

$a = $_POST['invoice'];
$b = $_POST['product'];
$c = (int)$_POST['qty']; // Convert to integer

if ($c <= 0) {
    die('Error: Quantity must be greater than 0');
}

$result = $db->prepare("SELECT * FROM products WHERE product_code= :userid");
$result->bindParam(':userid', $b);
$result->execute();

$asasa = 0; // Initialize with default value
$product_found = false;

for ($i = 0; $row = $result->fetch(); $i++) {
    $asasa = (float)$row['price']; // Convert to float
    $product_found = true;
}

if (!$product_found) {
    die('Error: Product not found');
}

//edit qty
$sql = "UPDATE products 
        SET qty=qty+?
		WHERE product_code=?";
$q = $db->prepare($sql);
$q->execute(array($c, $b));

$d = $asasa * $c; // Now both are numeric
// query
$sql = "INSERT INTO purchases_item (name,qty,cost,invoice) VALUES (:a,:b,:c,:d)";
$q = $db->prepare($sql);
$q->execute(array(':a' => $b, ':b' => $c, ':c' => $d, ':d' => $a));

header("location: purchasesportal.php?iv=" . urlencode($a));
exit;
