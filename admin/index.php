<?php
session_start();
require_once '../includes/config.php';

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <nav class="navbar" id="sticky-navbar">
        <div class="container-fluid">
            <button class="btn btn-link text-white" id="sidebarToggle"><i class="bx bx-menu bx-lg"></i></button>
            <span class="navbar-brand text-light">Dashboard</span>
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/side_dish.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Meals and Others: <span class="text-primary">
                                <?php
                                $sql = "SELECT * from menu";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>

                            </span></h5>
                        <a href="index.php" class="btn btn-primary">View Dashboard</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/main_dish.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Main Dishes: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from menu where meal_category = 'main'";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="main_dishes.php" class="btn btn-primary">View Main Dishes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/snacks.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Snacks: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from menu where meal_category = 'snack'";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="snacks.php" class="btn btn-primary">View Snacks</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/beverages.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Beverages: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from menu where meal_category = 'beverage'";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="beverages.php" class="btn btn-primary">View Beverages</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/groceries.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Groceries: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from menu where meal_category = 'grocery'";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="groceries.php" class="btn btn-primary">View groceries</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/email.png" alt="Main Dish Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Total Enquiries: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from contacts";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="enquiries.php" class="btn btn-primary">View Enquiries</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/orders.png" alt="Orders Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Pending Orders: <span class="text-primary">
                                <?php

                                $sql = "SELECT * from orders where order_status = 'Pending'";
                                $result = $conn->query($sql);
                                $count = 0;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        $count = $count + 1;
                                    }
                                }
                                echo "$count";
                                ?>
                            </span></h5>
                        <a href="orders.php" class="btn btn-primary">View Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="icon-wrapper">
                            <img src="assets/icons/orders.png" alt="Orders Icon" class="category-icon" width="60px" height="60px"
                                style="object-fit: cover;
                            border-radius: 50%;
                            background-color: var(--color-soft-peach);
                            padding: var(--spacing-small);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </div>
                        <h5 class="card-title">Completed Orders: <span class="text-primary"><?php

                                                                                            $sql = "SELECT * from menu where meal_category = 'grocery'";
                                                                                            $result = $conn->query($sql);
                                                                                            $count = 0;
                                                                                            if ($result->num_rows > 0) {
                                                                                                while ($row = $result->fetch_assoc()) {

                                                                                                    $count = $count + 1;
                                                                                                }
                                                                                            }
                                                                                            echo "$count";
                                                                                            ?></span></h5>
                        <a href="orders.php" class="btn btn-primary">View Orders</a>
                    </div>
                </div>
            </div>
        </div>
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
    </script>
</body>

</html>