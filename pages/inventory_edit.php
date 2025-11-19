<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';

    $inventoryId = $_GET['id'];
    $getInventoryDetails = mysqli_query($connect, "SELECT * FROM inventory_details WHERE inventory_id = '$inventoryId'");
    $fetch_getInventoryDetails = mysqli_fetch_assoc($getInventoryDetails);
    
    if (isset($_POST["updateInventory"])) {
        $item_name = $_POST['item_name'];
        $item_qty = $_POST['item_qty'];
        $date_of_purchase = $_POST['date_of_purchase'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        $updateQuery = mysqli_query($connect, "UPDATE inventory_details SET item_name = '$item_name', item_qty = '$item_qty', date_of_purchase = '$date_of_purchase', price = '$price', description = '$description', added_by = '$addedBy' WHERE inventory_id = '$inventoryId'");
        if ($updateQuery) {   
            header("LOCATION:inventory_list.php");
        } else {
            $userNotAdded = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert"></div>
            <strong>Inventory Not Updated. Something Went Wrong!</strong>';
        }
    }

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Edit Inventory</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Inventory Details</h4>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="Item Name" value="<?php echo $fetch_getInventoryDetails['item_name']; ?>" name="item_name" id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Qty</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" value="<?php echo $fetch_getInventoryDetails['item_qty']; ?>" placeholder="Item Qty"  required name="item_qty" id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Date of Purchase</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="date" placeholder="Date of Purchase" value="<?php echo $fetch_getInventoryDetails['date_of_purchase']; ?>" name="date_of_purchase" required id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Price" value="<?php echo $fetch_getInventoryDetails['price']; ?>" name="price" required id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" placeholder="Description" class="form-control"  id="description" required><?php echo $fetch_getInventoryDetails['description']; ?></textarea>
                                </div>

                            </div>


                            <hr>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include '../_partials/cancel.php'?>
                                    <button type="submit" name="updateInventory" class="btn btn-primary waves-effect waves-light">Update Inventory</button>
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