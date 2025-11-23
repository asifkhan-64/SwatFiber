<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';
    
    if (isset($_POST["addStock"])) {
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

        $insertStock = mysqli_query($connect, "INSERT INTO line_stock(item_id, item_qty, date_of_purchase, price, item_description, item_length, added_by)VALUES('$item_id', '$item_qty', '$date_of_purchase', '$price', '$description', '$item_length', '$addedBy')");  
        if ($insertStock) {
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty + '$length' WHERE sl_id = '$item_id'");
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
                
                <h5 class="page-title">Purchase Stock (Line Based)</h5>
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
                                        $optionsCategory.= '<option value='.$rowCat['sl_id'].'>'.$rowCat['item_name'].'</option>';
                                      }
                                    $optionsCategory.= "</select>";
                                echo $optionsCategory;
                                ?>
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Price" name="price" required id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Qty</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Item Qty" required name="item_qty" id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Length Per Pack</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Length" name="item_length" required id="example-text-input">
                                </div>

                                
                            </div>

                            <div class="form-group row">

                                <label for="example-text-input" class="col-sm-2 col-form-label">Date of Purchase</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="date" placeholder="Date of Purchase" name="date_of_purchase" required id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea name="description" placeholder="Description" class="form-control"  id="description" required></textarea>
                                </div>

                            </div>


                            <hr>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" name="addStock" class="btn btn-primary waves-effect waves-light">Add Stock</button>
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