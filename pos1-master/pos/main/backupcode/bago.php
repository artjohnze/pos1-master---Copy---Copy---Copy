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
            flex-wrap: nowrap;
            justify-content: center;
            gap: 32px;
            margin-bottom: 40px;
        }

        .col-card {
            flex: 0 1 480px;
            min-width: 320px;
            max-width: 520px;
            margin: 0 8px;
        }

        background: #fff;

        .chart-panel {
            display: none;
        }

        .chart-panel.active {
            display: block;
        }

        box-shadow: 0 8px 32px rgba(33, 199, 221, 0.18);{
        }

        .card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
            color: #1a6fa6;
            letter-spacing: 0.5px;
        }

        .chart-container {
            position: relative;
            height: 370px;
            margin: 0 auto;
            width: 98%;
        }

        canvas {
            width: 100% !important;
            height: 370px !important;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(33, 199, 221, 0.07);
        }

        @media (max-width: 1200px) {
            .row-cards {
                gap: 18px;
            }

            .col-card {
                max-width: 420px;
            }
        }

        @media (max-width: 900px) {
            .row-cards {
                flex-wrap: wrap;
                gap: 18px;
            }

            .col-card {
                min-width: 90vw;
                max-width: 98vw;
            }
        }

        @media (max-width: 768px) {
            .row-cards {
                flex-direction: column;
                align-items: center;
                gap: 16px;
            }

            .col-card {
                min-width: 98vw;
                max-width: 99vw;
            }

            .card {
                padding: 18px 6px 12px 6px;
            }

            .chart-container {
                height: 320px;
            }

            canvas {
                height: 320px !important;
            }
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        .total-card {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
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


            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-6 offset-md-3 text-center">
                    <label for="chartSelect" style="font-weight: bold; font-size: 18px;">Select Sales Chart Pair:</label>
                    <select id="chartSelect" class="form-control" style="width: 350px; display: inline-block; margin-left: 10px;">
                        <option value="pair1">Today's - Yesterday's</option>
                        <option value="pair2">Weekly Sales - Monthly Sales</option>
                        <option value="pair3">Yearly - Product Sales Today</option>
                    </select>
                </div>
            </div>

            <div class="row-cards">
                <div class="col-card chart-panel" id="panel-daily">
                    <div class="card">
                        <h3> Today's Sales</h3>
                        <div class="chart-container"><canvas id="dailyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-yesterday">
                    <div class="card">
                        <h3> Yesterday's Sales</h3>
                        <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-weekly">
                    <div class="card">
                        <h3> Weekly Sales</h3>
                        <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-monthly">
                    <div class="card">
                        <h3> Monthly Sales</h3>
                        <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-yearly">
                    <div class="card">
                        <h3> Yearly Sales</h3>
                        <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-product">
                    <div class="card">
                        <h3> Product Sales Today</h3>
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

                // Dropdown logic for pairs
                function showChartPair(pair) {
                    var panels = document.querySelectorAll('.chart-panel');
                    panels.forEach(function(panel) {
                        panel.style.display = 'none';
                    });
                    if (pair === 'pair1') {
                        document.getElementById('panel-daily').style.display = '';
                        document.getElementById('panel-yesterday').style.display = '';
                    } else if (pair === 'pair2') {
                        document.getElementById('panel-weekly').style.display = '';
                        document.getElementById('panel-monthly').style.display = '';
                    } else if (pair === 'pair3') {
                        document.getElementById('panel-yearly').style.display = '';
                        document.getElementById('panel-product').style.display = '';
                    }
                }
                // Initial display
                showChartPair('pair1');
                document.getElementById('chartSelect').addEventListener('change', function() {
                    showChartPair(this.value);
                });
            </script>

</body>

</html>



bago 
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
            flex-wrap: nowrap;
            justify-content: center;
            gap: 32px;
            margin-bottom: 40px;
        }

        .col-card {
            flex: 0 1 480px;
            min-width: 320px;
            max-width: 520px;
            margin: 0 8px;
        }

        background: #fff;

        .chart-panel {
            display: none;
        }

        .chart-panel.active {
            display: block;
        }

        box-shadow: 0 8px 32px rgba(33, 199, 221, 0.18);{
        }

        .card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
            color: #1a6fa6;
            letter-spacing: 0.5px;
        }

        .chart-container {
            position: relative;
            height: 370px;
            margin: 0 auto;
            width: 98%;
        }

        canvas {
            width: 100% !important;
            height: 370px !important;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(33, 199, 221, 0.07);
        }

        @media (max-width: 1200px) {
            .row-cards {
                gap: 18px;
            }

            .col-card {
                max-width: 420px;
            }
        }

        @media (max-width: 900px) {
            .row-cards {
                flex-wrap: wrap;
                gap: 18px;
            }

            .col-card {
                min-width: 90vw;
                max-width: 98vw;
            }
        }

        @media (max-width: 768px) {
            .row-cards {
                flex-direction: column;
                align-items: center;
                gap: 16px;
            }

            .col-card {
                min-width: 98vw;
                max-width: 99vw;
            }

            .card {
                padding: 18px 6px 12px 6px;
            }

            .chart-container {
                height: 320px;
            }

            canvas {
                height: 320px !important;
            }
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        .total-card {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
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


            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-6 offset-md-3 text-center">
                    <label for="chartSelect" style="font-weight: bold; font-size: 18px;">Select Sales Chart Pair:</label>
                    <select id="chartSelect" class="form-control" style="width: 350px; display: inline-block; margin-left: 10px;">
                        <option value="pair1">Today's - Yesterday's</option>
                        <option value="pair2">Weekly Sales - Monthly Sales</option>
                        <option value="pair3">Yearly - Product Sales Today</option>
                    </select>
                </div>
            </div>

            <div class="row-cards">
                <div class="col-card chart-panel" id="panel-daily">
                    <div class="card">
                        <h3> Today's Sales</h3>
                        <div class="chart-container"><canvas id="dailyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-yesterday">
                    <div class="card">
                        <h3> Yesterday's Sales</h3>
                        <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-weekly">
                    <div class="card">
                        <h3> Weekly Sales</h3>
                        <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-monthly">
                    <div class="card">
                        <h3> Monthly Sales</h3>
                        <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-yearly">
                    <div class="card">
                        <h3> Yearly Sales</h3>
                        <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
                    </div>
                </div>
                <div class="col-card chart-panel" id="panel-product">
                    <div class="card">
                        <h3> Product Sales Today</h3>
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

                // Dropdown logic for pairs
                function showChartPair(pair) {
                    var panels = document.querySelectorAll('.chart-panel');
                    panels.forEach(function(panel) {
                        panel.style.display = 'none';
                    });
                    if (pair === 'pair1') {
                        document.getElementById('panel-daily').style.display = '';
                        document.getElementById('panel-yesterday').style.display = '';
                    } else if (pair === 'pair2') {
                        document.getElementById('panel-weekly').style.display = '';
                        document.getElementById('panel-monthly').style.display = '';
                    } else if (pair === 'pair3') {
                        document.getElementById('panel-yearly').style.display = '';
                        document.getElementById('panel-product').style.display = '';
                    }
                }
                // Initial display
                showChartPair('pair1');
                document.getElementById('chartSelect').addEventListener('change', function() {
                    showChartPair(this.value);
                });
            </script>

</body>

</html>