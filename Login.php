<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = "localhost";
    $user = "root";
    $passwd = "";
    $database = "ecopacks";
    $con = mysqli_connect($host, $user, $passwd, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check in admin table
    $admin_sql = "SELECT id, email, password FROM admin WHERE email = ?";
    $admin_stmt = mysqli_prepare($con, $admin_sql);
    mysqli_stmt_bind_param($admin_stmt, "s", $email);
    mysqli_stmt_execute($admin_stmt);
    $admin_result = mysqli_stmt_get_result($admin_stmt);

    if ($admin_row = mysqli_fetch_assoc($admin_result)) {
        if (password_verify($password, $admin_row['password'])) {
            $_SESSION['sess_id'] = $admin_row['id'];
            $_SESSION['user_type'] = 'admin';
            header("Location: admin/Admins.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        // Check in member table
        $member_sql = "SELECT id, username, email, password FROM member WHERE email = ?";
        $member_stmt = mysqli_prepare($con, $member_sql);
        mysqli_stmt_bind_param($member_stmt, "s", $email);
        mysqli_stmt_execute($member_stmt);
        $member_result = mysqli_stmt_get_result($member_stmt);

        if ($member_row = mysqli_fetch_assoc($member_result)) {
            if (password_verify($password, $member_row['password'])) {
                $_SESSION['sess_id'] = $member_row['id'];
                $_SESSION['user_type'] = 'member';
                header("Location: Home.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "Invalid email.";
        }
    }

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
    <title>Login</title>
</head>
<body>
    <?php include './components/Navbar.php'?>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success" role="alert">';
        echo $_SESSION['success_message'];
        echo '</div>';
        unset($_SESSION['success_message']);
    }
    ?>
    <div class="container d-flex justify-content-center align-items-center p-4">
        <div class="card p-4 shadow" style="width: 40rem;">
            <h2 class="text-center pb-3 border-bottom">Sign In</h2>
            <?php if(isset($message)) echo "<p class='alert alert-danger'>$message</p>"; ?>
            <form id="loginForm" name="loginForm" action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype='multipart/form-data' class="addform">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" style="background-color: whitesmoke;" id="email" name="email" >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" style="background-color: whitesmoke;" id="password" name="password" >
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="mt-4 text-center">Don't have an account? <a href="Register.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>
