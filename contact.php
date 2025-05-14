<?php
session_start();
// Include your database connection
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Prepare and sanitize input data
    $cont_names = htmlspecialchars($_POST['cont_names']);
    $cont_message = htmlspecialchars($_POST['cont_message']);

    // Insert the data into the contacts table
    $query = "INSERT INTO contacts (cont_names, cont_message) VALUES ('$cont_names', '$cont_message')";

    if ($conn->query($query) === TRUE) {
        $success_message = "Your inquiry has been submitted successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
            color: var(--color-text-black);
            margin: 0;
            padding: var(--spacing-medium);
        }

        .header-menu {
            background-color: var(--color-navy-blue);
            padding: var(--spacing-medium);
            color: var(--color-text-white);
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            /* Centers the nav items horizontally */
            align-items: center;
            /* Aligns items vertically in the center */
            gap: var(--spacing-large);
        }

        .nav-link {
            color: var(--color-text-white);
            text-decoration: none;
            font-size: var(--font-size-large);
            transition: transform 0.3s, color 0.3s;
            /* Smooth hover effect */
            position: relative;
            /* Allows for positioning of the cart item counter */
            display: flex;
            /* Makes it possible to align the cart icon and text horizontally */
            align-items: center;
            /* Vertically center the text and icon */
            gap: 0.5rem;
            /* Adds some space between icon and text */
        }

        .nav-link i {
            font-size: 1.5rem;
            /* Size of the cart icon */
        }

        .nav-link:hover {
            transform: scale(1.05);
            /* Slightly scale the link when hovered */
            color: var(--color-golden-yellow);
            /* Change color on hover */
        }

        .active-link {
            transform: scale(1.1);
            font-weight: var(--font-weight-bold);
            color: var(--color-golden-yellow);
            /* Highlight the active page */
        }

        .nav-link sup {
            background-color: var(--color-golden-yellow);
            color: var(--color-text-black);
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 50%;
            padding: 0.2rem 0.4rem;
            position: absolute;
            top: -8px;
            right: -10px;
        }

        /* Responsive Design for smaller screens */
        @media (max-width: 768px) {
            .nav-menu {
                gap: var(--spacing-medium);
                /* Reduce spacing for smaller screens */
            }

            .nav-link {
                font-size: 1.2rem;
                /* Adjust font size for smaller screens */
            }

            .nav-link i {
                font-size: 1.2rem;
                /* Resize icon on smaller screens */
            }

            .nav-link sup {
                top: -5px;
                right: -8px;
                /* Adjust the cart counter position */
            }
        }


        .header {
            text-align: center;
            padding: var(--spacing-large);
            background-color: var(--color-navy-blue);
            color: var(--color-text-white);
            border-bottom-left-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
            box-shadow: var(--box-shadow-light);
        }

        .contact-form {
            background-color: var(--color-text-white);
            border-radius: var(--border-radius);
            padding: var(--spacing-large);
            box-shadow: var(--box-shadow-light);
            max-width: 600px;
            margin: 20px auto;
        }

        .form-group {
            margin-bottom: var(--spacing-large);
        }

        .form-group label {
            display: block;
            font-weight: var(--font-weight-bold);
            margin-bottom: var(--spacing-small);
        }

        .form-group input,
        .form-group textarea {
            width: 96%;
            padding: var(--spacing-small);
            border: 1px solid var(--color-charcoal-gray);
            border-radius: var(--border-radius);
            font-size: var(--font-size-base);
        }

        .form-group textarea {
            height: 100px;
            resize: none;
        }

        .submit-button {
            background-color: var(--color-golden-yellow);
            color: var(--color-text-black);
            border: none;
            padding: var(--spacing-medium);
            border-radius: var(--border-radius);
            font-size: var(--font-size-large);
            cursor: pointer;
            font-weight: var(--font-weight-bold);
            transition: background-color 0.3s;
            width: 100%;
        }

        .submit-button:hover {
            background-color: var(--color-soft-coral);
        }

        .message {
            text-align: center;
            margin-top: var(--spacing-medium);
            font-weight: var(--font-weight-medium);
            color: var(--color-soft-coral);
        }

        @media (max-width: 768px) {
            body {
                padding: var(--spacing-small);
            }

            .contact-form {
                padding: var(--spacing-medium);
            }
        }

        .footer-menu {
            background-color: var(--color-navy-blue);
            color: var(--color-text-white);
            text-align: center;
            padding: var(--spacing-small);
        }
    </style>
</head>

<body>

    <header class="header-menu">
        <nav class="nav-menu">
            <?php
            // Check if the user is logged in
            if (!isset($_SESSION['student_no'])) {
                echo '<a href="login.php" class="nav-link">Login</a>';
                echo '<a href="index.html" class="nav-link">Home</a>';
                echo '<a href="register.php" class="nav-link">Register</a>';
            } else {
                echo '<a href="logout.php" class="nav-link">Logout</a>';
                echo '<a href="cart.php" class="nav-link"><i class="bx bxs-cart-alt"></i> <sup>0</sup></a>';
                echo '<a href="contact.php" class="nav-link active-link">Contact</a>';
                echo '<a href="menu.php" class="nav-link">Menu</a>';
            }

            ?>
        </nav>
    </header>

    <div class="header">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you! Please fill out the form below.</p>
    </div>

    <div class="contact-form">
        <?php if (isset($success_message)): ?>
            <div class="message"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="cont_names">Your Names</label>
                <input type="text" id="cont_names" name="cont_names" required>
            </div>
            <div class="form-group">
                <label for="cont_message">Your Message</label>
                <textarea id="cont_message" name="cont_message" required></textarea>
            </div>
            <button type="submit" name="submit" class="submit-button">Send Inquiry</button>
        </form>
    </div>

    <footer class="footer-menu">
        <p>&copy; 2024 Zetech University Meal Card System. All rights reserved.</p>
    </footer>

</body>

</html>