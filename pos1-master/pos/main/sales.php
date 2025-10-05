<!DOCTYPE html>
<html>

<head>
    <!-- js -->
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox({
                loadingImage: 'src/loading.gif',
                closeImage: 'src/closelabel.png'
            })
        })
    </script>
    <title>
        POS
    </title>
    <?php
    require_once('auth.php');
    ?>

    <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">

    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <script src="vendors/jquery-1.7.2.min.js"></script>
    <script src="vendors/bootstrap.js"></script>

    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="sweet.js"></script>
    <!-- Handle success messages with SweetAlert -->
    <script>
        $(document).ready(function() {
            // Check for success parameter
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'item_removed') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Item has been successfully removed from cart!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Clean up URL
                urlParams.delete('success');
                var cleanUrl = window.location.pathname + '?' + urlParams.toString();
                if (cleanUrl.endsWith('?')) cleanUrl = cleanUrl.slice(0, -1);
                window.history.replaceState({}, document.title, cleanUrl);
            }

            // Check for error parameters
            if (urlParams.has('error')) {
                var errorType = urlParams.get('error');
                var errorMessage = '';

                switch (errorType) {
                    case 'insufficient_stock':
                        errorMessage = 'Not enough stock available for this quantity!';
                        break;
                    case 'product_not_found':
                        errorMessage = 'Product not found!';
                        break;
                    case 'checkout_failed':
                        errorMessage = 'Checkout failed. Please try again!';
                        break;
                    default:
                        errorMessage = 'An error occurred. Please try again!';
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });

                // Clean up URL
                urlParams.delete('error');
                var cleanUrl = window.location.pathname + '?' + urlParams.toString();
                if (cleanUrl.endsWith('?')) cleanUrl = cleanUrl.slice(0, -1);
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });
    </script>

    <script language="javascript" type="text/javascript">
        /* Visit http://www.yaldex.com/ for full source code
and get more free JavaScript, CSS and DHTML scripts! */
        <!-- Begin
        var timerID = null;
        var timerRunning = false;

        function stopclock() {
            if (timerRunning)
                clearTimeout(timerID);
            timerRunning = false;
        }

        function showtime() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds()
            var timeValue = "" + ((hours > 12) ? hours - 12 : hours)
            if (timeValue == "0") timeValue = 12;
            timeValue += ((minutes < 10) ? ":0" : ":") + minutes
            timeValue += ((seconds < 10) ? ":0" : ":") + seconds
            timeValue += (hours >= 12) ? " P.M." : " A.M."
            document.clock.face.value = timeValue;
            timerID = setTimeout("showtime()", 1000);
            timerRunning = true;
        }

        function startclock() {
            stopclock();
            showtime();
        }
        window.onload = startclock;
        // End -->
    </SCRIPT>

</head>
<?php
function createRandomPassword()
{
    $chars = "003232303232023232023456789";
    srand((float)microtime() * 1000000);
    $i = 0;
    $pass = '';
    while ($i <= 7) {

        $num = rand() % 33;

        $tmp = substr($chars, $num, 1);

        $pass = $pass . $tmp;

        $i++;
    }
    return $pass;
}
$finalcode = '' . createRandomPassword();
?>

