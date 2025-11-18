<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
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
                        <h4 class="mt-0 header-title">Grocessory List</h4>

                        <table id="datatable" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Supplier</th>
                                    <th>Payment By</th>
                                    <th>Date</th>
                                    <th class="text-center"> <i class="fa fa-edit"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grocessories = mysqli_query($connect, "SELECT grocessory.*, supplier.* FROM grocessory INNER JOIN supplier ON grocessory.supplier_id = supplier.supplier_id ORDER BY grocessory.grocessory DESC");
                                $iteration = 1;

                                while ($rowGrocessory = mysqli_fetch_assoc($grocessories)) {
                                    echo '
                                    <tr>
                                        <td>' . $iteration++ . '</td>
                                        <td>' . $rowGrocessory['item_name'] . '</td>
                                        <td><span class="badge badge-info" style="font-size: 16px">£ ' . $rowGrocessory['item_price'] . '</span></td>
                                        <td>' . $rowGrocessory['supplier_name'] . '</td>';

                                        if ($rowGrocessory['payment_by'] == 'Cash') {
                                            echo '<td><span class="badge badge-success" style="font-size: 16px">' . $rowGrocessory['payment_by'] . '</span></td>';
                                        } else {
                                            echo '<td><span class="badge badge-primary" style="font-size: 16px">' . $rowGrocessory['payment_by'] . '</span></td>';
                                        }
                                        echo '
                                        <td>' . $rowGrocessory['item_date'] . '</td>
                                        <td class="text-center"><a href="grocessory_edit.php?id=' . $rowGrocessory['grocessory'] . '" type="button" class="btn text-white btn-warning waves-effect waves-light">Edit</a></td>
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