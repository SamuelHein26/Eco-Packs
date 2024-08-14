<?php
session_start();
require_once('db_connection.php');

if (!isset($_SESSION['sess_id']) || !isset($_POST['purchase_id'])) {
    header("Location: Orders.php");
    exit();
}

$sess_id = $_SESSION['sess_id'];
$purchase_id = $_POST['purchase_id'];

// Fetch purchase details
$sql = "SELECT * FROM purchase WHERE id = ? AND member_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $purchase_id, $sess_id);
$stmt->execute();
$result = $stmt->get_result();
$purchase = $result->fetch_assoc();

if (!$purchase) {
    $_SESSION['message'] = "Purchase not found.";
    header("Location: Orders.php");
    exit();
}

// Fetch purchase item details
$sql = "SELECT * FROM purchase_detail WHERE purchase_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Order Details</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>
    
    <div class="container mt-5 mb-5">
        <h1 style="text-align: center;">Order Details</h1>
        <div class="card mb-4">
            <div class="card-header">
                <h2>Order Information</h2>
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($purchase['id']); ?></p>
                <p><strong>Purchase Date:</strong> <?php echo htmlspecialchars($purchase['purchase_date']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($purchase['payment_method']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($purchase['status']); ?></p>
                <p><strong>Total:</strong> <?php echo htmlspecialchars($purchase['total']); ?> Kyat</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Shipping Information</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($purchase['member_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($purchase['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($purchase['address']); ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Purchased Items</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['price']); ?> Kyat</td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="Orders.php" class="btn btn-primary rounded-3">Back to Orders</a>
    </div>
</body>
</html>
