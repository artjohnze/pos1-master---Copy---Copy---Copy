<?php
// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>POS - Sales Visualization</title>
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

    ?>
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
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                        <!-- <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a> -->
                        </li>

                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                    </ul>
                </div>
            </div>

            <?php
            // 1. Today Sales
            $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales
    FROM sales_order
    WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE()
    GROUP BY sdate");
            $stmt->execute();
            $labels_day = [];
            $data_day = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels_day[] = $row['sdate'];
                $data_day[] = (float)$row['total_sales'];
            }

            // 2. Yesterday
            $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales
    FROM sales_order
    WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE() - INTERVAL 1 DAY
    GROUP BY sdate");
            $stmt->execute();
            $labels_yesterday = [];
            $data_yesterday = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels_yesterday[] = $row['sdate'];
                $data_yesterday[] = (float)$row['total_sales'];
            }

            // 3. Weekly (last 7 days)
            $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales
    FROM sales_order
    WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) >= CURDATE() - INTERVAL 6 DAY
    GROUP BY sdate ORDER BY sdate");
            $stmt->execute();
            $labels_week = [];
            $data_week = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels_week[] = $row['sdate'];
                $data_week[] = (float)$row['total_sales'];
            }

            // 4. Monthly
            $stmt2 = $pdo->query("SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%m') as month_num, SUM(amount) as total_sales
    FROM sales_order GROUP BY month_num");
            $sales_month = [];
            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $sales_month[(int)$row['month_num']] = (float)$row['total_sales'];
            }
            $month_labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $month_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $month_data[] = $sales_month[$i] ?? 0;
            }

            // 5. Yearly
            $stmt = $pdo->query("SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%Y') as year, SUM(amount) as total_sales
    FROM sales_order GROUP BY year ORDER BY year");
            $labels_year = [];
            $data_year = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels_year[] = $row['year'];
                $data_year[] = (float)$row['total_sales'];
            }

            // 6. Product Sales Today
            $stmt = $pdo->prepare("SELECT product_code, SUM(qty) as total_qty
    FROM sales_order
    WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE()
    GROUP BY product_code ORDER BY total_qty DESC LIMIT 10");
            $stmt->execute();
            $product_labels = [];
            $product_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_labels[] = $row['product_code'];
                $product_data[] = (int)$row['total_qty'];
            }
            ?>

            <div class="row-cards">

                <!-- Daily -->
                <div class="col-card">
                    <div class="card">
                        <h3>📅 Today's Sales</h3>
                        <div class="chart-container"><canvas id="dailyChart"></canvas></div>
                    </div>
                </div>

                <!-- Yesterday -->
                <div class="col-card">
                    <div class="card">
                        <h3>🕒 Yesterday's Sales</h3>
                        <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
                    </div>
                </div>

                <!-- Weekly -->
                <div class="col-card">
                    <div class="card">
                        <h3>📅 Weekly Sales</h3>
                        <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
                    </div>
                </div>

                <!-- Monthly -->
                <div class="col-card">
                    <div class="card">
                        <h3>🗓 Monthly Sales</h3>
                        <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>

                <!-- Yearly -->
                <div class="col-card">
                    <div class="card">
                        <h3>📆 Yearly Sales</h3>
                        <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
                    </div>
                </div>

                <!-- Product Sales -->
                <div class="col-card">
                    <div class="card">
                        <h3>📦 Product Sales Today</h3>
                        <div class="chart-container"><canvas id="productChart"></canvas></div>
                    </div>
                </div>

            </div>

            <script>
                // Helper
                function pesoFormat(value) {
                    return '₱' + new Intl.NumberFormat('en-PH').format(value);
                }

                // Chart configs
                const chartData = [{
                        id: 'dailyChart',
                        label: "Today's Sales",
                        data: <?= json_encode($data_day) ?>,
                        labels: <?= json_encode($labels_day) ?>
                    },
                    {
                        id: 'yesterdayChart',
                        label: "Yesterday's Sales",
                        data: <?= json_encode($data_yesterday) ?>,
                        labels: <?= json_encode($labels_yesterday) ?>
                    },
                    {
                        id: 'weeklyChart',
                        label: "Weekly Sales",
                        data: <?= json_encode($data_week) ?>,
                        labels: <?= json_encode($labels_week) ?>
                    },
                    {
                        id: 'monthlyChart',
                        label: "Monthly Sales",
                        data: <?= json_encode($month_data) ?>,
                        labels: <?= json_encode($month_labels) ?>
                    },
                    {
                        id: 'yearlyChart',
                        label: "Yearly Sales",
                        data: <?= json_encode($data_year) ?>,
                        labels: <?= json_encode($labels_year) ?>
                    },
                    {
                        id: 'productChart',
                        label: "Units Sold",
                        data: <?= json_encode($product_data) ?>,
                        labels: <?= json_encode($product_labels) ?>
                    }
                ];

                chartData.forEach(cfg => {
                    new Chart(document.getElementById(cfg.id), {
                        type: 'bar',
                        data: {
                            labels: cfg.labels,
                            datasets: [{
                                label: cfg.label,
                                data: cfg.data,
                                backgroundColor: 'rgba(33, 199, 221, 0.7)'
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return cfg.id === 'productChart' ? value : pesoFormat(value);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return cfg.id === 'productChart' ?
                                                context.parsed.y + ' units' :
                                                pesoFormat(context.parsed.y);
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>

</body>

</html>


<!-- changes the design make it responsive ui


add also dropdown
Today's Sales, Yesterday's Sales, Weekly Sales = Product Sales Today, Monthly Sales Yearly Sales -->