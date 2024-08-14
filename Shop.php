<?php
session_start();
require_once('db_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Shop</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>
    <?php
    $sql = "SELECT id, name, price, type, description, image, status FROM product";
    $result = mysqli_query($con, $sql);

    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" role="alert">';
        echo $_SESSION['success_message'];
        echo '</div>';
        unset($_SESSION['success_message']);
    }
    ?>

    <div class="container mt-5 mb-5">
        <div class="row mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for product name...." style="background-color: lightblue;">
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4" id="productContainer">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php
                        $imageData = base64_encode($row['image']);
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                        ?>
                        <div class="d-flex justify-content-center align-items-center pt-2">
                            <img src="<?php echo $imageSrc; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="width: 100px; height: auto;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="card-text">Price: <?php echo $row['price']; ?> Kyat</p>
                            <p class="card-text">Type: <?php echo $row['type']; ?></p>
                            <?php if ($row['status'] !== 'In Stock'): ?>
                                <button class="btn btn-primary" disabled>Out of Stock</button>
                            <?php else: ?>
                                <form action="addToCart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary rounded-3" name="add_to_cart">Add to Cart</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <?php include './components/Footer.php'; ?>
    <script>
        const searchInput = document.getElementById('searchInput');
        const productContainer = document.getElementById('productContainer');

        searchInput.addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();

            // Send AJAX request to fetch search results
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'search_products.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    productContainer.innerHTML = this.responseText;
                }
            };
            xhr.send(`searchQuery=${searchQuery}`);
        });
    </script>
</body>
</html>
