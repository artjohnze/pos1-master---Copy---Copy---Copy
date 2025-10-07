<?php
include('../connect.php');
require_once('auth.php');

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="supplier_returns_report.xls"');
header('Cache-Control: max-age=0');

// Get data from database
$sql = "SELECT r.*, p.product_name 
        FROM supplier_returns r 
        JOIN products p ON r.product_id = p.product_id
        ORDER BY r.return_date DESC";
$result = $db->prepare($sql);
$result->execute();

// Create Excel content
echo '<table border="1">';
echo '<tr>
        <th>Return Date</th>
        <th>Product</th>
        <th>Supplier</th>
        <th>Quantity</th>
        <th>Reason</th>
      </tr>';

while ($row = $result->fetch()) {
    echo '<tr>';
    echo '<td>' . date('Y-m-d H:i', strtotime($row['return_date'])) . '</td>';
    echo '<td>' . $row['product_name'] . '</td>';
    echo '<td>' . $row['supplier'] . '</td>';
    echo '<td>' . $row['quantity'] . '</td>';
    echo '<td>' . $row['reason'] . '</td>';
    echo '</tr>';
}

echo '</table>';
