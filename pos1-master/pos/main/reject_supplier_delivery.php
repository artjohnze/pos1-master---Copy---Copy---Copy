<?php
require_once('auth.php');
include('../connect.php');

// Check if user is admin
if (!isset($_SESSION['SESS_USER_ROLE']) || $_SESSION['SESS_USER_ROLE'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid delivery ID";
    header('Location: supplier_deliveries.php');
    exit();
}

$id = $_GET['id'];

// Get delivery details before deleting
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

    // Update status to rejected
    $update = $db->prepare("UPDATE supplier_deliveries SET status = 'rejected' WHERE id = ?");
    $update->execute([$id]);

    // Log the rejection
    $log = $db->prepare("INSERT INTO stock_log (product_code, action, qty, user, timestamp, notes) 
                        VALUES (?, 'DELIVERY_REJECTED', ?, ?, NOW(), ?)");
    $log->execute([
        $delivery['product_code'],
        $delivery['qty'],
        $_SESSION['SESS_MEMBER_ID'],
        "Rejected supplier delivery from " . $delivery['supplier']
    ]);

    $db->commit();
    $_SESSION['success_message'] = "Delivery rejected successfully";
} catch (Exception $e) {
}

header('Location: supplier_deliveries.php');
exit();
