<?php
include('../connect.php');
session_start();
if (!isset($_SESSION['SESS_MEMBER_ID'])) {
    header('location: ../index.php');
    exit();
}

// Check if user has admin privileges
$user_id = $_SESSION['SESS_MEMBER_ID'];
$query = $db->prepare("SELECT position FROM user WHERE id = ?");
$query->execute(array($user_id));
$user = $query->fetch();

if ($user['position'] !== 'admin') {
    header('location: index.php');
    exit();
}

function createRandomPassword()
{
    $chars = "003232303232023232023456789";
    srand((float) microtime() * 1000000);
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

// Handle form submission for adding new user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $suplier_address = $_POST['suplier_address'] ?? '';
    $suplier_contact = $_POST['suplier_contact'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $note = $_POST['note'] ?? '';

    // Check if username already exists
    $check = $db->prepare("SELECT id FROM user WHERE username = ?");
    $check->execute(array($username));
    if ($check->rowCount() > 0) {
        $error = "Username already exists!";
    } else {
        try {
            $db->beginTransaction();

            // Insert user
            $insert = $db->prepare("INSERT INTO user (username, password, name, position) VALUES (?, ?, ?, ?)");
            $insert->execute(array($username, $password, $name, $position));

            // If position is supplier, also create supplier record
            if ($position === 'supplier') {
                $insert_supplier = $db->prepare("INSERT INTO supliers (suplier_name, suplier_address, suplier_contact, contact_person, note) VALUES (?, ?, ?, ?, ?)");
                $insert_supplier->execute(array($name, $suplier_address, $suplier_contact, $contact_person, $note));
            }

            $db->commit();
            $success = "User added successfully!" . ($position === 'supplier' ? " Supplier record also created." : "");
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Error creating user: " . $e->getMessage();
        }
    }
} // Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    // Prevent deleting your own account
    if ($id != $user_id) {
        try {
            $db->beginTransaction();

            // Get user details first
            $get_user = $db->prepare("SELECT name, position FROM user WHERE id = ?");
            $get_user->execute(array($id));
            $user_to_delete = $get_user->fetch();

            // If user is supplier, delete supplier record too
            if ($user_to_delete && $user_to_delete['position'] === 'supplier') {
                $delete_supplier = $db->prepare("DELETE FROM supliers WHERE suplier_name = ?");
                $delete_supplier->execute(array($user_to_delete['name']));
            }

            // Delete user
            $delete = $db->prepare("DELETE FROM user WHERE id = ?");
            $delete->execute(array($id));

            $db->commit();
            $success = "User deleted successfully!";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Error deleting user: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Roles Management</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>
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

        #supplier_fields {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        #supplier_fields table {
            width: 100%;
        }

        #supplier_fields input,
        #supplier_fields textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        #supplier_fields h4 {
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        #supplier_fields .table {
            margin-bottom: 0;
            background-color: white;
        }

        #supplier_fields .table td {
            padding: 10px;
            vertical-align: middle;
        }

        #supplier_fields input,
        #supplier_fields textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease-in-out;
        }

        #supplier_fields input:focus,
        #supplier_fields textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        #supplier_fields textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-control::placeholder {
            color: #999;
            font-style: italic;
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
</head>

