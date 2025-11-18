<?php
include('../_stream/config.php');

session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}
$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];



    $getTillReport = mysqli_query($connect, "SELECT * FROM `till_two_reports` WHERE report_date BETWEEN '$datefrom' AND '$dateto'");

    $fetch_getTillReport = mysqli_fetch_assoc($getTillReport);




include '../_partials/header.php';
?>
<style type="text/css">
    body {
        color: black;
    }

    .custom {
        font-size: 14px;
    }

    .customP {
        margin-bottom: 0 !important;
    }
</style>
<div class="page-content-wrapper ">
    <div class="container-fluid"><br>
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title d-inline">Till-2 Report</h5>
                <a href="<?php echo 'report_tilltwo_generate_confirm.php?datefrom='.$datefrom.'&dateto='.$dateto.''?>" rel="noopener" target="_blank" class="btn btn-success float-right btn-lg mb-3"><i class="fas fa-print"></i> Print</a>
            </div>
        </div>
        <!-- end row -->
        <!-- <div class="row noPrint" id="printElement"> -->
        <div class="row noPrint" id="printElement">
            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <div class="invoice-title">
                            <img src="../assets/logo.svg" alt="logo" height="80">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="invoice-title">
                            <h3 class="m-t-0 text-right">
                                <h3 align="right" style="font-size: 150%; font-family: Lucida Handwriting "><u><?php echo $fet['shop_title'] ?></u></h3>
                                <p class="text-right font-16" style="font-size: 100%"><?php echo $fet['shop_address'] ?></p>
                                <br>
                            </h3>
                        </div>
                    </div>
                </div>

                <hr style="margin-top: -2.5%;">


                <div class="row" style="margin-top: -3%;">
                    <div class="col-md-12 text-center">
                        <h3 style="font-size: 100%" style="padding-top: -2%;">Till-2 Report</h3>
                        <p class="font-16 text-center customP" style="margin-top: -1%; font-size: 100%"><span style="font-weight:600">Date Range: </span> <?php echo $datefrom . " <b>-to-</b> " . $dateto ?></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">S#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Daily Sale</th>
                                    <th scope="col">Card</th>
                                    <th scope="col">Cash</th>
                                    <th scope="col">Refund</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Extra</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $getTillReport = mysqli_query($connect, "SELECT * FROM `till_two_reports` WHERE report_date BETWEEN '$datefrom' AND '$dateto'");

                                $itr = 1;

                                $DailySale = 0;
                                $CardPayment = 0;
                                $CashPayment = 0;
                                $Refund = 0;
                                $TotalAmount = 0;
                                $Extra = 0;
                                
                                while ($row = mysqli_fetch_assoc($getTillReport)) {


                                    // $workerName = $row['expense_name'];
                                    // $explodeWorkerName = explode('-', $workerName);
                                    // $worker = $explodeWorkerName[1];

                                    // $getWorker = mysqli_query($connect, "SELECT * FROM `workers` WHERE id = '$worker'");
                                    // $fetchWorker = mysqli_fetch_assoc($getWorker);

                                    echo '
                                    <tr>
                                        <td>' . $itr++ . ' </td>
                                        <td> ' . $row['report_date'] . '</td>
                                        <td>£ ' . $row['daily_sale'] . '</td>
                                        <td>£ ' . $row['card_payment'] . '</td>
                                        <td>£ ' .$row['cash_payment'] . '</td>
                                        <td>£ ' .$row['refund_payment'] . '</td>
                                        <td>£ ' . $row['total_amount'] . '</td>';

                                        if ($row['extra'] >= 0) {
                                            echo '<td>£ ' . $row['extra'] . '</td>';
                                        } else {
                                        echo '<td>- £ ' . abs($row['extra']) . '</td>';
                                        }
                                        

                                    $TotalAmount = $TotalAmount + $row['total_amount'];
                                    $DailySale = $DailySale + $row['daily_sale'];
                                    $CardPayment = $CardPayment + $row['card_payment'];
                                    $CashPayment = $CashPayment + $row['cash_payment'];
                                    
                                    $Refund = $Refund + $row['refund_payment'];
                                    $Extra = $Extra + $row['extra'];
                                    echo '
                                        
                                    </tr>
                                    ';
                                }

                                echo '
                                <tfoot style="border: none !important;">
                                    <tr>
                                    <td><b></b></td>
                                    <td class="text-right"><b>Total</b></td>
                                        <td><b>£ ' . $DailySale . '</b></td>
                                        <td><b>£ ' . $CardPayment . '</b></td>
                                        <td><b>£ ' . $CashPayment . '</b></td>
                                        <td><b>£ ' . $Refund . '</b></td>
                                        <td><b>£ ' . $TotalAmount . '</b></td>';

                                        if ($Extra >= 0) {
                                            echo '<td><b>£ ' . $Extra . '</b></td>';
                                        } else {
                                            echo '<td><b>- £ ' . abs($Extra) . '</b></td>';
                                        }

                                        echo '
                                    </tr>
                                </tfoot>
                                ';
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>




            </div> <!-- end row -->
        </div><!-- container fluid -->
    </div> <!-- Page content Wrapper -->
</div> <!-- content -->
<?php include '../_partials/footer.php' ?>
</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
<?php include '../_partials/jquery.php' ?>
<!-- App js -->
<?php include '../_partials/app.php' ?>
<?php include '../_partials/datetimepicker.php' ?>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>

<script type="text/javascript" src="../assets/print.js"></script>


</body>

</html>