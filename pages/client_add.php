<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';
    
    if (isset($_POST["addClient"])) {
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
        

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        $selectsl_items = mysqli_query($connect, "SELECT * FROM `sl_items` WHERE item_name LIKE '%Router%' OR item_name LIKE '%Modem%'");
        $fetch_sl_items = mysqli_fetch_assoc($selectsl_items);
        $itemName = $fetch_sl_items['item_name'];
        $itemSLId = $fetch_sl_items['sl_id'];
        

        

        $checkUserTable = mysqli_query($connect, "SELECT COUNT(*)AS countedUsers FROM `client_tbl` WHERE contact = '$addUser_contact' AND cnic_no = '$addUser_cnic'");
        $fetch_checkUserTable = mysqli_fetch_array($checkUserTable);


        if ($fetch_checkUserTable['countedUsers'] < 1) {
            $profile= $_FILES['fileUpload'];
            $profile_name= $profile['name'];
            $profile_name= preg_replace("/\s+/", "", $profile_name);
            $profileTemp= $profile['tmp_name'];

            $profile_ext=pathinfo($profile_name,PATHINFO_EXTENSION);
            $profile_name=pathinfo($profile_name,PATHINFO_FILENAME);

            $profileNewName = $profile_name.date("miYis").'.'.$profile_ext;

            $saveProfileImage = "../__images/".$profileNewName;

            if (move_uploaded_file($profileTemp, $saveProfileImage)) {
            }else{
                echo "Error Profile Image Uploading";
            }
            

            $CNICFront= $_FILES['fileUploadCnicFront'];
            $CNICFront_name= $CNICFront['name'];
            $CNICFront_name=preg_replace("/\s+/", "", $CNICFront_name);
            $CNICFrontTemp= $CNICFront['tmp_name'];

            $CNICFront_ext=pathinfo($CNICFront_name,PATHINFO_EXTENSION);
            $CNICFront_name=pathinfo($CNICFront_name,PATHINFO_FILENAME);

            $CNICFrontNewName = $CNICFront_name.date("miYis").'.'.$CNICFront_ext;

            $saveCNICFront = "../__images/".$CNICFrontNewName;

            if (move_uploaded_file($CNICFrontTemp, $saveCNICFront)) {
            }else{
                echo "Error CNIC Front Image Uploading";
            }

            $CNICBack= $_FILES['fileUploadCnicBack'];
            $CNICBack_name= $CNICBack['name'];
            $CNICBack_name=preg_replace("/\s+/", "", $CNICBack_name);
            $CNICBackTemp= $CNICBack['tmp_name'];

            $CNICBack_ext=pathinfo($CNICBack_name,PATHINFO_EXTENSION);
            $CNICBack_name=pathinfo($CNICBack_name,PATHINFO_FILENAME);

            $CNICBackNewName = $CNICBack_name.date("miYis").'.'.$CNICBack_ext;

            $saveCNICBack = "../__images/".$CNICBackNewName;
            if (move_uploaded_file($CNICBackTemp, $saveCNICBack)) {
            }else{
                echo "Error CNIC Back Image Uploading";
            }
            


            $creatClient = mysqli_query($connect, "INSERT INTO `client_tbl`(`name`, `father_name`, `user_id`, `area_id`, `address`, `package_id`, `wire_length`, `ins_id`, `contact`, `cnic_no`, `cnic_front`, `cnic_back`, `modem_image`, `added_by`, `line_st_id`) VALUES ('$name', '$fatherName', '$userName', '$area', '$addUser_address', '$package_id', '$addUser_WireLength', '$ins_id', '$addUser_contact', '$addUser_cnic', '$CNICFrontNewName', '$CNICBackNewName', '$profileNewName', '$addedBy', '$line_st_id')");


            if ($creatClient) {
                $selectLine = mysqli_query($connect, "SELECT * FROM `line_stock` WHERE line_st_id = '$line_st_id'");
                $rowItems = mysqli_fetch_assoc($selectLine);
                $itemId = $rowItems['item_id'];


                $updateLineStock = mysqli_query($connect, "UPDATE `sl_items` SET `rem_qty` = rem_qty - '$addUser_WireLength' WHERE sl_id = '$itemId' ");

                $selectExpenseCat = mysqli_query($connect, "SELECT * FROM installation_type WHERE ins_id = '$ins_id'");
                $rowCat = mysqli_fetch_assoc($selectExpenseCat);
                $insType = $rowCat['ins_type'];
                    
                if (str_contains($insType, "New")) {
                    $updateStock = mysqli_query($connect, "UPDATE `sl_items` SET `rem_qty` = rem_qty - '1' WHERE sl_id =  '$itemSLId'");
                }else {
                    $updateStock = mysqli_query($connect, "UPDATE `sl_items` SET `old_qty` = old_qty - '1' WHERE sl_id =  '$itemSLId'");
                }

                
                if (!$creatClient) {
                    $userNotAdded = "Client not added! Try Again.";
                }else{
                    header("LOCATION: client_list.php");
                }
            } 
        }else {
            $userAlreadyinDatabase = "<div class=\"alert alert-dark\" role=\"alert\">Client Already Exist</div>";
        }
    }

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Add Client</h5>
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
                                    <input class="form-control" type="text" placeholder="Name" name="addUser_Name" id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Father Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="Father Name" name="addUser_FatherName" id="example-text-input">
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
                                            $optionsCategory.= '<option value='.$rowCat['id'].'>'.$rowCat['area_name'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>


                                <label for="example-email-input" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" name="addUser_address" placeholder="Address" id="example-email-input" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">User ID</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" placeholder="User ID" name="addUser_UserID" id="example-text-input">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Package</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM package_list");
                                        $optionsCategory = '<select class="form-control package" name="package_id" required="" style="width:100%">';
                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                            $optionsCategory.= '<option value='.$rowCat['p_id'].'>'.$rowCat['package_name'].' - Price: '.$rowCat['package_price'].'</option>';
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
                                            $optionsCategory.= '<option value='.$rowItems['line_st_id'].'>'.$rowItems['item_name'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Wire Length</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" placeholder="Wire Length" name="addUser_WireLength" id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-email-input" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-4">
                                    <input type="text" maxlength = "12" class="form-control contact" data-inputmask="'mask': '0399-99999999'" placeholder="03XX-XXXXXXX" name="addUser_contact"  required>
                                </div>
                            
                                <label for="example-email-input" class="col-sm-2 col-form-label">CNIC</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control cnic" data-inputmask="'mask': '99999-9999999-9'" name="addUser_cnic"  placeholder="XXXXX-XXXXXXX-X"  required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">CNIC Front</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUploadCnicFront" required="" class="btn-default">
                                </div>

                                <label class="col-sm-2 col-form-label">CNIC Back</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUploadCnicBack" required="" class="btn-default">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Modem (Lat/Lan)</label>
                                <div class="col-sm-4">
                                    <input type="file" name="fileUpload" required="" class="btn-default">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Installation Type</label>
                                <div class="col-sm-4">
                                    <?php
                                        $selectExpenseCat = mysqli_query($connect, "SELECT * FROM installation_type");
                                        $optionsCategory = '<select class="form-control ins" name="ins_id" required="" style="width:100%">';
                                        while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                            $optionsCategory.= '<option value='.$rowCat['ins_id'].'>'.$rowCat['ins_type'].' - Price: '.$rowCat['ins_price'].'</option>';
                                        }
                                        $optionsCategory.= "</select>";
                                    echo $optionsCategory;
                                    ?>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label for="example-password-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include '../_partials/cancel.php'?>

                                    <!-- <button type="button" class="btn btn-secondary waves-effect">Cancel</button> -->
                                    <button type="submit" name="addClient" class="btn btn-primary waves-effect waves-light">Add Client</button>
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