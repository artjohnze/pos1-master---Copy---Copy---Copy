<?php
// Script to fix qty_sold for existing products
// This should be run once to calculate correct qty_sold values based on existing sales

include('../connect.php');

// Get all products
$products_result = $db->prepare("SELECT product_id FROM products");
$products_result->execute();

while ($product = $products_result->fetch()) {
 $product_id = $product['product_id'];

 // Calculate total quantity sold for this product
 $sales_result = $db->prepare("SELECT SUM(qty) as total_sold FROM sales_order WHERE product = ?");
 $sales_result->execute(array($product_id));
 $sales_data = $sales_result->fetch();

 $total_sold = $sales_data['total_sold'] ? $sales_data['total_sold'] : 0;

 // Update the product's qty_sold
 $update_result = $db->prepare("UPDATE products SET qty_sold = ? WHERE product_id = ?");
 $update_result->execute(array($total_sold, $product_id));

 echo "Updated product ID $product_id: qty_sold = $total_sold<br>";
}

echo "Finished updating qty_sold for all products.";
