<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: ../Login.php");
    exit();
}

$sess_id = $_SESSION['sess_id'];

// Fetch member data
$sql = "SELECT username, fullName, email, dob, phone, address, pfp FROM member WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $sess_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Member Profile</title>
</head>
<body>
    <div class="container mt-5 mb-5">
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
                        <?php if ($member['pfp']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($member['pfp']); ?>" class="img-thumbnail" alt="Profile Picture" width="150">
                        <?php else: ?>
                            <img src="../images/Account.png" class="img-thumbnail" alt="Profile Picture" width="100">
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Username:</th>
                                <td><?php echo htmlspecialchars($member['username']); ?></td>
                            </tr>
                            <tr>
                                <th>Full Name:</th>
                                <td><?php echo htmlspecialchars($member['fullName']); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo htmlspecialchars($member['phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Date Of Birth:</th>
                                <td><?php echo htmlspecialchars($member['dob']); ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?php echo htmlspecialchars($member['address']); ?></td>
                            </tr>
                        </table>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <a href="../Home.php" class="btn btn-primary">Back to Home</a>
                            <a href="EditProfile.php" class="btn btn-secondary">Edit Profile</a>
                            <a href="EditPassword.php" class="btn btn-warning">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
