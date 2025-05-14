<?php
session_start();
include('../includes/config.php'); // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

$employeeNoAuth = $_SESSION['employee_no'];
$employeeauth = "SELECT * FROM admins WHERE employee_no = '$employeeNoAuth'";
$resultEmpAuth = mysqli_query($conn, $employeeauth);

$adminName = "";
if (mysqli_num_rows($resultEmpAuth) === 1) {
    $row = mysqli_fetch_assoc($resultEmpAuth);
    $adminName = $row['employee_names'];
}

// Fetch orders data
$ordersQuery = "SELECT * FROM orders"; // Adjust this based on your database structure
$ordersResult = mysqli_query($conn, $ordersQuery);



// Update order status if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_status'], $_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];
    $updateOrderQuery = "UPDATE orders SET order_status = '$new_status' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $updateOrderQuery)) {
        echo "<script>alert('Order status updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update order status.');</script>";
    }
}

// Fetch orders data
$ordersQuery = "SELECT * FROM orders"; // Adjust this based on your database structure
$ordersResult = mysqli_query($conn, $ordersQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <link rel="stylesheet" href="css/main_dishes.css">
</head>

<body>
    <nav class="navbar" id="sticky-navbar">
        <div class="container-fluid">
            <button class="btn btn-link text-white" id="sidebarToggle"><i class="bx bx-menu bx-lg"></i></button>
            <div class="popover-container m-2">
                <button class="btn btn-link text-white" id="profilePopover"
                    data-bs-toggle="popover"
                    data-bs-html="true"
                    data-bs-content='<a href="admin_logout.php" class="text-decoration-none text-dark"><i class="bx bx-power-off"></i>Logout</a>'>
                    <?php echo strtok($adminName, ' '); ?><i class='bx bxs-down-arrow' style="font-size: 10px;"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <button class="btn btn-link text-white sidebar-close-button" id="sidebarClose"><i class="bx bx-x"></i></button>
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>
        <ul class="sidebar-links">
            <a class="text-decoration-none text-light" href="index.php">
                <li><img src="assets/icons/dashboard.png" width="30px" height="30px"> Dashboard</li>
            </a>
            <a class="text-decoration-none text-light" href="main_dishes.php">
                <li><img src="assets/icons/main_dish.png" width="30px" height="30px"> Main Dishes</li>
            </a>
            <a class="text-decoration-none text-light" href="snacks.php">
                <li><img src="assets/icons/snacks.png" width="30px" height="30px"> Snacks</li>
            </a>
            <a class="text-decoration-none text-light" href="beverages.php">
                <li><img src="assets/icons/beverages.png" width="30px" height="30px"> Beverages</li>
            </a>
            <a class="text-decoration-none text-light" href="groceries.php">
                <li><img src="assets/icons/groceries.png" width="30px" height="30px"> Groceries</li>
            </a>
            <a class="text-decoration-none text-light" href="orders.php">
                <li><img src="assets/icons/orders.png" width="30px" height="30px"> Orders</li>
            </a>
            <a class="text-decoration-none text-light" href="users.php">
                <li><img src="assets/icons/users.png" width="30px" height="30px"> Users</li>
            </a>
            <a class="text-decoration-none text-light" href="contacts.php">
                <li><img src="assets/icons/contacts.png" width="30px" height="30px"> Contacts</li>
            </a>
            <a class="text-decoration-none text-light" href="add_admin.php">
                <li><img src="assets/icons/admin.png" width="30px" height="30px"> Add admin</li>
            </a>
        </ul>
    </div>

    <div class="container main-container mt-4 table-responsive">
        <h1>Orders</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student No</th>
                    <th>Invoice No</th>
                    <th>Amount</th>
                    <th>Order List</th>
                    <th>Order Status</th>
                    <th>Update Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($ordersResult) > 0): ?>
                    <?php $counter = 1; ?>
                    <?php while ($order = mysqli_fetch_assoc($ordersResult)): ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td>
                                <?php
                                $fetchStNO = "SELECT * FROM students WHERE student_id = '{$order['user_id']}'";
                                $nameResult = mysqli_query($conn, $fetchStNO);
                                $name = mysqli_fetch_assoc($nameResult);
                                echo $name['student_no'];
                                ?>
                            </td>
                            <td><?php echo $order['invoice_number']; ?></td>
                            <td><?php echo $order['total_amount']; ?></td>
                            <td>
                                <ul>
                                    <?php
                                    // Decode JSON order list to an associative array
                                    $order_list = json_decode($order['order_list'], true);

                                    // Check if $order_list is an array and iterate over each item
                                    if (is_array($order_list)) {
                                        foreach ($order_list as $item) {
                                            // Access 'meal_title' and 'quantity' fields from each item
                                            if (isset($item['meal_title'], $item['quantity'])) {
                                                echo "<li>" . htmlspecialchars($item['meal_title']) . " - " . htmlspecialchars($item['quantity']) . "</li>";
                                            } else {
                                                echo "<li>Invalid item format</li>";
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>



                            <td><?php echo $order['order_status']; ?></td>
                            <td>
                                <form method="POST">
                                    <select name="order_status" onchange="this.form.submit()">
                                        <option value="Pending" <?php if ($order['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Completed" <?php if ($order['order_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                </form>

                            </td>
                            <td>
                                <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        &copy; 2024 Zetech University Meal Card System. All rights reserved.
    </footer>

    <script src="js/script.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Store and restore scroll position
        window.addEventListener('beforeunload', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });
        window.addEventListener('load', function() {
            if (localStorage.getItem('scrollPosition') !== null) {
                window.scrollTo(0, parseInt(localStorage.getItem('scrollPosition')));
            }
        });
    </script>
</body>

</html>