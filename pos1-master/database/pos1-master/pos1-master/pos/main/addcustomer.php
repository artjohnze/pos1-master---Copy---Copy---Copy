<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="savecustomer.php" method="post">
 <center>
  <h4><i class="icon-plus-sign icon-large"></i> Add Customer</h4>
 </center>
 <hr>
 <div id="ac">
  <span>Full Name : </span><input type="text" style="width:265px; height:30px;" name="name" placeholder="Full Name" Required /><br>
  <span>Address : </span><input type="text" style="width:265px; height:30px;" name="address" placeholder="Address" /><br>
  <span>Contact : </span><input type="text" style="width:265px; height:30px;" name="contact" placeholder="Contact" /><br>
  <span>Product Name : </span>
  <select style="width:265px; height:30px;" name="prod_name" id="product_select" required onchange="updatePrice()">
   <option value="">Select Product</option>
   <?php
   include('../connect.php');
   $result = $db->prepare("SELECT * FROM products ORDER BY product_code ASC");
   $result->execute();
   while ($row = $result->fetch()) {
    echo '<option value="' . $row['product_code'] . '" data-price="' . $row['price'] . '">' . $row['product_code'] . '</option>';
   }
   ?>
  </select><br>
  <span>Total: </span><input type="text" style="width:265px; height:30px;" name="memno" id="total_field" placeholder="Total" readonly /><br>
  <span>Note : </span><textarea style="height:60px; width:265px;" name="note"></textarea><br>
  <span>Expected Date: </span><input type="date" style="width:265px; height:30px;" name="date" placeholder="Date" /><br>
  <div style="float:right; margin-right:10px;">
   <button class="btn btn-success btn-block btn-large" style="width:267px;"><i class="icon icon-save icon-large"></i> Save</button>
  </div>
 </div>
</form>

<script type="text/javascript">
 function updatePrice() {
  var selectElement = document.getElementById('product_select');
  var totalField = document.getElementById('total_field');
  var selectedOption = selectElement.options[selectElement.selectedIndex];

  if (selectedOption.value !== '') {
   var price = selectedOption.getAttribute('data-price');
   totalField.value = 'P ' + parseFloat(price).toFixed(2);
  } else {
   totalField.value = '';
  }
 }
</script>