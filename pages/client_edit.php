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
    $getClientData = mysqli_query($connect, "SELECT * FROM client_tbl WHERE client_id = '$id'");
    $fetch_getClientData = mysqli_fetch_assoc($getClientData);
    
    if (isset($_POST["editClient"])) {
        $name = $_POST['addUser_Name'];
        $fatherName = $_POST['addUser_FatherName'];
        $userName = $_POST['addUser_UserID'];
        $area = $_POST['area'];
        $addUser_address = $_POST['addUser_address'];
        $addUser_UserID = $_POST['addUser_UserID'];
        $package_id = $_POST['package_id'];
        $addUser_WireLength = $_POST['addUser_WireLength'];
        $ins_id = $_POST['ins_id'];
        $addUser_contact = $_POST['addUser_contact'];
        $addUser_cnic = $_POST['addUser_cnic'];
        $line_st_id = $_POST['line_st_id'];

        $userStatus = $_POST['userStatus'];
        $user_dues = $_POST['user_dues'];
        

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];


        $selectLine = mysqli_query($connect, "SELECT * FROM `line_stock` WHERE line_st_id = '$line_st_id'");
        $rowItems = mysqli_fetch_assoc($selectLine);
        $itemId = $rowItems['item_id'];

        $checkPreviousWireLength = mysqli_query($connect, "SELECT * FROM `client_tbl` WHERE client_id = '$id'");
        $fetch_checkPreviousWireLength = mysqli_fetch_assoc($checkPreviousWireLength);
        $previousWireLength = $fetch_checkPreviousWireLength['wire_length'];

        $findDifferenceQty = $previousWireLength - $addUser_WireLength;
        if ($findDifferenceQty < 0) {
            $remQty = $fetch_checkPreviousWireLength['rem_qty'] - abs($findDifferenceQty);
            $DifferenceQty = abs($findDifferenceQty);
            $updateLineStock = mysqli_query($connect, "UPDATE `sl_items` SET `rem_qty` = rem_qty - '$DifferenceQty' WHERE sl_id = '$itemId' ");
        } else {
            $remQty = $fetch_checkPreviousWireLength['rem_qty'] + abs($findDifferenceQty);
            $updatesl_itemsQty = mysqli_query($connect, "UPDATE sl_items SET rem_qty = rem_qty + '$findDifferenceQty' WHERE sl_id = '$itemId'");
        }

        $getClientData = mysqli_query($connect, "SELECT client_tbl.*, installation_type.* FROM `client_tbl`
        INNER JOIN installation_type ON installation_type.ins_id = client_tbl.ins_id
        WHERE client_tbl.client_id = '$id';");
        $fetch_getClientData = mysqli_fetch_assoc($getClientData);
        $insType = $fetch_getClientData['ins_type'];
        $insID = $fetch_getClientData['ins_id'];

        $selectsl_items = mysqli_query($connect, "SELECT * FROM `sl_items` WHERE item_name LIKE '%Router%' OR item_name LIKE '%Modem%'");
        $fetch_sl_items = mysqli_fetch_assoc($selectsl_items);
        $itemName = $fetch_sl_items['item_name'];
        $itemSLId = $fetch_sl_items['sl_id'];

        if ($insID === $ins_id) {
        }else {
            if (str_contains($insType, "New")) {
                $updateStock = mysqli_query($connect, "UPDATE `sl_items` SET `old_qty` = old_qty - '1', `rem_qty` = rem_qty + '1' WHERE sl_id =  '$itemSLId'");
            }else {
                $updateStock = mysqli_query($connect, "UPDATE `sl_items` SET `rem_qty` = rem_qty - '1', `old_qty` = old_qty + '1' WHERE sl_id =  '$itemSLId'");
            }
        }


        $updateClient = mysqli_query($connect, "UPDATE `client_tbl` SET `name` = '$name', `father_name` = '$fatherName', `user_id` = '$userName', `area_id` = '$area', `address` = '$addUser_address', `package_id` = '$package_id', `wire_length` = '$addUser_WireLength', `ins_id` = '$ins_id', `contact` = '$addUser_contact', `cnic_no` = '$addUser_cnic', `added_by` = '$addedBy', `client_status` = '$userStatus', `old_remaining` = '$user_dues' WHERE client_id = '$id'");

        if (!$updateClient) {
            $userNotAdded = "Client not Updated! Try Again.";
        }else{
            header("LOCATION: client_list.php");
        }
        
    }

    include('../_partials/header.php');

    date_default_timezone_set('Asia/Karachi');
    $currentDate = date('Y-m-d');

    $client_id = $_GET["id"];
    // $client_id = $_GET["customer"];
    $getClientData= mysqli_query($connect, "SELECT * FROM client_tbl WHERE client_id = '$client_id'");
    $fetch = mysqli_fetch_assoc($getClientData);
    $newBillingDate = $fetch['new_billing_date'];
    $lastPaymentDate = $fetch['last_paid_date'];

    $old_dues = $fetch['old_remaining'];

    $interval = date_create($currentDate)->diff(date_create($newBillingDate));
    $daysDifference = $interval->days;
    
    
   $billingMonths = round($daysDifference / 30, 2);

   if ($billingMonths >= 1) {
       if (is_float($billingMonths)) {
        $billingMonths = number_format($billingMonths, 2, '.', '');
        $explode = explode('.', $billingMonths);
        $monthsExploded = $explode[1];
    }else {
        $explode = explode('.', $billingMonths);
        $monthsExploded = $explode[1];
    }
    

    $getDOC = $fetch['doc'];

    if ($monthsExploded < 80) {
        $months = floor($billingMonths);
        // $dateObject = new DateTime($getDOC);

        // $dateObject->modify('+1 month');

        // $dateObject->format('Y-m-d');
    }else {
        $months = ceil($billingMonths);
        
        // $dateObject = new DateTime($getDOC);

        // $dateObject->modify('+2 month');

        // $dateObject->format('Y-m-d');

    }
    
    $getPackageDataClient = mysqli_query($connect, "SELECT * FROM client_payments WHERE client_id = '$client_id'");
    $packageData = mysqli_fetch_assoc($getPackageDataClient);



    $amount = $packageData['package_amount'] * $months;
    $netAmount = $amount + $old_dues;
    }else {
       $netAmount =$old_dues;
   }
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Edit Client</h5>
            </div>
        </div>
        <!-- end row -->
         
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Client Details</h4>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="Name" name="addUser_Name" id="example-text-input" value="<?php echo $fetch_getClientData['name']; ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Father Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="Father Name" name="addUser_FatherName" id="example-text-input" value="<?php echo $fetch_getClientData['father_name']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Area</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM area");
                                        $optionsCategory = '<select class="form-control area" name="area" required="" style="width:100%">';
                                        // echo '<option></option>';
                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                            $selected = ($rowCat['id'] == $fetch_getClientData['area_id']) ? 'selected' : '';
                                            $optionsCategory.= '<option value='.$rowCat['id'].' '.$selected.'>'.$rowCat['area_name'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>


                                <label for="example-email-input" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" name="addUser_address" placeholder="Address" id="example-email-input" value="<?php echo $fetch_getClientData['address']; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">User ID</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="User ID" name="addUser_UserID" id="example-text-input" value="<?php echo $fetch_getClientData['user_id']; ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Package</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM package_list");
                                        $optionsCategory = '<select class="form-control package" name="package_id" required="" style="width:100%">';
                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                            $selected = ($rowCat['p_id'] == $fetch_getClientData['package_id']) ? 'selected' : '';
                                            $optionsCategory.= '<option value='.$rowCat['p_id'].' '.$selected.'>'.$rowCat['package_name'].' - Price: '.$rowCat['package_price'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Wire</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectLine = mysqli_query($connect, "SELECT line_stock.*, sl_items.item_name FROM `line_stock`
                                        INNER JOIN sl_items ON sl_items.sl_id = line_stock.item_id");
                                        $optionsCategory = '<select class="form-control ins" name="line_st_id" required="" style="width:100%">';
                                        while ($rowItems = mysqli_fetch_assoc($selectLine)) {
                                            if ($rowItems['line_st_id'] == $fetch_getClientData['line_st_id']) {
                                                $optionsCategory.= '<option value='.$rowItems['line_st_id'].' selected>'.$rowItems['item_name'].'</option>';
                                            }else{
                                            $optionsCategory.= '<option value='.$rowItems['line_st_id'].'>'.$rowItems['item_name'].'</option>';
                                            }
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Wire Length</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Wire Length"  name="addUser_WireLength" id="example-text-input" value="<?php echo $fetch_getClientData['wire_length']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-4">
                                    <input type="text" maxlength = "12" class="form-control contact" data-inputmask="'mask': '0399-99999999'" placeholder="03XX-XXXXXXX" name="addUser_contact" value="<?php echo $fetch_getClientData['contact']; ?>" required>
                                </div>
                            
                                <label for="example-email-input" class="col-sm-2 col-form-label">CNIC</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control cnic" data-inputmask="'mask': '99999-9999999-9'" name="addUser_cnic" value="<?php echo $fetch_getClientData['cnic_no']; ?>" placeholder="XXXXX-XXXXXXX-X"  required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Installation Type</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM installation_type");
                                        $optionsCategory = '<select class="form-control ins" name="ins_id" required="" style="width:100%">';
                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                            $selected = ($rowCat['ins_id'] == $fetch_getClientData['ins_id']) ? 'selected' : '';
                                            $optionsCategory.= '<option value='.$rowCat['ins_id'].' '.$selected.'>'.$rowCat['ins_type'].' - Price: '.$rowCat['ins_price'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>

                                <label class="col-sm-2 col-form-label">User Status</label>
                                    <div class="col-sm-4">
                                            <?php
                                            if ($fetch_getClientData['client_status'] == 1) {
                                                echo '
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" checked="" value="1" name="userStatus">Active
                                                </label>
                                            </div>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" value="0" name="userStatus">De-Active
                                                </label>
                                            </div>';
                                            }elseif ($fetch_getClientData['client_status'] == 0) {
                                            echo '
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" value="1" name="userStatus">Active
                                                </label>
                                            </div>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" checked="" value="0" name="userStatus">De-Active
                                                </label>
                                            </div>';
                                            }
                                            ?>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Dues</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control contact"  name="user_dues" value="<?php echo $netAmount; ?>" readonly required>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include '../_partials/cancel.php'?>

                                    <!-- <button type="button" class="btn btn-secondary waves-effect">Cancel</button> -->
                                    <button type="submit" name="editClient" class="btn btn-primary waves-effect waves-light">Edit Client</button>
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

$('.area').select2({
    placeholder: 'Select Option',
    allowClear: true

});

$('.package').select2({
    placeholder: 'Select Option',
    allowClear: true

});

$('.ins').select2({
    placeholder: 'Select Option',
    allowClear: true

});
</script>

<script src="../assets/inputmask.js"></script>

<script>
    $(".contact").inputmask();
    $(".cnic").inputmask();
</script>

</body>

</html>