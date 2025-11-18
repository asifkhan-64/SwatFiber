<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$alreadyAdded = '';
$added = '';
$error = '';

if (isset($_POST['addPackage'])) {
    $packageName = $_POST['packageName'];
    $packagePrice = $_POST['packagePrice'];

    $sesssionEmail = $_SESSION["user"];
    $query = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$sesssionEmail' ");
    $fetch_query = mysqli_fetch_assoc($query);

    $loginUser = $fetch_query['id'];

    $countQuery = mysqli_query($connect, "SELECT COUNT(*)AS countedPackages FROM package_list WHERE package_name = '$packageName' AND package_price = '$packagePrice'");
    $fetch_countQuery = mysqli_fetch_assoc($countQuery);


    if ($fetch_countQuery['countedPackages'] == 0) {
        $insertQuery = mysqli_query($connect, "INSERT INTO package_list(package_name, package_price, added_by)VALUES('$packageName', '$packagePrice', '$loginUser')");
        if (!$insertQuery) {
            $error = 'Not Added! Try again!';
        } else {
            $added = '
                <div class="alert alert-primary" role="alert">
                                Package Added!
                             </div>';
        }
    } else {
        $alreadyAdded = '<div class="alert alert-dark" role="alert">
                                Package Already Added!
                             </div>';
    }
}


include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Packages</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Package Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Package Name" type="text" value="" id="example-text-input" name="packageName" required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Package Price</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Package Price" type="text" value="" id="example-text-input" name="packagePrice" required="">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="addPackage">Add Package</button>
                                </div>
                            </div>
                        </form>
                        <h5 align="center"><?php echo $error ?></h5>
                        <h5 align="center"><?php echo $added ?></h5>
                        <h5 align="center"><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Package List</h4>

                        <table id="datatable" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Package Name</th>
                                    <th>Package Pice</th>
                                    <th class="text-center"> <i class="fa fa-edit"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $uppliers = mysqli_query($connect, "SELECT * FROM package_list ORDER BY package_name ASC");
                                $iteration = 1;

                                while ($rowSuppliers = mysqli_fetch_assoc($uppliers)) {
                                    echo '
                                    <tr>
                                         <td>' . $iteration++ . '</td>
                                         <td>' . $rowSuppliers['package_name'] . '</td>
                                         <td>' . $rowSuppliers['package_price'] . '</td>
                                         <td class="text-center"><a href="package_edit.php?id=' . $rowSuppliers['p_id'] . '" type="button" class="btn text-white btn-warning waves-effect waves-light">Edit</a></td>
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
</body>

</html>