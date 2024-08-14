
<?php
session_start();
require_once('../db_connection.php');
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Manage Products</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'components/SideBar.php'; ?>
        <div class="main">
            <?php include 'components/header.php'; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php
            $sql = "SELECT id, image, name, status, type, description, price FROM product";
            $result = mysqli_query($con, $sql);
            ?>
            <div class="container mt-4">
                <h2 style="text-align: center;">Product Management</h2>
                <a href="add_product.php" class="btn btn-primary">Add Product</a>
                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="<?php echo $row['name']; ?>" style="width: 50px; height: auto;"></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['type']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['price']; ?> Kyat</td>
                                    <td class="d-flex gap-2">
                                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a> 
                                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button> 
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this admin?')) {
            window.location.href = 'delete_product.php?id=' + id;
        }
    }
    </script>
</body>
</html>
