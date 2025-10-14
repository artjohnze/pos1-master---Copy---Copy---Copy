<?php
<<<<<<< HEAD
// Start session and authentication first
require_once('auth.php');

=======
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
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
<<<<<<< HEAD
=======
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
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }



        .row-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }

        .col-card {
            flex: 1 1 350px;
            /* smaller min width */
            max-width: 400px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            padding: 10px;
            /* even smaller padding */
            margin-bottom: 10px;
        }

        .card h2 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            text-align: center;
            color: #333;
        }

        .chart-container {
            position: relative;
            height: 200px;
            /* much smaller height */
            margin: 5px 0;
        }

        canvas {
            width: 100% !important;
            height: 200px !important;
            /* much smaller graph */
        }

        .total-card {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: rgb(219, 107, 15);
        }

        @media (max-width: 768px) {
            .col-card {
                min-width: 90%;
            }

            .row-cards {
                flex-direction: column;
            }

            .chart-container {
                height: 180px !important;
            }

            canvas {
                height: 180px !important;
            }
        }
    </style>
<<<<<<< HEAD

    <style>
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
=======
    <?php require_once('auth.php'); ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
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
<<<<<<< HEAD
                        <!-- <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li> -->
=======
                        <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
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
<<<<<<< HEAD
                        <h2>📅 Today's Sales</h2>
=======
                        <h3>📅 Today's Sales</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="dailyChart"></canvas></div>
                    </div>
                </div>

                <!-- Yesterday -->
                <div class="col-card">
                    <div class="card">
<<<<<<< HEAD
                        <h2>🕒 Yesterday's Sales</h2>
=======
                        <h3>🕒 Yesterday's Sales</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="yesterdayChart"></canvas></div>
                    </div>
                </div>

                <!-- Weekly -->
                <div class="col-card">
                    <div class="card">
<<<<<<< HEAD
                        <h2>📅 Weekly Sales</h2>
=======
                        <h3>📅 Weekly Sales</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
                    </div>
                </div>

                <!-- Monthly -->
                <div class="col-card">
                    <div class="card">
<<<<<<< HEAD
                        <h2>🗓 Monthly Sales</h2>
=======
                        <h3>🗓 Monthly Sales</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>

                <!-- Yearly -->
                <div class="col-card">
                    <div class="card">
<<<<<<< HEAD
                        <h2>📆 Yearly Sales</h2>
=======
                        <h3>📆 Yearly Sales</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="yearlyChart"></canvas></div>
                    </div>
                </div>

                <!-- Product Sales -->
                <div class="col-card">
                    <div class="card">
<<<<<<< HEAD
                        <h2>📦 Product Sales Today</h2>
=======
                        <h3>📦 Product Sales Today</h3>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                        <div class="chart-container"><canvas id="productChart"></canvas></div>
                    </div>
                </div>

            </div>

            <script>
<<<<<<< HEAD
                // Helper function
=======
                // Helper
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                function pesoFormat(value) {
                    return '₱' + new Intl.NumberFormat('en-PH').format(value);
                }

