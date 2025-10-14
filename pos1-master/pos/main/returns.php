<?php
include('../connect.php');
require_once('auth.php');
?>
<html>

<head>
    <title>Supplier Returns</title>
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

        /* Make table headers non-interactive */
        table th {
            pointer-events: none;
            cursor: default;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/tcal.css" />
    <script src="tcal.js" type="text/javascript"></script>
    <script src="jeffartagame.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/application.js" type="text/javascript" charset="utf-8"></script>
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="vendors/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Check for success/error messages
            var urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('deleted') && urlParams.get('deleted') === 'all') {
                Swal.fire({
                    title: 'Success!',
                    text: 'All returns have been deleted successfully!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
            if (urlParams.has('error') && urlParams.get('error') === 'delete_failed') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete returns. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            // Clean up URL
            window.history.replaceState({}, document.title, 'returns.php');
        });
    </script>
</head>

<body>
    <?php include('navfixed.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <!-- <li><a href="sales.php?id=cash&invoice=?php echo $finalcode ?>"><i class="icon-shopping-cart icon-2x"></i> Sales</a></li> -->
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li class="active"><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
<<<<<<< HEAD
                        <!-- <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li> -->
=======
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                    </ul>
                </div>
            </div>
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-share"></i> Supplier Returns
                </div>
                <br>
                <!-- <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li> /
                    <li class="active">Supplier Returns</li>
                </ul> -->
                <div style="margin-bottom: 10px;" class="row-fluid">
                    <a href="export_returns_pdf.php" class="btn btn-primary"><i class="icon-file icon-large"></i> Export to PDF</a>
                    <a href="export_returns_excel.php" class="btn btn-success"><i class="icon-table icon-large"></i> Export to Excel</a>
                </div>
                <!-- Delete All Modal -->
                <div id="deleteAllModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Delete All Returns</h3>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete all returns? This cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> Cancel</button>
                        <a href="delete_all_returns.php" class="btn btn-danger"><i class="icon-trash"></i> Delete All</a>
                    </div>
                </div>
                <table class="table table-bordered" id="resultTable" data-responsive="table">
                    <thead>
                        <tr>
                            <th>Return Date</th>
                            <th>Product</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT r.*, p.product_name 
                               FROM supplier_returns r 
                               JOIN products p ON r.product_id = p.product_id
                               ORDER BY r.return_date DESC";
                        $result = $db->prepare($sql);
                        $result->execute();
                        while ($row = $result->fetch()) {
                        ?>
                            <tr class="record">
                                <td><?php echo date('Y-m-d H:i', strtotime($row['return_date'])); ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['supplier']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['reason']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                Total Returns: <?php echo $result->rowCount(); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script src="js/jquery.js"></script>
</body>
<?php include('footer.php'); ?>

</html>