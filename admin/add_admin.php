<?php
// Start the session to handle feedback messages
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

// Initialize an empty message variable for feedback
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input
    $employee_no = $_POST['employee_no'];
    $admin_names = $_POST['admin_names'];
    $admin_password = $_POST['admin_password'];
    $admin_cpassword = $_POST['admin_cpassword'];

    // Validate password confirmation
    if ($admin_password != $admin_cpassword) {
        $message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = md5($admin_password);

        // Insert the new admin into the database
        $query = "INSERT INTO admins (employee_no, employee_names, password) 
                  VALUES ('$employee_no', '$admin_names', '$hashed_password')";

        if (mysqli_query($conn, $query)) {
            $message = "New admin added successfully.";
        } else {
            $message = "Error adding admin: " . mysqli_error($conn);
        }
    }
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
    <link rel="stylesheet" href="css/add_admin.css">
</head>

<body>
    <nav class="navbar" id="sticky-navbar">
        <div class="container-fluid">
            <button class="btn btn-link text-white" id="sidebarToggle"><i class="bx bx-menu bx-lg"></i></button>
            <span class="navbar-brand text-light"></span>
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

    <div class="container main-container">
        <h1>Add New Admin</h1>

        <!-- Feedback Message -->
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="adminName" class="form-label">Admin Names</label>
                <input type="text" class="form-control" id="adminNames" name="admin_names" placeholder="Enter Admin Names" required>
            </div>
            <div class="mb-3">
                <label for="adminEmail" class="form-label">Employee No</label>
                <input type="text" class="form-control" id="employeeNo" name="employee_no" placeholder="Enter Employee Number" required>
            </div>
            <div class="mb-3">
                <label for="adminPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="adminPassword" name="admin_password" placeholder="Enter Password" required>
            </div>
            <div class="mb-3">
                <label for="adminCPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="adminCPassword" name="admin_cpassword" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Admin</button>
        </form>
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