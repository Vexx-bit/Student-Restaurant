<?php
session_start();
include('../includes/config.php'); // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}
// Fetch snack data for the given ID
if (isset($_GET['id'])) {
    $snackID = intval($_GET['id']);
    $fetchSnackQuery = "SELECT * FROM menu WHERE meal_id = $snackID AND meal_category = 'snack'";
    $snackResult = mysqli_query($conn, $fetchSnackQuery);
    $snackData = mysqli_fetch_assoc($snackResult);

    if (!$snackData) {
        echo "<script>alert('Snack not found!'); window.location.href='snacks.php';</script>";
        exit();
    }
}

// Update snack details
if (isset($_POST['updateSnack'])) {
    $snackTitle = $_POST['snackTitle'];
    $snackPrice = $_POST['snackPrice'];
    $snackImage = $snackData['meal_img']; // Keep existing image

    // Handle image upload if a new image is provided
    if ($_FILES['snackImage']['name']) {
        $targetDir = "uploads/images/";
        $snackImage = $_FILES['snackImage']['name'];
        $targetFilePath = $targetDir . $snackImage;
        move_uploaded_file($_FILES['snackImage']['tmp_name'], $targetFilePath);
    }

    // Escape values to prevent SQL injection (using simple methods)
    $snackTitle = mysqli_real_escape_string($conn, $snackTitle);
    $snackPrice = mysqli_real_escape_string($conn, $snackPrice); // Escape price as well
    $snackImage = mysqli_real_escape_string($conn, $snackImage);

    // Update the snack in the database
    $updateSnackQuery = "UPDATE menu SET meal_title='$snackTitle', meal_price='$snackPrice', meal_img='$snackImage' WHERE meal_id=$snackID AND meal_category='snack'";
    if (mysqli_query($conn, $updateSnackQuery)) {
        echo "<script>alert('Snack updated successfully!'); window.location.href='snacks.php';</script>";
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
    <title>Edit Snack</title>
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
                <li class="breadcrumb-item"><a href="snacks.php">Snacks</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Snack</li>
            </ol>
        </nav>
        <h1>Edit Snack</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="snackTitle" class="form-label">Snack Title</label>
                <input type="text" class="form-control" id="snackTitle" name="snackTitle" value="<?php echo htmlspecialchars($snackData['meal_title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="snackPrice" class="form-label">Snack Price (KES)</label>
                <input type="number" class="form-control" id="snackPrice" name="snackPrice" step="0.01" value="<?php echo htmlspecialchars($snackData['meal_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="snackImage" class="form-label">Snack Image</label>
                <input type="file" class="form-control" id="snackImage" name="snackImage" accept="image/*">
                <small class="form-text text-muted">Leave this empty if you don't want to change the image.</small>
            </div>
            <button type="submit" class="btn btn-primary" name="updateSnack">Update Snack</button>
        </form>
    </div>

    <footer class="footer">
        &copy; 2024 Zetech University Meal Card System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>