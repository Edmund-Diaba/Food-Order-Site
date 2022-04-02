<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>
        
        <?php
           if(isset($_SESSION['upload']))
           {
               echo $_SESSION['upload'];
               unset($_SESSION['upload']);
           }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Title Of The Food">
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description Of The Food"></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>
                
                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">

                            <?php
                                //Create PHP code to display categories from Database
                                //1. Create SQL to get all active categories from Database
                                $sql="SELECT * FROM tbl_category WHERE active='Yes'";
                        
                                //Executing query
                                $res = mysqli_query($conn, $sql);

                                //Count rows to check whether we have categories or not
                                $count = mysqli_num_rows($res);

                                //If count is greater than Zero, then we have categories else we do not have categories
                                if($count>0)
                                {
                                    //We have Categories
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        //get the details of categories
                                        $id=$row['id'];
                                        $title=$row['title'];

                                        ?>

                                        <option value="<?php echo $id; ?>"><?php echo $title;?></option>

                                        <?php
                                    }
                                }
                                else
                                {
                                    //We Do not have categories
                                    ?>

                                    <option value="0">No Category Found</option>

                                    <?php
                                }                            
                               
                                //2. Display on Dropdown
                            ?>

                            
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured:</td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes
                        <input type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="submit" class="btn-secondary"> 
                    </td>
                </tr>


            </table>
        </form>

        <?php
        
            //Check whether the button is clicked or not
            if(isset($_POST['submit']))
            {
                //Add the food in database
                //echo "clicked";

                //1. Get the data from form
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];

                //Check whether the radio buttons for featured and active are checked or not
                if(isset($_POST['featured']))
                {
                    $featured = $_POST['featured'];
                }
                else
                {
                    $featured = "No"; //Setting the default value
                }

                if(isset($_POST['active']))
                {
                    $active = $_POST['active'];
                }
                else
                {
                    $active = "No"; // Setting  dafault value
                }

                //2. Upload the image if selected
                    //Check whether the select image is clicked or not and upload the image only if the image is selected
                    if(isset($_FILES['image']['name']))
                    {
                        //Get the details of the selected image
                        $image_name = $_FILES['image']['name'];
                        
                        //Check whether the image is selected or not and upload image o ly if selected
                        if($image_name != "")
                        {
                            //Image Is selected
                            //A. Rename the image
                            //Get the extension of the selected image (jpg, png, gif, jpeg, etc.) "Edmund-Diaba.jpg"
                            $ext = end(explode('.', $image_name));

                            //Create new name for image
                            $image_name = "Food-name-".rand(0000,9999).".".$ext; //New name may be "Food-Name_564,png"
                        
                            //B. Upload the image
                            //Get the source path and the destination path

                            //Source path is the current location of the image
                            $src = $_FILES['image']['tmp_name'];

                            //Destination path for the image to be uploaded
                            $dst = "../images/food/".$image_name;

                            //Finally upload the food image
                            $upload = move_uploaded_file($src, $dst);

                            //check whether image uploaded or not
                            if($upload==false)
                            {
                                //Failed to upload the image
                                //Redirect to add food page with error message
                                $_SESSION['upload'] = "<div class='error'>Failed To Upload Image</div>";
                                header('location:'.SITEURL.'admin/add-food.php');
                                //Stop the process
                                die();
                            }
                        }
                    }
                    else
                    {
                        $image_name = ""; //Setting Default value as blank
                    }

                //3. Insert into database
                //Create a sql query to add food
                //For numerical values we donot need to pass value through quotes '' but for string values it is compulsory to add quotes ''
                $sql2 = "INSERT INTO tbl_food SET
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = $category,
                    featured = '$featured',
                    active = '$active'   
                ";

                //Execute the query
                $res2 = mysqli_query($conn, $sql2);
                //Check whether data inserted or not
                //4. Redirect with message to manage food page
                
                if($res2 == true)
                {
                    //Data Inserted Successfully
                    $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //Failed To Insert Data
                    $_SESSION['add'] = "<div class='error'>Failed To Add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

                
            }
        
        ?>


    </div>
</div>

<?php include('partials/footer.php'); ?>