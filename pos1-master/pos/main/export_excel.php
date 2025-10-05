<?php
require_once('auth.php');

try {
    include('../connect.php');
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="sales_inventory_' . date("Y-m-d_H-i-s") . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Function to format money
function formatMoney($number, $fractional = false)
{
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
}

// Start Excel output
echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<style>';
echo '.header { font-size: 16px; font-weight: bold; text-align: center; }';
echo '.subheader { font-size: 12px; text-align: center; color: #666; }';
echo 'table { border-collapse: collapse; width: 100%; }';
echo 'th, td { border: 1px solid #000; padding: 5px; }';
echo 'th { background-color: #f0f0f0; font-weight: bold; }';
echo '.total-row { background-color: #e0e0e0; font-weight: bold; }';
echo '.amount { text-align: right; }';
echo '</style>';
echo '</head>';
echo '<body>';

echo '<div class="header">Product Inventory Report</div>';
echo '<div class="subheader">Generated on: ' . date('Y-m-d H:i:s') . '</div>';
echo '<br>';

echo '<table>';
echo '<tr>';
echo '<th>Invoice</th>';
echo '<th>Date</th>';
echo '<th>Brand Name</th>';
echo '<th>Generic Name</th>';
echo '<th>Category/Description</th>';
echo '<th>Price</th>';
echo '<th>QTY</th>';
echo '<th>Total Amount</th>';
echo '</tr>';

// Get all records
try {
    $result = $db->prepare("SELECT * FROM sales_order ORDER BY transaction_id DESC");
    $result->execute();
} catch (Exception $e) {
    die('Database query failed: ' . $e->getMessage());
}

$total_amount = 0;
$record_count = 0;

while ($row = $result->fetch()) {
    $record_count++;

    // Get product description
    $product_id = $row['product'];
    $desc_result = $db->prepare("SELECT product_name FROM products WHERE product_id = :id");
    $desc_result->bindParam(':id', $product_id);
    $desc_result->execute();
    $desc_row = $desc_result->fetch();
    $product_desc = $desc_row ? $desc_row['product_name'] : 'N/A';

    // Safely convert to numeric values
    $amount = isset($row['amount']) ? floatval($row['amount']) : 0;
    $total_amount += $amount;

    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['invoice']) . '</td>';
    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
    echo '<td>' . htmlspecialchars($row['product_code']) . '</td>';
    echo '<td>' . htmlspecialchars($row['gen_name']) . '</td>';
    echo '<td>' . htmlspecialchars($product_desc) . '</td>';
    echo '<td class="amount">' . formatMoney($row['price'], true) . '</td>';
    echo '<td>' . $row['qty'] . '</td>';
    echo '<td class="amount">' . formatMoney($amount, true) . '</td>';
    echo '</tr>';
}

// Add summary rows
echo '<tr><td colspan="8">&nbsp;</td></tr>'; // Empty row
echo '<tr class="total-row">';
echo '<td colspan="7" style="text-align: right; font-weight: bold;">TOTAL:</td>';
echo '<td class="amount">' . formatMoney($total_amount, true) . '</td>';
echo '</tr>';

echo '<tr><td colspan="8">&nbsp;</td></tr>'; // Empty row
echo '<tr>';
echo '<td colspan="7" style="text-align: right; font-weight: bold;">Total Records:</td>';
echo '<td>' . $record_count . '</td>';
echo '</tr>';

echo '</table>';
echo '</body>';
echo '</html>';
