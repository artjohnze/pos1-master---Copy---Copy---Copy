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

    <script src="lib/jquery.js"></script>
    <script src="src/facebox.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    .total-card {
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        color: #28a745;
    }

    canvas {
        width: 100% !important;
        height: 320px !important;
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
    $finalcode = 'RS-' . createRandomPassword();
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
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <li><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i
                                    class="icon-shopping-cart icon-2x"></i> Sales</a></li>
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
<!--                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a></li>-->
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a>
                        </li>

                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
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
                    // DB connection
                    $pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");

                    // DAILY SALES
                    $stmt = $pdo->prepare("
    SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate,
           SUM(amount) as total_sales
    FROM sales_order
    WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE()
    GROUP BY sdate
");
                    $stmt->execute();
                    $labels_day = [];
                    $data_day = [];
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $labels_day[] = $row['sdate'];
                        $data_day[]   = (float)$row['total_sales'];
                    }

                    // MONTHLY SALES
                    $stmt2 = $pdo->query("
                        SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%m') as month_num,
                               SUM(amount) as total_sales
                        FROM sales_order
                        GROUP BY month_num
                    ");
                    $sales_month = [];
                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        $sales_month[(int)$row['month_num']] = (float)$row['total_sales'];
                    }
                    $month_labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    $month_data = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $month_data[] = $sales_month[$i] ?? 0;
                    }

                    // GRAND TOTAL
                    $grand = $pdo->query("SELECT SUM(amount) AS total FROM sales_order")->fetch(PDO::FETCH_ASSOC);
                    $grand_total = $grand['total'] ?? 0;
                    ?>

                <div class="row-cards">
                    <div class="col-card">
                        <div class="card">
                            <h3>📅 Daily Sales</h3>
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                    <div class="col-card">
                        <div class="card">
                            <h3>🗓 Monthly Sales (Jan–Dec)</h3>
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
    // Daily Chart
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels_day) ?>,
            datasets: [{
                label: 'Daily Sales (₱)',
                data: <?= json_encode($data_day) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 6
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

    // Monthly Chart
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($month_labels) ?>,
            datasets: [{
                label: 'Monthly Sales (₱)',
                data: <?= json_encode($month_data) ?>,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.25)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 3,
                tension: 0.4,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 5
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
    <?php } ?>
</body>
<?php include('footer.php'); ?>

</html>