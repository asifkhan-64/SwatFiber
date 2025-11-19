<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';

    $id = $_GET['id'];
    $getUserData = mysqli_query($connect, "SELECT * FROM login_user WHERE id = '$id'");
    $fetUserData = mysqli_fetch_assoc($getUserData);
    
    if (isset($_POST["addUser"])) {
        $name = $_POST['addUser_Name'];
        $userName = 'Default';
        $email = $_POST['addUser_email'];
        $password = $_POST['addUser_password'];
        $role = $_POST['addUser_role'];
        $contact = $_POST['addUser_contact'];
        $area = $_POST['area'];
        $address = $_POST['addUser_address'];

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        

        

        $checkUserTable = mysqli_query($connect, "SELECT COUNT(*)AS countedUsers FROM `login_user` WHERE email = '$email'");
        $fetch_checkUserTable = mysqli_fetch_array($checkUserTable);


        if ($fetch_checkUserTable['countedUsers'] < 1) {
            $profile= $_FILES['fileUpload'];
            $profile_name= $profile['name'];
            $profile_name= preg_replace("/\s+/", "", $profile_name);
            $profileTemp= $profile['tmp_name'];

            $profile_ext=pathinfo($profile_name,PATHINFO_EXTENSION);
            $profile_name=pathinfo($profile_name,PATHINFO_FILENAME);

            $profileNewName = $profile_name.date("miYis").'.'.$profile_ext;

            $saveProfileImage = "../__images/".$profileNewName;

            if (move_uploaded_file($profileTemp, $saveProfileImage)) {
                // echo "Done";
            }else{
                echo "Error Profile Image Uploading";
            }
            

            $CNICFront= $_FILES['fileUploadCnicFront'];
            $CNICFront_name= $CNICFront['name'];
            $CNICFront_name=preg_replace("/\s+/", "", $CNICFront_name);
            $CNICFrontTemp= $CNICFront['tmp_name'];

            $CNICFront_ext=pathinfo($CNICFront_name,PATHINFO_EXTENSION);
            $CNICFront_name=pathinfo($CNICFront_name,PATHINFO_FILENAME);

            $CNICFrontNewName = $CNICFront_name.date("miYis").'.'.$CNICFront_ext;

            $saveCNICFront = "../__images/".$CNICFrontNewName;

            if (move_uploaded_file($CNICFrontTemp, $saveCNICFront)) {
                // echo "Done";
            }else{
                echo "Error CNIC Front Image Uploading";
            }

            $CNICBack= $_FILES['fileUploadCnicBack'];
            $CNICBack_name= $CNICBack['name'];
            $CNICBack_name=preg_replace("/\s+/", "", $CNICBack_name);
            $CNICBackTemp= $CNICBack['tmp_name'];

            $CNICBack_ext=pathinfo($CNICBack_name,PATHINFO_EXTENSION);
            $CNICBack_name=pathinfo($CNICBack_name,PATHINFO_FILENAME);

            $CNICBackNewName = $CNICBack_name.date("miYis").'.'.$CNICBack_ext;

            $saveCNICBack = "../__images/".$CNICBackNewName;
            if (move_uploaded_file($CNICBackTemp, $saveCNICBack)) {
                // echo "Done";
            }else{
                echo "Error CNIC Back Image Uploading";
            }
            


            $createUser = mysqli_query($connect, "INSERT INTO login_user(name, username, email, password, user_role, contact, area_id, address, profile_image, cnic_front, cnic_back, added_by)VALUES('$name', '$userName', '$email', '$password', '$role', '$contact', '$area', '$address', '$profileNewName', '$CNICFrontNewName', '$CNICBackNewName', '$addedBy')");

            if (!$createUser) {
                $userNotAdded = "User not added! Try Again.";
            }else{
                header("LOCATION: users_list.php");
            }
        }else {
            $userAlreadyinDatabase = "<div class=\"alert alert-dark\" role=\"alert\">User Already Exist</div>";
        }
    }

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">User Images</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <form method="POST" enctype="multipart/form-data">

                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <button type="button" class="btn btn-secondary waves-effect">Cancel</button>
                        <button type="submit" name="addUser" class="btn btn-primary waves-effect waves-light">Update User</button>
                    </div>
                </div>
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Profile Image</h4>
                        
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label">Profile Image</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUpload" required="" class="btn-default">
                                </div>
                                <?php
                                $profile =  $fetUserData['profile_image'];
                                if (empty($profile)) {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../assets/sample.png" width="200px" height="200px" style="border-radius: 200px;">
                                    </div>
                                    ';
                                }else {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../__images/'.$fetUserData['profile_image'].'" width="200px" height="200px" style="border-radius: 200px;">
                                    </div>
                                    ';
                                }
                                ?>
                                
                            </div>
                    </div>
                </div>


                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">CNIC Front Image</h4>
                        
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label">CNIC Front</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUploadCnicFront" required="" class="btn-default">
                                </div>
                                <?php
                                $profile =  $fetUserData['cnic_front'];
                                if (empty($profile)) {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../assets/sample.png"  width="200px" height="200px" >
                                    </div>
                                    ';
                                }else {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../__images/'.$fetUserData['cnic_front'].'" width="200px" height="200px" >
                                    </div>
                                    ';
                                }
                                ?>
                                
                            </div>
                    </div>
                </div>

                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">CNIC Back Image</h4>
                        
                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label">CNIC Back</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUploadCnicBack" required="" class="btn-default">
                                </div>
                                <?php
                                $profile =  $fetUserData['cnic_back'];
                                if (empty($profile)) {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../assets/sample.png"  width="200px" height="200px" >
                                    </div>
                                    ';
                                }else {
                                    echo '
                                    <div class="col-sm-6">
                                        <img src="../__images/'.$fetUserData['cnic_back'].'" width="200px" height="200px" >
                                    </div>
                                    ';
                                }
                                ?>
                                
                            </div>
                    </div>
                </div>                           
                        
                </form>
                
                <h3 align="center">
                    <?php echo $userAlreadyinDatabase; ?>
                </h3>
                <h3 align="center">
                    <?php echo $userAdded; ?>
                </h3>
                <h3 align="center">
                    <?php echo $userNotAdded; ?>
                </h3>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container fluid -->
</div> <!-- Page content Wrapper -->
</div> <!-- content -->
<?php include('../_partials/footer.php') ?>

</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
        <?php include('../_partials/jquery.php') ?>

<!-- App js -->
        <?php include('../_partials/app.php') ?>
<script>
$(document).ready(function() {
    $('form').parsley();
});
</script>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">

$('.select2').select2({
    placeholder: 'Select Option',
    allowClear: true

});

$('.area').select2({
    placeholder: 'Select Option',
    allowClear: true

});
</script>

<script src="https://unpkg.com/imask"></script>
<script>
    const phoneInput = document.getElementById('phone-mask');
    const maskOptions = {
        mask: '{92}0000000000'
    };
    const mask = IMask(phoneInput, maskOptions);
</script>
</body>

</html>