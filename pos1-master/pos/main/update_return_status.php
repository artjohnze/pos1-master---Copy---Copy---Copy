<?php
session_start();
include('../connect.php');

$return_id = $_GET['id'];
$new_status = $_GET['status'];

try {
    $db->beginTransaction();

    // Update the return status with user info
    $update = $db->prepare("UPDATE supplier_returns 
                           SET status = :status, 
                               updated_by = :updated_by,
                               updated_at = NOW() 
                           WHERE return_id = :return_id");
    $update->execute(array(
        ':status' => $new_status,
        ':return_id' => $return_id,
        ':updated_by' => $_SESSION['SESS_FIRST_NAME'] . ' ' . $_SESSION['SESS_LAST_NAME']
    ));

    // If cancelling the return, restore the quantity
    if ($new_status == 'cancelled') {
        // Get return details
        $get_return = $db->prepare("SELECT product_id, quantity FROM supplier_returns WHERE return_id = :return_id");
        $get_return->execute(array(':return_id' => $return_id));
        $return = $get_return->fetch();

        // Restore product quantity
        $restore_qty = $db->prepare("UPDATE products SET qty = qty + :quantity WHERE product_id = :product_id");
        $restore_qty->execute(array(
            ':quantity' => $return['quantity'],
            ':product_id' => $return['product_id']
        ));

        // Log the restoration
        $sql_log = "INSERT INTO stock_log (product_id, action_type, quantity_changed, old_quantity, new_quantity, date_created, user_id, notes) 
                    SELECT :product_id, 'RETURN_CANCELLED', :qty, qty - :qty, qty, NOW(), :user_id, :notes 
                    FROM products WHERE product_id = :product_id";
        $q_log = $db->prepare($sql_log);
        $q_log->execute(array(
            ':product_id' => $return['product_id'],
            ':qty' => $return['quantity'],
            ':user_id' => $_SESSION['SESS_MEMBER_ID'],
            ':notes' => "Return cancelled - quantity restored"
        ));
    }

    $db->commit();
    header("location: returns.php?success=1");
} catch (Exception $e) {
    $db->rollBack();
    header("location: returns.php?error=1");
}
