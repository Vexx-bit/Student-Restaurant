<?php
session_start();
// Include database configuration
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input
    $employee_no = $_POST['employee_no'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = md5($password);

    // Query to check if the admin exists
    $query = "SELECT * FROM admins WHERE employee_no = '$employee_no' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Valid credentials
        $_SESSION['employee_no'] = $employee_no;
        echo "<script>alert('Login successful. Welcome!'); window.location.href = 'index.php';</script>";
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Employee Number or Password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

            /* Font Sizes */
            --font-size-base: 1rem;
            --font-size-small: 0.875rem;
            --font-size-large: 1.25rem;
            --font-size-xl: 2rem;
            --font-size-xxl: 2.5rem;

            /* Font Weights */
            --font-weight-regular: 400;
            --font-weight-medium: 500;
            --font-weight-bold: 600;

            /* Spacing */
            --spacing-small: 0.5rem;
            --spacing-medium: 1rem;
            --spacing-large: 1.5rem;

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

        .main-container {
            max-width: 600px;
            margin: 50px auto;
            padding: var(--spacing-large);
            background-color: var(--color-text-white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-light);
        }

        h1 {
            font-family: var(--font-heading);
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
            color: var(--color-navy-blue);
            text-align: center;
            margin-bottom: var(--spacing-large);
        }

        label {
            font-weight: var(--font-weight-medium);
            color: var(--color-text-black);
        }

        .form-control {
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-medium);
        }

        .btn-primary {
            background-color: var(--color-sky-blue);
            border: none;
            padding: var(--spacing-small) var(--spacing-large);
            font-size: var(--font-size-large);
            font-weight: var(--font-weight-medium);
            border-radius: var(--border-radius);
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: var(--color-navy-blue);
        }

        .btn-secondary {
            margin-top: var(--spacing-small);
            font-size: var(--font-size-small);
            color: var(--color-text-black);
            width: 100%;
            border: none;
            text-align: center;
        }

        .btn-secondary:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: var(--spacing-medium);
            }

            h1 {
                font-size: var(--font-size-large);
            }
        }
    </style>
</head>

<body>

    <div class="main-container">
        <h1>Admin Login</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="employee_no" class="form-label">Employee No</label>
                <input type="text" class="form-control" id="employee_no" name="employee_no" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>