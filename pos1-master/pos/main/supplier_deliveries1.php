<?php
require_once('auth.php');
include('../connect.php');

$supplier = isset($_GET['name']) ? trim($_GET['name']) : '';
$from = isset($_GET['from']) ? trim($_GET['from']) : '';
$to = isset($_GET['to']) ? trim($_GET['to']) : '';

// ✅ START: Updated logic for admin and supplier access
if ($supplier === '') {
    if (isset($_SESSION['SESS_USER_ROLE']) && $_SESSION['SESS_USER_ROLE'] === 'admin') {
        // Admin sees all pending deliveries
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
    } elseif (isset($_SESSION['SESS_USER_ROLE']) && $_SESSION['SESS_USER_ROLE'] === 'supplier') {
        // Supplier sees their own deliveries
        $supplier = $_SESSION['SESS_SUPPLIER_NAME'] ?? ''; // make sure supplier name is saved in session on login
        if ($supplier === '') {
            echo '<div class="alert alert-error">Supplier name not found in session.</div>';
            exit;
        }

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
    } else {
        echo '<div class="alert alert-error">Unauthorized access.</div>';
        exit;
    }
} else {
    // ✅ Normal supplier deliveries page
    $sql = "SELECT pu.invoice_number, pu.date AS purchase_date, pi.name AS product_code, pr.product_name, pi.qty, pi.cost
    FROM purchases_item pi
    JOIN purchases pu ON pi.invoice = pu.invoice_number
    LEFT JOIN products pr ON pr.product_code = pi.name
    WHERE pu.suplier = :supplier";
    $params = [':supplier' => $supplier];

    if ($from !== '' && $to !== '') {
        $sql .= " AND STR_TO_DATE(pu.date, '%Y-%m-%d') BETWEEN STR_TO_DATE(:from, '%Y-%m-%d') AND STR_TO_DATE(:to, '%Y-%m-%d')";
        $params[':from'] = $from;
        $params[':to'] = $to;
    }

    $sql .= " ORDER BY pu.date DESC, pu.invoice_number, pi.name";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
// ✅ END: Updated logic
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Deliveries by <?php echo htmlspecialchars($supplier); ?></title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="lib/jquery.js"></script>
    <script src="src/facebox.js"></script>

    <style>
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
            text-align: center;
        }

        .status-cell {
            font-weight: bold;
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

        .supplier-name {
            font-weight: bold;
            color: #337ab7;
        }

        .contentheader {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        h4 {
            margin-top: 30px;
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
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard</a></li>
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                    </ul>
                </div>
            </div>

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
                                        <td class="supplier-name"><?php echo htmlspecialchars($p['supplier']); ?></td>
                                        <td>
                                            <div class="product-name"><?php echo htmlspecialchars($p['product_name']); ?></div>
                                            <div class="product-code">Code: <?php echo htmlspecialchars($p['product_code']); ?></div>
                                        </td>
                                        <td><?php echo (int)$p['qty']; ?></td>
                                        <td><?php echo htmlspecialchars($p['expiry_date']); ?></td>
                                        <td><?php echo htmlspecialchars($p['submitted_at']); ?></td>
                                        <td>
                                            <a href="accept_supplier_delivery.php?id=<?php echo $p['id']; ?>" class="btn btn-success btn-mini">Accept</a>
                                            <a href="reject_supplier_delivery.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-mini">Reject</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="contentheader">
                        <i class="icon-truck"></i> Deliveries by Supplier:
                        <span style="color:#007bff;"><?php echo htmlspecialchars($supplier); ?></span>
                    </div>

                    <?php if (!empty($rows)): ?>
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
                    <?php if (!empty($supplier_submissions)): ?>
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
                                        <td><?php echo htmlspecialchars($s['product_code']); ?></td>
                                        <td><?php echo htmlspecialchars($s['product_name']); ?></td>
                                        <td><?php echo (int)$s['qty']; ?></td>
                                        <td><?php echo htmlspecialchars($s['expiry_date']); ?></td>
                                        <td><?php echo htmlspecialchars($s['status']); ?></td>
                                        <td><?php echo htmlspecialchars($s['submitted_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>