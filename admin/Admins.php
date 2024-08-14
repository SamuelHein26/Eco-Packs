<?php
session_start();
include_once '../db_connection.php';
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
    <title>Manage Admins</title>
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
            // Fetch data from the admin database
            $sql = "SELECT id, username, email, phone, address, pfp FROM admin";
            $result = mysqli_query($con, $sql);
            ?>
            <div class="container mt-4">
                <h2 style="text-align: center;">Admin Management</h2>
                <a href="add_admin.php" class="btn btn-primary">Add Admin</a>

                <div class="table-responsive">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile Picture</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Actions</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <?php if (!empty($row['pfp'])): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['pfp']); ?>" alt="Profile Picture" style="width: 50px; height: auto;">
                                        <?php else: ?>
                                            <img src="../images/Account.png" alt="Profile Picture" style="width: 50px; height: auto;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td>
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
                window.location.href = 'delete_admin.php?id=' + id;
            }
        }
    </script>
</body>
</html>

