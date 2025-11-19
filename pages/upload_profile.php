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

            $id = $_GET['id'];
            

            $getUser = $_SESSION["user"];
            $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
            $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
            $addedBy = $fetch_getUserQuery['id'];


            $DP = $_FILES['fileUpload']['name'];
            if (empty($DP)) {
                $userNotAdded = "<div class='alert alert-primary' role='alert'>Record Not Updated! Please select an image and try again.</div>";
            }else {
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

                $updateQuery = mysqli_query($connect, "UPDATE login_user SET profile_image = '$profileNewName' WHERE id = '$id'");

                if (!$updateQuery) {
                    $userNotAdded = "<div class='alert alert-primary' role='alert'>User not added! Try Again.</div>";
                }else{
                    header("LOCATION: user_view.php?id=".$id."");
                }
            }


                
                


                

            // if (!$createUser) {
            //     $userNotAdded = "User not added! Try Again.";
            // }else{
            //     header("LOCATION: users_list.php");
            // }
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
                        <?php include '../_partials/cancel.php'?>
                        <button type="submit" name="addUser" class="btn btn-primary waves-effect waves-light">Update User</button>
                    </div>
                </div>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <h4 class="mt-0 header-title text-center">Profile Image</h4>
                            </div>
                            
                        </div>

                        <div class="form-group row"> 
                            <?php
                            if (empty($fetUserData['profile_image'])) {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <img src="../assets/sample.png" width="100%" height="auto" > <hr />
                                </div>

                                <div class="col-4">
                                    <input type="file" name="fileUpload" class="form-control" />
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <img src="../__images/'.$fetUserData['profile_image'].' "width="100%" style="border-radius: 10px; box-shadow: 3px 3px 3px 3px #ccc" height="auto" ><hr >
                                </div>

                                <div class="col-4">
                                    <input type="file" name="fileUpload" class="form-control" />
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