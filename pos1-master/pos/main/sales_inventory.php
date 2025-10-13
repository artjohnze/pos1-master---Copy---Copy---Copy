<html>

<head>
    <title>
        POS
    </title>

    <?php
    require_once('auth.php');
    ?>
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

    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <!--sa poip up-->
    <script src="jeffartagame.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/application.js" type="text/javascript" charset="utf-8"></script>
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="tcal.css" />
    <script type="text/javascript" src="tcal.js"></script>
    <script language="javascript">
        function Clickheretoprint() {
            var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
            disp_setting += "scrollbars=yes,width=700, height=400, left=100, top=25";
            var content_vlue = document.getElementById("content").innerHTML;

            var docprint = window.open("", "", disp_setting);
            docprint.document.open();
            docprint.document.write(
                '</head><body onLoad="self.print()" style="width: 700px; font-size:11px; font-family:arial; font-weight:normal;">'
            );
            docprint.document.write(content_vlue);
            docprint.document.close();
            docprint.focus();
        }
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox({
                loadingImage: 'src/loading.gif',
                closeImage: 'src/closelabel.png'
            })
        })
    </script>
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

<body>
    <?php include('navfixed.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <!-- <li><a href="sales.php?id=cash&invoice=?php echo $finalcode ?>"><i
                                    class="icon-shopping-cart icon-2x"></i> Sales</a> </li> -->
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a> </li>
                        <!--                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a> </li>-->
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a> </li>
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
                        <!-- <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a> -->
                        </li>
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <li class="active"><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product
                                Inventory</a> </li>
                        <br><br><br><br><br><br>
                        <li>
                            <div class="hero-unit-clock">
                            </div>
                        </li>
                    </ul>
                </div>
                <!--/.well -->
            </div>
            <!--/span-->
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-bar-chart"></i> Product Inventory
                </div>
                <br>
                <div style="clear: both;"></div>
                <?php
                // Pagination settings - Configure how many records to show per page
                // Show 10 rows per page
                $records_per_page = 10; // Show 10 rows per page
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page number or default to 1
                $offset = ($page - 1) * $records_per_page; // Calculate offset for database query
                // Get total number of records from sales_order table
                include('../connect.php');
                $total_result = $db->prepare("SELECT COUNT(*) FROM sales_order");
                $total_result->execute();
                $total_records = $total_result->fetchColumn(); // Total number of sales records
                $total_pages = ceil($total_records / $records_per_page); // Calculate total pages needed
                ?>
                <input type="text" style="padding:15px;" name="filter" value="" id="filter" placeholder="Search here..."
                    autocomplete="off" />
                <div class="content" id="content">
                    <table class="table table-bordered" id="resultTable" data-responsive="table"
                        style="text-align: left;">
                        <thead>
                            <tr>
                                <th width="12%"> Invoice </th>
                                <th width="9%"> Date </th>
                                <th width="14%"> Brand Name </th>
                                <th width="16%"> Generic Name </th>
                                <th width="15%"> Category / Description </th>
                                <th> Price </th>
                                <th> QTY </th>
                                <th width="8%"> Total Amount </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            // Function to format numbers as money with commas
                            // Example: 1234.56 becomes 1,234.56
                            function formatMoney($number, $fractional = false)
                            {
                                // Add decimal places if fractional is true
                                if ($fractional) {
                                    $number = sprintf('%.2f', $number);
                                }
                                // Add commas as thousand separators
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

                            // Database query logic - Handle both pagination and print-all scenarios
                            if (isset($_GET['print']) && $_GET['print'] == 'all') {
                                // Get ALL ROWS from database for printing (no pagination)
                                // Order by transaction_id DESC to show newest records first (5,4,3,2,1)
                                $result = $db->prepare("SELECT * FROM sales_order ORDER BY transaction_id DESC");
                                $result->execute();
                                $all_records_count = $result->rowCount();
                            } else {
                                // Get paginated records for display (only 10 records per page)
                                // Order by transaction_id DESC to show newest records first (5,4,3,2,1)
                                $result = $db->prepare("SELECT * FROM sales_order ORDER BY transaction_id DESC LIMIT :limit OFFSET :offset");
                                $result->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
                                $result->bindParam(':offset', $offset, PDO::PARAM_INT);
                                $result->execute();
                            }

                            // Loop through each sales record and create table rows
                            // This displays each transaction with all its details
                            for ($i = 0; $row = $result->fetch(); $i++) {
                            ?>
                                <tr class="record">
                                    <!-- Display invoice number -->
                                    <td><?php echo $row['invoice']; ?></td>
                                    <!-- Display transaction date -->
                                    <td><?php echo $row['date']; ?></td>
                                    <!-- Display product brand name -->
                                    <td><?php echo $row['product_code']; ?></td>
                                    <!-- Display product generic name -->
                                    <td><?php echo $row['gen_name']; ?></td>
                                    <!-- Get and display product description from products table -->
                                    <td><?php
                                        // Get product description from products table using product_id
                                        $product_id = $row['product'];
                                        $desc_result = $db->prepare("SELECT product_name FROM products WHERE product_id = :id");
                                        $desc_result->bindParam(':id', $product_id);
                                        $desc_result->execute();
                                        $desc_row = $desc_result->fetch();
                                        echo $desc_row ? $desc_row['product_name'] : 'N/A'; // Show 'N/A' if no description found
                                        ?></td>
                                    <!-- Display formatted price -->
                                    <td><?php
                                        $price = $row['price'];
                                        // Format price with commas and decimals
                                        echo formatMoney($price, true); // Format price with commas and decimals
                                        ?></td>
                                    <!-- Display quantity sold -->
                                    <td><?php echo $row['qty']; ?></td>
                                    <!-- Display formatted total amount -->
                                    <td><?php
                                        $oprice = $row['amount'];
                                        echo formatMoney($oprice, true); // Format amount with commas and decimals
                                        ?></td>
                                    <!-- Show Return button only when not printing -->

                                </tr>
                            <?php
                            }
                            ?>



                            <!-- Total Amount Row - Calculate and display sum of all sales -->
                            <tr>
                                <!-- Span across 7 columns and right-align the label -->
                                <th colspan="7" style="text-align: right;"><strong style="font-size: 20px; color: #222222;">Total Amount:</strong></th>
                                <!-- Display the calculated total amount -->
                                <th><strong style="font-size: 13px; color: #222222;">
                                        <?php
                                        // Calculate sum of all amounts in sales_order table
                                        $resultas = $db->prepare("SELECT sum(amount) from sales_order");
                                        $resultas->execute();
                                        for ($i = 0; $rowas = $resultas->fetch(); $i++) {
                                            $fgfg = $rowas['sum(amount)']; // Get total sum
                                            echo formatMoney($fgfg, true); // Format total with commas and decimals
                                        }
                                        ?>
                                    </strong></th>

                            </tr>







                        </tbody>
                    </table>

                    <!-- Pagination Controls (hidden when printing all records) -->
                    <?php if (!isset($_GET['print']) || $_GET['print'] != 'all'): ?>
                        <div class="pagination-wrapper" style="text-align: center; margin-top: 20px;">
                            <div class="pagination">
                                <ul>
                                    <?php if ($page > 1): ?>
                                        <li><a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a></li>
                                    <?php endif; ?>

                                    <?php
                                    // Show page numbers
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        if ($i == $page) {
                                            echo '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
                                        } else {
                                            echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }
                                    ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li><a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <div style="margin-top: 10px; color: #666;">
                                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $records_per_page, $total_records); ?>
                                of <?php echo $total_records; ?> sales transactions (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; margin-top: 10px; color: #666;">
                            <strong>🖨️ PRINTING ALL ROWS - Complete Sales Inventory Report</strong><br>
                            <span style="color: #d9534f; font-weight: bold;">Total Records: <?php echo $total_records; ?> ROWS</span>
                        </div>
                    <?php endif; ?>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
    <script type="text/javascript">
        $(function() {


            $(".delbutton").click(function() {
                //Save the link in a variable called element
                var element = $(this);
                //Find the id of the link that was clicked
                var del_id = element.attr("id");
                //Built a url to send
                var info = 'id=' + del_id;
                if (confirm("Sure you want to delete this update? There is NO undo!")) {
                    $.ajax({
                        type: "GET",
                        url: "deletesalesinventory.php",
                        data: info,
                        success: function() {}
                    });
                    $(this).parents(".record").animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                }
                return false;
            });
        });
    </script>
</body>
<?php include('footer.php'); ?>

</html>