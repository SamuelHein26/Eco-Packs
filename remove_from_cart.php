<?php
session_start();

if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Find the product in the cart and remove it
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $productId) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['message'] = "Item removedd cart";
            break;
        }
    }
    

    header("Location: CartPage.php");
    exit();
} else {
    header("Location: CartPage.php");
    exit();
}
?>
