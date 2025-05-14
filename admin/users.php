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


// Delete a student
if (isset($_GET['deleteStudent'])) {
    $studentID = intval($_GET['deleteStudent']);
    $deleteStudentQuery = "DELETE FROM students WHERE student_id = $studentID";
    if (mysqli_query($conn, $deleteStudentQuery)) {
        echo "<script>window.location.href='users.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="css/main_dishes.css">
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

    <div class="container main-container mt-4 table-responsive">
        <h1>Users</h1>
        <button class="add-meal-btn">Add New User <i class="bx bx-plus"></i></button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Names</th>
                    <th>Student No</th>
                    <th>Ip Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_users = "SELECT * FROM students";
                $result = mysqli_query($conn, $get_users);

                if (mysqli_num_rows($result) >= 1) {
                    $row_count = mysqli_num_rows($result);
                    $counter = 1;
                    while ($row_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . htmlspecialchars($row_data['student_names']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_data['student_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_data['student_ip']) . "</td>";
                        echo "<td>
                    <a href='users.php?deleteStudent=" . $row_data['student_id'] . "' class='bx bx-trash action-icons text-danger'></a>
                    </td>";
                        echo "</tr>";
                        $counter++;
                    }
                }
                ?>

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

        window.addEventListener('beforeunload', function() {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Restore scroll position
        window.addEventListener('load', function() {
            if (localStorage.getItem('scrollPosition') !== null) {
                window.scrollTo(0, localStorage.getItem('scrollPosition'));
            }
        });
    </script>
</body>

</html>