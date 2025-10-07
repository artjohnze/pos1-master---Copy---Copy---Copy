<?php
session_start();
include('../connect.php');

// Check if all required fields are present
if (!isset($_POST['product_id'], $_POST['supplier'], $_POST['quantity'], $_POST['reason'])) {
    header("location: products.php?error=missing_fields");
    exit;
}

$product_id = $_POST['product_id'];
$supplier = $_POST['supplier'];
$quantity = (int)$_POST['quantity'];
$reason = $_POST['reason'];

// Validate quantity
if ($quantity <= 0) {
    header("location: products.php?error=invalid_quantity");
    exit;
}

try {
    // First check if product exists and has enough quantity
    $check_product = $db->prepare("SELECT qty FROM products WHERE product_id = :product_id");
    $check_product->execute([':product_id' => $product_id]);
    $product = $check_product->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("location: products.php?error=product_not_found");
        exit;
    }

    if ($product['qty'] < $quantity) {
        header("location: products.php?error=insufficient_quantity");
        exit;
    }

    // Start transaction
    $db->beginTransaction();

    // First get the current quantity for accurate logging
    $get_qty = $db->prepare("SELECT qty FROM products WHERE product_id = :product_id FOR UPDATE");
    $get_qty->execute([':product_id' => $product_id]);
    $current_qty = $get_qty->fetch(PDO::FETCH_ASSOC)['qty'];

    // Update product quantity
    $update_qty = $db->prepare("UPDATE products SET qty = qty - :quantity WHERE product_id = :product_id");
    $update_qty->execute([
        ':quantity' => $quantity,
        ':product_id' => $product_id
    ]);

    // Insert into supplier_returns
    $sql = "INSERT INTO supplier_returns (product_id, supplier, quantity, reason, return_date) 
            VALUES (:product_id, :supplier, :quantity, :reason, NOW())";
    $q = $db->prepare($sql);
    $q->execute([
        ':product_id' => $product_id,
        ':supplier' => $supplier,
        ':quantity' => $quantity,
        ':reason' => $reason
    ]);

    // Log the stock change
    $sql_log = "INSERT INTO stock_log (product_id, action_type, quantity_changed, old_quantity, new_quantity, date_created, user_id, notes) 
                VALUES (:product_id, 'RETURN_TO_SUPPLIER', :qty_changed, :old_qty, :new_qty, NOW(), :user_id, :notes)";
    $q_log = $db->prepare($sql_log);
    $q_log->execute([
        ':product_id' => $product_id,
        ':qty_changed' => -$quantity, // Negative because we're reducing stock
        ':old_qty' => $current_qty,
        ':new_qty' => $current_qty - $quantity,
        ':user_id' => isset($_SESSION['SESS_MEMBER_ID']) ? $_SESSION['SESS_MEMBER_ID'] : '0',
        ':notes' => "Return to supplier. Reason: " . $reason
    ]);

    // Commit transaction
    $db->commit();

    // Redirect with success message
    header("location: returns.php?success=1");
    exit;
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();

    // Log the error (you might want to implement proper error logging)
    error_log("Return error: " . $e->getMessage());

    // Redirect with error
    header("location: products.php?error=transaction_failed");
    exit;
}
