<?php
include('../connect.php');
require_once('auth.php');
$product_id = $_GET['id'];

// Get product details
$result = $db->prepare("SELECT * FROM products WHERE product_id= :product_id");
$result->bindParam(':product_id', $product_id);
$result->execute();
$product = $result->fetch();
?>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="save_return.php" method="post">
    <center>
        <h4><i class="icon-plus-sign icon-large"></i> Return Product to Supplier</h4>
    </center>
    <hr>
    <div id="ac">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
        <span>Product Name: </span><input type="text" style="width:265px; height:30px;" name="product_name" value="<?php echo $product['product_name']; ?>" readonly /><br>
        <span>Supplier: </span><input type="text" style="width:265px; height:30px;" name="supplier" value="<?php echo $product['supplier']; ?>" readonly /><br>
        <span>CurrentQuantity:</span><input type="text" style="width:265px; height:30px;" value="<?php echo $product['qty']; ?>" readonly /><br>
        <span>Return Quantity: </span><input type="number" style="width:265px; height:30px;" name="quantity" min="1" max="<?php echo $product['qty']; ?>" required /><br>
        <span>Reason for Return : </span>
        <textarea style="width:265px; height:60px;" name="reason" required></textarea><br>
        <div style="float:right; margin-right:10px;">
            <button class="btn btn-success btn-block btn-large" style="width:267px;"><i class="icon icon-save icon-large"></i> Save Return</button>
        </div>
    </div>
</form>