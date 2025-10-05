<?php
require_once('auth.php');

try {
    include('../connect.php');
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Set headers for HTML-based PDF (browser can print to PDF)
header('Content-Type: text/html; charset=utf-8');

// Simple PDF generation using HTML with proper CSS for PDF conversion
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 0.5in;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left; 
            font-size: 11px;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            font-size: 12px;
        }
        .total-row { 
            font-weight: bold; 
            background-color: #e9ecef; 
            font-size: 12px;
        }
        .amount {
            text-align: right;
        }
    </style>
</head>
<body>';

$html .= '<div class="header">';
$html .= '<h2>Product Inventory Report</h2>';
$html .= '<p>Generated on: ' . date('Y-m-d H:i:s') . '</p>';
$html .= '</div>';

$html .= '<table>';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th width="12%">Invoice</th>';
$html .= '<th width="12%">Date</th>';
$html .= '<th width="12%">Brand Name</th>';
$html .= '<th width="12%">Generic Name</th>';
$html .= '<th width="16%">Category/Description</th>';
$html .= '<th width="12%">Price</th>';
$html .= '<th width="12%">QTY</th>';
$html .= '<th width="12%">Total Amount</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Get all records
try {
    $result = $db->prepare("SELECT * FROM sales_order ORDER BY transaction_id DESC");
    $result->execute();
} catch (Exception $e) {
    die('Database query failed: ' . $e->getMessage());
}

$total_amount = 0;

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

while ($row = $result->fetch()) {
    // Get product description
    $product_id = $row['product'];
    $desc_result = $db->prepare("SELECT product_name FROM products WHERE product_id = :id");
    $desc_result->bindParam(':id', $product_id);
    $desc_result->execute();
    $desc_row = $desc_result->fetch();
    $product_desc = $desc_row ? $desc_row['product_name'] : 'N/A';

    $total_amount += $row['amount'];

    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['invoice']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['date']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['product_code']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['gen_name']) . '</td>';
    $html .= '<td>' . htmlspecialchars($product_desc) . '</td>';
    $html .= '<td class="amount">' . formatMoney($row['price'], true) . '</td>';
    $html .= '<td>' . $row['qty'] . '</td>';
    $html .= '<td class="amount">' . formatMoney($row['amount'], true) . '</td>';
    $html .= '</tr>';
}

// Add totals row
$html .= '<tr class="total-row">';
$html .= '<td colspan="7" style="text-align: right; font-weight: bold;">TOTAL:</td>';
$html .= '<td class="amount">' . formatMoney($total_amount, true) . '</td>';
$html .= '</tr>';

$html .= '</tbody>';
$html .= '</table>';

$html .= '<div style="margin-top: 20px; text-align: center; no-print;">';
$html .= '<button onclick="window.print()" style="padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 5px; cursor: pointer;">Print This Report</button>';
$html .= '<button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Close Window</button>';
$html .= '</div>';

$html .= '<style>@media print { .no-print { display: none; } }</style>';
$html .= '</body></html>';

echo $html;
