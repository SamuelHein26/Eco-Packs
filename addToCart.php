<?php
session_start();

if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Fetch product details from the database based on product ID
    require_once('db_connection.php');
    $sql = "SELECT id, name, price FROM product WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Add product to cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => 1
        ];
    }

    // Set success message
    $_SESSION['success_message'] = "Item added to cart successfully.";

    // Redirect back to the shop page
    header("Location: Shop.php");
    exit();
} else {
    // Redirect to the shop page if add to cart button is not clicked
    header("Location: Shop.php");
    exit();
}
?>
