<?php
// Start session and authentication
require_once('auth.php');

// DB connection
$pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
?>

<!DOCTYPE html>
<html>

<head>
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

        .chart-container {
            position: relative;
            height: 400px;
            margin: 10px 0;
        }

        canvas {
            width: 100% !important;
            height: 400px !important;
        }

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
            canvas {
                height: 350px !important;
            }
        }
    </style>
</head>

<body>
    <?php include('navfixed.php'); ?>

    <?php
    $position = $_SESSION['SESS_LAST_NAME'];
    if ($position == 'cashier') {
        echo '<a href="../index.php">Logout</a>';
    }

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

</body>

</html>