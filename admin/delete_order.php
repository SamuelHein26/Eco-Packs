<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    // Sanitize the order ID
    $order_id = mysqli_real_escape_string($con, $_GET['id']);

    // Start a transaction
    mysqli_begin_transaction($con);

    try {
        // Delete order details first
        $sql_delete_details = "DELETE FROM purchase_detail WHERE purchase_id = '$order_id'";
        if (!mysqli_query($con, $sql_delete_details)) {
            throw new Exception("Error deleting order details: " . mysqli_error($con));
        }

        // Delete the order
        $sql_delete_order = "DELETE FROM purchase WHERE id = '$order_id'";
        if (!mysqli_query($con, $sql_delete_order)) {
            throw new Exception("Error deleting order: " . mysqli_error($con));
        }

        // Commit the transaction
        mysqli_commit($con);

        $_SESSION['message'] = "Order deleted successfully.";
        $_SESSION['alert_type'] = "success";
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($con);

        $_SESSION['message'] = $e->getMessage();
        $_SESSION['alert_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Error: Order ID not provided.";
    $_SESSION['alert_type'] = "danger";
}

header("Location: Orders.php");
exit();
?>
