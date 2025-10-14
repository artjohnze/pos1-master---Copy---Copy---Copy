<<<<<<< HEAD
<?php
// Start session and authentication
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

=======
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
<!DOCTYPE html>
<html>

<head>
<<<<<<< HEAD
    <title>POS - Sales Visualization</title>
    <meta charset="utf-8">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/DT_bootstrap.css">
    <script src="lib/jquery.js"></script>
    <script src="src/facebox.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-nav {
            padding: 9px 0;
=======
    <title>Sales Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />

    <script src="lib/jquery.js"></script>
    <script src="src/facebox.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding: 20px;
        }

        .row-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .col-card {
            flex: 1;
            min-width: 400px;
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
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
<<<<<<< HEAD
=======
            color: #333;
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
        }

        .chart-container {
            position: relative;
            height: 400px;
<<<<<<< HEAD
            margin: 10px 0;
=======
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }

<<<<<<< HEAD
        .form-group {
            margin: 20px 0;
        }

        .chart-section {
            display: none;
        }

        .chart-section.active {
            display: block;
        }

        @media (max-width: 768px) {
=======
        @media (max-width: 768px) {
            .col-card {
                min-width: 100%;
            }

            .row-cards {
                flex-direction: column;
            }

>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
            canvas {
                height: 350px !important;
            }
        }
<<<<<<< HEAD
    </style>
=======

        .total-card {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
        }

        .dropdown {
            margin-bottom: 20px;
        }

        .dropdown select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            max-width: 300px;
        }

        /* Improved Chart Colors */
        .chart-container {
            background-color: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 20px;
        }

        .chart-container canvas {
            border-radius: 14px;
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
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
</head>

<body>
    <?php include('navfixed.php'); ?>
<<<<<<< HEAD

=======
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
    <?php
    $position = $_SESSION['SESS_LAST_NAME'];
    if ($position == 'cashier') {
        echo '<a href="../index.php">Logout</a>';
    }

<<<<<<< HEAD
    // Today's Sales
    $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales FROM sales_order WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE() GROUP BY sdate");
    $stmt->execute();
    $labels_day = [];
    $data_day = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels_day[] = $row['sdate'];
        $data_day[] = (float)$row['total_sales'];
    }

    // Yesterday
    $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales FROM sales_order WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE() - INTERVAL 1 DAY GROUP BY sdate");
    $stmt->execute();
    $labels_yesterday = [];
    $data_yesterday = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels_yesterday[] = $row['sdate'];
        $data_yesterday[] = (float)$row['total_sales'];
    }

    // Weekly (last 7 days)
    $stmt = $pdo->prepare("SELECT DATE(STR_TO_DATE(`date`, '%m/%d/%y')) as sdate, SUM(amount) as total_sales FROM sales_order WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) >= CURDATE() - INTERVAL 6 DAY GROUP BY sdate ORDER BY sdate");
    $stmt->execute();
    $labels_week = [];
    $data_week = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels_week[] = $row['sdate'];
        $data_week[] = (float)$row['total_sales'];
    }

    // Monthly
    $stmt2 = $pdo->query("SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%m') as month_num, SUM(amount) as total_sales FROM sales_order GROUP BY month_num");
    $sales_month = [];
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $sales_month[(int)$row['month_num']] = (float)$row['total_sales'];
    }
    $month_labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $month_data = [];
    for ($i = 1; $i <= 12; $i++) {
        $month_data[] = $sales_month[$i] ?? 0;
    }

    // Yearly
    $stmt = $pdo->query("SELECT DATE_FORMAT(STR_TO_DATE(`date`, '%m/%d/%y'), '%Y') as year, SUM(amount) as total_sales FROM sales_order GROUP BY year ORDER BY year");
    $labels_year = [];
    $data_year = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels_year[] = $row['year'];
        $data_year[] = (float)$row['total_sales'];
    }

    // Product Sales Today
    $stmt = $pdo->prepare("SELECT product_code, SUM(qty) as total_qty FROM sales_order WHERE DATE(STR_TO_DATE(`date`, '%m/%d/%y')) = CURDATE() GROUP BY product_code ORDER BY total_qty DESC LIMIT 10");
    $stmt->execute();
    $product_labels = [];
    $product_data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_labels[] = $row['product_code'];
        $product_data[] = (int)$row['total_qty'];
    }
    ?>

    <div class="container">

        <!-- Dropdown -->
        <div class="form-group">
            <label for="chartSelector"><strong>📊 Select Chart to View:</strong></label>
            <select id="chartSelector" class="form-control" style="max-width: 300px;">
                <option value="dailyChart">Today's Sales</option>
                <option value="productChart">Product Sales Today</option>
                <option value="yesterdayChart">Yesterday's Sales</option>
                <option value="weeklyChart">Weekly Sales</option>
                <option value="monthlyChart">Monthly Sales</option>
                <option value="yearlyChart">Yearly Sales</option>
            </select>
        </div>

        <!-- Chart Sections -->
        <div class="chart-section" id="dailyChartSection">
            <div class="card">
                <h3>📅 Today's Sales</h3>
                <div class="chart-container"><canvas id="dailyChart"></canvas></div>
            </div>
        </div>

        <div class="chart-section" id="productChartSection">
            <div class="card">
                <h3>📦 Product Sales Today</h3>
                <div class="chart-container"><canvas id="productChart"></canvas></div>
            </div>
        </div>

        <div class="chart-section" id="yesterdayChartSection">
            <div class="card">
                <h3>🕒 Yesterday's Sales</h3>
                <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
            </div>
        </div>

        <div class="chart-section" id="weeklyChartSection">
            <div class="card">
                <h3>📅 Weekly Sales</h3>
                <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
            </div>
        </div>

        <div class="chart-section" id="monthlyChartSection">
            <div class="card">
                <h3>🗓 Monthly Sales</h3>
                <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
            </div>
        </div>

        <div class="chart-section" id="yearlyChartSection">
            <div class="card">
                <h3>📆 Yearly Sales</h3>
                <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
            </div>
        </div>
    </div>

    <script>
        // Chart data from PHP
        const chartData = {
            dailyChart: {
                labels: <?= json_encode($labels_day) ?>,
                data: <?= json_encode($data_day) ?>,
                label: "Today's Sales"
            },
            productChart: {
                labels: <?= json_encode($product_labels) ?>,
                data: <?= json_encode($product_data) ?>,
                label: "Units Sold"
            },
            yesterdayChart: {
                labels: <?= json_encode($labels_yesterday) ?>,
                data: <?= json_encode($data_yesterday) ?>,
                label: "Yesterday's Sales"
            },
            weeklyChart: {
                labels: <?= json_encode($labels_week) ?>,
                data: <?= json_encode($data_week) ?>,
                label: "Weekly Sales"
            },
            monthlyChart: {
                labels: <?= json_encode($month_labels) ?>,
                data: <?= json_encode($month_data) ?>,
                label: "Monthly Sales"
            },
            yearlyChart: {
                labels: <?= json_encode($labels_year) ?>,
                data: <?= json_encode($data_year) ?>,
                label: "Yearly Sales"
            }
        };

        const pesoFormat = value => '₱' + new Intl.NumberFormat('en-PH').format(value);

        function renderChart(id, type) {
            const ctx = document.getElementById(id).getContext('2d');
            const config = chartData[id];

            let datasetOptions = {
                label: config.label,
                data: config.data,
                backgroundColor: type === 'doughnut' ? [
                    '#667eea', '#764ba2', '#f093fb', '#f5576c',
                    '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
                    '#fa709a', '#fee140'
                ] : 'rgba(102, 126, 234, 0.8)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 1
            };

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type === 'doughnut',
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (id === 'productChart') {
                                    return context.label + ': ' + context.parsed + ' unit(s)';
                                } else {
                                    return pesoFormat(context.parsed.y || context.parsed);
                                }
                            }
                        }
                    }
                },
                scales: type !== 'doughnut' ? {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return id === 'productChart' ? value : pesoFormat(value);
                            }
                        }
                    }
                } : {}
            };

            return new Chart(ctx, {
                type: type,
                data: {
                    labels: config.labels,
                    datasets: [datasetOptions]
                },
                options: chartOptions
            });
        }

        // Render all charts on load
        const charts = {
            dailyChart: renderChart('dailyChart', 'bar'),
            productChart: renderChart('productChart', 'doughnut'),
            yesterdayChart: renderChart('yesterdayChart', 'bar'),
            weeklyChart: renderChart('weeklyChart', 'bar'),
            monthlyChart: renderChart('monthlyChart', 'bar'),
            yearlyChart: renderChart('yearlyChart', 'bar')
        };

        // Show selected chart only
        function showChart(selected) {
            document.querySelectorAll('.chart-section').forEach(section => {
                section.classList.remove('active');
            });
            const activeSection = document.getElementById(selected + 'Section');
            if (activeSection) {
                activeSection.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const selector = document.getElementById('chartSelector');
            showChart(selector.value); // Initial chart

            selector.addEventListener('change', function() {
                showChart(this.value);
            });
        });
    </script>
=======
    ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
                        <li><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
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
                                backgroundColor: 'rgba(33, 199, 221, 0.7)',
                                borderColor: 'rgba(33, 199, 221, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#333'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return cfg.id === 'productChart' ? value : pesoFormat(value);
                                        },
                                        color: '#333'
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        label: function(context) {
                                            return cfg.id === 'productChart' ?
                                                context.parsed.y + ' units' :
                                                pesoFormat(context.parsed.y);
                                        }
                                    }
                                },
                                legend: {
                                    position: 'top',
                                    align: 'center',
                                    labels: {
                                        color: '#333'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f

</body>

</html>