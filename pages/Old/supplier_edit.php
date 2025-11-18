<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$alreadyAdded = '';
$added = '';
$error = '';

$supplierId = $_GET['id'];
$selectSupplier = mysqli_query($connect, "SELECT * FROM supplier WHERE supplier_id = '$supplierId'");
$fetchSupplier = mysqli_fetch_assoc($selectSupplier);   

if (isset($_POST['updateSupplier'])) {
    $supplierName = $_POST['supplierName'];

    $countQuery = mysqli_query($connect, "SELECT COUNT(*)AS countedSuppliers FROM supplier WHERE supplier_name = '$supplierName'");
    $fetch_countQuery = mysqli_fetch_assoc($countQuery);


    if ($fetch_countQuery['countedSuppliers'] == 0) {
        $updateQuery = mysqli_query($connect, "UPDATE supplier SET supplier_name = '$supplierName' WHERE supplier_id = '$supplierId'");
        if (!$updateQuery) {
            $error = 'Not Updated! Try again!';
        } else {
            header("LOCATION:supplier_list.php");
        }
    } else {
        $alreadyAdded = '<div class="alert alert-dark" role="alert">
                                Supplier Already Added!
                             </div>';
    }
}


include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Supplier Edit</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="Supplier Name" type="text" value="<?php echo $fetchSupplier['supplier_name']; ?>" id="example-text-input" name="supplierName" required="">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="updateSupplier">Update</button>
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
</body>

</html>