<?php
session_start();
include_once '../db_connection.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    session_destroy();
    header("Location: ../login.php");
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
    <title>Manage Orders</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'components/SideBar.php'; ?>
        <div class="main">
            <?php include 'components/header.php'; ?>
            <div class="container mt-4">
                <h2 style="text-align: center;">Order Management</h2>
                 <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>" role="alert">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['alert_type']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <?php
                // Fetch data from the purchase database
                $sql = "SELECT id, member_id, member_name, phone, address, total, purchase_date, status, payment_method FROM purchase";
                $result = mysqli_query($con, $sql);
                ?>

                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Member ID</th>
                                <th>Member Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Total</th>
                                <th>Purchase Date</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th> <!-- New column for action buttons -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['member_id']; ?></td>
                                    <td><?php echo $row['member_name']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['total']; ?></td>
                                    <td><?php echo $row['purchase_date']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['payment_method']; ?></td>
                                    <td class="d-flex gap-3">
                                        <a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View</a>
                                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(orderId) {
            if (confirm("Are you sure you want to delete this order?")) {
                window.location.href = "delete_order.php?id=" + orderId;
            }
        }
    </script>
</body>
</html>
