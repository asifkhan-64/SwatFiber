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
                <h5 class="page-title">Till-1 Report List</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Report List</h4>

                        <table id="datatable" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Day</th>
                                    <th>Date</th>
                                    <th>Sale</th>
                                    <th>Card </th>
                                    <th>Cash </th>
                                    <th>Refund </th>
                                    <th>Total </th>
                                    <th>Extra </th>
                                    <th class="text-center"> <i class="fa fa-edit"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tillOneReports = mysqli_query($connect, "SELECT * FROM till_one_reports ORDER BY report_date DESC");
                                $iteration = 1;

                                while ($rowReport = mysqli_fetch_assoc($tillOneReports)) {
                                    echo '
                                    <tr>
                                         <td>' . $iteration++ . '</td>
                                         <td>' . date('l - M,Y', strtotime($rowReport['report_date'])) . '</td>
                                         <td>' . $rowReport['report_date'] . '</td>
                                         <td><span class="badge badge-primary" style="font-size: 15px">£' . $rowReport['daily_sale'] . '</span></td>
                                         <td><span class="badge badge-secondary" style="font-size: 15px">£' . $rowReport['card_payment'] . '</span></td>
                                         <td><span class="badge badge-success" style="font-size: 15px">£' . $rowReport['cash_payment'] . '</span></td>
                                         <td><span class="badge badge-warning" style="font-size: 15px">£' . $rowReport['refund_payment'] . '</span></td>
                                         <td><span class="badge badge-info" style="font-size: 15px">£' . $rowReport['total_amount'] . '</span></td>';

                                         if ($rowReport['extra'] >= 0) {
                                            echo '<td><span class="badge badge-danger" style="font-size: 15px">£' . $rowReport['extra'] . '</span></td>';
                                         } else {
                                            echo '<td><span class="badge badge-danger" style="font-size: 15px">- £' . abs($rowReport['extra']) . '</span></td>';
                                         }
                                         echo '
                                         <td class="text-center"><a href="till_one_edit.php?id=' . $rowReport['one_id'] . '" type="button" class="btn text-white btn-warning waves-effect waves-light">Edit</a></td>
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