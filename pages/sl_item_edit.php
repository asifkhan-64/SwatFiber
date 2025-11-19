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
    $getsl_item = mysqli_query($connect, "SELECT * FROM sl_items WHERE sl_id = '$id'");
    $fetch_getsl_item = mysqli_fetch_assoc($getsl_item);

    if (isset($_POST['updateItem'])) {
        $item_name = $_POST['item_name'];
        $item_type = $_POST['item_type'];

        $countItems = mysqli_query($connect, "SELECT COUNT(*)AS countedItems FROM sl_items WHERE item_name = '$item_name' AND item_type = '$item_type'");
        $fetch_countQuery = mysqli_fetch_assoc($countItems);

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];


        if ($fetch_countQuery['countedItems'] == 0) {
            $updateItem = mysqli_query($connect, "UPDATE sl_items SET item_name = '$item_name', item_type = '$item_type', added_by = '$addedBy' WHERE sl_id = '$id'");
            if (!$updateItem) {
                $error = '<div class="alert alert-primary" role="alert">Not Added! Try again!</div>';
            }else {
                header("LOCATION: storeline_items_list.php");
            }
        }else {
            $alreadyAdded = '<div class="alert alert-dark" role="alert">
                                Item Already Added!
                             </div>';
        }
    }


    include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Edit Store/Line Items</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Item Name"  type="text" id="example-text-input" name="item_name" required="" value="<?php echo $fetch_getsl_item['item_name'] ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Select Type</label>
                                <div class="col-sm-4">
                                    <select class="form-control select2" name="item_type" required>
                                        <option></option>
                                        <option value="1" <?php if ($fetch_getsl_item['item_type'] == '1') echo 'selected'; ?>>Line Item</option>
                                        <option value="2" <?php if ($fetch_getsl_item['item_type'] == '2') echo 'selected'; ?>>Store Item</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="updateItem">Update Item</button>
                                </div>
                            </div>
                        </form>
                        <h5 align="center"><?php echo $error ?></h5>
                        <h5 align="center"><?php echo $added ?></h5>
                        <h5 align="center"><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
            </div>
            
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

<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">

$('.select2').select2({
    placeholder: 'Select Option',
    allowClear: true

});

</script>
</body>

</html>