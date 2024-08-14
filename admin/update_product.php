<?php
session_start();
require_once('../db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $new_image = isset($_FILES['new_image']) && $_FILES['new_image']['size'] > 0 ? file_get_contents($_FILES['new_image']['tmp_name']) : null;

    if (empty($name) || empty($status) || empty($type) || empty($description) || empty($price)) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: edit_product.php?id=$product_id");
        exit();
    } else {
        if ($new_image !== null) {
            $sql = "UPDATE product SET name = ?, status = ?, type = ?, description = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssdsi", $name, $status, $type, $description, $price, $new_image, $product_id);
        } else {
            $sql = "UPDATE product SET name = ?, status = ?, type = ?, description = ?, price = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssdi", $name, $status, $type, $description, $price, $product_id);
        }

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product updated successfully.";
            header("Location: Products.php"); 
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating product.";
            header("Location: edit_product.php?id=$product_id");
            exit();
        }
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: Products.php");
    exit();
}
?>
