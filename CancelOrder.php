<?php
session_start();
require_once('db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: Orders.php");
    exit();
}

$sess_id = $_SESSION['sess_id'];

if (isset($_GET['id'])) {
    $purchase_id = mysqli_real_escape_string($con, $_GET['id']);
    $sql = "UPDATE purchase SET status = 'Cancelled' WHERE id = ? AND member_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $purchase_id, $sess_id);
}

if ($stmt->execute()) {
    $_SESSION['message'] = "Order cancelled successfully!";
} else {
    $_SESSION['message'] = "Failed to cancel order.";
}

header("Location: Orders.php");
exit();
?>
