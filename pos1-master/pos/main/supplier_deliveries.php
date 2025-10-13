<?php
require_once('auth.php');
include('../connect.php');


$supplier = isset($_GET['name']) ? trim($_GET['name']) : '';
$from = isset($_GET['from']) ? trim($_GET['from']) : '';
$to = isset($_GET['to']) ? trim($_GET['to']) : '';

// If no supplier provided and user is admin, show pending supplier submissions
if ($supplier === '' && isset($_SESSION['SESS_USER_ROLE']) && $_SESSION['SESS_USER_ROLE'] === 'admin') {
    // ensure table exists
    $db->exec("CREATE TABLE IF NOT EXISTS supplier_deliveries (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        supplier VARCHAR(200) NOT NULL,
        product_code VARCHAR(200) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        qty INT(11) NOT NULL,
        price VARCHAR(100) NULL,
        expiry_date VARCHAR(100) NULL,
        status ENUM('pending','accepted','rejected') DEFAULT 'pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $db->prepare('SELECT * FROM supplier_deliveries WHERE status = "pending" ORDER BY submitted_at DESC');
    $stmt->execute();
    $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if ($supplier === '') {
        echo '<div class="alert alert-error">No supplier specified. Go back to <a href="supplier.php">Suppliers</a>.</div>';
        exit;
    }

    // Build query with optional date filter for purchases
    $sql = "SELECT pu.invoice_number, pu.date AS purchase_date, pi.name AS product_code, pr.product_name, pi.qty, pi.cost
    FROM purchases_item pi
    JOIN purchases pu ON pi.invoice = pu.invoice_number
    LEFT JOIN products pr ON pr.product_code = pi.name
    WHERE pu.suplier = :supplier";
    $params = [':supplier' => $supplier];

    if ($from !== '' && $to !== '') {
        // assumes dates are in YYYY-MM-DD format; adjust as needed
        $sql .= " AND STR_TO_DATE(pu.date, '%Y-%m-%d') BETWEEN STR_TO_DATE(:from, '%Y-%m-%d') AND STR_TO_DATE(:to, '%Y-%m-%d')";
        $params[':from'] = $from;
        $params[':to'] = $to;
    }

    $sql .= " ORDER BY pu.date DESC, pu.invoice_number, pi.name";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // also fetch supplier-submitted deliveries (accepted/rejected/pending)
    $db->exec("CREATE TABLE IF NOT EXISTS supplier_deliveries (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        supplier VARCHAR(200) NOT NULL,
        product_code VARCHAR(200) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        qty INT(11) NOT NULL,
        price VARCHAR(100) NULL,
        expiry_date VARCHAR(100) NULL,
        status ENUM('pending','accepted','rejected') DEFAULT 'pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $sstmt = $db->prepare('SELECT * FROM supplier_deliveries WHERE supplier = :s ORDER BY submitted_at DESC');
    $sstmt->execute([':s' => $supplier]);
    $supplier_submissions = $sstmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Deliveries by <?php echo htmlspecialchars($supplier); ?></title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">

    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />

    <script src="lib/jquery.js"></script>
    <script src="src/facebox.js"></script>
    <script src="chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Table styling */
        .table-bordered {
            border-radius: 4px;
            border-collapse: separate;
        }

        .table-bordered th {
            background-color: #f5f5f5;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
            text-align: center;
        }

        .table-bordered td {
            vertical-align: middle;
            padding: 12px 8px;
        }

        /* Status cell styling */
        .status-cell {
            font-weight: bold;
            text-align: center;
        }

        .status-pending {
            color: #f0ad4e;
        }

        .status-accepted {
            color: #5cb85c;
        }

        .status-rejected {
            color: #d9534f;
        }

        /* Action buttons styling */
        .action-buttons {
            white-space: nowrap;
            text-align: center;
        }

        .action-buttons .btn {
            margin: 2px;
            min-width: 80px;
            padding: 4px 12px;
            font-weight: bold;
        }

        .btn-success {
            background: linear-gradient(to bottom, #5cb85c 0%, #449d44 100%);
        }

        .btn-danger {
            background: linear-gradient(to bottom, #d9534f 0%, #c9302c 100%);
        }

        .btn-mini i {
            margin-right: 5px;
        }

        /* Hover effects */
        .table-bordered tbody tr:hover {
            background-color: #f9f9f9;
        }

        /* Quantity styling */
        .qty-cell {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        /* Date styling */
        .date-cell {
            text-align: center;
            color: #666;
        }

        /* Product info styling */
        .product-code {
            font-family: monospace;
            color: #666;
        }

        .product-name {
            font-weight: bold;
            color: #333;
        }

        /* Supplier name styling */
        .supplier-name {
            font-weight: bold;
            color: #337ab7;
        }
    </style>
    <style>
        .row-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col-card {
            flex: 1;
            min-width: 400px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }

        h3 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 400px;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }
    </style>
    <style>
        .sidebar-nav {
            padding: 9px 0;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 25px;

        }

        .card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col-card {
            flex: 1;
            min-width: 400px;
        }

        @media (max-width: 768px) {
            .col-card {
                min-width: 100%;
            }

            .row-cards {
                flex-direction: column;
            }

            canvas {
                height: 350px !important;
            }
        }

        .total-card {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <?php include('navfixed.php'); ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <!-- <li><a href="sales.php?id=cash&invoice=?php echo $finalcode ?>"><i
                                        class="icon-shopping-cart icon-2x"></i> Sales</a></li> -->
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <!--                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a></li>-->
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <!-- <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a> -->
                        </li>

                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                    </ul>
                </div>
            </div>
            <div class="row-cards">
                <div class="container">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php
                            echo $_SESSION['error_message'];
                            unset($_SESSION['error_message']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($pending)): ?>
                        <div class="contentheader">
                            <i class="icon-truck"></i> Pending Supplier Deliveries
                        </div>
                        <br>


                        <?php if (empty($pending)): ?>
                            <div class="alert alert-warning">No pending supplier submissions.</div>
                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Product Details</th>
                                        <th>Quantity</th>
                                        <th>Expiry Date</th>
                                        <th>Submission Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending as $p): ?>
                                        <tr>
                                            <td class="supplier-name" style="text-align: center;">
                                                <?php echo htmlspecialchars($p['supplier']); ?>
                                            </td>

                                            <td style="text-align: center;">
                                                <div class="product-name"><?php echo htmlspecialchars($p['product_name']); ?></div>
                                            </td>

                                            <td class="qty-cell" style="text-align: center;">
                                                <?php echo (int)$p['qty']; ?> units
                                            </td>

                                            <td class="date-cell" style="text-align: center;">
                                                <?php
                                                echo !empty($p['expiry_date'])
                                                    ? htmlspecialchars($p['expiry_date'])
                                                    : '<span class="text-muted">Not specified</span>';
                                                ?>
                                            </td>

                                            <td class="date-cell" style="text-align: center;">
                                                <?php
                                                $date = new DateTime($p['submitted_at']);
                                                echo $date->format('M d, Y g:i A');
                                                ?>
                                            </td>

                                            <td class="action-buttons" style="text-align: center;">
                                                <div class="btn-group">
                                                    <a href="accept_supplier_delivery.php?id=<?php echo $p['id']; ?>"
                                                        class="btn btn-success"
                                                        onclick="return confirm('Are you sure you want to accept this delivery from <?php echo htmlspecialchars($p['supplier']); ?>?\n\nProduct: <?php echo htmlspecialchars($p['product_name']); ?>\nQuantity: <?php echo (int)$p['qty']; ?> units\n\nThis will add the items to inventory.');">
                                                        <i class="icon-ok icon-white"></i> Accept
                                                    </a>
                                                    <a href="reject_supplier_delivery.php?id=<?php echo $p['id']; ?>"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to reject this delivery from <?php echo htmlspecialchars($p['supplier']); ?>?\n\nProduct: <?php echo htmlspecialchars($p['product_name']); ?>\nQuantity: <?php echo (int)$p['qty']; ?> units\n\nThis action cannot be undone.');">
                                                        <i class="icon-remove icon-white"></i> Reject
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="contentheader">
                            <i class="icon-truck"></i> Deliveries by Supplier:
                            <span style="color:#007bff;">
                                <?php echo htmlspecialchars($supplier); ?>
                            </span>
                        </div>


                        <?php if (empty($rows)): ?>

                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $r): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['invoice_number']); ?></td>
                                            <td><?php echo htmlspecialchars($r['purchase_date']); ?></td>
                                            <td><?php echo htmlspecialchars($r['product_code']); ?></td>
                                            <td><?php echo htmlspecialchars($r['product_name'] ?: 'Product not found'); ?></td>
                                            <td><?php echo (int)$r['qty']; ?></td>
                                            <td><?php echo htmlspecialchars($r['cost']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <h4>Products</h4>
                        <?php if (empty($supplier_submissions)): ?>

                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Expiry</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($supplier_submissions as $s): ?>
                                        <tr>
                                            <td style="text-align:center;"><?php echo htmlspecialchars($s['product_code']); ?></td>
                                            <td style="text-align:center;"><?php echo htmlspecialchars($s['product_name']); ?></td>
                                            <td style="text-align:center;"><?php echo (int)$s['qty']; ?></td>
                                            <td style="text-align:center;"><?php echo htmlspecialchars($s['expiry_date']); ?></td>
                                            <td style="text-align:center;"><?php echo htmlspecialchars($s['status']); ?></td>
                                            <td style="text-align:center;"><?php echo htmlspecialchars($s['submitted_at']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php include('footer.php'); ?>
</body>

</html>