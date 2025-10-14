<?php
include('../connect.php');
require_once('auth.php');

// Get data from database
$sql = "SELECT r.*, p.product_name 
        FROM supplier_returns r 
        JOIN products p ON r.product_id = p.product_id
        ORDER BY r.return_date DESC";
$result = $db->prepare($sql);
$result->execute();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Supplier Returns Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 0.9em;
            color: #666;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right; margin: 10px;">
        <button onclick="window.print()" style="margin-left: 5px;">Print PDF</button>
        <button onclick="window.location.href='returns.php'" style="margin-left: 5px;">Back to Returns</button>
    </div>
    <div class="print-header">
        <h1>Supplier Returns Report</h1>
    </div>
    <div class="print-date">
        Generated on: <?php echo date('Y-m-d H:i:s'); ?>
    </div>
    <table>

        <tbody>


            <!DOCTYPE html>
            <html>

            <head>
                <title>Supplier Returns Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }

                    th,
                    td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }

                    th {
                        background-color: #f5f5f5;
                    }

                    h1 {
                        text-align: center;
                        color: #333;
                    }

                    .print-header {
                        text-align: center;
                        margin-bottom: 20px;
                    }

                    .print-date {
                        text-align: right;
                        margin-bottom: 20px;
                        font-size: 0.9em;
                        color: #666;
                    }

                    @media print {
                        .no-print {
                            display: none;
                        }

                        body {
                            margin: 0;
                            padding: 15px;
                        }
                    }
                </style>

            </head>

            <body>
                <div class="report-header">
                    <h2>Supplier Returns Report</h2>
                    <p>Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Return Date</th>
                            <th>Product Name</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_quantity = 0;
                        while ($row = $result->fetch()):
                            $total_quantity += $row['quantity'];
                        ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i', strtotime($row['return_date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                                <td><?php echo number_format($row['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right">Total Quantity:</td>
                            <td><?php echo number_format($total_quantity); ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print()">Print Report</button>
                    <button onclick="window.close()">Close</button>
                </div>
            </body>

            </html>



            <?php while ($row = $result->fetch()): ?>
                <tr>
                    <td><?php echo date('Y-m-d H:i', strtotime($row['return_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo htmlspecialchars($row['reason']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">
                    <strong>Total Returns: <?php echo $result->rowCount(); ?></strong>
                </td>
            </tr>
        </tfoot>
    </table>
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            // Give a small delay to ensure everything is loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>