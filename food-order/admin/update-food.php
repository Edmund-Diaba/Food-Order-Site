<?php include('partials/menu.php'); ?>

<?php 
    //check whether id is set or not
    if(isset($_GET['id']))
    {
        //Get the details
        $id = $_GET['id'];
        //sql query to get selected food
        $sql2="SELECT * FROM tbl_food WHERE id=$id";
        //Execute the query
        $res2=mysqli_query($conn, $sql2);

        //Get the value based on query executed
        $row2 = mysqli_fetch_assoc($res2);

        //Get the individual value of selected food
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active']; 
        
    }
    else
    {
        //Redirect to manage food page
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>



    <div class="main-content">
        <div class="wrapper">
            <h1>Update Food</h1>
            <br><br>
    
            <form action="" method="POST" enctype="multipart/form-data">
                <table class="tbl-30">
                    <tr>
                        <td>Title:</td>
                        <td>
                            <input type="text" name="title" value="<?php echo $title; ?>">
                        </td>
                    </tr>
    
                    <tr>
                        <td>Description:</td>
                        <td>
                            <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Price:</td>
                        <td>
                            <input type="number" name="price" value="<?php echo $price; ?>">
                        </td>
                    </tr>
    
                    <tr>
                        <td>Current Image:</td>
                        <td>
                            <?php
                                if($current_image == "")
                                {
                                    //Image not available
                                    echo "<div class='error'>Image Not Available</div>";
                                }
                                else
                                {
                                    //Image available
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                                    <?php
                                }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Select New Image: </td>
                        <td>
                            <input type="file" name="image">
                        </td>
                    </tr>
    
                    <tr>
                        <td>Category:</td>
                        <td>
                            <select name="category">
                                
                                <?php
                                    //Querry to get active categories 
                                    $sql = "SELECT * FROM tbl_category WHERE active='Yes'";

                                    //Execute the query
                                    $res = mysqli_query($conn, $sql);
                                    //count rows
                                    $count = mysqli_num_rows($res);

                                    //check whether category available or not
                                    if($count>0)
                                    {
                                        //Category Available
                                        while($row=mysqli_fetch_assoc($res))
                                        {
                                            $category_title = $row['title'];
                                            $category_id = $row['id'];

                                            //echo "<option value='$category_id'>$category_title</option>";

                                            ?>
                                            <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        //Category not available
                                        echo "<option value='0'>Category Not Available</option>";
                                    }
                                ?>

                                
                            </select>
                        </td>
                    </tr>
    
                    <tr>
                        <td>Featured:</td>
                        <td>
                            <input <?php if($featured=="Yes"){echo "checked"; } ?> type="radio" name="featured" value="yes">Yes
                            <input <?php if($featured=="No"){echo "checked"; } ?> type="radio" name="featured" value="No">No
                        </td>
                    </tr>
    
                    <tr>
                        <td>Active:</td>
                        <td>
                            <input <?php if($active=="Yes"){echo "checked"; } ?> type="radio" name="active" value="yes">Yes
                            <input <?php if($active=="No"){echo "checked"; } ?> type="radio" name="active" value="No">No
                        </td>
                    </tr>
    
                    <tr>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                            <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                        </td>    
                    </tr>
                </table>    
            </form>
 
            <?php
                if(isset($_POST['submit']))
                {
                    //echo "button clicked";
                    //1. Get all  the details from the form
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $current_image = $_POST['current_image'];
                    $category = $_POST['category'];
                    
                    $featured = $_POST['featured'];
                    $active = $_POST['active'];


                    //2. Upload the image if selected
                    //Check whether the upload button is clicked or not
                    if(isset($_FILES['image']['name']))
                    {
                        //upload button clicked
                        $image_name = $_FILES['image']['name']; // new image name

                        //Check whether the file is available or not
                        if($image_name != "")
                        {
                            //image is available
                            //A. Uploading New Image

                            //Rename the image
                            $ext = (explode('.', $image_name)); //Gets the extention of the image

                            $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; // This will rename image

                            //Get the source path and the destination path
                            $src_path = $_FILES['image']['tmp_name']; //source path
                            $dest_path = "../images/food/".$image_name; //destination path

                            //Upload the image
                            $upload = move_uploaded_file($src_path, $dest_path);

                            //Check whether the image is uploaded or not
                            if($upload==false)
                            {
                                //Failed to upload
                                $_SESSION['upload'] = "<div class='error'>Failed To Upload New Image</div>";
                                //Redirect to manage food page
                                header('location:'.SITEURL.'admin/manage-food.php');
                                //Stop the process
                                die();
                            }

                            //3. Remove image if new image is uploaded 
                            //B. Remove Current Image If available
                            if($current_image != "")
                            {
                                //Current image available
                                // Remove the image
                                $remove_path = "../images/food/".$current_image;

                                $remove = unlink($remove_path);

                                //Check whether the image is removed or not
                                if($remove==false)
                                {
                                    //Failed to remove current image
                                    $_SESSION['remove-failed'] = "<div class='error'>Failed To Remove Current Image</div>";
                                    //Redirect to manage food
                                    header('location:'.SITEURL.'admin/manage-food.php');
                                    //Stop the process
                                    die();
                                }
                            }
                            
                        }
                        else
                        {
                            $image_name = $current_image; // Default image when image not selected    
                        }
                    }
                    else
                    {
                        $image_name = $current_image; // Default image when button is not clicked
                    }
                    
                    //4. Update the food in database
                    $sql3 = "UPDATE tbl_food SET
                        title = '$title',
                        description = '$description',
                        price = $price,
                        image_name = '$image_name',
                        category_id = '$category',
                        featured = '$featured',
                        active = '$active'
                        WHERE id = $id
                    ";

                    //Execute the sql query
                    $res3 = mysqli_query($conn, $sql3);
                    
                    //Redirect to manage food with session message
                    //Check whether the query is executed
                    if($res3==true)
                    {
                        //Query executed and food updated
                        $_SESSION['update'] = "<div class='success'>Food Updated Successfully</div>";
                        //redirect to manage food page
                        header('location:'.SITEURL.'admin/manage-food.php');
                        
                    }
                    else
                    {
                        //Failed to execute query
                        $_SESSION['update'] = "<div class='error'>Failed To Update Food</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                    }
                    

                }
            ?>

        </div>
    </div>

<?php include('partials/footer.php'); ?>