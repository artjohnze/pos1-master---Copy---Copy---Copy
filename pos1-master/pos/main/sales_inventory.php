<?php
require_once('auth.php');
include('../connect.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sales Inventory</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />

    <style>
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        table th,
        table td {
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .contentheader {
            font-size: 22px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .total-row th {
            text-align: right;
            font-size: 18px;
            background-color: #fafafa;
        }

        input#filter {
            margin-bottom: 15px;
            width: 250px;
        }
    </style>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        /* Make table headers non-interactive */
        table th {
            pointer-events: none;
            cursor: default;
        }
    </style>

    <script src="lib/jquery.js" type="text/javascript"></script>
</head>

<body>
    <?php include('navfixed.php'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
<<<<<<< HEAD
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <li class="active"><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                        <br><br><br><br><br><br>
                        <li>
                            <div class="hero-unit-clock">
                                <form name="clock">
                                    <input type="text" name="face" value="" style="border: none; background: transparent; font-size: 14px; text-align: center; width: 100%;" readonly>
                                </form>
=======
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
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="span10">
                <div class="contentheader">
                    <i class="icon-bar-chart"></i> Product Inventory
                </div>
<<<<<<< HEAD
                <input type="text" name="filter" id="filter" placeholder="Search here..." autocomplete="off" />

                <?php
                // pagination setup
                $records_per_page = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $records_per_page;

=======
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
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                $total_result = $db->prepare("SELECT COUNT(*) FROM sales_order");
                $total_result->execute();
                $total_records = $total_result->fetchColumn();
                $total_pages = ceil($total_records / $records_per_page);

                // query with product join
                $result = $db->prepare("
                    SELECT s.*, p.product_name, p.qty AS initial_stock
                    FROM sales_order s
                    LEFT JOIN products p ON s.product = p.product_id
                    ORDER BY s.transaction_id DESC
                    LIMIT :limit OFFSET :offset
                ");
                $result->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
                $result->bindParam(':offset', $offset, PDO::PARAM_INT);
                $result->execute();

                // money formatter
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
                ?>
<<<<<<< HEAD

                <div class="content" id="content">
                    <table class="table table-bordered" id="resultTable">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Brand Name</th>
                                <th>Generic Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Stock</th>
                                <th>Total Amount</th>
=======
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
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch()) : ?>
                                <?php
                                // Compute remaining stock using: qty + sold
                                $remaining = $row['initial_stock'] + $row['qty'];
                                ?>
                                <tr class="record">
                                    <td><?php echo htmlspecialchars($row['invoice']); ?></td>
                                    <td><?php echo date('m/d/y', strtotime($row['date'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gen_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo formatMoney($row['price'], true); ?></td>


                                    <td><?php echo (int)$remaining; ?></td>
                                    <td><?php echo (int)$row['qty']; ?></td>
                                    <td><?php echo (int)$row['initial_stock']; ?></td>
                                    <td><?php echo formatMoney($row['amount'], true); ?></td>
                                </tr>
                            <?php endwhile; ?>

                            <tr class="total-row">
                                <th colspan="9"><strong>Total Amount:</strong></th>
                                <th>
                                    <strong>
                                        <?php
                                        $resultas = $db->prepare("SELECT SUM(amount) FROM sales_order");
                                        $resultas->execute();
                                        $rowas = $resultas->fetch();
                                        echo formatMoney($rowas['SUM(amount)'], true);
                                        ?>
                                    </strong>
                                </th>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-wrapper" style="text-align:center; margin:20px 0;">
                        <div class="pagination" style="display:inline-block;">
                            <ul style="list-style:none; padding:0; margin:0; display:inline-flex; gap:5px;">
                                <?php if ($page > 1): ?>
                                    <li style="display:inline-block;">
                                        <a href="?page=<?php echo $page - 1; ?>"
                                            style="text-decoration:none; color:#007bff; border:1px solid #ddd; padding:6px 12px; border-radius:5px; transition:0.3s;">
                                            &laquo; Previous
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i == $page): ?>
                                        <li style="display:inline-block;">
                                            <a href="?page=<?php echo $i; ?>"
                                                style="text-decoration:none; color:#fff; background-color:#007bff; border:1px solid #007bff; padding:6px 12px; border-radius:5px; font-weight:500;">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li style="display:inline-block;">
                                            <a href="?page=<?php echo $i; ?>"
                                                style="text-decoration:none; color:#007bff; border:1px solid #ddd; padding:6px 12px; border-radius:5px; transition:0.3s;">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li style="display:inline-block;">
                                        <a href="?page=<?php echo $page + 1; ?>"
                                            style="text-decoration:none; color:#007bff; border:1px solid #ddd; padding:6px 12px; border-radius:5px; transition:0.3s;">
                                            Next &raquo;
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div style="margin-top:10px; color:#555; font-size:14px; text-align:center;">
                            Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $records_per_page, $total_records); ?>
                            of <?php echo $total_records; ?> transactions
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
</body>

<?php include('footer.php'); ?>

</html>