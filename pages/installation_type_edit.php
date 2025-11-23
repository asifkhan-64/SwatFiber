<?php
    include('../_stream/config.php');
    session_start();
        if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    $alreadyAdded = '';
    $added = '';
    $error= '';

    $id = $_GET['id'];
    $retData = mysqli_query($connect, "SELECT * FROM installation_type WHERE ins_id = '$id'");
    $fetch_retData = mysqli_fetch_assoc($retData);

    if (isset($_POST['updateIns'])) {
        $id = $_POST['id'];
        $ins_price = $_POST['ins_price'];

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $updated_by = $fetch_getUserQuery['id'];

        $updateQuery = mysqli_query($connect, "UPDATE installation_type SET ins_price = '$ins_price', updated_by = '$updated_by' WHERE ins_id = '$id'");
        
        if (!$updateQuery) {
            $error = 'Not Added! Try again!';
        }else {
            header("LOCATION: installation_type_list.php");
        }
        
    }


    include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Edit Installation Type</h5>
            </div>
        </div>
        <!-- end row -->
         
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Installation</label>
                                <div class="col-sm-4">
                                    <input class="form-control" value="<?php echo $fetch_retData['ins_type'] ?>" readonly placeholder="Installation Type" type="text" value="" id="example-text-input"  name="nameArea"  required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" value="<?php echo $fetch_retData['ins_price'] ?>" placeholder="Installation Price" type="number" value="" id="example-text-input"  name="ins_price"  required="">
                                </div>
                            </div>
                            <hr />
                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="updateIns">Update Installation Type</button>
                                </div>
                            </div>
                        </form>
                        <h5><?php echo $error ?></h5>
                        <h5><?php echo $added ?></h5>
                        <h5><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
                
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
<!-- Required datatable js -->
<?php include('../_partials/datatable.php') ?>
<!-- Datatable init js -->
<?php include('../_partials/datatableInit.php') ?>
<!-- Buttons examples -->
<?php include('../_partials/buttons.php') ?>
<!-- App js -->
<?php include('../_partials/app.php') ?>
<!-- Responsive examples -->
<?php include('../_partials/responsive.php') ?>
<!-- Sweet-Alert  -->
<?php include('../_partials/sweetalert.php') ?>
</body>

</html>