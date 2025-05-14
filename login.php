<?php
session_start();
// Include database configuration
include 'includes/config.php';

if (isset($_POST['login'])) {
    $student_no = $_POST['student_no'];
    $password = $_POST['password'];

    // Hash the password using md5 to compare with stored hashed password
    $hashed_password = md5($password);

    // Query to check for the user
    $query = "SELECT * FROM students WHERE student_no = '$student_no' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Successful login
        $_SESSION['student_no'] = $student_no; // Store student number in session
        echo "<script>alert('Login successful!'); window.location.href='menu.php';</script>";
    } else {
        echo "<script>alert('Invalid student number or password!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">

    <style>
        .breadcrumb {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-bottom: 1.5rem;
            font-family: var(--font-body);
        }

        .breadcrumb li {
            margin-right: 0.5rem;
        }

        .breadcrumb li+li:before {
            content: "/";
            margin-right: 0.5rem;
            color: var(--color-soft-gray);
        }
    </style>

</head>

<body>

    <div class="login-container">
        <div class="login-form">
            <h1 class="login-title">Welcome Back</h1>
            <form action="login.php" method="post" class="form-auth">
                <div class="form-group">
                    <label for="student_no" class="form-label">Student No/ Employee No</label>
                    <input type="text" id="student_no" name="student_no" required class="form-input" placeholder="Enter your student no/ employee no">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" required class="form-input" placeholder="Enter your password">
                </div>
                <button type="submit" name="login" class="form-button">Login</button>
                <p class="form-footer">Don't have an account? <a href="register.php" class="form-link">Register here</a></p>
            </form>
        </div>
        <!-- Breadcrumb Navigation -->
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Login</li>
        </ul>
    </div>
    <footer class="footer-auth">
        <p>&copy; 2024 Zetech University Meal Card System. All rights reserved.</p>
    </footer>
</body>

</html>