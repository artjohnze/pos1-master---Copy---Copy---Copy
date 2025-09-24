<?php
session_start();
include('../connect.php');
$a = $_POST['invoice'];
$b = $_POST['product'];
$c = floatval($_POST['qty']); // Convert to float
$w = $_POST['pt'];
$date = $_POST['date'];
$discount = floatval($_POST['discount']); // Convert to float

$result = $db->prepare("SELECT * FROM products WHERE product_id= :userid");
$result->bindParam(':userid', $b);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $asasa = floatval($row['price']); // Convert to float
    $code = $row['product_code'];
    $gen = $row['gen_name'];
    $name = $row['product_name'];
    $p = floatval($row['profit']); // Convert to float
}

//edit qty and qty_sold
$sql = "UPDATE products 
        SET qty=qty-?, qty_sold=qty_sold+?
        WHERE product_id=?";
$q = $db->prepare($sql);
$q->execute(array($c, $c, $b));

$fffffff = $asasa - $discount; // Now both are floats
$d = $fffffff * $c; // Now both are floats
$profit = $p * $c; // Now both are floats

// query
$sql = "INSERT INTO sales_order (invoice,product,qty,amount,name,price,profit,product_code,gen_name,date) VALUES (:a,:b,:c,:d,:e,:f,:h,:i,:j,:k)";
$q = $db->prepare($sql);
$q->execute(array(':a' => $a, ':b' => $b, ':c' => $c, ':d' => $d, ':e' => $name, ':f' => $asasa, ':h' => $profit, ':i' => $code, ':j' => $gen, ':k' => $date));
header("location: sales.php?id=$w&invoice=$a");
