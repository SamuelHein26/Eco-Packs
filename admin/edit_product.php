<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM product WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        $_SESSION['error_message'] = "Product not found.";
        header("Location: Products.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Product ID not provided.";
    header("Location: Products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Product</title>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Product</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form name="editProductForm" action="update_product.php" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="In Stock" <?php echo ($product['status'] == 'In Stock') ? 'selected' : ''; ?>>In Stock</option>
                                    <option value="Out Of Stock" <?php echo ($product['status'] == 'Out Of Stock') ? 'selected' : ''; ?>>Out Of Stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type:</label>
                                <input type="text" class="form-control" id="type" name="type" value="<?php echo $product['type']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $product['description']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price:</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Current Image:</label>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="Current Image" style="max-height: 50px;">
                            </div>
                            <div class="mb-3">
                                <label for="new_image" class="form-label">New Image:</label>
                                <input class="form-control" type="file" id="new_image" name="new_image" accept="image/*">
                            </div>

                            <div id="jsError" class="alert alert-danger d-none"></div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="Products.php" class="btn btn-secondary">Back to Product List</a>
                            </divc>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const name = document.forms["editProductForm"]["name"].value;
            const status = document.forms["editProductForm"]["status"].value;
            const type = document.forms["editProductForm"]["type"].value;
            const description = document.forms["editProductForm"]["description"].value;
            const price = document.forms["editProductForm"]["price"].value;

            if (!name || !status || !type || !description || !price) {
                showError("All fields must be filled out");
                return false;
            }

            return true;
        }

        function showError(message) {
            const jsError = document.getElementById("jsError");
            jsError.textContent = message;
            jsError.classList.remove("d-none");
        }
    </script>
</body>
</html>
