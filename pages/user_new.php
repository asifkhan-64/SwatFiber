<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';
    
    if (isset($_POST["addUser"])) {
        $name = $_POST['addUser_Name'];
        $userName = 'Default';
        $email = $_POST['addUser_email'];
        $password = $_POST['addUser_password'];
        $role = $_POST['addUser_role'];
        $contact = $_POST['addUser_contact'];

        $checkUserTable = mysqli_query($connect, "SELECT COUNT(*)AS countedUsers FROM `login_user` WHERE email = '$email'");
        $fetch_checkUserTable = mysqli_fetch_array($checkUserTable);


        if ($fetch_checkUserTable['countedUsers'] < 1) {
            $createUser = mysqli_query($connect, "INSERT INTO login_user(name, username, email, password, user_role, contact)VALUES('$name', '$userName', '$email', '$password', '$role', '$contact')");

            if (!$createUser) {
                $userNotAdded = "User not added! Try Again.";
            }else{
                $userAdded = "User Added!";
                $description = "Dear ".$name.". You can access SMC WebApp using the following credentials. Email:".$email." and Password: ".$password.". Thank You!";
                
                $insertMsg = mysqli_query($connect, "INSERT INTO message_tbl
                    (from_device, to_device, message_body, status)
                    VALUES
                    ('1', '$contact', '$description', '1')");
                header("LOCATION:users_list.php");
            }
        }else {
            $userAlreadyinDatabase = "User Already Exist";
        }
    }

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Add New User</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Users Details</h4>
                        
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" placeholder="Name" name="addUser_Name" id="example-text-input">
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="addUser_userName" placeholder="User Name" id="example-text-input">
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" name="addUser_contact" placeholder="Contact" id="example-email-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="email" name="addUser_email" placeholder="Name@example.com" id="example-email-input">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" name="addUser_role">
                                        <option value="1">Administrator</option>
                                        <option value="2">Manager</option>
                                        <option value="3">Counter Screen</option>
                                        <option value="4">Laboratory</option>
                                        <option value="5">Pharmacy</option>
                                        <option value="6">Ground Floor Counter</option>
                                        <option value="7">Ground Floor Second Counter</option>
                                        <option value="10">Medical Officer</option>
                                        <option value="11">Lab Result</option>
                                        <option value="12">Lab Receptionist</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" id="pass2" name="addUser_password" class="form-control" required placeholder="Password" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <!-- <button type="button" class="btn btn-secondary waves-effect">Cancel</button> -->
                                    <button type="submit" name="addUser" class="btn btn-primary waves-effect waves-light">Create User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
</script>
</body>

</html>