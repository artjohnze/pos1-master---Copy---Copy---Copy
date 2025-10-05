<?php
session_start();
include('../connect.php');
$a = $_POST['invoice'];
$b = $_POST['product'];
$c = floatval($_POST['qty']); // Convert to float
$w = $_POST['pt'];
$date = $_POST['date'];
$discount = floatval($_POST['discount']); // Convert to float

// Check if product has enough stock
$result = $db->prepare("SELECT * FROM products WHERE product_id= :userid");
$result->bindParam(':userid', $b);
$result->execute();
$row = $result->fetch();

if (!$row) {
    header("location: sales.php?id=$w&invoice=$a&error=product_not_found");
    exit();
}

$asasa = floatval($row['price']); // Convert to float
$code = $row['product_code'];
$gen = $row['gen_name'];
$available_qty = intval($row['qty']);

// Check if enough stock available
if ($available_qty < $c) {
    header("location: sales.php?id=$w&invoice=$a&error=insufficient_stock");
    exit();
}

// Create temporary_cart table if it doesn't exist
$create_temp_table = "CREATE TABLE IF NOT EXISTS temporary_cart (
    transaction_id int(11) NOT NULL AUTO_INCREMENT,
    invoice varchar(100) NOT NULL,
    product varchar(100) NOT NULL,
    qty varchar(100) NOT NULL,
    amount varchar(100) NOT NULL,
    product_code varchar(150) NOT NULL,
    gen_name varchar(200) NOT NULL,
    price varchar(100) NOT NULL,
    discount varchar(100) NOT NULL,
    date varchar(500) NOT NULL,
    session_id varchar(100) NOT NULL,
    PRIMARY KEY (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
$db->exec($create_temp_table);

$fffffff = $asasa - $discount; // Now both are floats
$d = $fffffff * $c; // Now both are floats

// Check if item already exists in temporary cart
$check_existing = $db->prepare("SELECT * FROM temporary_cart WHERE invoice = :invoice AND product = :product");
$check_existing->bindParam(':invoice', $a);
$check_existing->bindParam(':product', $b);
$check_existing->execute();
$existing_item = $check_existing->fetch();

if ($existing_item) {
    // Update existing item quantity
    $new_qty = floatval($existing_item['qty']) + $c;
    $new_amount = $fffffff * $new_qty;

    // Check if total quantity would exceed available stock
    if ($new_qty > $available_qty) {
        header("location: sales.php?id=$w&invoice=$a&error=insufficient_stock");
        exit();
    }

    $sql = "UPDATE temporary_cart SET qty = :qty, amount = :amount WHERE transaction_id = :id";
    $q = $db->prepare($sql);
    $q->execute(array(':qty' => $new_qty, ':amount' => $new_amount, ':id' => $existing_item['transaction_id']));
} else {
    // Insert new item to temporary cart (NOT to actual sales_order yet)
    $sql = "INSERT INTO temporary_cart (invoice,product,qty,amount,price,product_code,gen_name,date,discount,session_id) VALUES (:a,:b,:c,:d,:f,:i,:j,:k,:l,:m)";
    $q = $db->prepare($sql);
    $q->execute(array(':a' => $a, ':b' => $b, ':c' => $c, ':d' => $d, ':f' => $asasa, ':i' => $code, ':j' => $gen, ':k' => $date, ':l' => $discount, ':m' => session_id()));
}

header("location: sales.php?id=$w&invoice=$a");
