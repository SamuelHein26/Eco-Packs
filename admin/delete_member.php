<?php
session_start();
require_once('../db_connection.php');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page or display an error message
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $member_id = mysqli_real_escape_string($con, $_GET['id']);

    $sql = "DELETE FROM member WHERE id = '$member_id'";
    if (mysqli_query($con, $sql)) {
        $_SESSION['success_message'] = "Member deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error: " . mysqli_error($con);
    }
} else {
    $_SESSION['error_message'] = "Error: Member ID not provided.";
}

header("Location: Members.php");
exit();
?>
