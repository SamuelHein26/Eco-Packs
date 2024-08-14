<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: login.php");
    exit();
}

$sess_id = $_SESSION['sess_id'];

// Fetch current password hash from the database
$sql = "SELECT password FROM member WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $sess_id);
$stmt->execute();
$stmt->bind_result($currentPasswordHash);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Validate form inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        $error = "All fields are required.";
    } elseif (!password_verify($currentPassword, $currentPasswordHash)) {
        $error = "Current password is incorrect.";
    } else {
        $error = "";
        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password in the database
        $sql = "UPDATE member SET password = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $newPasswordHash, $sess_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "Password updated successfully.";
        header("Location: Profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Password</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Change Password</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div id="jsError" class="alert alert-danger d-none"></div>
                        <form name="editPasswordForm" onsubmit="return validateForm()" method="post">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword" >
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" >
                            </div>
                            <div class="mb-3">
                                <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" >
                            </div>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                            <a href="Profile.php" class="btn btn-secondary">Back to Profile</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showError(message) {
            const jsError = document.getElementById("jsError");
            jsError.textContent = message;
            jsError.classList.remove("d-none");
        }

        function validateForm() {
            const newPassword = document.forms["editPasswordForm"]["newPassword"].value;
            const confirmNewPassword = document.forms["editPasswordForm"]["confirmNewPassword"].value;

            if (!newPassword || !confirmNewPassword) {
                showError("All fields must be filled out");
                return false;
            }

            if (newPassword !== confirmNewPassword) {
                showError("New passwords do not match");
                return false;
            }

            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
            if (!passwordPattern.test(newPassword)) {
                showError("Password must be at least 8 characters long and include at least one letter, one number, and one special character.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
