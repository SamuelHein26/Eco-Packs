<?php
session_start();
require_once('db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['proceed_to_payment'])) {
    $sess_id = $_SESSION['sess_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    } else {
        $_SESSION['message'] = "Your cart is empty.";
        header("Location: CartPage.php");
        exit();
    }

    $status = ($payment_method === 'Cash On Delivery') ? 'Pending' : 'Paid';

    // Insert purchase into the database
    $purchase_date = date('Y-m-d');
    $sql = "INSERT INTO purchase (member_id, member_name, phone, address, total, purchase_date, status, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssdsss", $sess_id, $name, $phone, $address, $total, $purchase_date, $status, $payment_method);
    $stmt->execute();
    $purchase_id = $stmt->insert_id;

    // Insert purchase details
    $sql = "INSERT INTO purchase_detail (purchase_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("iisdi", $purchase_id, $item['id'], $item['name'], $item['price'], $item['quantity']);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Set the last purchase ID
    $_SESSION['last_purchase_id'] = $purchase_id;

    $_SESSION['message'] = "Order placed successfully!";
    header("Location: ThankYou.php");
    exit();
} else {
    header("Location: CartPage.php");
    exit();
}
?>
