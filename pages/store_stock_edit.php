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
    $getStock = mysqli_query($connect, "SELECT * FROM store_stock WHERE store_st_id = '$id'");
    $fetch_getStock = mysqli_fetch_assoc($getStock);
    
    if (isset($_POST["updateStock"])) {
        $item_id = $_POST['item_id'];
        $item_qty = $_POST['item_qty'];
        $date_of_purchase = $_POST['date_of_purchase'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $item_condition = $_POST['item_condition'];

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        $checkQuantitySLItems = mysqli_query($connect, "SELECT * FROM sl_items WHERE sl_id = '$item_id'");
        $fetch_checkQuantitySLItems = mysqli_fetch_assoc($checkQuantitySLItems);
        $findDifferenceQty = $item_qty - $fetch_getStock['item_qty'];

        if ($findDifferenceQty < 0) {
            $remQty = $fetch_checkQuantitySLItems['rem_qty'] - abs($findDifferenceQty);
            $DifferenceQty = abs($findDifferenceQty);
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty - '$DifferenceQty' WHERE sl_id = '$item_id'");
        } else {
            $remQty = $fetch_checkQuantitySLItems['rem_qty'] + abs($findDifferenceQty);
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty + '$findDifferenceQty' WHERE sl_id = '$item_id'");
        }
        

        $updateStock = mysqli_query($connect, "UPDATE store_stock SET item_id = '$item_id', item_qty = '$item_qty', date_of_purchase = '$date_of_purchase', price = '$price', item_description = '$description', item_condition = '$item_condition', added_by = '$addedBy' WHERE store_st_id = '$id'");  
        if ($updateStock) {
            header("LOCATION:store_stock_list.php");
        } else {
            $userNotAdded = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert"></div>
            <strong>Stock Not Updated. Something Went Wrong!</strong>';
        }
    }

    include('../_partials/header.php') ;
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Edit Purchased Stock (Qty Based)</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Stock Details</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Name</label>
                                <div class="col-sm-4">
                                <?php
                                $selectExpenseCat = mysqli_query($connect, "SELECT * FROM sl_items WHERE item_type = '2'");
                                    $optionsCategory = '<select class="form-control item" name="item_id" required="" style="width:100%">';
                                      while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                        if ($fetch_getStock['item_id'] === $rowCat['sl_id']) {
                                            $optionsCategory.= '<option value='.$rowCat['sl_id'].' selected>'.$rowCat['item_name'].'</option>';
                                        }else {
                                            $optionsCategory.= '<option value='.$rowCat['sl_id'].'>'.$rowCat['item_name'].'</option>';
                                        }
                                      }
                                    $optionsCategory.= "</select>";
                                echo $optionsCategory;
                                ?>
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Qty</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Item Qty" required name="item_qty" id="example-text-input" value="<?php echo $fetch_getStock['item_qty']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Price" name="price" required id="example-text-input" value="<?php echo $fetch_getStock['price']; ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Date of Purchase</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="date" placeholder="Date of Purchase" name="date_of_purchase" required id="example-text-input" value="<?php echo $fetch_getStock['date_of_purchase']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                <select class="form-control condition" name="item_condition" required>
                                        <option></option>
                                        <option value="Default" <?php if ($fetch_getStock['item_condition'] === 'Default') echo 'selected'; ?>>Default</option>
                                        <option value="New" <?php if ($fetch_getStock['item_condition'] === 'New') echo 'selected'; ?>>    New</option>
                                        <option value="Old" <?php if ($fetch_getStock['item_condition'] === 'Old') echo 'selected'; ?>>    Old</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" placeholder="Description" class="form-control"  id="description" required><?php echo $fetch_getStock['item_description']; ?></textarea>
                                </div>

                            </div>


                            <hr>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include('../_partials/cancel.php') ?>
                                    <!-- <button type="button" class="btn btn-secondary waves-effect">Cancel</button> -->
                                    <button type="submit" name="updateStock" class="btn btn-primary waves-effect waves-light">Update Stock</button>
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

$('.item').select2({
    placeholder: 'Select Option',
    allowClear: true

});

$('.condition').select2({
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