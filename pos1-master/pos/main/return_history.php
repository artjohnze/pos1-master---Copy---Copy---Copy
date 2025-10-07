<?php
include('../connect.php');
require_once('auth.php');
?>
<html>

<head>
    <title>Return History</title>
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

        .table-header {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="jeffartagame.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/application.js" type="text/javascript" charset="utf-8"></script>
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
                        <li><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i class="icon-shopping-cart icon-2x"></i> Sales</a></li>
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li class="active"><a href="return_history.php"><i class="icon-book icon-2x"></i> Return History</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                    </ul>
                </div>
            </div>
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-book"></i> Return History
                </div>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li> /
                    <li class="active">Return History</li>
                </ul>

                <div class="table-header">
                    <form action="" method="get" class="form-inline">
                        <select name="status" class="input-medium" onchange="this.form.submit()">
                            <option value="all" <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'selected' : ''; ?>>All Status</option>
                            <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        &nbsp;
                        <input type="text" name="date_from" class="input-small" placeholder="Date From" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>" />
                        <input type="text" name="date_to" class="input-small" placeholder="Date To" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>" />
                        <button type="submit" class="btn btn-info"><i class="icon-search"></i> Search</button>
                    </form>
                </div>

                <table class="table table-bordered" id="resultTable" data-responsive="table">
                    <thead>
                        <tr>
                            <th>Return Date</th>
                            <th>Product</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $status_filter = isset($_GET['status']) && $_GET['status'] != 'all' ? " AND r.status = '" . $_GET['status'] . "'" : " AND r.status != 'pending'";
                        $date_filter = "";
                        if (isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from']) && !empty($_GET['date_to'])) {
                            $date_filter = " AND DATE(r.return_date) BETWEEN '" . $_GET['date_from'] . "' AND '" . $_GET['date_to'] . "'";
                        }

                        $result = $db->prepare("SELECT r.*, p.product_name 
                                              FROM supplier_returns r 
                                              JOIN products p ON r.product_id = p.product_id 
                                              WHERE 1=1 " . $status_filter . $date_filter . "
                                              ORDER BY r.return_date DESC");
                        $result->execute();
                        for ($i = 0; $row = $result->fetch(); $i++) {
                            $status = $row['status'];
                            $statusClass = ($status == 'completed') ? 'label label-success' : 'label label-important';
                        ?>
                            <tr class="record">
                                <td><?php echo date('Y-m-d H:i', strtotime($row['return_date'])); ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['supplier']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['reason']; ?></td>
                                <td><span class="<?php echo $statusClass; ?>"><?php echo ucfirst($status); ?></span></td>
                                <td><?php echo $row['updated_by'] ? $row['updated_by'] : 'System'; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                Total Records: <?php echo $result->rowCount(); ?>
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