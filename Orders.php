<?php
session_start();
require_once('db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Orders</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>
    <div class="container mt-5">
        <h1 style="text-align: center;">Your Orders</h1>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); // Clear message after displaying ?>
            </div>
        <?php endif; ?>

        <?php
        $sess_id = $_SESSION['sess_id'];

        // Fetch purchase data for the logged-in member
        $sql = "SELECT id, member_name, phone, address, total, purchase_date, status, payment_method FROM purchase WHERE member_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $sess_id);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Total</th>
                            <th>Purchase Date</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['total']); ?> Kyat</td>
                                <td><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                <td>
                                    <div class="d-flex gap-3">
                                    <form action="OrderDetails.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="purchase_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn rounded-3">Details</button>
                                    </form>
                                    <?php if ($row['status'] !== 'Cancelled'): ?>
                                        <form action="CancelOrder.php" method="post" style="display:inline-block;">
                                            <input type="hidden" name="purchase_id" value="<?php echo $row['id']; ?>">
                                            <button type="button" class="btn btn-danger rounded-3" style="background-color: #ff1d18;" onclick="confirmCancellation(<?php echo $row['id']; ?>)">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Cancelled</button>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You have no orders.</p>
        <?php endif; ?>
    </div>
    <script>
        function confirmCancellation(id) {
            if (confirm('Are you sure you want to Cancel Order?')) {
                window.location.href = 'CancelOrder.php?id=' + id;
            }
        }
    </script>
</body>
</html>
