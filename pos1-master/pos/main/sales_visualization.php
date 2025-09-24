<!DOCTYPE html>
<html>

<head>
    <title>POS - Sales Visualization</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>POS - Sales Visualization</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                background: #f4f6f9;
                font-family: "Segoe UI", Arial, sans-serif;
            }

            .card {
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                padding: 25px;
                margin-bottom: 30px;
                transition: transform 0.2s ease;
            }

            .card:hover {
                transform: translateY(-3px);
            }

            .card h3 {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                color: #333;
                text-align: center;
            }

            .row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }

            .col {
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
    </head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-inner">
            <a class="brand" href="#"><i class="icon-dashboard"></i> POS Dashboard</a>
            <ul class="nav">
                <li><a href="index.php"><i class="icon-home"></i> Dashboard</a></li>
                <li><a href="sales.php"><i class="icon-shopping-cart"></i> Sales</a></li>
                <li><a href="products.php"><i class="icon-list-alt"></i> Products</a></li>
                <li><a href="customer.php"><i class="icon-group"></i> Customers</a></li>
                <li><a href="supplier.php"><i class="icon-truck"></i> Suppliers</a></li>
                <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart"></i> Sales Report</a></li>
                <li class="active"><a href="sales_visualization.php"><i class="icon-signal"></i> Visualization</a></li>
            </ul>
            <ul class="nav pull-right">
                <li><a href="../index.php"><i class="icon-off"></i> Logout</a></li>
            </ul>
        </div>
    </nav>
    <?php
    $pdo = new PDO("mysql:host=localhost;dbname=sales;charset=utf8", "root", "");
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
    $grand = $pdo->query("SELECT SUM(amount) AS total FROM sales_order")->fetch(PDO::FETCH_ASSOC);
    $grand_total = $grand['total'] ?? 0;
    ?>
    <div class="dashboard container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <h3>📅 Daily Sales</h3>
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <h3>🗓 Monthly Sales (Jan–Dec)</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="card total-card">
            💰 Grand Total Sales: ₱<?= number_format($grand_total, 2) ?>
        </div>
    </div>
    <script>
        new Chart(document.getElementById('dailyChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels_day) ?>,
                datasets: [{
                    label: 'Daily Sales (₱)',
                    data: <?= json_encode($data_day) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1200
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
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
                animation: {
                    duration: 1200
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>