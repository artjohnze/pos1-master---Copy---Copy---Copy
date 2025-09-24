<html>

<head>
    <title>
        POS
    </title>

    <?php
    require_once('auth.php');
    ?>
    <link href="css/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">

    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <!--sa poip up-->
    <script src="jeffartagame.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/application.js" type="text/javascript" charset="utf-8"></script>
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox({
                loadingImage: 'src/loading.gif',
                closeImage: 'src/closelabel.png'
            })
        })
    </script>
</head>
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
$finalcode = '' . createRandomPassword();
?>

<script>
    function sum() {
        var txtFirstNumberValue = document.getElementById('txt1').value;
        var txtSecondNumberValue = document.getElementById('txt2').value;
        var result = parseInt(txtFirstNumberValue) - parseInt(txtSecondNumberValue);
        if (!isNaN(result)) {
            document.getElementById('txt3').value = result;

        }

        var txtFirstNumberValue = document.getElementById('txt11').value;
        var txtSecondNumberValue = document.getElementById('txt22').value;
        var result = parseInt(txtFirstNumberValue) + parseInt(txtSecondNumberValue);
        if (!isNaN(result)) {
            document.getElementById('txt44').value = result;
        }

        var txtFirstNumberValue = document.getElementById('txt11').value;
        var txtSecondNumberValue = document.getElementById('txt33').value;
        var result = parseInt(txtFirstNumberValue) + parseInt(txtSecondNumberValue);
        if (!isNaN(result)) {
            document.getElementById('txt55').value = result;

        }

        var txtFirstNumberValue = document.getElementById('txt4').value;
        var result = parseInt(txtFirstNumberValue);
        if (!isNaN(result)) {
            document.getElementById('txt5').value = result;
        }

    }
</script>


<script language="javascript" type="text/javascript">
    /* Visit http://www.yaldex.com/ for full source code
and get more free JavaScript, CSS and DHTML scripts! */
    <!-- Begin
    var timerID = null;
    var timerRunning = false;

    function stopclock() {
        if (timerRunning)
            clearTimeout(timerID);
        timerRunning = false;
    }

    function showtime() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds()
        var timeValue = "" + ((hours > 12) ? hours - 12 : hours)
        if (timeValue == "0") timeValue = 12;
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        document.clock.face.value = timeValue;
        timerID = setTimeout("showtime()", 1000);
        timerRunning = true;
    }

    function startclock() {
        stopclock();
        showtime();
    }
    window.onload = startclock;
    // End -->
</SCRIPT>

<body>
    <?php include('navfixed.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <li><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i
                                    class="icon-shopping-cart icon-2x"></i> Sales</a> </li>
                        <li class="active"><a href="products.php"><i class="icon-table icon-2x"></i> Products</a> </li>
                        <!--                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a> </li>-->
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a> </li>
                        <!-- <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a> -->
                        </li>
                        <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a>

                            <br><br><br><br><br><br>
                        <li>
                            <div class="hero-unit-clock">


                            </div>
                        </li>

                    </ul>
                </div>
                <!--/.well -->
            </div>
            <!--/span-->
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-table"></i> Products
                </div>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li> /
                    <li class="active">Products</li>
                </ul>


                <div style="margin-top: -19px; margin-bottom: 21px;">
                    <a href="index.php"><button class="btn btn-default btn-large" style="float: left;"><i
                                class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
                    <?php
                    include('../connect.php');
                    $result = $db->prepare("SELECT * FROM products ORDER BY product_id DESC");
                    $result->execute();
                    $rowcount = $result->rowcount();
                    ?>
                    <div style="text-align:center;">
                        Total Number of Products: <font color="green" style="font:bold 22px 'Aleo';">
                            <?php echo $rowcount; ?></font>
                    </div>
                </div>
                <br>
                <?php
                // Pagination settings
                $records_per_page = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $records_per_page;

                // Get total number of records
                $total_result = $db->prepare("SELECT COUNT(*) FROM products");
                $total_result->execute();
                $total_records = $total_result->fetchColumn();
                $total_pages = ceil($total_records / $records_per_page);
                ?>

                <input type="text" style="padding:15px;" name="filter" value="" id="filter"
                    placeholder="Search Product..." autocomplete="off" />
                <a rel="facebox" href="addproduct.php"><Button type="submit" class="btn btn-info"
                        style="float:right; width:230px; height:35px;" /><i class="icon-plus-sign icon-large"></i> Add
                    Product</button></a><br><br>
                <table class="table table-bordered" id="resultTable" data-responsive="table" style="text-align: left;">
                    <thead>
                        <tr>
                            <th> Brand Name </th>
                            <th> Generic Name </th>
                            <th> Description </th>
                            <th> Expiry Date </th>
                            <th> Original Price </th>
                            <th> Selling Price </th>
                            <th> Supplier </th>
                            <th width="7%"> Quantity </th>
                            <th width="5%"> Sold </th>
                            <th width="13%"> Action </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        function formatMoney($number, $fractional = false)
                        {
                            if ($fractional) {
                                $number = sprintf('%.2f', $number);
                            }
                            while (true) {
                                $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
                                if ($replaced != $number) {
                                    $number = $replaced;
                                } else {
                                    break;
                                }
                            }
                            return $number;
                        }
                        include('../connect.php');
                        $result = $db->prepare("SELECT * FROM products ORDER BY product_id DESC LIMIT :limit OFFSET :offset");
                        $result->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
                        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $result->execute();
                        for ($i = 0; $row = $result->fetch(); $i++) {
                        ?>
                            <tr class="record">
                                <td><?php echo $row['product_code']; ?></td>
                                <td><?php echo $row['gen_name']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['expiry_date']; ?></td>
                                <td><?php
                                    $oprice = $row['o_price'];
                                    echo formatMoney($oprice, true);
                                    ?></td>
                                <td><?php
                                    $pprice = $row['price'];
                                    echo formatMoney($pprice, true);
                                    ?></td>
                                <td><?php echo $row['supplier']; ?></td>
                                <td><?php echo $row['qty']; ?></td>
                                <td><?php echo $row['qty_sold']; ?></td>
                                <td><a rel="facebox" title="Click to edit the product"
                                        href="editproduct.php?id=<?php echo $row['product_id']; ?>"><button
                                            class="btn btn-mini btn-warning"><i></i>Update</button> </a> <a
                                        href="index.php" title="Back to Dashboard"><button class="btn btn-mini btn-primary"><i></i> Return</button></a></td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>

                <!-- Pagination Controls -->
                <div class="pagination-wrapper" style="text-align: center; margin-top: 20px;">
                    <div class="pagination">
                        <ul>
                            <?php if ($page > 1): ?>
                                <li><a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a></li>
                            <?php endif; ?>

                            <?php
                            // Show page numbers
                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $page) {
                                    echo '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                            ?>

                            <?php if ($page < $total_pages): ?>
                                <li><a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div style="margin-top: 10px; color: #666;">
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $records_per_page, $total_records); ?>
                        of <?php echo $total_records; ?> products (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
</body>
<?php include('footer.php'); ?>

</html>