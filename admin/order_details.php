<?php
session_start();
require_once('../db_connection.php');

// Check if the user is logged in and has the necessary privileges
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the order ID is provided in the request
if (isset($_GET['id'])) {
    // Sanitize the order ID
    $order_id = mysqli_real_escape_string($con, $_GET['id']);

    // Fetch order information from the purchase table
    $sql_order = "SELECT * FROM purchase WHERE id = '$order_id'";
    $result_order = mysqli_query($con, $sql_order);

    if ($result_order && mysqli_num_rows($result_order) > 0) {
        $order = mysqli_fetch_assoc($result_order);

        // Fetch order details from the purchase_detail table
        $sql_details = "SELECT * FROM purchase_detail WHERE purchase_id = '$order_id'";
        $result_details = mysqli_query($con, $sql_details);
    } else {
        $_SESSION['error_message'] = "Order not found.";
        header("Location: order_management.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Error: Order ID not provided.";
    header("Location: order_management.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Order Details</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'components/SideBar.php'; ?>
        <div class="main">
            <?php include 'components/header.php'; ?>
            <div class="container mt-4 mb-4">
                <h2>Order Details</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Order Information</h5>
                        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                        <p><strong>Member ID:</strong> <?php echo $order['member_id']; ?></p>
                        <p><strong>Member Name:</strong> <?php echo $order['member_name']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
                        <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                        <p><strong>Total:</strong> <?php echo $order['total']; ?></p>
                        <p><strong>Purchase Date:</strong> <?php echo $order['purchase_date']; ?></p>
                        <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                    </div>
                </div>

                <h5>Products in this Order</h5>
                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_details)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['product_id']; ?></td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['price']; ?> Kyat</td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['price'] * $row['quantity']; ?> Kyat</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <a href="Orders.php" class="btn btn-secondary mt-3">Back to Order Management</a>
            </div>
        </div>
    </div>
</body>
</html>
