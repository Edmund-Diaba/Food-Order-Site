<?php
    //Include constants file here
    include('../config/constants.php');

    //echo "Delete Page";
    //Check whether the id and the image_name is set or not
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        //Get the value and delete
        //echo "Get Value and Delete";
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //Remove the physical image file if it is available
        if($image_name != "")
        {
            //Image is Available. So remove it
            $path = "../images/category/".$image_name;
            //Remove the image
            $remove = unlink($path);
            //If failed to remove image, then add error message and stop th process
            if($remove ==false)
            {
                //Set the SESSION message
                $_SESSION['remove'] = "<div class='error'>Failed To Remove Category Image</div>";

                //Redirect to manage category page
                header('location:'.SITEURL.'admin/manage-category.php');

                //Stop the process
                die();   
            }
        }

        //Delete Data from Database
        //SQL query to delete data from databse
        $sql = "DELETE FROM tbl_category WHERE id=$id";

        //Execute the query
        $res = mysqli_query($conn, $sql);

        //Check whether the data is deleted from the database or not
        if($res==true)
        {
            //Set success message and redirect
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
            //Redirect to manage category
            header('location:'.SITEURL.'admin/manage-category.php');
        }
        else
        {
            //Set error message and redirect
            $_SESSION['delete'] = "<div class='error>Failed To Delete Category.</div>";
            //Redirect to manage category
            header('location:'.SITEURL.'admin/manage-category.php');

        }

    }    
    else
    {
        //redirect to manage category page
        header('location:'.SITEURL.'admin/manage-category.php');
    }

?>