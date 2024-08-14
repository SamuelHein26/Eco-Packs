<?php
   session_start();
   if(isset($_SESSION["sess_id"])){
       session_destroy();
       header('location: Login.php');
   }
   else{
       header('location: Login.php');
   }
?>
