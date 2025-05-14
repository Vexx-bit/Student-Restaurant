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
$row = [];
if (mysqli_num_rows($studentData) === 1) {
    $row = mysqli_fetch_assoc($studentData);
    $studentIP = $row['student_ip'];
    $studentID = $row['student_id'];
}

// Fetch orders for the logged-in user
$ordersQuery = "SELECT * FROM orders WHERE user_id = '$studentID'";
$ordersResult = mysqli_query($conn, $ordersQuery);

if (isset($_POST['updateProfile'])) {
    // Check if a profile picture already exists
    $profileImage = empty($row['student_pfp']) ? '' : $row['student_pfp'];

    // Handle image upload if a new image is provided
    if (!empty($_FILES['student_pfp']['name'])) {
        $targetDir = "uploads/images/";
        $profileImage = $_FILES['student_pfp']['name'];
        $targetFilePath = $targetDir . basename($profileImage);
        move_uploaded_file($_FILES['student_pfp']['tmp_name'], $targetFilePath);
    }

    // Escape values to prevent SQL injection
    $profileImage = mysqli_real_escape_string($conn, $profileImage);

    // Insert or update the profile image based on the existing image status
    if (empty($row['student_pfp'])) {
        // Insert if the profile image is empty
        $updateProfileQuery = "UPDATE students SET user_pfp='$profileImage' WHERE student_id = $studentID";
    } else {
        // Update if a profile image already exists
        $updateProfileQuery = "UPDATE students SET user_pfp='$profileImage' WHERE student_id = $studentID";
    }

    if (mysqli_query($conn, $updateProfileQuery)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
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
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <header class="profile-header">
        <a href="menu.php" class="back-link">
            <i class='bx bx-arrow-back'></i>
            <span class="back-text">Menu</span>
        </a>
        <h1 class="profile-heading">User Profile</h1>
    </header>

    <div class="profile-container">
        <section class="profile-info">
            <div class="profile-image">
                <img src="<?php
                            // Display a default image if no profile picture is set
                            echo empty($row['user_pfp']) ? 'assets/images/user (1).png' : 'uploads/images/' . htmlspecialchars($row['user_pfp']);
                            ?>" alt="User Profile Picture" class="profile-avatar">
            </div>

            <div class="profile-details">
                <h2 class="profile-name"><?php echo htmlspecialchars($row['student_names']); ?></h2>
                <p class="student-number"><?php echo htmlspecialchars($row['student_no']); ?></p>
            </div>
            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProfile">
                <i class='bx bx-pencil'></i> Edit Profile
            </button>
        </section>

        <section class="profile-orders">
            <h2 class="orders-heading">Order History</h2>
            <div class="order-list">
                <?php if (mysqli_num_rows($ordersResult) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($ordersResult)): ?>
                        <div class="order-item">
                            <div class="order-details">
                                <span class="order-date" style="font-style: italic;"><?php echo htmlspecialchars($order['order_date']); ?></span>
                                <span class="order-status <?php echo strtolower($order['order_status']); ?>">
                                    <?php echo htmlspecialchars($order['order_status']); ?>
                                </span>
                            </div>
                            <p class="order-description">
                                Invoice:<?php echo htmlspecialchars($order['invoice_number']); ?> <br>
                                <?php
                                $order_list = json_decode($order['order_list'], true);
                                if (is_array($order_list)) {
                                    foreach ($order_list as $item) {
                                        if (isset($item['meal_title'], $item['quantity'])) {
                                            echo htmlspecialchars($item['meal_title']) . " (Qty: " . htmlspecialchars($item['quantity']) . "), ";
                                        } else {
                                            echo "Invalid item format, ";
                                        }
                                    }
                                }
                                ?>
                            </p>
                            <div class="order-amount" style="font-weight: bold;">
                                <span class="order-total">Total: Kshs <?php echo htmlspecialchars($order['total_amount']); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </section>

        <footer class="profile-footer">
            <button class="btn-logout" onclick="window.location.href='logout.php'">Log Out</button>
        </footer>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--color-navy-blue);color: var(--color-text-white);">
                    <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="studentNo" class="form-label">Student No</label>
                            <input type="text" class="form-control" id="studentNo" name="studentNo" value="<?php echo htmlspecialchars($row['student_no']); ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo htmlspecialchars($row['student_names']); ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="student_pfp" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="student_pfp" name="student_pfp" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary" name="updateProfile">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>