<?php
session_start();
require_once('db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['sess_id'];

// Fetch member details from the database
$sql = "SELECT fullName, email, phone, address FROM member WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Confirm Order</title>

</head>
<body onload="toggleCreditCardForm()">
    <?php include './components/Navbar.php'; ?>

    <div class="container mt-5">
        <h1 style="text-align: center;">Confirm Order</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Order Details</h2>
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
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['price'] * $item['quantity']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" align="right"><b>Total</b></td>
                            <td><b><?php echo $total; ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Shipping Information</h2>
            </div>
            <div class="card-body">
                <form action="ProcessPayment.php" method="post" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $member['fullName']; ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $member['email']; ?>" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $member['phone']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo $member['address']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <h4 for="payment_method" class="form-label "><b>Payment Method</b></>
                        <select class="form-control mt-3" id="payment_method" name="payment_method" onchange="toggleCreditCardForm()" required>
                            <option value="Cash On Delivery">Cash On Delivery</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>
                    <div id="credit_card_form" style="display: none;">
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 1234 1234 1234">
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVC">
                        </div>
                    </div>
                    <div id="message" class="mb-3" style="color: red;"></div>
                    <button type="submit" name="proceed_to_payment" class="btn btn-primary">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
        <script>
    function validateForm() {
        var phone = document.forms[0]["phone"].value;
        var phonePattern = /^[0-9]{8,11}$/;
        var errorMessage = "";

        if (!phonePattern.test(phone)) {
            errorMessage += "Phone number must be between 8 and 11 digits long.\n";
        }

        var paymentMethod = document.forms[0]["payment_method"].value;
        if (paymentMethod === "Credit Card") {
            var cardNumber = document.getElementById("card_number").value;
            var expiryDate = document.getElementById("expiry_date").value;
            var cvv = document.getElementById("cvv").value;

            if (cardNumber === "" || expiryDate === "" || cvv === "") {
                errorMessage += "Please fill out all credit card information.\n";
            }
        }

        if (errorMessage !== "") {
            document.getElementById("message").innerHTML = errorMessage;
            return false; 
        }

        return true; 
    }

    function toggleCreditCardForm() {
        var paymentMethod = document.getElementById("payment_method").value;
        var creditCardForm = document.getElementById("credit_card_form");
        if (paymentMethod === "Credit Card") {
            creditCardForm.style.display = "block";
        } else {
            creditCardForm.style.display = "none";
        }
    }
    </script>
</body>
</html>
