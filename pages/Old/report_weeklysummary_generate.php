<?php
include('../_stream/config.php');

session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}
$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];



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
                <h5 class="page-title d-inline"> Weekly Payment Summary</h5>
                <a href="<?php echo 'report_weeklysummary_generate_confirm.php?datefrom='.$datefrom.'&dateto='.$dateto.''?>" rel="noopener" target="_blank" class="btn btn-success float-right btn-lg mb-3"><i class="fas fa-print"></i> Print</a>
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
                        <h3 style="font-size: 100%" style="padding-top: -2%;">Weekly Payment Report</h3>
                        <p class="font-16 text-center customP" style="margin-top: -1%; font-size: 100%"><span style="font-weight:600">Date Range: </span> <?php echo $datefrom . " <b>-to-</b> " . $dateto ?></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);">
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
                                            echo '<td>£0</td>';
                                        } else {
                                            echo '<td>£'.$fetchTillOne['card_payment'].'</td>';
                                        }

                                        if (empty($fetchTillOne['cash_payment'])) {
                                            echo '<td>£0</td>';
                                        } else {
                                            echo '<td>£'.$fetchTillOne['cash_payment'].'</td>';
                                        }

                                        if (empty($fetchTillTwo['card_payment'])) {
                                            echo '<td>£0</td>';
                                        } else {
                                            echo '<td>£'.$fetchTillTwo['card_payment'].'</td>';
                                        }

                                        if (empty($fetchTillTwo['cash_payment'])) {
                                            echo '<td>£0</td>';
                                        } else {
                                            echo '<td>£'.$fetchTillTwo['cash_payment'].'</td>';
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

                                        <td>£'.$total.'</td>

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
                                        <td><b>'.$toltalAll.'</b></td>
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