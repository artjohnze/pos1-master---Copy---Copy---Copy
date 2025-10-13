<?php
require_once('auth.php');
include('../connect.php');

// Check if user is admin
if (!isset($_SESSION['SESS_USER_ROLE']) || $_SESSION['SESS_USER_ROLE'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access";
    header('Location: ../index.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid delivery ID";
    header('Location: supplier_deliveries.php');
    exit();
}

$id = (int)$_GET['id'];

// Get delivery details before processing
$stmt = $db->prepare("SELECT * FROM supplier_deliveries WHERE id = ? AND status = 'pending'");
$stmt->execute([$id]);
$delivery = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$delivery) {
    $_SESSION['error_message'] = "Delivery not found or already processed";
    header('Location: supplier_deliveries.php');
    exit();
}

try {
    // Start transaction
    $db->beginTransaction();

    // We already checked for delivery existence before starting transaction

    // Create products table if doesn't exist (use schema provided by user)
    $db->exec("CREATE TABLE IF NOT EXISTS products (
        product_id int(11) NOT NULL,
        product_code varchar(200) NOT NULL,
        gen_name varchar(200) NOT NULL,
        product_name varchar(200) NOT NULL,

        o_price varchar(100) NOT NULL,
        price varchar(100) NOT NULL,
        
        supplier varchar(100) NOT NULL,
        
        qty int(10) NOT NULL,
        qty_sold int(10) NOT NULL,
        expiry_date varchar(500) NOT NULL,
        date_arrival timestamp NOT NULL DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;");

    // Create stock_log table if doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS stock_log (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        product_code VARCHAR(200) NOT NULL,
        action VARCHAR(50) NOT NULL,
        qty INT(11) NOT NULL,
        user INT(11) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        notes TEXT
    )");

    // Check if product exists
    $check_product = $db->prepare("SELECT * FROM products WHERE product_code = ?");
    $check_product->execute([$delivery['product_code']]);
    $product = $check_product->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Update existing product quantity and set columns using safe defaults
        $update = $db->prepare("UPDATE products 
                              SET qty = qty + ?,
                                  expiry_date = COALESCE(NULLIF(?, ''), expiry_date),
                                  price = COALESCE(NULLIF(?, ''), price),
                                  gen_name = COALESCE(NULLIF(?, ''), gen_name),
                                  supplier = COALESCE(NULLIF(?, ''), supplier)
                              WHERE product_code = ?");
        $update->execute([
            (int)$delivery['qty'],
            $delivery['expiry_date'] ?? '',
            $delivery['price'] ?? '',
            $delivery['gen_name'] ?? '',
            $delivery['supplier'] ?? '',
            $delivery['product_code']
        ]);
    } else {
        // Insert new product with NOT NULL columns filled using safe defaults
        $insert = $db->prepare("INSERT INTO products (
                                product_code, 
                                gen_name,
                                product_name, 
                                o_price,
                                price, 
                                supplier, 
                                qty, 
                                qty_sold,
                                expiry_date
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([
            $delivery['product_code'] ?? '',
            $delivery['gen_name'] ?? '',
            $delivery['product_name'] ?? '',
            $delivery['o_price'] ?? '',
            $delivery['price'] ?? '',
            $delivery['supplier'] ?? '',
            (int)($delivery['qty'] ?? 0),
            0,
            $delivery['expiry_date'] ?? ''
        ]);
    }

    // Update delivery status
    $update_status = $db->prepare("UPDATE supplier_deliveries SET status = 'accepted' WHERE id = ?");
    $update_status->execute([$id]);

    // Log the acceptance
    $log = $db->prepare("INSERT INTO stock_log (
                            product_code, 
                            action, 
                            qty, 
                            user, 
                            notes
                        ) VALUES (?, 'DELIVERY_ACCEPTED', ?, ?, ?)");
    $log->execute([
        $delivery['product_code'],
        $delivery['qty'],
        $_SESSION['SESS_MEMBER_ID'],
        "Accepted supplier delivery from " . $delivery['supplier']
    ]);

    // Add to purchases for tracking
    $invoice = 'SD-' . date('YmdHis');
    $purchase = $db->prepare("INSERT INTO purchases (
                                invoice_number, 
                                date, 
                                suplier, 
                                remarks
                            ) VALUES (?, NOW(), ?, 'Supplier delivery')");
    $purchase->execute([$invoice, $delivery['supplier']]);

    $purchase_item = $db->prepare("INSERT INTO purchases_item (
                                    name, 
                                    qty, 
                                    cost, 
                                    invoice
                                ) VALUES (?, ?, ?, ?)");
    $purchase_item->execute([
        $delivery['product_code'],
        $delivery['qty'],
        $delivery['price'],
        $invoice
    ]);

    $db->commit();
    $_SESSION['success_message'] = "Delivery accepted successfully. Product inventory updated.";
} catch (Exception $e) {
}

header('Location: supplier_deliveries.php');
exit();
