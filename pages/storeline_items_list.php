<?php
    include('../_stream/config.php');
    session_start();
        if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    $alreadyAdded = '';
    $added = '';
    $error= '';

    if (isset($_POST['addItem'])) {
        $item_name = $_POST['item_name'];
        $item_type = $_POST['item_type'];

        $countItems = mysqli_query($connect, "SELECT COUNT(*)AS countedItems FROM sl_items WHERE item_name = '$item_name' AND item_type = '$item_type'");
        $fetch_countQuery = mysqli_fetch_assoc($countItems);

        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];


        if ($fetch_countQuery['countedItems'] == 0) {
            $insertItem = mysqli_query($connect, "INSERT INTO sl_items(item_name, item_type, added_by)VALUES('$item_name', '$item_type', '$addedBy')");
            if (!$insertItem) {
                $error = '<div class="alert alert-primary" role="alert">Not Added! Try again!</div>';
            }else {
                $added = '
                <div class="alert alert-primary" role="alert">
                    Item Added!
                </div>';
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
                <h5 class="page-title">Store/Line Items</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <?php if($fetchUserRole['user_role'] == 4){}else { ?>
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Item Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Item Name" type="text" id="example-text-input" name="item_name" required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Select Type</label>
                                <div class="col-sm-4">
                                    <select class="form-control select2" name="item_type" required>
                                        <option></option>
                                        <option value="1">Line Item</option>
                                        <option value="2">Store Item</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="addItem">Add Item</button>
                                </div>
                            </div>
                        </form>
                        <h5 align="center"><?php echo $error ?></h5>
                        <h5 align="center"><?php echo $added ?></h5>
                        <h5 align="center"><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Items Details</h4>
                       
                        <table id="datatable" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Item Type</th>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> <i class="fa fa-edit"></i></th> <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $retItems = mysqli_query($connect, "SELECT * FROM sl_items ORDER BY sl_id DESC");
                                $iteration = 1;

                                while ($rowItems = mysqli_fetch_assoc($retItems)) {
                                    echo '
                                    <tr>
                                        <td>'.$iteration++.'</td>
                                        <td>'.$rowItems['item_name'].'</td>';

                                        if ($rowItems['item_type'] === '1') {
                                            echo '
                                            <td>Line Item</td>
                                            ';
                                        }else {
                                            echo '
                                            <td>Store Item</td>
                                            ';
                                        }
                                        if($fetchUserRole['user_role'] == 4){}else {
                                        echo '
                                        <td class="text-center"><a href="sl_item_edit.php?id='.$rowItems['sl_id'].'" type="button" class="btn text-white btn-warning waves-effect waves-light">Edit</a></td>';
                                        }
                                        echo '
                                    </tr>
                                    ';
                                }
                                ?>
                            </tbody>
                        </table>
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

<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">

$('.select2').select2({
    placeholder: 'Select Option',
    allowClear: true

});

</script>
</body>

</html>