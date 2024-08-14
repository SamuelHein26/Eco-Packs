<?php
session_start();
require_once('../db_connection.php');

// Ensure only authenticated users can access this page
if (!isset($_SESSION['sess_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = isset($_FILES['image']) && $_FILES['image']['size'] > 0 ? file_get_contents($_FILES['image']['tmp_name']) : null;

    // Validate inputs
    if (empty($name) || empty($status) || empty($type) || empty($description) || empty($price) || empty($image)) {
        $error = "All fields are required.";
    } else {
        // Insert new product into the database

        $sql = "INSERT INTO product (name, status, type, description, price, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $null = NULL;
        $stmt->bind_param("ssssdb", $name, $status, $type, $description, $price, $image);
        $stmt->send_long_data(5, $image);  
    
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product added successfully.";
            header("Location: Products.php"); // Redirect to a list of products or another appropriate page
            exit();
        } else {
            $error = "Error adding product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Product</title>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Add Product</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form name="addProductForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" >
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out Of Stock">Out Of Stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type:</label>
                                <input type="text" class="form-control" id="type" name="type" >
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3" ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price:</label>
                                <input type="number" class="form-control" id="price" name="price"  >
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image:</label>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            </div>
                            <div id="jsError" class="alert alert-danger d-none"></div>

                            <button type="submit" class="btn btn-primary">Add Product</button>
                            <a href="Products.php" class="btn btn-secondary">Back to Product List</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const name = document.forms["addProductForm"]["name"].value;
            const status = document.forms["addProductForm"]["status"].value;
            const type = document.forms["addProductForm"]["type"].value;
            const description = document.forms["addProductForm"]["description"].value;
            const price = document.forms["addProductForm"]["price"].value;
            const image = document.forms["addProductForm"]["image"].value;

            if (!name || !status || !type || !description || !price || !image) {
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
