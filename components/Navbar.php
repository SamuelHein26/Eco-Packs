<?php
require_once('db_connection.php');

$profileImage = 'images/Account.png'; // Default profile image

// Check if session variables are set
if (isset($_SESSION['sess_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member') {
    $member_id = $_SESSION['sess_id'];
    $sql = "SELECT pfp FROM member WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['pfp'])) {
            $profileImage = 'data:image/jpeg;base64,' . base64_encode($row['pfp']);
        }
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

</head>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!--logo-->
        <a class="navbar-brand" href="#">
            <img class="logo" src="images/Logo.png" alt="Eco-packs">
        </a>
        <!--SideBar-->
        <div class="sidebar offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                    <img class="logo" src="images/Logo.png" alt="Eco-packs">
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 " aria-current="page" href="Home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="About.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="Shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="CartPage.php">Cart</a>
                    </li>
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
                    <li>
                        <a class="nav-link mx-lg-2" href="Orders.php">Your Orders</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center gap-3">
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
                <a href="member/Profile.php">
                    <!-- Display profile picture -->
                    <img src="<?php echo $profileImage; ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                </a>
                <a href="logout.php" class="btn btn-secondary rounded-3">Logout</a>
            <?php else: ?>
                <a href="Login.php" class="login-button text-decoration-none px-3 py-1 rounded-4">Login</a>
            <?php endif; ?>
            <!-- Toggle Button -->
            <button class="navbar-toggler pe-0 shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>

<script>
  const navbarToggler = document.querySelector('.navbar-toggler');
  const offcanvasNavbar = document.getElementById('offcanvasNavbar');
  const closeSidebarButton = document.querySelector('.offcanvas-header .btn-close'); 

  navbarToggler.addEventListener('click', () => {
    offcanvasNavbar.classList.toggle('show');
  });

  closeSidebarButton.addEventListener('click', () => {
    offcanvasNavbar.classList.remove('show');
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
      offcanvasNavbar.classList.remove('show');
    }
  });
</script>
