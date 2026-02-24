<?php 
    include('../_stream/config.php');

    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    $id = $_GET['id'];

    $selectUser = mysqli_query($connect, "SELECT * FROM login_user WHERE id = '$id'");
    $fetch_selectUser = mysqli_fetch_assoc($selectUser);

    $userNotUpdated = '';

    if (isset($_POST['updateUser'])) {
        $id = $_POST['id'];
        $name = $_POST['addUser_Name'];
        $userName = 'Default';
        $password = $_POST['addUser_password'];
        $role = $_POST['addUser_role'];
        $contact = $_POST['addUser_contact'];
        $area = $_POST['area'];
        $address = $_POST['addUser_address'];
        
        $userStatus = $_POST['userStatus'];
        
        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $updatedBy = $fetch_getUserQuery['id'];

        $editUserQuery = mysqli_query($connect, "UPDATE login_user SET name = '$name', username = '$userName', password = '$password', user_role = '$role', contact = '$contact', area_id = '$area', address = '$address', status = '$userStatus', updated_by = '$updatedBy' WHERE id = '$id'");
        if (!$editUserQuery) {
            $userNotUpdated = "Failed to update. Try Again!";
        }else {
            header("LOCATION: users_list.php");
        }
        }
    // }

    include('../_partials/header.php');
?>
                <!-- Top Bar End -->

                    <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="float-right page-breadcrumb">
                                    </div>
                                    <h5 class="page-title">Edit User</h5>
                                </div>
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <!-- <h4 class="mt-0 header-title">Heading</h4> -->
                                            <!-- <p class="text-muted m-b-30 font-14">Example Text</p> -->
            								<form method="POST">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                           
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" type="text" value="<?php echo $fetch_selectUser['name']; ?>" placeholder="Name" name="addUser_Name" id="example-text-input">
                                                </div>
                                            
                                                <label for="example-email-input" class="col-sm-2 col-form-label">Contact</label>
                                                <div class="col-sm-4">
                                                    <input type="tel" id="phone-mask" class="form-control" name="addUser_contact" value="<?php echo $fetch_selectUser['contact']; ?>"  placeholder="923*********"  required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Area</label>
                                                <div class="col-sm-4">
                                                    <?php
                                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM area");
                                                        $optionsCategory = '<select class="form-control area" name="area" required="" style="width:100%">';
                                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                                            if ($fetch_selectUser['area_id'] == $rowCat['id']) {
                                                                $optionsCategory.= '<option value='.$rowCat['id'].' selected>'.$rowCat['area_name'].'</option>';
                                                            }else {
                                                                $optionsCategory.= '<option value='.$rowCat['id'].'>'.$rowCat['area_name'].'</option>';
                                                            }
                                                            
                                                        }
                                                        $optionsCategory.= "</select>";
                                                    echo $optionsCategory;
                                                    ?>
                                                </div>


                                                <label for="example-email-input" class="col-sm-2 col-form-label">Address</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" type="text" name="addUser_address" value="<?php echo $fetch_selectUser['address']; ?>" placeholder="Address" id="example-email-input" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" type="email" name="addUser_email" readonly value="<?php echo $fetch_selectUser['email']; ?>" placeholder="Name@example.com" id="example-email-input" required>
                                                </div>
                                            
                                                <label class="col-sm-2 col-form-label">Password</label>
                                                <div class="col-sm-4">
                                                    <input type="password" id="pass2" name="addUser_password" value="<?php echo $fetch_selectUser['password']; ?>" class="form-control" required placeholder="Password" required />
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Role</label>
                                                <div class="col-sm-4">
                                                    <select class="form-control select2" name="addUser_role" required>
                                                        <?php
                                                            if ($fetch_selectUser['user_role'] == 1) {
                                                                echo '
                                                                <option value="1" selected>Admin</option>
                                                                <option value="2">Technician</option>
                                                                <option value="3">Accounts</option>';
                                                            }elseif ($fetch_selectUser['user_role'] == 2) {
                                                                echo '
                                                                    <option value="1" >Admin</option>
                                                                    <option value="2" selected>Technician</option>
                                                                    <option value="3">Accounts</option>';
                                                            }elseif ($fetch_selectUser['user_role'] == 3) {
                                                                echo '
                                                                    <option value="1" >Admin</option>
                                                                    <option value="2">Technician</option>
                                                                    <option value="3" selected>Accounts</option>';
                                                            }
                                                        ?>
                                                        
                                                    </select>
                                                </div>

                                                <label class="col-sm-2 col-form-label">User Status</label>
                                                <div class="col-sm-4">
                                                        <?php
                                                        if ($fetch_selectUser['status'] == 1) {
                                                            echo '
                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" checked="" value="1" name="userStatus">Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" value="0" name="userStatus">Inactive
                                                            </label>
                                                        </div>';
                                                        }elseif ($fetch_selectUser['status'] == 0) {
                                                        echo '
                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" value="1" name="userStatus">Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input" checked="" value="0" name="userStatus">Inactive
                                                            </label>
                                                        </div>';
                                                        }
                                                        ?>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                    <?php include '../_partials/cancel.php'; ?>
                                                    <button type="submit" name="updateUser" class="btn btn-primary waves-effect waves-light">Update User</button>
                                                </div>
                                            </div>







                                        </form>
                                        </div>
                                    </div>
                                        <h3 align="center"><?php echo $userNotUpdated ?></h3>
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
    </body>
</html>