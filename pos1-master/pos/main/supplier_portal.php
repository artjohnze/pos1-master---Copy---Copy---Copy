<?php
require_once('auth.php');
include('../connect.php');

// allow only users with position 'supplier' to access

if (!isset($_SESSION['SESS_USER_ROLE']) || $_SESSION['SESS_USER_ROLE'] !== 'supplier') {
    header('Location: ../index.php');
    exit;
}

$supplier_name = isset($_SESSION['SESS_FIRST_NAME']) ? $_SESSION['SESS_FIRST_NAME'] : '';

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Supplier Portal</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">

    <link href="css/bootstrap.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
</head>

<body>
    <?php include('navfixed.php'); ?>

    <div class="container">
        <h3>Supplier Portal</h3>
        <p>Welcome, <?php echo htmlspecialchars($supplier_name); ?>. Use the form below to submit delivered products. Admin will review and accept deliveries.</p>

        <style>
            .form-container {
                background: #f9f9f9;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                font-weight: bold;
                color: #555;
                display: block;
                margin-bottom: 5px;
            }

            .form-control {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                transition: border-color 0.3s;
            }

            .form-control:focus {
                border-color: #66afe9;
                outline: none;
                box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
            }

            .form-row {
                display: flex;
                gap: 15px;
                margin-bottom: 15px;
            }

            .form-col {
                flex: 1;
            }

            .help-text {
                font-size: 12px;
                color: #666;
                margin-top: 4px;
            }

            .btn-submit {
                background: #337ab7;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background 0.3s;
            }

            .btn-submit:hover {
                background: #286090;
            }
        </style>

        <form action="save_supplier_delivery.php" method="post" class="form-container">
            <input type="hidden" name="supplier" value="<?php echo htmlspecialchars($supplier_name); ?>">

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label>Brand name</label>
                        <input name="product_code" class="form-control" required
                            placeholder="Enter unique product code">

                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label>Generic Name</label>
                        <input name="gen_name" class="form-control" required

                            placeholder="Enter generic/common name">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input name="product_name" class="form-control" required
                    placeholder="Enter Description">

            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input name="qty" type="number" class="form-control"
                            required min="1" value="1">
                        <div class="help-text">Number of units being delivered</div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label>Cost / Price</label>
                        <input name="price" class="form-control" type="number" step="0.01"
                            placeholder="0.00">
                        <div class="help-text">Cost per unit (optional)</div>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input name="expiry_date" type="date" class="form-control">
                        <div class="help-text">Product expiration date (if applicable)</div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn-submit">
                    <i class="icon-upload"></i> Submit Delivery
                </button>
            </div>
        </form>

    </div>
    <?php include('footer.php'); ?>
</body>

</html>