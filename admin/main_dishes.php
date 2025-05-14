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

// Insert a new meal
if (isset($_POST['insertMeal'])) {
    $mealTitle = $_POST['mealTitle'];
    $mealPrice = $_POST['mealPrice'];
    $mealCategory = $_POST['mealCategory']; // New hidden input

    // Handle image upload
    $targetDir = "uploads/images/";
    $mealImage = $_FILES['mealImage']['name'];
    $targetFilePath = $targetDir . $mealImage;
    move_uploaded_file($_FILES['mealImage']['tmp_name'], $targetFilePath);

    // Escape values to prevent SQL injection (using simple methods)
    $mealTitle = mysqli_real_escape_string($conn, $mealTitle);
    $mealPrice = floatval($mealPrice); // Convert to float
    $mealImage = mysqli_real_escape_string($conn, $mealImage);

    // Insert the meal into the database
    $insertMealQuery = "INSERT INTO menu (meal_title, meal_price, meal_img, meal_category) VALUES ('$mealTitle', '$mealPrice', '$mealImage', '$mealCategory')";
    if (mysqli_query($conn, $insertMealQuery)) {
        echo "<script>alert('New meal added successfully!')</script>";
        echo "<script>window.location.href='main_dishes.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete a meal
if (isset($_GET['deleteMeal'])) {
    $mealID = intval($_GET['deleteMeal']);
    $deleteMealQuery = "DELETE FROM menu WHERE meal_id = $mealID";
    if (mysqli_query($conn, $deleteMealQuery)) {
        echo "<script>alert('Meal deleted successfully!');</script>";
        echo "<script>window.location.href='main_dishes.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting meal: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Dishes</title>
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
        <h1>Main Dishes</h1>
        <button class="add-meal-btn btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addMealModal">Add a New Meal <i class="bx bx-plus"></i></button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Meal Title</th>
                    <th>Meal Image</th>
                    <th>Meal Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize a counter
                $counter = 1; // Start counting from 1
                $fetchMealsQuery = "SELECT * FROM menu WHERE meal_category = 'main'";
                $mealsResult = mysqli_query($conn, $fetchMealsQuery);
                while ($row = mysqli_fetch_assoc($mealsResult)) {
                    echo '<tr>
                            <td>' . $counter++ . '</td>
                            <td>' . htmlspecialchars($row['meal_title']) . '</td>
                            <td><img src="uploads/images/' . htmlspecialchars($row['meal_img']) . '" alt="' . htmlspecialchars($row['meal_title']) . '" width="80"></td>
                            <td>' . htmlspecialchars($row['meal_price']) . 'KES</td>
                            <td>
                                <a href="edit_main_dish.php?id=' . $row['meal_id'] . '" class="bx bx-edit action-icons"></a>
                                <a href="main_dishes.php?deleteMeal=' . $row['meal_id'] . '" class="bx bx-trash action-icons text-danger" onclick="return confirm(\'Are you sure you want to delete this beverage?\')"></a>
                            </td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->

    <!-- Add Meal Modal -->
    <div class="modal fade" id="addMealModal" tabindex="-1" aria-labelledby="addMealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--color-navy-blue);color: var(--color-text-white);">
                    <h5 class="modal-title" id="addMealModalLabel">Add New Meal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="mealCategory" value="main"> <!-- Hidden input for meal category -->
                        <div class="mb-3">
                            <label for="mealTitle" class="form-label">Meal Title(Qty)</label>
                            <input type="text" class="form-control" id="mealTitle" name="mealTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="mealPrice" class="form-label">Meal Price(KES)</label>
                            <input type="number" class="form-control" id="mealPrice" name="mealPrice" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="mealImage" class="form-label">Meal Image</label>
                            <input type="file" class="form-control" id="mealImage" name="mealImage" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="insertMeal">Add Meal</button>
                    </form>
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