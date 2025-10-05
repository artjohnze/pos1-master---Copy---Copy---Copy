<?php
// Debug script to check temporary cart contents
session_start();
include('../connect.php');

$invoice = isset($_GET['invoice']) ? $_GET['invoice'] : '';

if (empty($invoice)) {
    echo "Please provide an invoice number: debug_cart.php?invoice=YOUR_INVOICE_NUMBER";
    exit;
}

echo "<h2>Debug: Checking Temporary Cart for Invoice: $invoice</h2>";

// Check if temporary_cart table exists
try {
    $result = $db->query("SHOW TABLES LIKE 'temporary_cart'");
    $table_exists = $result->rowCount() > 0;

    if (!$table_exists) {
        echo "<p style='color: red;'>ERROR: temporary_cart table does not exist!</p>";
        echo "<p>Creating temporary_cart table...</p>";

        $create_temp_table = "CREATE TABLE IF NOT EXISTS temporary_cart (
            transaction_id int(11) NOT NULL AUTO_INCREMENT,
            invoice varchar(100) NOT NULL,
            product varchar(100) NOT NULL,
            qty varchar(100) NOT NULL,
            amount varchar(100) NOT NULL,
            product_code varchar(150) NOT NULL,
            gen_name varchar(200) NOT NULL,
            price varchar(100) NOT NULL,
            discount varchar(100) NOT NULL,
            date varchar(500) NOT NULL,
            session_id varchar(100) NOT NULL,
            PRIMARY KEY (transaction_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        $db->exec($create_temp_table);
        echo "<p style='color: green;'>temporary_cart table created successfully!</p>";
    } else {
        echo "<p style='color: green;'>temporary_cart table exists.</p>";
    }

    // Check items in temporary cart
    $temp_items = $db->prepare("SELECT * FROM temporary_cart WHERE invoice = :invoice");
    $temp_items->bindParam(':invoice', $invoice);
    $temp_items->execute();
    $cart_items = $temp_items->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Items in Temporary Cart:</h3>";
    if (empty($cart_items)) {
        echo "<p style='color: red;'>No items found in temporary cart for this invoice.</p>";

        // Check if items are in sales_order instead
        $sales_items = $db->prepare("SELECT * FROM sales_order WHERE invoice = :invoice");
        $sales_items->bindParam(':invoice', $invoice);
        $sales_items->execute();
        $sales_cart_items = $sales_items->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($sales_cart_items)) {
            echo "<p style='color: blue;'>Found " . count($sales_cart_items) . " items in sales_order table (already processed).</p>";
        }
    } else {
        echo "<p style='color: green;'>Found " . count($cart_items) . " items in temporary cart:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Product</th><th>Product Code</th><th>Name</th><th>Qty</th><th>Price</th><th>Amount</th><th>Date</th></tr>";
        foreach ($cart_items as $item) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['transaction_id']) . "</td>";
            echo "<td>" . htmlspecialchars($item['product']) . "</td>";
            echo "<td>" . htmlspecialchars($item['product_code']) . "</td>";
            echo "<td>" . htmlspecialchars($item['gen_name']) . "</td>";
            echo "<td>" . htmlspecialchars($item['qty']) . "</td>";
            echo "<td>" . htmlspecialchars($item['price']) . "</td>";
            echo "<td>" . htmlspecialchars($item['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($item['date']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Check sales table
    $sales_check = $db->prepare("SELECT * FROM sales WHERE invoice_number = :invoice");
    $sales_check->bindParam(':invoice', $invoice);
    $sales_check->execute();
    $sales_data = $sales_check->fetch();

    echo "<h3>Sales Record:</h3>";
    if ($sales_data) {
        echo "<p style='color: green;'>Sale found in database:</p>";
        echo "<ul>";
        echo "<li>Invoice: " . htmlspecialchars($sales_data['invoice_number']) . "</li>";
        echo "<li>Cashier: " . htmlspecialchars($sales_data['cashier']) . "</li>";
        echo "<li>Date: " . htmlspecialchars($sales_data['date']) . "</li>";
        echo "<li>Type: " . htmlspecialchars($sales_data['type']) . "</li>";
        echo "<li>Amount: " . htmlspecialchars($sales_data['amount']) . "</li>";
        echo "<li>Due Date/Cash: " . htmlspecialchars($sales_data['due_date']) . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>No sale record found for this invoice.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    table {
        margin: 10px 0;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>