<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect post data
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $terms = isset($_POST['terms']);

    include 'db_connection.php';

    // Check if the username already exists
    $userCheckQuery = "SELECT username FROM member WHERE username = ?";
    $userCheckStmt = mysqli_prepare($con, $userCheckQuery);
    mysqli_stmt_bind_param($userCheckStmt, "s", $username);
    mysqli_stmt_execute($userCheckStmt);
    mysqli_stmt_store_result($userCheckStmt);

    // Check if the email already exists
    $emailCheckQuery = "SELECT email FROM member WHERE email = ?";
    $emailCheckStmt = mysqli_prepare($con, $emailCheckQuery);
    mysqli_stmt_bind_param($emailCheckStmt, "s", $email);   
    mysqli_stmt_execute($emailCheckStmt);
    mysqli_stmt_store_result($emailCheckStmt);

    $errorFlag = false;
    if (mysqli_stmt_num_rows($userCheckStmt) > 0) {
        echo "<p class='alert alert-danger'>Username already exists.</p>";
        $errorFlag = true;
    }
    if (mysqli_stmt_num_rows($emailCheckStmt) > 0) {
        echo "<p class='alert alert-danger'>Email already exists.</p>";
        $errorFlag = true;
    }

    if (!$errorFlag) {
        // Handle profile picture upload
        $pfp_blob = null;
        if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['pfp']['tmp_name'];
            $file_type = $_FILES['pfp']['type'];
            $file_size = $_FILES['pfp']['size'];

            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            if (in_array($file_type, $allowed_types)) {
                $fp = fopen($file_tmp, 'rb');
                $pfp_blob = fread($fp, $file_size);
                fclose($fp);
            } else {
                echo "<p class='alert alert-danger'>Invalid file type. Please upload a JPEG, PNG, or GIF image.</p>";
                $errorFlag = true;
            }
        }

        if (!$errorFlag) {
            if ($pfp_blob !== null) {
                $sql = "INSERT INTO member (username, fullName, email, password, dob, phone, address, pfp) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                if ($stmt) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ssssssss", $username, $fullname, $email, $hashed_password, $dob, $phone, $address, $pfp_blob);
                }
            } else {
                $sql = "INSERT INTO member (username, fullName, email, password, dob, phone, address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                if ($stmt) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sssssss", $username, $fullname, $email, $hashed_password, $dob, $phone, $address);
                }
            }
            if ($stmt) {
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['success_message'] = 'Registration successful! Please log in.';
                    header("Location: Login.php");
                    exit();
                } else {
                    echo "<p class='alert alert-danger'>Error: " . mysqli_stmt_error($stmt) . "</p>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<p class='alert alert-danger'>Error preparing statement: " . mysqli_error($con) . "</p>";
            }
        }
    }

    // Close statements
    mysqli_stmt_close($userCheckStmt);
    mysqli_stmt_close($emailCheckStmt);
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="styles.css">
    <title>Register</title>
</head>
<body>
    <?php include './components/Navbar.php'?>
    <div class="container d-flex justify-content-center align-items-center p-4">
        <div class="card p-4 shadow" style="width: 40rem;">
            <h2 class="text-center pb-3 border-bottom">Sign Up</h2>
            <form id="signupForm" name="signupForm" action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype='multipart/form-data' class="addform" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" style="background-color: whitesmoke;" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" style="background-color: whitesmoke;" id="fullname" name="fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address:</label>
                    <input type="email" class="form-control" style="background-color: whitesmoke;" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" style="background-color: whitesmoke;" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" style="background-color: whitesmoke;" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth:</label>
                    <input type="date" class="form-control" style="background-color: whitesmoke;" id="dob" name="dob" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number:</label>
                    <input type="text" class="form-control" style="background-color: whitesmoke;" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" style="background-color: whitesmoke;" rows="5" ></textarea>
                </div>
                <div class="mb-3">
                    <label for="pfp" class="form-label">Profile Picture (optional):</label>
                    <input type="file" class="form-control" style="background-color: whitesmoke;" id="pfp" name="pfp" >
                </div>
                <div class="mb-3">
                    <input type="checkbox" id="terms" name="terms" required> I agree to the Terms of Service and Privacy Policy.
                </div>
                <div id="message" class="mb-3" style="color: red;"></div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                <p class="mt-4 text-center">Already have an account? <a href="Login.php">Sign In</a></p>
            </form>
        </div>
    </div>

    <script>
    function validateForm() {
        var username = document.forms["signupForm"]["username"].value;
        var fullname = document.forms["signupForm"]["fullname"].value;
        var email = document.forms["signupForm"]["email"].value;
        var password = document.forms["signupForm"]["password"].value;
        var confirm_password = document.forms["signupForm"]["confirm_password"].value;
        var dob = document.forms["signupForm"]["dob"].value;
        var phone = document.forms["signupForm"]["phone"].value;
        var address = document.forms["signupForm"]["address"].value;
        var terms = document.forms["signupForm"]["terms"].checked;

        var errorMessage = "";

        // Check if any field is empty
        if (username == "" || fullname == "" || email == "" || password == "" || confirm_password == "" || dob == "" || phone == "" || address == "" || !terms) {
            errorMessage += "All fields are required.\n";
        }

        // Password validation
        if (password != "" && (password.length < 8 || !/\d/.test(password) || !/[a-zA-Z]/.test(password) || !/^[a-zA-Z0-9!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]+$/.test(password))) {
            errorMessage += "Password must be at least 8 characters long and contain letters, numbers, and special characters.\n";
        }

        // Confirm password validation
        if (confirm_password != "" && password != confirm_password) {
            errorMessage += "Passwords do not match.\n";
        }

        
        var phonePattern = /^\d{8,11}$/;
        if (phone != "" && !phonePattern.test(phone)) {
        errorMessage += "Phone number must be between 8 and 11 digits long.\n";
        }

        // Display error message if any
        if (errorMessage !== "") {
            document.getElementById("message").innerHTML = errorMessage;
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
    </script>

</body>
</html>
