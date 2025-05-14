<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['student_no'])) {
    header("Location: login.php");
    exit();
}

$student_no = $_SESSION['student_no'];

// Fetch student data
$studentDetails = "SELECT * FROM students WHERE student_no = '$student_no'";
$studentData = mysqli_query($conn, $studentDetails);

$studentIP = "";
$studentID = "";
if (mysqli_num_rows($studentData) === 1) {
    $row = mysqli_fetch_assoc($studentData);
    $studentIP = $row['student_ip'];
    $studentID = $row['student_id'];
}

// Handle cart updates
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $meal_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            $update_query = "UPDATE `cart` SET quantity = '$quantity' WHERE meal_id = '$meal_id' AND ip_address='$studentIP' AND student_id='$studentID'";
            mysqli_query($conn, $update_query);
        } else {
            $delete_query = "DELETE FROM `cart` WHERE meal_id = '$meal_id' AND ip_address='$studentIP' AND student_id='$studentID'";
            mysqli_query($conn, $delete_query);
        }
    }
}

// Handle item removal
if (isset($_POST['remove_cart'])) {
    if (isset($_POST['removeitem'])) {
        foreach ($_POST['removeitem'] as $remove_id) {
            $remove_query = "DELETE FROM `cart` WHERE meal_id = '$remove_id' AND ip_address='$studentIP' AND student_id='$studentID'";
            mysqli_query($conn, $remove_query);
        }
    }
}

// Handle checkout
if (isset($_POST['checkout'])) {
    $order_list = [];
    $total_amount = 0;

    $cart_query = "SELECT * FROM `cart` WHERE ip_address='$studentIP' AND student_id='$studentID'";
    $result = mysqli_query($conn, $cart_query);

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['meal_id'];
        $quantity = $row['quantity'];

        $select_products = "SELECT * FROM `menu` WHERE meal_id='$product_id'";
        $result_products = mysqli_query($conn, $select_products);

        if ($product = mysqli_fetch_assoc($result_products)) {
            $meal_title = $product['meal_title'];
            $meal_price = $product['meal_price'];
            $subtotal = $meal_price * $quantity;
            $total_amount += $subtotal;

            $order_list[] = [
                "meal_title" => $meal_title,
                "quantity" => $quantity
            ];
        }
    }

    $invoice_number = uniqid('inv_');
    $order_list_json = json_encode($order_list); // Store as JSON
    $order_status = 'Pending';

    // Insert order into database
    $insert_order = "INSERT INTO orders (user_id, meal_id, invoice_number, total_amount, order_list, order_status, order_date) VALUES ('$studentID', '$product_id', '$invoice_number', '$total_amount', '$order_list_json', '$order_status', NOW())";
    mysqli_query($conn, $insert_order);

    // Clear the cart after checkout
    $clear_cart = "DELETE FROM `cart` WHERE ip_address='$studentIP' AND student_id='$studentID'";
    mysqli_query($conn, $clear_cart);

    echo "<p class='text-success'>Checkout complete! Your order has been placed.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cart</title>
    <style>
        body {
            background-color: var(--color-gray-bg);
            font-family: var(--font-body);
            color: var(--color-text-black);
            padding: var(--spacing-large);
        }

        .back-button {
            display: flex;
            align-items: center;
            margin-bottom: var(--spacing-medium);
            cursor: pointer;
            font-size: var(--font-size-large);
            color: inherit;
        }

        .back-button i {
            margin-right: var(--spacing-small);
            color: var(--color-navy-blue);
        }

        .table {
            overflow: auto;
        }

        .footer-menu {
            background-color: var(--color-navy-blue);
            color: var(--color-text-white);
            text-align: center;
            padding: var(--spacing-small);
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <a href="menu.php" class="back-button">
        <i class='bx bxs-chevron-left'></i> <span>Menu</span>
    </a>
    <h1 class="cart-title">Your Cart</h1>

    <div class="container table-responsive">
        <form method="POST" action="cart.php">
            <table class="table table-bordered text-center mt-5 w-100">
                <?php
                $total_price = 0;
                $cart_query = "SELECT * FROM `cart` WHERE ip_address='$studentIP' AND student_id='$studentID'";
                $result = mysqli_query($conn, $cart_query);

                if (mysqli_num_rows($result) > 0) {
                    echo "
                        <thead>
                            <tr>
                                <th>Meal Title</th>
                                <th>Meal Image</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Check</th>
                                <th colspan='2'>Operations</th>
                            </tr>
                        </thead>
                        <tbody>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_id = $row['meal_id'];
                        $select_products = "SELECT * FROM `menu` WHERE meal_id='$product_id'";
                        $result_products = mysqli_query($conn, $select_products);

                        while ($row_product_price = mysqli_fetch_assoc($result_products)) {
                            $product_price = $row_product_price['meal_price'];
                            $product_title = $row_product_price['meal_title'];
                            $product_image1 = $row_product_price['meal_img'];
                            $quantity = $row['quantity'];
                            $item_total = $product_price * $quantity;
                            $total_price += $item_total;
                ?>
                            <tr>
                                <td><?php echo $product_title; ?></td>
                                <td><img src="admin/uploads/images/<?php echo $product_image1; ?>" alt="<?php echo $product_title; ?>" width="100"></td>
                                <td><input type="number" name="qty[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="0"></td>
                                <td>Kshs <?php echo number_format($item_total, 2); ?></td>
                                <td><input type="checkbox" name="removeitem[]" value="<?php echo $product_id; ?>"></td>
                                <td><button type="submit" name="update_cart" class="btn btn-warning">Update</button></td>
                            </tr>
                <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Total Price:</td>
                    <td>Kshs <?php echo number_format($total_price, 2); ?></td>
                    <td colspan="2">
                        <button type="submit" name="remove_cart" class="btn btn-danger">Remove Selected</button>
                        <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

    <div class="footer-menu">
        <p>&copy; 2024 Zetech University</p>
    </div>
</body>

</html>