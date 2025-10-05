<?php
session_start();
include('../connect.php');
$a = $_POST['invoice'];
$b = $_POST['cashier'];
$c = $_POST['date'];
$d = $_POST['ptype'];
$e = $_POST['amount'];

// Create temporary_cart table if it doesn't exist
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

// Start transaction to ensure data consistency
$db->beginTransaction();

try {
    // Get all items from temporary cart
    $temp_items = $db->prepare("SELECT * FROM temporary_cart WHERE invoice = :invoice");
    $temp_items->bindParam(':invoice', $a);
    $temp_items->execute();

    $cart_items = $temp_items->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        throw new Exception("No items found in cart");
    }

    // Move items from temporary cart to actual sales_order table
    foreach ($cart_items as $item) {
        // Insert into sales_order
        $sql = "INSERT INTO sales_order (invoice,product,qty,amount,price,product_code,gen_name,date,discount) VALUES (:a,:b,:c,:d,:e,:f,:g,:h,:i)";
        $q = $db->prepare($sql);
        $q->execute(array(
            ':a' => $item['invoice'],
            ':b' => $item['product'],
            ':c' => $item['qty'],
            ':d' => $item['amount'],
            ':e' => $item['price'],
            ':f' => $item['product_code'],
            ':g' => $item['gen_name'],
            ':h' => $item['date'],
            ':i' => $item['discount']
        ));

        // Update product inventory (reduce qty, increase qty_sold)
        $qty = floatval($item['qty']);
        $product_id = $item['product'];

        $update_inventory = "UPDATE products SET qty=qty-?, qty_sold=qty_sold+? WHERE product_id=?";
        $inv_q = $db->prepare($update_inventory);
        $inv_q->execute(array($qty, $qty, $product_id));
    }

    // Insert into sales table based on payment type
    if ($d == 'credit') {
        $f = $_POST['due'];
        $sql = "INSERT INTO sales (invoice_number,cashier,date,type,amount,due_date) VALUES (:a,:b,:c,:d,:e,:f)";
        $q = $db->prepare($sql);
        $q->execute(array(':a' => $a, ':b' => $b, ':c' => $c, ':d' => $d, ':e' => $e, ':f' => $f));
    } else if ($d == 'cash') {
        $f = $_POST['cash'];
        $sql = "INSERT INTO sales (invoice_number,cashier,date,type,amount,due_date) VALUES (:a,:b,:c,:d,:e,:f)";
        $q = $db->prepare($sql);
        $q->execute(array(':a' => $a, ':b' => $b, ':c' => $c, ':d' => $d, ':e' => $e, ':f' => $f));
    }

    // Clear temporary cart for this invoice
    $clear_cart = $db->prepare("DELETE FROM temporary_cart WHERE invoice = :invoice");
    $clear_cart->bindParam(':invoice', $a);
    $clear_cart->execute();

    // Commit transaction
    $db->commit();

    // Redirect to preview with proper parameters
    if ($d == 'cash') {
        $cash_amount = floatval($_POST['cash']);
        $change = $cash_amount - $e;
        header("location: preview.php?invoice=$a&total=$e&cash=$cash_amount&change=$change&cashier=$b");
    } else {
        header("location: preview.php?invoice=$a&total=$e&cashier=$b");
    }
    exit();
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();

    // Redirect back with error
    $invoice = $_POST['invoice'];
    $pt = $_POST['ptype'];
    header("location: sales.php?id=$pt&invoice=$invoice&error=checkout_failed");
    exit();
}
