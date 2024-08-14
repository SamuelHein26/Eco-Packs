<?php
// Default values
$username = '';
$profileImage = '../images/Account.png'; // Default profile image

// Check if admin is logged in
if (isset($_SESSION['sess_id']) && $_SESSION['user_type'] == 'admin') {
    $admin_id = $_SESSION['sess_id'];
    
    // Fetch admin's username and profile picture from the database
    $sql = "SELECT username, pfp FROM admin WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        if (!empty($row['pfp'])) {
            $profileImage = 'data:image/jpeg;base64,' . base64_encode($row['pfp']);
        }
    }

    mysqli_stmt_close($stmt);
}
?>

<div style="background-color: cadetblue; width: 100%; height: 70px;">
    <div class="d-flex justify-content-end align-items-center p-2">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
            <!-- Display admin's username and profile picture -->
            <div>
                <span style="color: white;" class="pe-3">Welcome, <?php echo $username; ?></span>
                <a href="Profile.php">
                <img src="<?php echo $profileImage; ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
            </div>
        <?php else: ?>
            <!-- If admin is not logged in, display login button -->
            <a href="admin/Login.php" style="color: white;">Admin Login</a>
        <?php endif; ?>
    </div>
</div>
