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
        $name = $_POST['editName'];
        $email = $_POST['editEmail'];
        $role = $_POST['editRole'];
        $password = $_POST['editPassword'];
        $userStatus = $_POST['userStatus'];
        $contact = $_POST['edit_contact'];
        $emailMsg = $_POST['emailMsg'];

        

            $editUserQuery = mysqli_query($connect, "UPDATE login_user SET name = '$name', password = '$password', user_role = '$role', status = '$userStatus' WHERE id = '$id'");
            if (!$editUserQuery) {
                $userNotUpdated = "Failed to update. Try Again!";
            }else {
                $description = "Dear ".$name.". Your Credentails. Email:".$emailMsg." and Password: ".$password.". Thank You!";
                
                $insertMsg = mysqli_query($connect, "INSERT INTO message_tbl
                    (from_device, to_device, message_body, status)
                    VALUES
                    ('1', '$contact', '$description', '1')");
                header("LOCATION:users_list.php");
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
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" name="editName" type="text" value="<?php echo $fetch_selectUser['name'] ?>" id="example-text-input">
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">

                                            

                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <input class="form-control" name="editUserName" value="<?php echo $fetch_selectUser['username'] ?>" type="hidden" id="example-text-input">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="example-email-input" class="col-sm-2 col-form-label">Contact</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="number" value="<?php echo $fetch_selectUser['contact'] ?>" name="edit_contact" placeholder="Contact" id="example-email-input">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" name="editEmail" value="<?php echo $fetch_selectUser['email'] ?>" type="email" placeholder="Name@example.com" id="example-email-input" disabled>
                                                </div>
                                            </div>
                                            <input type="hidden" name="emailMsg" value="<?php echo $fetch_selectUser['email'] ?>">

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Role</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control select2" name="editRole">
                                                        <?php
                                                        if ($fetch_selectUser['user_role'] == '1') {
                                                        echo '
                                                        <option value="1" selected>Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>
                                                        ';
                                                        }elseif ($fetch_selectUser['user_role'] == '2') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2" selected>Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '3') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3" selected>Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '4') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4" selected>Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '5') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5" selected>Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '6') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6" selected>Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '7') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7" selected>Ground Floor Second Counter</option>
                                                        <option value="10">Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '10') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10" selected>Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '11') {
                                                            echo '
                                                            <option value="1">Administrator</option>
                                                            <option value="2">Manager</option>
                                                            <option value="3">Counter Screen</option>
                                                            <option value="4">Laboratory</option>
                                                            <option value="5">Pharmacy</option>
                                                            <option value="6">Ground Floor Counter</option>
                                                            <option value="7">Ground Floor Second Counter</option>
                                                            <option value="10" >Medical Officer</option>
                                                            <option value="11" selected>Lab Result</option>
                                                            <option value="12">Lab Receiptionist</option>';
                                                        }elseif ($fetch_selectUser['user_role'] == '12') {
                                                        echo '
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Manager</option>
                                                        <option value="3">Counter Screen</option>
                                                        <option value="4">Laboratory</option>
                                                        <option value="5">Pharmacy</option>
                                                        <option value="6">Ground Floor Counter</option>
                                                        <option value="7">Ground Floor Second Counter</option>
                                                        <option value="10" >Medical Officer</option>
                                                        <option value="11">Lab Result</option>
                                                        <option value="12" selected>Lab Receiptionist</option>';
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Password</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="editPassword" value="<?php echo $fetch_selectUser['password'] ?>" id="pass2" class="form-control" required placeholder="Password"/>
                                                </div>
                                            </div>
                                                

                                            <div class="form-group row">
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
                                                   <!-- <button type="button" class="btn btn-secondary waves-effect">Cancel</button> -->
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
</script>
    </body>
</html>