<?php
session_start();
include('../includes/config.php'); // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

// Fetch meal data for the given ID
if (isset($_GET['id'])) {
    $mealID = intval($_GET['id']);
    $fetchMealQuery = "SELECT * FROM menu WHERE meal_id = $mealID AND meal_category = 'grocery'";
    $mealResult = mysqli_query($conn, $fetchMealQuery);
    $mealData = mysqli_fetch_assoc($mealResult);

    if (!$mealData) {
        echo "<script>alert('Grocery not found!'); window.location.href='groceries.php';</script>";
        exit();
    }
}

// Update grocery details
if (isset($_POST['updateMeal'])) {
    $beverageTitle = $_POST['mealTitle'];
    $beveragePrice = $_POST['mealPrice'];
    $beverageImage = $mealData['meal_img']; // Keep existing image

    // Handle image upload if a new image is provided
    if ($_FILES['mealImage']['name']) {
        $targetDir = "uploads/images/";
        $beverageImage = $_FILES['mealImage']['name'];
        $targetFilePath = $targetDir . $beverageImage;
        move_uploaded_file($_FILES['mealImage']['tmp_name'], $targetFilePath);
    }

    // Escape values to prevent SQL injection
    $beverageTitle = mysqli_real_escape_string($conn, $beverageTitle);
    $beveragePrice = mysqli_real_escape_string($conn, $beveragePrice);
    $beverageImage = mysqli_real_escape_string($conn, $beverageImage);

    // Update the beverage in the database
    $updateMealQuery = "UPDATE menu SET meal_title='$beverageTitle', meal_price='$beveragePrice', meal_img='$beverageImage' WHERE meal_id=$mealID AND meal_category='grocery'";
    if (mysqli_query($conn, $updateMealQuery)) {
        echo "<script>alert('Grocery updated successfully!'); window.location.href='groceries.php';</script>";
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
    <title>Edit Meal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <style>
        :root {
            /* Primary Colors */
            --color-navy-blue: #1C3D5A;
            --color-sky-blue: #67B7D1;
            --color-golden-yellow: #F2A900;
            --color-soft-coral: #F28C8C;

            /* Neutral Colors */
            --color-gray-bg: #F5F5F5;
            --color-charcoal-gray: #333333;
            --color-text-black: #2D2D2D;
            --color-text-white: #FFFFFF;

            /* Font Families */
            --font-heading: 'Poppins', sans-serif;
            --font-body: 'Lato', sans-serif;

            /* Font Sizes (Responsive) */
            --font-size-base: 1rem;
            /* 16px */
            --font-size-small: 0.875rem;
            /* 14px */
            --font-size-large: 1.25rem;
            /* 20px */
            --font-size-xl: 2rem;
            /* 32px */
            --font-size-xxl: 2.5rem;
            /* 40px */

            /* Font Weights */
            --font-weight-regular: 400;
            --font-weight-medium: 500;
            --font-weight-bold: 600;

            /* Spacing (for padding, margins) */
            --spacing-small: 0.5rem;
            /* 8px */
            --spacing-medium: 1rem;
            /* 16px */
            --spacing-large: 1.5rem;
            /* 24px */

            /* Line Heights */
            --line-height-heading: 1.2;
            --line-height-body: 1.6;

            /* Border Radius */
            --border-radius: 8px;

            /* Box Shadow */
            --box-shadow-light: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: var(--font-body);
            background-color: var(--color-gray-bg);
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: var(--spacing-large);
            background-color: var(--color-text-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-light);
        }

        .breadcrumb {
            justify-content: center;
            background: none;
            padding: var(--spacing-medium);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '>';
        }

        footer {
            text-align: center;
            padding: var(--spacing-medium);
            background-color: var(--color-navy-blue);
            color: var(--color-text-white);
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--color-golden-yellow);
            border-color: var(--color-golden-yellow);
        }

        .btn-primary:hover {
            background-color: var(--color-soft-coral);
            border-color: var(--color-soft-coral);
        }

        .form-label {
            font-weight: var(--font-weight-medium);
        }

        .footer {
            background-color: var(--color-navy-blue);
            color: var(--color-text-white);
            text-align: center;
            padding: var(--spacing-medium);
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: auto;
            /* Push the footer to the bottom */
        }
    </style>
</head>

<body>

    <div class="container mt-5 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="Groceries.php">Groceries</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Meal</li>
            </ol>
        </nav>
        <h1>Edit Grocery</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="mealTitle" class="form-label">Grocery Title</label>
                <input type="text" class="form-control" id="mealTitle" name="mealTitle" value="<?php echo htmlspecialchars($mealData['meal_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="mealPrice" class="form-label">Grocery Price (KES)</label>
                <input type="number" class="form-control" id="mealPrice" name="mealPrice" step="0.01" value="<?php echo htmlspecialchars($mealData['meal_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="mealImage" class="form-label">Grocery Image</label>
                <input type="file" class="form-control" id="mealImage" name="mealImage" accept="image/*">
                <small class="form-text text-muted">Leave this empty if you don't want to change the image.</small>
            </div>
            <button type="submit" class="btn btn-primary" name="updateMeal">Update Grocery</button>
        </form>
    </div>

    <footer class="footer">
        &copy; 2024 Zetech University Meal Card System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>