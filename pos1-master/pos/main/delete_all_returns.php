<?php
include('../connect.php');
require_once('auth.php');

try {
    // Start transaction
    $db->beginTransaction();

    // Delete all returns
    $sql = "DELETE FROM supplier_returns";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    // Commit transaction
    $db->commit();

    // Redirect with success message
    header("location: returns.php?deleted=all");
    exit;
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();

    // Redirect with error message
    header("location: returns.php?error=delete_failed");
    exit;
}
