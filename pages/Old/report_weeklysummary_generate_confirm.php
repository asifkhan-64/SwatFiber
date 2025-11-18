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
                        <h3 style="font-size: 100%" style="padding-top: -2%;">Weekly Payment Report</h3>
                        <p class="font-16 text-center customP" style="margin-top: -1%; font-size: 100%"><span style="font-weight:600">Date Range: </span> <?php echo $datefrom . " <b>-to-</b> " . $dateto ?></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead style="width: 100% !important">
                                <tr>
                                    <th rowspan="2" >Date</th>

                                    <th rowspan="2" >Day</th>
                                    
                                    <th colspan="2" class="text-center" >Till-1 Report</th>
                                    
                                    <th colspan="2" class="text-center" >Till-2 Report</th>
                                    
                                    <th rowspan="2" >Total</th>
                                </tr>
                                
                                <tr>
                                    <th>Card</th>
                                    <th>Cash</th>
                                    
                                    <th>Card</th>
                                    <th>Cash</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                // if ($number != 0) {
                                //     echo "A";
                                // } else {
                                //     echo "B";
                                // }

                                // Define the custom start and end dates
                                $start_date = new DateTime($datefrom);
                                $end_date = new DateTime($dateto);

                                // Define the interval for incrementing
                                $interval = new DateInterval('P1D'); // Period of 1 Day

                                // "## Dates from " . $start_date->format('Y-m-d') . " to " . $end_date->format('Y-m-d') . " (Inclusive):\n";

                                echo "<br>";

                                // Clone the start date so you don't modify the original
                                
                                $current_date = clone $start_date;

                                // Loop while the current date is less than or equal to the end date

                                $toltalAll = 0;
                                while ($current_date <= $end_date) {
                                    
                                    $dateNew = $current_date->format('Y-m-d');
                                    $dateUsed = $current_date->format('Y-M-d');
                                    $dayNew = $current_date->format('l');

                                    $getTillOneReport = mysqli_query($connect, "SELECT * FROM `till_one_reports` WHERE report_date = '$dateNew'");
                                    $fetchTillOne = mysqli_fetch_assoc($getTillOneReport);

                                    $getTillTwoReport = mysqli_query($connect, "SELECT * FROM `till_two_reports` WHERE report_date = '$dateNew'");
                                    $fetchTillTwo = mysqli_fetch_assoc($getTillTwoReport);

                                    

                                    echo '
                                    <tr>
                                        <td>'.$dateUsed.'</td>
                                        <td>'.$dayNew.'</td>';

                                        if (empty($fetchTillOne['card_payment'])) {
                                            echo '<td>£ 0</td>';
                                        } else {
                                            echo '<td>£ '.$fetchTillOne['card_payment'].'</td>';
                                        }

                                        if (empty($fetchTillOne['cash_payment'])) {
                                            echo '<td>£ 0</td>';
                                        } else {
                                            echo '<td>£ '.$fetchTillOne['cash_payment'].'</td>';
                                        }

                                        if (empty($fetchTillTwo['card_payment'])) {
                                            echo '<td>£ 0</td>';
                                        } else {
                                            echo '<td>£ '.$fetchTillTwo['card_payment'].'</td>';
                                        }

                                        if (empty($fetchTillTwo['cash_payment'])) {
                                            echo '<td>£ 0</td>';
                                        } else {
                                            echo '<td>£ '.$fetchTillTwo['cash_payment'].'</td>';
                                        }

                                        // For Till one card and cash

                                        if (empty ($fetchTillOne['card_payment'])) {
                                            $tillOneCard = 0;
                                        }else {
                                            $tillOneCard = $fetchTillOne['card_payment'];
                                        }

                                        if (empty ($fetchTillOne['cash_payment'])) {
                                            $tillOneCash = 0;
                                        }else {
                                            $tillOneCash = $fetchTillOne['cash_payment'];
                                        }
                                        

                                        // For Till two card and cash

                                        if (empty ($fetchTillTwo['card_payment'])) {
                                            $tillTwoCard = 0;
                                        }else {
                                            $tillTwoCard = $fetchTillTwo['card_payment'];
                                        }

                                        if (empty ($fetchTillTwo['cash_payment'])) {
                                            $tillTwoCash = 0;
                                        }else {
                                            $tillTwoCash = $fetchTillTwo['cash_payment'];
                                        }




                                        $total = $tillOneCard + $tillOneCash + $tillTwoCard + $tillTwoCash;

                                        $toltalAll = $toltalAll + $total;
                                        echo '

                                        <td>£ '.$total.'</td>

                                    </tr>
                                    ';
                                    

                                    // Increment the date by the interval
                                    $current_date->add($interval);
                                }




                                
                                

                                echo '
                                <tfoot style="border: none !important;">
                                    <tr>
                                        <td></td>
                                        <td </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center"><b>Total: </b></td>
                                        <td><b>£ '.$toltalAll.'</b></td>
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