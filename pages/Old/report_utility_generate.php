<?php
include('../_stream/config.php');

session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}
$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];



$getWagesExpenses = mysqli_query($connect, "SELECT expense.*, expense_category.expense_name FROM `expense`
                                            INNER JOIN expense_category ON expense_category.id = expense.cat_id
                                            WHERE expense_category.expense_name LIKE '%Utility%' AND expense.expense_date BETWEEN '$datefrom' AND '$dateto'");

$fetch_getWagesExpenses = mysqli_fetch_assoc($getWagesExpenses);




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
                <h5 class="page-title d-inline">Utility Report</h5>
                <a href="<?php echo 'report_utility_generate_confirm.php?datefrom='.$datefrom.'&dateto='.$dateto.''?>" rel="noopener" target="_blank" class="btn btn-success float-right btn-lg mb-3"><i class="fas fa-print"></i> Print</a>
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
                        <h3 style="font-size: 100%" style="padding-top: -2%;">Utility Report</h3>
                        <p class="font-16 text-center customP" style="margin-top: -1%; font-size: 100%"><span style="font-weight:600">Date Range: </span> <?php echo $datefrom . " <b>-to-</b> " . $dateto ?></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">S#</th>

                                    <th scope="col">Utility</th>

                                    <th scope="col">Date</th>

                                    
                                    <th scope="col">Payment</th>
                                    
                                    <th scope="col">Description</th>
                                    <th scope="col">Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $getInvoiceItemsLoop = mysqli_query($connect, "SELECT expense.*, expense_category.expense_name FROM `expense`
                                            INNER JOIN expense_category ON expense_category.id = expense.cat_id
                                            WHERE expense_category.expense_name LIKE '%Utility%' AND expense.expense_date BETWEEN '$datefrom' AND '$dateto'");
                                $itr = 1;
                                $totalAmount = 0;

                                while ($row = mysqli_fetch_assoc($getInvoiceItemsLoop)) {


                                    $workerName = $row['expense_name'];
                                    $explodeWorkerName = explode('-', $workerName);
                                    $worker = $explodeWorkerName[1];

                                    // $getWorker = mysqli_query($connect, "SELECT * FROM `workers` WHERE id = '$worker'");
                                    // $fetchWorker = mysqli_fetch_assoc($getWorker);

                                    echo '
                                    <tr>
                                        <td>' . $itr++ . ' </td>
                                        <td>' . $worker . '</td>
                                        <td>' . $row['expense_date'] . '</td>
                                        <td> ' .$row['payment_by'] . '</td>
                                        <td> ' .$row['expense_description'] . '</td>
                                        <td>£' . $row['expense_amount'] . '</td>
                                        ';

                                    $totalAmount = $totalAmount + $row['expense_amount'];

                                    echo '
                                        
                                    </tr>
                                    ';
                                }

                                echo '
                                <tfoot style="border: none !important;">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>Total:</b></td>
                                        <td><b>£' . number_format($totalAmount) . '</b></td>
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