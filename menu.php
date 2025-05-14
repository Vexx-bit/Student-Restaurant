<?php
session_start();
// Include your database connection
include 'includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['student_no'])) {
    header("Location: login.php");
    exit();
}

$student_no = $_SESSION['student_no'];

// Fetch student details
$studentDetails = "SELECT * FROM students WHERE student_no = '$student_no'";
$studentData = mysqli_query($conn, $studentDetails);

$studentIP = "";
$studentID = "";
if (mysqli_num_rows($studentData) === 1) {
    $row = mysqli_fetch_assoc($studentData);
    $studentIP = $row['student_ip'];
    $studentID = $row['student_id'];
}


// Query for main dishes, snacks, and beverages
$menuItems = $conn->query("SELECT meal_id AS id, meal_title AS title, meal_price AS price, meal_img AS img FROM menu ORDER BY rand()");

if (isset($_GET['add_to_cart'])) {
    $get_meal_id = (int)$_GET['add_to_cart'];

    // Check if the item is already in the cart
    $select_query = "SELECT * FROM `cart` WHERE ip_address='$studentIP' AND meal_id = $get_meal_id AND student_id = $studentID";
    $result_query = mysqli_query($conn, $select_query);

    if (mysqli_num_rows($result_query) > 0) {
        echo "<script>alert('This item is already in your cart');</script>";
    } else {
        $insert_query = "INSERT INTO `cart` (meal_id, ip_address, quantity, student_id) VALUES ('$get_meal_id', '$studentIP', 1, $studentID)";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Item added to your cart');</script>";
        } else {
            die("Error in insert query: " . mysqli_error($conn));
        }
    }
    echo "<script>window.open('menu.php', '_self');</script>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/menu.css">
</head>

<body>
    <header class="header-menu">
        <nav class="nav-menu">
            <img src="assets/images/logo_name.png" class="nav-link" height="40rem" style="margin-right: auto;">
            <a href="logout.php" class="nav-link">Logout</a>
            <a href="menu.php" class="nav-link active-link">Menu</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="cart.php" class="nav-link"><i class='bx bxs-cart-alt'></i><sup>
                    <?php
                    $sql = "SELECT * from `cart`";
                    $result = $conn->query($sql);
                    $count = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $count = $count + 1;
                        }
                    }
                    echo "$count";
                    ?>
                </sup></a>
            <a class="nav-link" href="profile.php" style="margin-left: auto;"><i class='bx bxs-user-circle' style="font-size: 2.5rem;color:var(--color-soft-coral)"></i></a>
        </nav>
    </header>

    <main class="menu-container">
        <div class="search-container">
            <form action="search.php" method="GET">
                <input type="text" name="search_term" placeholder="Search meals..." class="search-input" required>
                <button type="submit" name="search" class="search-button">Search</button>
            </form>
        </div>
        <div class="filter-container">
            <h2 class="filter-title">Filter Menu</h2>
            <div class="filter-buttons">
                <button class="filter-button active" data-category="all"><a href="menu.php" style="text-decoration: none;color: inherit;">All</a></button>
                <button class="filter-button" data-category="main-dishes"><a href="main_dishes.php" style="text-decoration: none;color: inherit;">Main Dishes</a></button>
                <button class="filter-button" data-category="snacks"><a href="snacks.php" style="text-decoration: none;color: inherit;">Snacks</a></button>
                <button class="filter-button" data-category="beverages"><a href="beverages.php" style="text-decoration: none;color: inherit;">Beverages</a></button>
                <button class="filter-button" data-category="groceries"><a href="groceries.php" style="text-decoration: none;color: inherit;">Groceries</a></button>
            </div>
        </div>
        <h1 class="menu-title">All Meals</h1>
        <div class="grid-container">
            <?php while ($item = $menuItems->fetch_assoc()): ?>
                <div class="menu-card">
                    <a href="menu.php?add_to_cart=<?php echo htmlspecialchars($item['id']); ?>" style="text-decoration: none;">
                        <div class="add-to-cart">
                            <span class="plus-sign">+</span>
                        </div>
                    </a>
                    <div class="menu-image-container">
                        <img src="admin/uploads/images/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="menu-image">
                        <div class="menu-item-info">
                            <h2 class="menu-item-title"><?php echo htmlspecialchars($item['title']); ?></h2>
                            <p class="menu-item-price">Kshs <?php echo htmlspecialchars($item['price']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="footer-menu">
        <p>&copy; 2024 Zetech University Meal Card System. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                container: 'body'
            });
        });
    </script>
</body>

</html>