<!DOCTYPE html>
<html>

<head>
    <title>POS - Sales Visualization</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />

    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sidebar-nav {
            padding: 9px 0;
        }

        .chart-container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .1);
        }

        .total-box {
            margin: 20px auto;
            padding: 20px;
            max-width: 500px;
            background: #f5f5f5;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
    </style>

    <?php require_once('auth.php'); ?>
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
</head>

<body>
    <?php include('navfixed.php'); ?>
    <?php
    $position = $_SESSION['SESS_LAST_NAME'];
    if ($position == 'cashier') {
        echo '<a href="../index.php">Logout</a>';
    }
    if ($position == 'admin') {
    ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li><a href="#"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                            <li><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i
                                        class="icon-shopping-cart icon-2x"></i> Sales</a></li>
                            <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                            <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a></li>
                            <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                            <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a>
                            </li>
                            <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                            <li class="active"><a href="sales_visualization.php"><i class="icon-bar-chart icon-2x"></i>
                                    Sales Visualization</a></li>
                        </ul>
                    </div>
                </div>

                <div class="span10">
                    <div class="contentheader">
                        <i class="icon-bar-chart"></i> Sales Visualization
                    </div>
                    <ul class="breadcrumb">
                        <li class="active">Sales Visualization</li>
                    </ul>

                    <?php
                    // Database connection
                    $pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");

                    // DAILY SALES (per date)
                    $stmt = $pdo->query("
                        SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate,
                               SUM(amount) as total_sales
                        FROM sales_order
                        GROUP BY sdate
                        ORDER BY sdate
                    ");
                    $labels_day = [];
                    $data_day = [];
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $labels_day[] = $row['sdate'];
                        $data_day[]   = (float)$row['total_sales'];
                    }

                    // MONTHLY SALES (grouped by year-month)
                    $stmt2 = $pdo->query("
                        SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%Y-%m') as month,
                               SUM(amount) as total_sales
                        FROM sales_order
                        GROUP BY month
                        ORDER BY month
                    ");
                    $labels_month = [];
                    $data_month = [];
                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        $labels_month[] = $row['month']; // e.g. "2025-08"
                        $data_month[]   = (float)$row['total_sales'];
                    }

                    // GRAND TOTAL
                    $grand = $pdo->query("SELECT SUM(amount) AS total FROM sales_order")->fetch(PDO::FETCH_ASSOC);
                    $grand_total = $grand['total'] ?? 0;
                    ?>

                    <div class="chart-container">
                        <h3 class="text-center">📅 Daily Sales</h3>
                        <canvas id="dailyChart"></canvas>
                    </div>

                    <div class="chart-container">
                        <h3 class="text-center">🗓 Monthly Sales</h3>
                        <canvas id="monthlyChart"></canvas>
                    </div>

                    <div class="total-box">
                        💰 Grand Total Sales: ₱<?= number_format($grand_total, 2) ?>
                    </div>

                    <script>
                        // Daily chart
                        new Chart(document.getElementById('dailyChart').getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: <?= json_encode($labels_day) ?>,
                                datasets: [{
                                    label: 'Daily Sales (₱)',
                                    data: <?= json_encode($data_day) ?>,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });

                        // Monthly chart
                        new Chart(document.getElementById('monthlyChart').getContext('2d'), {
                            type: 'line', // line chart for trend
                            data: {
                                labels: <?= json_encode($labels_month) ?>,
                                datasets: [{
                                    label: 'Monthly Sales (₱)',
                                    data: <?= json_encode($data_month) ?>,
                                    fill: true,
                                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 2,
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    <?php } ?>
</body>
<?php include('footer.php'); ?>

</html>