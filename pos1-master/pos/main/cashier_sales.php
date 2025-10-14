<?php
session_start();
// Only allow access if user is cashier
if (!isset($_SESSION['SESS_USER_ROLE']) || $_SESSION['SESS_USER_ROLE'] !== 'cashier') {
    header('Location: ../index.php');
    exit();
}
// Cashier sales page content
?>
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
    <script src="chart.js"></script>
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

<body>
    <?php include('navfixed.php'); ?>
    <div class="container">
        <h2>Cashier Sales Page</h2>
        <p>Welcome, cashier! You can process sales here.</p>
        <!-- Add cashier sales logic here -->
        <a href="sales.php?id=cash">Go to Sales</a>
        <a href="../index.php">Logout</a>
    </div>
</body>

</html>