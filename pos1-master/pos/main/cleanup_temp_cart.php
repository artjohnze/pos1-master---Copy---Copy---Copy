<?php
// Cleanup script for temporary cart
// This script should be run periodically to clean up abandoned cart items
// You can set up a cron job to run this daily

include('../connect.php');

try {
    // Delete temporary cart items older than 24 hours
    $cleanup_sql = "DELETE FROM temporary_cart WHERE DATE(date) < CURDATE() - INTERVAL 1 DAY";
    $db->exec($cleanup_sql);

    echo "Temporary cart cleanup completed successfully.\n";
} catch (Exception $e) {
    echo "Error during cleanup: " . $e->getMessage() . "\n";
}
