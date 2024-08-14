<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['sess_id'])) {
    header("Location: login.php");
    exit();
}

$sess_id = $_SESSION['sess_id'];

// Fetch current member data
$sql = "SELECT username, fullName, email, dob, phone, address, pfp FROM member WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $sess_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pfp = isset($_FILES['pfp']['tmp_name']) && !empty($_FILES['pfp']['tmp_name']) ? file_get_contents($_FILES['pfp']['tmp_name']) : null;

    // Check if username or email already exist
    $sql = "SELECT id FROM member WHERE (username = ? OR email = ?) AND id != ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $sess_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username or email already exists.";
    } else {
        // Update member data
        if ($pfp) {
            $sql = "UPDATE member SET username = ?, fullName = ?, email = ?, dob = ?, phone = ?, address = ?, pfp = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssssi", $username, $fullName, $email, $dob, $phone, $address, $pfp, $sess_id);
        } else {
            $sql = "UPDATE member SET username = ?, fullName = ?, email = ?, dob = ?, phone = ?, address = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssssi", $username, $fullName, $email, $dob, $phone, $address, $sess_id);
        }
        $stmt->execute();
        $_SESSION['message'] = "Profile updated successfully.";
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
    <title>Edit Profile</title>
    
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form name="editProfileForm" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                            <div class="mb-3 text-center">
                                <?php if ($member['pfp']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($member['pfp']); ?>" class="img-thumbnail" alt="Profile Picture" width="150">
                                <?php else: ?>
                                    <img src="../images/Account.png" class="img-thumbnail" alt="Profile Picture" width="100">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($member['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($member['fullName']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($member['dob']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($member['address']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="pfp" class="form-label">Profile Picture</label>
                                <input class="form-control" type="file" id="pfp" name="pfp" accept="image/*">
                            </div>
                            <div id="message" class="mb-3" style="color: red;"></div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="Profile.php" class="btn btn-secondary">Back to Profile</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
        const username = document.forms["editProfileForm"]["username"].value;
        const fullName = document.forms["editProfileForm"]["fullName"].value;
        const email = document.forms["editProfileForm"]["email"].value;
        const dob = document.forms["editProfileForm"]["dob"].value;
        const phone = document.forms["editProfileForm"]["phone"].value;
        const address = document.forms["editProfileForm"]["address"].value;

        let errorMessage = "";

        if (!username || !fullName || !email || !dob || !phone || !address) {
            errorMessage += "All fields must be filled out.\n";
        }

        var phonePattern = /^\d{8,11}$/;
        if (phone != "" && !phonePattern.test(phone)) {
            errorMessage += "Phone number must be between 8 and 11 digits long.\n";
        }

        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            errorMessage += "Invalid email format.\n"; 
        }
        
        if (errorMessage !== "") {
            document.getElementById("message").innerHTML = errorMessage;
            return false; // Prevent form submission
        }

        return true; 

        }
    </script>
</body>
</html>
