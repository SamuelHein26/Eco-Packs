<?php
session_start();
require_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchQuery'])) {
    $searchQuery = $_POST['searchQuery'];

    // Fetch products matching the search query
    $sql = "SELECT id, name, price, type, description, image, status FROM product WHERE LOWER(name) LIKE '%$searchQuery%' OR LOWER(description) LIKE '%$searchQuery%'";
    $result = mysqli_query($con, $sql);

    // Generate HTML for the search results
    ob_start();
    while ($row = mysqli_fetch_assoc($result)): ?>
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
                    <?php else:
                    ?>
                        <form action="addToCart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile;
    $searchResultsHTML = ob_get_clean();

    // Output the generated HTML
    echo $searchResultsHTML;
} else {
    // Handle invalid or missing search query
    echo "Invalid search query.";
}
?>
