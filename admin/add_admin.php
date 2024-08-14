<?php
session_start();
require_once('../db_connection.php');

// Ensure only authenticated users can access this page
if (!isset($_SESSION['sess_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pfp = isset($_FILES['pfp']) && $_FILES['pfp']['size'] > 0 ? file_get_contents($_FILES['pfp']['tmp_name']) : null;

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($phone) || empty($address)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $sql = "SELECT id FROM admin WHERE username = ? OR email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Insert new admin into the database
            if ($pfp) {
                $sql = "INSERT INTO admin (username, email, password, phone, address, pfp) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                $null = NULL;
                $stmt->bind_param("sssssb", $username, $email, $passwordHash, $phone, $address, $null);
                $stmt->send_long_data(5, $pfp);  // Send the BLOB data
            } else {
                $sql = "INSERT INTO admin (username, email, password, phone, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("sssss", $username, $email, $passwordHash, $phone, $address);
            }

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Admin added successfully.";
                header("Location: Admins.php"); // Redirect to a list of admins or another appropriate page
                exit();
            } else {
                $error = "Error adding admin.";
            }
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
    <title>Add Admin</title>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Add Admin</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form name="addAdminForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" >
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" >
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" >
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" >
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" >
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="pfp" class="form-label">Profile Picture</label>
                                <input class="form-control" type="file" id="pfp" name="pfp" accept="image/*">
                            </div>
                            <div id="jsError" class="alert alert-danger d-none"></div>
                            <button type="submit" class="btn btn-primary">Add Admin</button>
                            <a href="Admins.php" class="btn btn-secondary">Back to Admin List</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const username = document.forms["addAdminForm"]["username"].value;
            const email = document.forms["addAdminForm"]["email"].value;
            const password = document.forms["addAdminForm"]["password"].value;
            const confirmPassword = document.forms["addAdminForm"]["confirmPassword"].value;
            const phone = document.forms["addAdminForm"]["phone"].value;
            const address = document.forms["addAdminForm"]["address"].value;

            if (!username || !email || !password || !confirmPassword || !phone || !address) {
                showError("All fields must be filled out");
                return false;
            }

            if (password !== confirmPassword) {
                showError("Passwords do not match");
                return false;
            }

            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
            if (!passwordPattern.test(password)) {
                showError("Password must be at least 8 characters long and include at least one letter, one number, and one special character.");
                return false;
            }

            var phonePattern = /^\d{8,11}$/;
            if (phone != "" && !phonePattern.test(phone)) {
            errorMessage += "Phone number must be between 8 and 11 digits long.\n";
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
