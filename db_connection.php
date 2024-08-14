<?php
$host = "localhost";
$user = "root";
$passwd = "";
$database = "ecopacks";
$con = mysqli_connect($host, $user, $passwd, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>