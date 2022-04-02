<?php include('partials/menu.php');?>


<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>

        <br><br>

        <?php 
            if(isset($_SESSION['add'])) //checking whether the session is set or not
            {
                echo $_SESSION['add'];  // Display the session message if set
                unset($_SESSION['add']); //Removing the sessio message
            } 
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Full Name:</td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter Your Name">
                    </td>    
                </tr>
                <tr>
                    <td>Username:</td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username">
                    </td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>


<?php include('partials/footer.php');?>

<?php
//Processing data from form and saving it in database

//check whether the submit button is clicked or not

    if(isset($_POST['submit']))
    {
        //button clicked
        //echo "button clicked";

        //1. Get data from form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);//password encryption with md5 

        //2. SQL Query to save the data into database
        $sql = "INSERT INTO tbl_admin SET
            full_name = '$full_name',
            username = '$username',
            password = '$password'
        ";

        //3. Executing Query and Saving Data into Database
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

        //4. Check whether the (Query is executed) data is inserted or not and display appropriate message
        if($res==TRUE)
        {
            //Data inserted
            //echo "Data inserted";
            //create a session variable to display message
            $_SESSION['add'] = "Admin Added Successfully";
            //redirect page to manage Admin
            header('location:'.SITEURL.'admin/manage-admin.php');
        }
        else
        {
            //Failed to insert Data
            //echo "Failed to insert Data";
            //create a session variable to display message
            $_SESSION['add'] = "Failed To Add Admin";
            //redirect page to add Admin
            header('location:'.SITEURL.'admin/add-admin.php');
        }

    }


?>