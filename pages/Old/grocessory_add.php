<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$alreadyAdded = '';
$added = '';
$error = '';

if (isset($_POST['addGrocessory'])) {
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemDate = $_POST['itemDate'];
    $supplierId = $_POST['supplierId'];
    $paymentBy = $_POST['paymentBy'];


    $insertGrocessary = mysqli_query($connect, "INSERT INTO grocessory (item_name, item_price, item_date, supplier_id, payment_by) VALUES ('$itemName', '$itemPrice', '$itemDate', '$supplierId', '$paymentBy')");
    if (!$insertGrocessary) {
        $error = 'Not Updated! Try again!';
    } else {
        header("LOCATION: grocessory_list.php");
    }
}



include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Grocessory</h5>
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
                                    <input class="form-control" placeholder="Item Name" type="text" id="example-text-input" name="itemName" required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Item Price" type="number" id="example-text-input" step="any" name="itemPrice" required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Date of Purchase</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Date of Purchase" type="date" id="example-text-input" name="itemDate" required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Supplier</label>
                                <div class="col-sm-4">
                                    <?php
                                    $getSuppliers = mysqli_query($connect, "SELECT * FROM supplier");

                                    echo '<select class="form-control supplier" name="supplierId" required>
                                    <option></option>';
                                    while ($row = mysqli_fetch_assoc($getSuppliers)) {
                                        echo '<option value="' . $row['supplier_id'] . '">' . $row['supplier_name'] . '</option>';
                                    }

                                    echo '</select>';
                                    ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Payment By</label>
                                <div class="col-sm-4">
                                    <?php
                                    

                                    echo '<select class="form-control payment" name="paymentBy" required>
                                    <option></option>';
                                    echo '<option value="Cash">Cash</option>';
                                    echo '<option value="Card">Card</option>';

                                    echo '</select>';
                                    ?>
                                </div>
                            </div>
                            
                            <hr>

                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="addGrocessory">Add Grocessory</button>
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
    $('.supplier').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
    $('.payment').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
</script>
</body>

</html>