<<<<<<< HEAD
                // Enhanced chart configurations with better styling
                const chartConfigs = [{
                        id: 'dailyChart',
                        label: "Today's Sales",
                        data: <?= json_encode($data_day) ?>,
                        labels: <?= json_encode($labels_day) ?>,
                        color: 'rgba(102, 126, 234, 0.8)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        type: 'bar'
=======
                // Chart configs
                const chartData = [{
                        id: 'dailyChart',
                        label: "Today's Sales",
                        data: <?= json_encode($data_day) ?>,
                        labels: <?= json_encode($labels_day) ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                    },
                    {
                        id: 'yesterdayChart',
                        label: "Yesterday's Sales",
                        data: <?= json_encode($data_yesterday) ?>,
<<<<<<< HEAD
                        labels: <?= json_encode($labels_yesterday) ?>,
                        color: 'rgba(240, 147, 251, 0.8)',
                        borderColor: 'rgba(240, 147, 251, 1)',
                        type: 'bar'
=======
                        labels: <?= json_encode($labels_yesterday) ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                    },
                    {
                        id: 'weeklyChart',
                        label: "Weekly Sales",
                        data: <?= json_encode($data_week) ?>,
<<<<<<< HEAD
                        labels: <?= json_encode($labels_week) ?>,
                        color: 'rgba(79, 172, 254, 0.8)',
                        borderColor: 'rgba(79, 172, 254, 1)',
                        type: 'bar'
=======
                        labels: <?= json_encode($labels_week) ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                    },
                    {
                        id: 'monthlyChart',
                        label: "Monthly Sales",
                        data: <?= json_encode($month_data) ?>,
<<<<<<< HEAD
                        labels: <?= json_encode($month_labels) ?>,
                        color: 'rgba(67, 233, 123, 0.8)',
                        borderColor: 'rgba(67, 233, 123, 1)',
                        type: 'bar'
=======
                        labels: <?= json_encode($month_labels) ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                    },
                    {
                        id: 'yearlyChart',
                        label: "Yearly Sales",
                        data: <?= json_encode($data_year) ?>,
<<<<<<< HEAD
                        labels: <?= json_encode($labels_year) ?>,
                        color: 'rgba(250, 112, 154, 0.8)',
                        borderColor: 'rgba(250, 112, 154, 1)',
                        type: 'bar'
=======
                        labels: <?= json_encode($labels_year) ?>
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f
                    },
                    {
                        id: 'productChart',
                        label: "Units Sold",
                        data: <?= json_encode($product_data) ?>,
<<<<<<< HEAD
                        labels: <?= json_encode($product_labels) ?>,
                        color: 'rgba(118, 75, 162, 0.8)',
                        borderColor: 'rgba(118, 75, 162, 1)',
                        type: 'doughnut'
                    }
                ];

                // Create enhanced charts
                chartConfigs.forEach(config => {
                    const ctx = document.getElementById(config.id).getContext('2d');

                    // Create gradient for better visual appeal
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, config.color);
                    gradient.addColorStop(1, config.color.replace('0.8', '0.2'));

                    const chartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1500,
                            easing: 'easeInOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: config.borderColor,
                                borderWidth: 1,
                                cornerRadius: 6,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return config.id === 'productChart' ?
                                            context.label + ': ' + context.parsed + ' item' :
                                            config.label + ': ' + pesoFormat(context.parsed.y || context.parsed);
                                    }
                                }
                            }
                        }
                    };

                    // Add scales for bar and line charts
                    if (config.type === 'bar' || config.type === 'line') {
                        chartOptions.scales = {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    callback: function(value) {
                                        return config.id === 'productChart' ? value : pesoFormat(value);
                                    }
                                }
                            }
                        };
                    }

                    // Chart data based on type
                    let chartData;
                    if (config.type === 'doughnut') {
                        chartData = {
                            labels: config.labels,
                            datasets: [{
                                data: config.data,
                                backgroundColor: [
                                    '#667eea', '#764ba2', '#f093fb', '#f5576c',
                                    '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
                                    '#fa709a', '#fee140'
                                ],
                                borderWidth: 0,
                                hoverOffset: 8
                            }]
                        };
                    } else {
                        chartData = {
                            labels: config.labels,
                            datasets: [{
                                label: config.label,
                                data: config.data,
                                backgroundColor: config.type === 'line' ? gradient : gradient,
                                borderColor: config.borderColor,
                                borderWidth: config.type === 'line' ? 3 : 2,
                                borderRadius: config.type === 'line' ? 0 : 4,
                                borderSkipped: false,
                                fill: config.type === 'line',
                                tension: config.type === 'line' ? 0.4 : 0,
                                pointBackgroundColor: config.type === 'line' ? config.borderColor : undefined,
                                pointBorderColor: config.type === 'line' ? '#fff' : undefined,
                                pointBorderWidth: config.type === 'line' ? 2 : undefined,
                                pointRadius: config.type === 'line' ? 5 : undefined,
                                pointHoverRadius: config.type === 'line' ? 7 : undefined
                            }]
                        };
                    }

                    new Chart(ctx, {
                        type: config.type,
                        data: chartData,
                        options: chartOptions
                    });
                });
            </script>

</body>
=======
                        labels: <?= json_encode($product_labels) ?>
                    }
                ];
>>>>>>> 7afa611c0227699da662c76ad192913c471abc9f

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