<?php
    //include constants page
    include('../config/constants.php');

    //echo "Delete Food Page";

    if(isset($_GET['id']) && isset($_GET['image_name'])) //Either use '&&' or 'AND'
    {
        //Process to delete
        //echo "Process to delete";

        //1. Get ID and image name
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //2. Remove the image if available
        //Check whether the image is available or not and delete only if available
        if($image_name !="")
        {
            //It has image and need to be removed from folder
            //Get the image path
            $path = "../images/food/".$image_name;

            //REmove image file from folder
            $remove = unlink($path);

            //Check whether the image is removed or   not
            if($remove==false)
            {
                //Failed to remove image
                $_SESSION['upload'] = "<div class='error'>Failed to remove image</div>";
                //Redirect to manage food
                header('location:'.SITEURL.'admin/manage-food.php');
                //Stop the process of deleting food
                die();
            }
        }


        //3. Delete food from database
        $sql = "DELETE FROM tbl_food WHERE id=$id";
        //Execute the query
        $res = mysqli_query($conn, $sql);

        //Check whether the query is executed successfully and set the session message respectively
        //4. Redirect to manage food with session message
        if($res==true)
        {
            //Query executed successfully and food deleted
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //Failed to execute query and food not deleted
            $_SESSION['delete'] = "<div class='error'>Failed To Delete Food</div>";
            header('location:'.SITEURL.'admin/manage-food.php');
        }

        
    }
    else
    {
        //Redirect to manage food page with error message
        //echo "Redirect";
        $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access</div>";
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>