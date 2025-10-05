<?php
session_start();
require_once('auth.php');
include('../connect.php');

// Set content type to JSON
header('Content-Type: application/json');

// Function to send JSON response
function sendResponse($success, $message, $data = null)
{
    $response = array(
        'success' => $success,
        'message' => $message
    );
    if ($data) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Validate input
if (!isset($_POST['product_id']) || !isset($_POST['action'])) {
    sendResponse(false, 'Missing required parameters');
}

$product_id = intval($_POST['product_id']);
$action = $_POST['action']; // 'add' or 'set'

// Validate product ID
if ($product_id <= 0) {
    sendResponse(false, 'Invalid product ID');
}

// Check if product exists and get current quantity
try {
    $check_product = $db->prepare("SELECT product_id, gen_name, qty FROM products WHERE product_id = :product_id");
    $check_product->bindParam(':product_id', $product_id);
    $check_product->execute();
    $product = $check_product->fetch();

    if (!$product) {
        sendResponse(false, 'Product not found');
    }

    $current_qty = intval($product['qty']);
    $new_qty = $current_qty;

    if ($action === 'add') {
        // Add stock
        if (!isset($_POST['add_qty'])) {
            sendResponse(false, 'Add quantity not provided');
        }

        $add_qty = intval($_POST['add_qty']);
        if ($add_qty <= 0) {
            sendResponse(false, 'Add quantity must be greater than 0');
        }

        $new_qty = $current_qty + $add_qty;
        $action_description = "Added {$add_qty} units";
    } elseif ($action === 'set') {
        // Set exact stock
        if (!isset($_POST['set_qty'])) {
            sendResponse(false, 'Set quantity not provided');
        }

        $set_qty = intval($_POST['set_qty']);
        if ($set_qty < 0) {
            sendResponse(false, 'Set quantity cannot be negative');
        }

        $new_qty = $set_qty;
        $difference = $new_qty - $current_qty;
        $action_description = "Stock set to {$set_qty} units (change: {$difference})";
    } else {
        sendResponse(false, 'Invalid action type');
    }

    // Update the product quantity in database
    $update_qty = $db->prepare("UPDATE products SET qty = :new_qty WHERE product_id = :product_id");
    $update_qty->bindParam(':new_qty', $new_qty);
    $update_qty->bindParam(':product_id', $product_id);

    if ($update_qty->execute()) {
        // Log the stock change (create table if doesn't exist)
        $create_log_table = "CREATE TABLE IF NOT EXISTS stock_log (
            log_id int(11) NOT NULL AUTO_INCREMENT,
            product_id int(11) NOT NULL,
            action_type varchar(50) NOT NULL,
            quantity_changed int(11) NOT NULL,
            old_quantity int(11) NOT NULL,
            new_quantity int(11) NOT NULL,
            date_created datetime NOT NULL,
            user_id varchar(100) DEFAULT NULL,
            notes text DEFAULT NULL,
            PRIMARY KEY (log_id),
            KEY idx_product_id (product_id),
            KEY idx_date (date_created)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        try {
            $db->exec($create_log_table);

            // Log the action
            $user_id = isset($_SESSION['SESS_MEMBER_ID']) ? $_SESSION['SESS_MEMBER_ID'] : 'Unknown';
            $action_type = ($action === 'add') ? 'ADD_STOCK' : 'SET_STOCK';
            $quantity_changed = $new_qty - $current_qty;

            $log_entry = $db->prepare("INSERT INTO stock_log (product_id, action_type, quantity_changed, old_quantity, new_quantity, date_created, user_id, notes) VALUES (:product_id, :action_type, :qty_changed, :old_qty, :new_qty, NOW(), :user_id, :notes)");
            $log_entry->execute(array(
                ':product_id' => $product_id,
                ':action_type' => $action_type,
                ':qty_changed' => $quantity_changed,
                ':old_qty' => $current_qty,
                ':new_qty' => $new_qty,
                ':user_id' => $user_id,
                ':notes' => $action_description
            ));
        } catch (Exception $log_error) {
            // Continue even if logging fails
        }

        sendResponse(true, 'Stock updated successfully in database', array(
            'product_name' => $product['gen_name'],
            'action' => $action,
            'old_qty' => $current_qty,
            'new_qty' => $new_qty,
            'difference' => $new_qty - $current_qty,
            'description' => $action_description
        ));
    } else {
        sendResponse(false, 'Failed to update product quantity in database');
    }
} catch (Exception $e) {
    sendResponse(false, 'Database error: ' . $e->getMessage());
}
