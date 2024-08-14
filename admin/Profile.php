<?php
session_start();
require_once('../db_connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch admin profile information
$admin_id = $_SESSION['sess_id']; // Assuming the user ID is stored in the session
$sql = "SELECT username, email, phone, address, pfp FROM admin WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    $_SESSION['error_message'] = "Admin not found.";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Profile</title>
</head>
<body>
    <div class="container mt-5">
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Profile</h3>
                    </div>
                    <div class="d-flex justify-content-center align-items-center p-3">
                        <?php if ($admin['pfp']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($admin['pfp']); ?>" alt="Profile Picture" style="height: 100px; width: auto;" >
                        <?php else: ?>
                            <img src="../images/Account.png" alt="Default Profile Picture" style="height: 100px; width: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Username:</th>
                                <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo htmlspecialchars($admin['phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?php echo htmlspecialchars($admin['address']); ?></td>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <a href="Admins.php" class="btn btn-primary">Back to Home</a>
                            <a href="edit_profile.php" class="btn btn-secondary">Edit Profile</a>
                            <a href="edit_password.php" class="btn btn-warning">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
