<?php
require_once('auth.php');
include('../connect.php');

// Accept POST from supplier_portal
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: supplier_portal.php');
    exit;
}
$supplier = $_POST['supplier'] ?? '';
$product_code = $_POST['product_code'] ?? '';
$gen_name = $_POST['gen_name'] ?? '';
$product_name = $_POST['product_name'] ?? '';
$qty = (int)($_POST['qty'] ?? 0);
$price = $_POST['price'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? null;

if ($supplier === '' || $product_code === '' || $product_name === '' || $qty <= 0) {
    header('Location: supplier_portal.php');
    exit;
}
// create supplier_deliveries table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS supplier_deliveries (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    supplier VARCHAR(200) NOT NULL,
    product_code VARCHAR(200) NOT NULL,
    gen_name VARCHAR(200) NULL,
    product_name VARCHAR(255) NOT NULL,
    qty INT(11) NOT NULL,
    price VARCHAR(100) NULL,
    expiry_date VARCHAR(100) NULL,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db->prepare("INSERT INTO supplier_deliveries (supplier, product_code, gen_name, product_name, qty, price, expiry_date) VALUES (:s, :code, :gen, :name, :qty, :price, :expiry)");
$stmt->execute([':s' => $supplier, ':code' => $product_code, ':gen' => $gen_name, ':name' => $product_name, ':qty' => $qty, ':price' => $price, ':expiry' => $expiry_date]);

header('Location: supplier_portal.php?status=submitted');
exit;
