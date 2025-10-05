<?php
// Simple test file to check if exports work
require_once('auth.php');

try {
    include('../connect.php');
    echo "✅ Database connection successful<br>";

    // Test query
    $result = $db->prepare("SELECT COUNT(*) as total FROM sales_order");
    $result->execute();
    $row = $result->fetch();
    echo "✅ Found " . $row['total'] . " records in sales_order table<br>";

    // Test product table
    $result2 = $db->prepare("SELECT COUNT(*) as total FROM products");
    $result2->execute();
    $row2 = $result2->fetch();
    echo "✅ Found " . $row2['total'] . " records in products table<br>";

    echo "<br><strong>Export Tests:</strong><br>";
    echo "<a href='export_excel.php' target='_blank'>🔗 Test Excel Export</a><br>";
    echo "<a href='export_pdf.php' target='_blank'>🔗 Test PDF Export</a><br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
