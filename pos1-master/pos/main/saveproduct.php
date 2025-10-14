<?php
session_start();
include('../connect.php');

$a = $_POST['code'];
$b = $_POST['name'];
$c = $_POST['exdate'];
$d = $_POST['price'];
$e = $_POST['supplier'];
$f = $_POST['qty']; // current stock
$i = $_POST['gen'];

// Set date_arrival to current date and time
$date_arrival = date('Y-m-d H:i:s');

// When adding a new product, set both qty and original_qty the same
$sql = "INSERT INTO products 
        (product_code, product_name, expiry_date, price, supplier, qty,  gen_name, date_arrival) 
        VALUES (:a, :b, :c, :d, :e, :f,  :i, :date_arrival)";

$q = $db->prepare($sql);
$q->execute(array(
    ':a' => $a,
    ':b' => $b,
    ':c' => $c,
    ':d' => $d,
    ':e' => $e,
    ':f' => $f,
    ':f' => $f, // Save the initial stock here
    ':i' => $i,
    ':date_arrival' => $date_arrival
));

header("location: products.php");