<body>
    <?php include('navfixed.php'); ?>
    <?php
    $position = $_SESSION['SESS_LAST_NAME'];
    if ($position == 'cashier') {
    ?>
        <a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>">Cash</a>

        <a href="../index.php">Logout</a>
    <?php
    }
    if ($position == 'admin') {
    ?>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                            <li class="active"><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i
                                        class="icon-shopping-cart icon-2x"></i> Sales</a> </li>
                            <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a> </li>
                            <!--                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a> </li>-->
                            <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a> </li>
                            <!-- <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a> -->
                            </li>
                            <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a>
                                <br><br><br><br><br><br>
                            <li>

                                <div class="hero-unit-clock">


                                </div>
                            </li>

                        </ul>
                    <?php } ?>
                    </div>
                    <!--/.well -->
                </div>
                <!--/span-->
                <div class="span10">
                    <div class="contentheader">
                        <i class="icon-money"></i> Sales
                    </div>
                    <ul class="breadcrumb">
                        <a href="index.php">
                            <li>Dashboard</li>
                        </a>
                        <li class="active">Sales</li>
                    </ul>
                    <div style="margin-top: -19px; margin-bottom: 21px;">
                        <a href="index.php"><button class="btn btn-default btn-large" style="float: none;"><i
                                    class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
                    </div>

                    <form action="incoming.php" method="post">

                        <input type="hidden" name="pt" value="<?php echo $_GET['id']; ?>" />
                        <input type="hidden" name="invoice" value="<?php echo $_GET['invoice']; ?>" />
                        <select name="product" style="width:650px;" class="chzn-select" required>
                            <option></option>
                            <?php
                            include('../connect.php');
                            $result = $db->prepare("SELECT * FROM products");
                            $result->execute();
                            while ($row = $result->fetch()) {
                                $qty = intval($row['qty']);
                                $disabled = ($qty <= 0) ? 'disabled' : '';
                                $label = htmlspecialchars($row['product_code']) . ' - ' .
                                    htmlspecialchars($row['gen_name']) . ' - ' .
                                    htmlspecialchars($row['product_name']) . ' | Expires at: ' .
                                    htmlspecialchars($row['expiry_date']);
                                if ($qty <= 0) {
                                    $label .= " (Out of Stock)";
                                }
                            ?>
                                <option value="<?php echo htmlspecialchars($row['product_id']); ?>" <?php echo $disabled; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="number" name="qty" value="1" min="1" placeholder="Qty" autocomplete="off"
                            style="width: 68px; height:30px; padding-top:6px; padding-bottom: 4px; margin-right: 4px; font-size:15px;"
                            required />
                        <input type="hidden" name="discount" value="" autocomplete="off"
                            style="width: 68px; height:30px; padding-top:6px; padding-bottom: 4px; margin-right: 4px; font-size:15px;" />
                        <input type="hidden" name="date" value="<?php echo date("m/d/y"); ?>" />
                        <button type="submit" class="btn btn-info" style="width: 123px; height:35px; margin-top:-5px;"><i
                                class="icon-plus-sign icon-large"></i> Add</button>
                    </form>
                    <table class="table table-bordered" id="resultTable" data-responsive="table">
                        <thead>
                            <tr>
                                <th> Product Name </th>
                                <th> Generic Name </th>


                                <th> Description </th>

                                <th> Price </th>
                                <th> Qty </th>
                                <th> Amount </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $id = $_GET['invoice'];
                            include('../connect.php');

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

                            // Read from temporary cart instead of sales_order for pending sales
                            $result = $db->prepare("SELECT * FROM temporary_cart WHERE invoice= :userid");
                            $result->bindParam(':userid', $id);
                            $result->execute();
                            for ($i = 1; $row = $result->fetch(); $i++) {
                            ?>
                                <tr class="record">
                                    <td hidden><?php echo $row['product']; ?></td>
                                    <td><?php echo $row['product_code']; ?></td>
                                    <td><?php echo $row['gen_name']; ?></td>
                                    <td><?php
                                        // Get product description from products table
                                        $product_id = $row['product'];
                                        $desc_result = $db->prepare("SELECT product_name FROM products WHERE product_id = :id");
                                        $desc_result->bindParam(':id', $product_id);
                                        $desc_result->execute();
                                        $desc_row = $desc_result->fetch();
                                        echo $desc_row ? $desc_row['product_name'] : 'N/A';
                                        ?></td>
                                    <td>
                                        <?php
                                        $ppp = $row['price'];
                                        echo formatMoney($ppp, true);
                                        ?>
                                    </td>
                                    <td><?php echo $row['qty']; ?></td>
                                    <td>
                                        <?php
                                        $dfdf = $row['amount'];
                                        echo formatMoney($dfdf, true);
                                        ?>
                                    </td>
                                    <td width="90">
                                        <button class="btn btn-m+ini btn-warning delete-item"
                                            data-id="<?php echo $row['transaction_id']; ?>"
                                            data-invoice="<?php echo $_GET['invoice']; ?>"
                                            data-dle="<?php echo $_GET['id']; ?>"
                                            data-qty="<?php echo $row['qty']; ?>"
                                            data-code="<?php echo $row['product']; ?>"
                                            data-name="<?php echo htmlspecialchars($row['gen_name']); ?>">
                                            <i class="icon icon-remove"></i> Cancel
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>
                                <th> </th>

                                <th> </th>
                            </tr>
                            <tr>
                                <th colspan="4"><strong style="font-size: 12px; color: #222222;">Total:</strong></th>
                                <td colspan="1"><strong style="font-size: 12px; color: #222222;">
                                        <?php
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
                                        $sdsd = $_GET['invoice'];
                                        $sdsd = $_GET['invoice'];
                                        $resultas = $db->prepare("SELECT sum(amount) as total_amount FROM temporary_cart WHERE invoice= :a");
                                        $resultas->bindParam(':a', $sdsd);
                                        $resultas->execute();
                                        $rowas = $resultas->fetch();
                                        $fgfg = isset($rowas['total_amount']) ? $rowas['total_amount'] : 0;
                                        echo formatMoney($fgfg, true);
                                        ?>
                                    </strong></td>
                                <th></th>
                            </tr>
                        </tbody>
                    </table><br>

                    <!-- Save button - disabled when cart is empty -->
                    <?php if ($fgfg > 0) { ?>
                        <a rel="facebox"
                            href="checkout.php?pt=<?php echo $_GET['id'] ?>&invoice=<?php echo $_GET['invoice'] ?>&total=<?php echo $fgfg ?>&cashier=<?php echo $_SESSION['SESS_FIRST_NAME'] ?>">
                            <button class="btn btn-success btn-large btn-block">
                                <i class="icon icon-save icon-large"></i> SAVE
                            </button>
                        </a>
                    <?php } else { ?>
                        <button class="btn btn-success btn-large btn-block" disabled style="opacity: 0.5; cursor: not-allowed;">
                            <i class="icon icon-save icon-large"></i> SAVE (Cart is Empty)
                        </button>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- SweetAlert Delete Confirmation Script -->
        <script>
            $(document).ready(function() {
                $('.delete-item').click(function(e) {
                    e.preventDefault();

                    var itemId = $(this).data('id');
                    var invoice = $(this).data('invoice');
                    var dle = $(this).data('dle');
                    var qty = $(this).data('qty');
                    var code = $(this).data('code');
                    var itemName = $(this).data('name');
                    var element = $(this);

                    Swal.fire({
                        title: 'Remove Item?',
                        html: 'Are you sure you want to remove:<br><strong>' + itemName + '</strong><br>from your cart?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Remove Item',
                        cancelButtonText: 'Keep Item',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Removing item...',
                                text: 'Please wait while we remove the item from your cart.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Use AJAX to delete the item
                            $.ajax({
                                type: "GET",
                                url: "",
                                data: {
                                    id: itemId,
                                    invoice: invoice,
                                    dle: dle,
                                    qty: qty,
                                    code: code
                                },
                                success: function(response) {
                                    // Remove the row from table with animation
                                    element.closest('.record').animate({
                                        backgroundColor: "#fbc7c7"
                                    }, "fast").animate({
                                        opacity: "hide"
                                    }, "slow", function() {
                                        $(this).remove();
                                        // Recalculate totals
                                        updateTotals();
                                    });

                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Item Removed!',
                                        text: itemName + ' has been removed from your cart.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Failed to remove item. Please try again.',
                                    });
                                }
                            });
                        }
                    });
                });

                // Function to update totals after item removal
                function updateTotals() {
                    var totalAmount = 0;
                    var totalProfit = 0;

                    $('.record').each(function() {
                        var amountText = $(this).find('td:eq(6)').text().replace(/[^\d.-]/g, '');
                        var profitText = $(this).find('td:eq(7)').text().replace(/[^\d.-]/g, '');

                        if (amountText) totalAmount += parseFloat(amountText) || 0;
                        if (profitText) totalProfit += parseFloat(profitText) || 0;
                    });

                    // Update total displays
                    $('td:contains("Total:")').next().html('<strong style="font-size: 12px; color: #222222;">' + formatMoney(totalAmount, true) + '</strong>');
                    $('td:contains("Total:")').next().next().html('<strong style="font-size: 12px; color: #222222;">' + formatMoney(totalProfit, true) + '</strong>');

                    // Update save button state
                    if (totalAmount <= 0) {
                        $('.btn-success:contains("SAVE")').prop('disabled', true).html('<i class="icon icon-save icon-large"></i> SAVE (Cart is Empty)').css({
                            'opacity': '0.5',
                            'cursor': 'not-allowed'
                        });
                    }
                }

                // Format money function for JavaScript
                function formatMoney(number, fractional) {
                    if (fractional) {
                        number = parseFloat(number).toFixed(2);
                    }
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            });
        </script>
</body>
<?php include('footer.php'); ?>

</html>