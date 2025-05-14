<?php
// Include database configuration
include 'includes/config.php';

if (isset($_POST['register'])) {
    $student_no = $_POST['student_no'];
    $student_names = $_POST['student_names'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $student_ip = $_SERVER['REMOTE_ADDR']; // Get user's IP address

    // Check if passwords match
    if ($password === $cpassword) {
        // Encrypt the password
        $hashed_password = md5($password);

        // Check if the student number already exists
        $query = "SELECT * FROM students WHERE student_no = '$student_no'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Student number already exists! Please login or use a different one.');</script>";
        } else {
            // Insert student data into the database
            $sql = "INSERT INTO students (student_no, student_names, password, student_ip) 
                    VALUES ('$student_no', '$student_names', '$hashed_password', '$student_ip')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: Could not register.');</script>";
            }
        }
    } else {
        echo "<script>alert('Passwords do not match!');</script>";
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

        .footer-auth {
            margin-top: 12px;
        }
    </style>
</head>

<body>
    <div class="reg-container" style="margin-bottom: 50px;">
        <div class="reg-form">
            <h1 class="reg-title">Create Your Account</h1>
            <form action="register.php" method="post" class="form-auth">
                <div class="form-group">
                    <label for="student_no" class="form-label">Student No/Employee No</label>
                    <input type="text" id="student_no" name="student_no" required class="form-input" placeholder="Enter your student no/ employee no">
                </div>
                <div class="form-group">
                    <label for="student_names" class="form-label">Student Names</label>
                    <input type="text" id="student_names" name="student_names" required class="form-input" placeholder="Enter your names">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" required class="form-input" placeholder="Enter your password">
                </div>
                <div class="form-group">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" id="cpassword" name="cpassword" required class="form-input" placeholder="Confirm your password">
                </div>
                <button type="submit" name="register" class="form-button">Register</button>
                <p class="form-footer">Already have an account? <a href="login.php" class="form-link">Login here</a></p>
            </form>
        </div>
        <!-- Breadcrumb Navigation -->
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Register</li>
        </ul>
    </div>
    <footer class="footer-auth">
        <p>&copy; 2024 Zetech University Meal Card System. All rights reserved.</p>
    </footer>
</body>

</html>