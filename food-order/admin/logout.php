<?php
    //Include constants.php
    include('../config/constants.php');

    //1. Destroy the SESSION
    SESSION_destroy(); //Unsets $_SESSION['user']

    //2. Redirect to login page
    header('location:'.SITEURL.'admin/login.php');
?>