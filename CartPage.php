<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Cart</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>

    <div class="container mt-5">
        <h1>Shopping Cart</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
            
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; ?>
                        <?php $total += $subtotal; ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['price']; ?> Kyat</td>
                            <td><input class="form-control quantity-input" style="width: 60px;" type="number" data-index="<?php echo $index; ?>" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10"></td>
                            <td class="subtotal"><?php echo $subtotal; ?> </td>
                            <td>
                                <form action="remove_from_cart.php" method="post" onsubmit="return confirm('Are you sure you want to remove this item from the cart?');">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="remove_from_cart" style="background-color: #ff1d18;" class="btn btn-danger rounded">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" align="right"><b>Total</b></td>
                        <td colspan="2"><b id="total"><?php echo $total; ?></b></td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center justify-content-center">
        <?php if (isset($_SESSION['sess_id'])): ?>
            <form action="ConfirmOrder.php" method="post">
                <button type="submit" name="confirm_order" class="btn btn-primary btn-lg rounded">Confirm Order</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to confirm your order.</p>
        <?php endif; ?>
    </div>

    <?php else: ?>
        <tr>
            <td colspan="5" class="">Your cart is empty.</td>
        </tr>
    <?php endif; ?>
    
    <script>
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', function() {
                let index = this.getAttribute('data-index');
                let quantity = parseInt(this.value);
                let price = parseFloat(this.closest('tr').querySelector('td:nth-child(2)').innerText);
                let subtotalElem = this.closest('tr').querySelector('.subtotal');
                let subtotal = quantity * price;
                subtotalElem.innerText = subtotal;

                // Update the total
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(subtotalElem => {
                    total += parseFloat(subtotalElem.innerText);
                });
                document.getElementById('total').innerText = total;

                // Update the session data via AJAX
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_cart.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('index=' + index + '&quantity=' + quantity);
            });
        });
    </script>
</body>
</html>
