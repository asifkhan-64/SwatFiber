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
    $getStock = mysqli_query($connect, "SELECT * FROM line_stock WHERE line_st_id = '$id'");
    $fetch_getStock = mysqli_fetch_assoc($getStock);


    if (isset($_POST["updateStock"])) {
        $item_id = $_POST['item_id'];
        $item_qty = $_POST['item_qty'];
        $date_of_purchase = $_POST['date_of_purchase'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $item_length = $_POST['item_length'];

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        $length = $item_qty * $item_length;

        $getStockToUpdate = mysqli_query($connect, "SELECT * FROM line_stock WHERE line_st_id = '$id'");
        $fetch_getStockToUpdate = mysqli_fetch_assoc($getStockToUpdate);

        $wireLength = $fetch_getStockToUpdate['item_length'];

        if ($item_length > $wireLength) {
            $lengthDifference = $wireLength - $item_length;
            $totalLengthDifference = $lengthDifference * $item_qty;
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty - '$totalLengthDifference' WHERE sl_id = '$item_id'");

        } else {
            $lengthDifference = $item_length - $wireLength;
            $totalLengthDifference = $lengthDifference * $item_qty;
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty + '$totalLengthDifference' WHERE sl_id = '$item_id'");

        }

        $updateStock = mysqli_query($connect, "UPDATE line_stock SET item_id = '$item_id', item_qty = '$item_qty', date_of_purchase = '$date_of_purchase', price = '$price', item_description = '$description', item_length = '$item_length', added_by = '$addedBy' WHERE line_st_id = '$id'");  
        
        if ($updateStock) {
            header("LOCATION:line_stock_list.php");
        } else {
            $userNotAdded = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert"></div>
            <strong>Line Stock Not Added. Something Went Wrong!</strong>';
        }
    }

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Edit Purchased Stock (Line Based)</h5>
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
                                $selectExpenseCat = mysqli_query($connect, "SELECT * FROM sl_items WHERE item_type = '1'");
                                    $optionsCategory = '<select class="form-control item" name="item_id" required="" style="width:100%">';
                                      while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                        $selected = ($rowCat['sl_id'] == $fetch_getStock['item_id']) ? 'selected' : '';
                                        $optionsCategory.= '<option value='.$rowCat['sl_id'].' '.$selected.'>'.$rowCat['item_name'].'</option>';
                                      }
                                    $optionsCategory.= "</select>";
                                echo $optionsCategory;
                                ?>
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" value="<?php echo $fetch_getStock['price']; ?>" placeholder="Price" name="price" required id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Qty</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" value="<?php echo $fetch_getStock['item_qty']; ?>" placeholder="Item Qty" required name="item_qty" id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Length Per Pack</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" value="<?php echo $fetch_getStock['item_length']; ?>" placeholder="Length" name="item_length" required id="example-text-input">
                                </div>

                                
                            </div>

                            <div class="form-group row">

                                <label for="example-text-input" class="col-sm-2 col-form-label">Date of Purchase</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="date" value="<?php echo $fetch_getStock['date_of_purchase']; ?>" placeholder="Date of Purchase" name="date_of_purchase" required id="example-text-input">
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