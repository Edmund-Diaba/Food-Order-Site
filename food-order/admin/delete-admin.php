<?php

    // include constants.php file here
    include('../config/constants.php');

    // 1. Get the ID of Admin t be deleted
    $id = $_GET['id'];

    // 2. Create SQL Query to delete Admin
    $sql = "DELETE FROM tbl_admin WHERE id=$id";

    //Execute the query
    $res =mysqli_query($conn, $sql);

    //Check whether the query executed successully or not
    if($res==true)
    {
        //Query executed successfully and admin deleted
       //echo "Admin Deleted";
       //create session variable to display message
       $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully</div>";
       //Redirect to manage admin page
       header('location:'.SITEURL.'admin/manage-admin.php');
    }
    else
    {
        //Failed to delete admin
        //echo "Failed to delete admin";
        $_SESSION['delete'] = "<div class='error'>Failed To Delete Admin. Try Again Later.</div>";
        //Redirect to manage admin page
       header('location:'.SITEURL.'admin/manage-admin.php');

    }

    // 3. Redirect to manage admin page with message (Success/Error)
?>