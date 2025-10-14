<?php
require_once('auth.php');
include('../connect.php');

// Check if user is admin
if (!isset($_SESSION['SESS_USER_ROLE']) || $_SESSION['SESS_USER_ROLE'] !== 'admin') {
<<<<<<< HEAD
    $_SESSION['error_message'] = "Unauthorized access";
    header('Location: ../index.php');
=======
    header('Location: index.php');
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid delivery ID";
    header('Location: supplier_deliveries.php');
    exit();
}

<<<<<<< HEAD
$id = (int)$_GET['id'];

// Get delivery details before processing
=======
$id = $_GET['id'];

// Get delivery details before deleting
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
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

<<<<<<< HEAD
    // Update delivery status to rejected
    $update_status = $db->prepare("UPDATE supplier_deliveries SET status = 'rejected' WHERE id = ?");
    $update_status->execute([$id]);

    // Check if stock_log table exists, create if not
    $db->exec("CREATE TABLE IF NOT EXISTS stock_log (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        product_code VARCHAR(200) NOT NULL,
        action VARCHAR(50) NOT NULL,
        qty INT(11) NOT NULL,
        user INT(11) NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        notes TEXT
    )");

    // Log the rejection
    $log = $db->prepare("INSERT INTO stock_log (
                            product_code, 
                            action, 
                            qty, 
                            user, 
                            notes
                        ) VALUES (?, 'DELIVERY_REJECTED', ?, ?, ?)");
=======
    // Update status to rejected
    $update = $db->prepare("UPDATE supplier_deliveries SET status = 'rejected' WHERE id = ?");
    $update->execute([$id]);

    // Log the rejection
    $log = $db->prepare("INSERT INTO stock_log (product_code, action, qty, user, timestamp, notes) 
                        VALUES (?, 'DELIVERY_REJECTED', ?, ?, NOW(), ?)");
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
    $log->execute([
        $delivery['product_code'],
        $delivery['qty'],
        $_SESSION['SESS_MEMBER_ID'],
        "Rejected supplier delivery from " . $delivery['supplier']
    ]);

    $db->commit();
<<<<<<< HEAD
    $_SESSION['success_message'] = "Delivery rejected successfully.";
} catch (Exception $e) {
    // Only rollback if there's an active transaction
    if ($db->inTransaction()) {
        $db->rollBack();
    }
=======
    $_SESSION['success_message'] = "Delivery rejected successfully";
} catch (Exception $e) {
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
}

header('Location: supplier_deliveries.php');
exit();