<body>
    <?php include('navfixed.php'); ?>
    <?php
    $position = $_SESSION['SESS_LAST_NAME'];
    if ($position == 'cashier') {
        echo '<a href="../index.php">Logout</a>';
    }
    if ($position == 'admin') {
    ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                            <!-- <li><a href="sales.php?id=cash&invoice=?php echo $finalcode ?>"><i class="icon-shopping-cart icon-2x"></i> Sales</a></li> -->
                            <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                            <li><a href="returns.php"><i class="icon-share icon-2x"></i> Returns</a></li>
                            <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                            <li><a href="supplier_deliveries.php"><i class="icon-truck icon-2x"></i> Supplier Deliveries</a></li>
                            <li class="active"><a href="user_roles.php"><i class="icon-user icon-2x"></i> User Roles</a></li>
                            <li><a href="sales_inventory.php"><i class="icon-table icon-2x"></i> Product Inventory</a></li>
                        </ul>
                    </div>
                </div>
                <div class="span10">
                    <div class="contentheader">
                        <i class="icon-group"></i> User Management
                    </div>
                    <br />

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <!-- Add New User Form -->
                    <form action="" method="post" class="addform">
                        <h3>Add New User</h3>
                        <table>
                            <tr>
                                <td>Username:</td>
                                <td><input type="text" name="username" required /></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input type="password" name="password" required /></td>
                            </tr>
                            <tr>
                                <td>Full Name:</td>
                                <td><input type="text" name="name" required /></td>
                            </tr>
                            <tr>
                                <td>Role:</td>
                                <td>
                                    <select name="position" id="position" required>
                                        <option value="admin">Admin</option>
                                        <option value="cashier">Cashier</option>
                                        <option value="supplier">Supplier</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="supplier_fields" style="display:none;">
                                        <h4>Supplier Details</h4>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td width="30%"><strong>Address:</strong></td>
                                                <td><input type="text" name="suplier_address" class="form-control" placeholder="Complete Address" /></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contact No:</strong></td>
                                                <td><input type="text" name="suplier_contact" class="form-control" placeholder="Phone/Mobile Number" /></td>
                                            </tr>

                                            <tr>
                                                <td><strong>Additional Notes:</strong></td>
                                                <td><textarea name="note" rows="3" class="form-control" placeholder="Any additional information"></textarea></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" name="add_user" value="Add User" class="btn btn-primary" />
                                </td>
                            </tr>
                        </table>
                    </form>

                    <script>
                        // Show/hide supplier fields based on position selection
                        document.addEventListener('DOMContentLoaded', function() {
                            var positionSelect = document.getElementById('position');
                            var supplierFields = document.getElementById('supplier_fields');

                            function toggleSupplierFields() {
                                if (positionSelect.value === 'supplier') {
                                    // Show supplier fields with animation
                                    supplierFields.style.display = 'block';
                                    supplierFields.style.opacity = '0';
                                    setTimeout(() => {
                                        supplierFields.style.transition = 'opacity 0.3s ease-in-out';
                                        supplierFields.style.opacity = '1';
                                    }, 50);

                                    // Make supplier fields required
                                    document.querySelectorAll('#supplier_fields input, #supplier_fields textarea').forEach(function(input) {
                                        input.required = true;
                                        if (input.tagName.toLowerCase() === 'input') {
                                            // Add placeholder text if not already present
                                            if (!input.placeholder) {
                                                input.placeholder = 'Enter ' + input.name.replace('suplier_', '').replace('_', ' ');
                                            }
                                        }
                                    });
                                } else {
                                    // Hide supplier fields with animation
                                    supplierFields.style.opacity = '0';
                                    setTimeout(() => {
                                        supplierFields.style.display = 'none';
                                    }, 300);

                                    // Remove required attribute and clear fields
                                    document.querySelectorAll('#supplier_fields input, #supplier_fields textarea').forEach(function(input) {
                                        input.required = false;
                                        input.value = '';
                                    });
                                }
                            }

                            // Event listeners
                            positionSelect.addEventListener('change', toggleSupplierFields);

                            // Form validation
                            document.querySelector('form.addform').addEventListener('submit', function(e) {
                                if (positionSelect.value === 'supplier') {
                                    let isValid = true;
                                    document.querySelectorAll('#supplier_fields input, #supplier_fields textarea').forEach(function(input) {
                                        if (!input.value.trim()) {
                                            isValid = false;
                                            input.style.borderColor = '#ff0000';
                                        } else {
                                            input.style.borderColor = '#ccc';
                                        }
                                    });
                                    if (!isValid) {
                                        e.preventDefault();
                                        alert('Please fill in all supplier details');
                                    }
                                }
                            });

                            // Initialize fields
                            toggleSupplierFields();
                        });
                    </script> <!-- User List -->
                    <div class="user-list">
                        <h3>Current Users</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = $db->query("SELECT * FROM user ORDER BY position, name");
                                while ($row = $users->fetch()):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['position'])); ?></td>
                                        <td>
                                            <?php if ($row['id'] != $user_id): ?>
                                                <a href="?delete=<?php echo $row['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    class="btn btn-danger btn-sm">Delete</a>
                                            <?php else: ?>
                                                <span class="text-muted">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>
<?php include('footer.php'); ?>

</html>
<?php } // end admin sidebar/content 
?>
<?php include('footer.php'); ?>
</body>

</html>