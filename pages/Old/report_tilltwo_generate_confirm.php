<?php
    include('../_stream/config.php');

    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    $date = date_default_timezone_set("Europe/London");
    $currentDate = date('Y-m-d h:i:s A');
    $date = date('Y-m-d');

    $datefrom = $_GET['datefrom'];
    $dateto = $_GET['dateto'];



    $getTillReport = mysqli_query($connect, "SELECT * FROM `till_two_reports` WHERE report_date BETWEEN '$datefrom' AND '$dateto'");

    $fetch_getTillReport = mysqli_fetch_assoc($getTillReport);

    $get = mysqli_query($connect, "SELECT * FROM `shop_info`");
    $fet = mysqli_fetch_assoc($get);

// include '../_partials/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php echo $fet['shop_title']; ?></title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- <link rel="shortcut icon" href="../assets/LogoFinal.png"> -->
    <link rel="shortcut icon" href="../assets/logo.svg">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="../assets/plugins/morris/morris.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">

    <link href="../assets/package/dist/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables -->
    <link href="../assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="../assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/customStyles.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-slider.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-datepicker.min.css">

    <script src='../assets/kit.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<style>

    body, td {
        color: black;
    }
    
    table {
        font-size: 14px !important;
    }

    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
    
    .custom {
        font-size: 14px;
        color: black;
    }
</style>
<!-- Top Bar End -->

<br>
<div class="page-content-wrapper ">
    <div class="container-fluid"><br>
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
<?php // include '../_partials/footer.php'?>
</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
<?php include '../_partials/jquery.php'?>
<!-- App js -->
<?php include '../_partials/app.php'?>
<?php include '../_partials/datetimepicker.php'?>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript" src="../assets/print.js"></script>

<script type="text/javascript">

    // function printReport() {
    //     console.log('print');

    //      var printContents = document.getElementsByClassName('card')[0].innerH‌​TML;
    //  var originalContents = document.body.innerHTML;

    //  document.body.innerHTML = printContents;

    //  window.print();

    //  document.body.innerHTML = originalContents;

        // w = window.open();
        // w.document.write(document.getElementsByClassName('card')[0].innerH‌​TML);
        // w.print();
        // w.close();

    // }
    function print() {
    printJS({
    printable: 'printElement',
    type: 'html',
    targetStyles: ['*']
 })
}

document.getElementById('printButton').addEventListener ("click", print)

//     function printDiv(divName) {
//      var printContents = document.getElementById(divName).innerHTML;
//      var originalContents = document.body.innerHTML;

//      document.body.innerHTML = printContents;

//      window.print();

//      document.body.innerHTML = originalContents;
// }

</script>


<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
  window.addEventListener("load", window.print());
</script>
</body>

</html>