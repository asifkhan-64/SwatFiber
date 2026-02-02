<?php
include('../_stream/config.php');

session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$client_id = $_GET['client_id'];

$getClientData = mysqli_query($connect, "SELECT client_tbl.*, area.*, package_list.*, installation_type.* FROM client_tbl 
    INNER JOIN area ON area.id = client_tbl.area_id
    INNER JOIN package_list ON package_list.p_id = client_tbl.package_id
    INNER JOIN installation_type ON installation_type.ins_id = client_tbl.ins_id
    WHERE client_tbl.client_id = '$client_id'");
    $rowClientData = mysqli_fetch_assoc($getClientData);

    $getClientPaymentDetails = mysqli_query($connect, "SELECT * FROM client_payments WHERE client_id = '$client_id'");
    $rowClientPaymentDetails = mysqli_fetch_assoc($getClientPaymentDetails);

    date_default_timezone_set('Asia/Karachi');
    $currentDate = date('Ymd');
    $currentDateNew = date('d M, Y');
    $currentDateNewNoDay = date('M, Y');

    $newClient = $client_id;
// $datefrom = $_GET['datefrom'];
// $dateto = $_GET['dateto'];

// From HERE
date_default_timezone_set('Asia/Karachi');
$currentDate = date('Y-m-d');

// $client_id = $_POST["customer"];
$getClientData= mysqli_query($connect, "SELECT * FROM client_tbl WHERE client_id = '$client_id'");
$fetch = mysqli_fetch_assoc($getClientData);
$newBillingDate = $fetch['new_billing_date'];
$lastPaymentDate = $fetch['last_paid_date'];

    $old_dues = $fetch['old_remaining'];

    $interval = date_create($currentDate)->diff(date_create($newBillingDate));
    $daysDifference = $interval->days;
    
    
   $billingMonths = round($daysDifference / 30, 2);

   if ($billingMonths >= 1) {
       if (is_float($billingMonths)) {
        $billingMonths = number_format($billingMonths, 2, '.', '');
        $explode = explode('.', $billingMonths);
        $monthsExploded = $explode[1];
    }else {
        $explode = explode('.', $billingMonths);
        $monthsExploded = $explode[1];
    }
    

        $getDOC = $fetch['doc'];

        $currentDate;

        $explodegetDOC = explode('-', $getDOC);
        $DOCyear = $explodegetDOC[0];
        $DOCmonth = $explodegetDOC[1];
        $DOCDate = $explodegetDOC[2];

        $explodeCurrentDate = explode('-', $currentDate);
        $currentYearNew = $explodeCurrentDate[0];
        $currentMonthNew = $explodeCurrentDate[1];
        $currentDateNew = $explodeCurrentDate[2];

        if ($currentMonthNew == 12) {
            $newMonth = 01;
            $newYear = $currentYearNew + 1;
            $newFormatDate = $newYear . '-' . $newMonth . '-' . $DOCDate;
        } else {
            $newMonth = $currentMonthNew + 1;
            $newYear = $currentYearNew;
            $newFormatDate = $newYear . '-0' . $newMonth . '-' . $DOCDate;
        }


    if ($monthsExploded < 80) {
        $months = floor($billingMonths);
        // $dateObject = new DateTime($getDOC);

        // $dateObject->modify('+1 month');

        // $dateObject->format('Y-m-d');
    }else {
        $months = ceil($billingMonths);
        
        // $dateObject = new DateTime($getDOC);

        // $dateObject->modify('+2 month');

        // $dateObject->format('Y-m-d');

    }
    
        $getPackageDataClient = mysqli_query($connect, "SELECT * FROM client_payments WHERE client_id = '$client_id'");
        $packageData = mysqli_fetch_assoc($getPackageDataClient);



        $amount = $packageData['package_amount'] * $months;
        $totalAmount = $amount + $old_dues;
    }else {
       $totalAmount = $old_dues;
   }


    $get = mysqli_query($connect, "SELECT * FROM `shop_info`");
    $fet = mysqli_fetch_assoc($get);

    include '../_partials/header.php'
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
                <h5 class="page-title d-inline">Generate Bill</h5>
                <a href="<?php echo 'generate_bill_confirm.php?client_id='.$client_id.''?>" rel="noopener" target="_blank" class="btn btn-success float-right btn-lg mb-3"><i class="fas fa-print"></i> Print</a>
            </div>
        </div>
        <!-- end row -->
        <!-- <div class="row noPrint" id="printElement"> -->
        <div class="row noPrint" id="printElement">
            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <div class="invoice-title">
                            <img src="../assets/logo.png" alt="logo" height="150">
                        </div>
                    </div>

                    <div class="col-6" >
                        <div class="invoice-title">
                            <h3 class="m-t-0 text-right">
                                <h3 align="right" style="font-size: 150%; font-family: Lucida Handwriting "><u><?php echo $fet['shop_title'] ?></u></h3>
                                <p class="text-right font-16" style="font-size: 100%"><?php echo $fet['shop_name'] ?></p>
                                <p class="text-right font-16" style="font-size: 100%"><?php echo $fet['shop_address'] ?></p>
                                <p class="text-right font-16" style="font-size: 100%">Contact: <?php echo "0".$fet['shop_contact'] ?></p>
                                <p class="text-right font-16" style="font-size: 100%"><?php echo "0".$fet['shop_contact_two'] ?></p>
                                <br>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <hr style="margin-top: -2.5%; background-color: black">
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-6 " style="border: 1px solid black;">
                        <table class="table">
                            <thead style="border: none !important">
                                <tr style="border: none !important">
                                    <th style="border: none !important">Invoice No.</th>
                                    <th style="border: none !important"><?php echo $rowClientData['user_id']."/".$currentDate ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Date</th>
                                    <th style="border: none !important"><?php echo $currentDate ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Date Due</th>
                                    <th style="border: none !important"><?php echo $newFormatDate ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Package</th>
                                    <th style="border: none !important"><?php echo $rowClientData['package_name'] ?></th>
                                </tr>


                                <tr style="border: none !important">
                                    <th style="border: none !important">Total Amount</th>
                                    <th style="border: none !important"><?php echo $totalAmount ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important; color: red">Payable amount after due Date: </th>
                                    <th style="border: none !important"><?php echo $totalAmount ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-md-5 offset-1" style="border: 1px solid black;">
                        <table class="table">
                            <thead style="border: none !important">
                                <tr style="border: none !important">
                                    <th style="border: none !important">Billing/User ID</th>
                                    <th style="border: none !important"><?php echo $rowClientData['user_id'] ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Name</th>
                                    <th style="border: none !important"><?php echo $rowClientData['name'] ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Address</th>
                                    <th style="border: none !important"><?php echo $rowClientData['address'] ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Contact</th>
                                    <th style="border: none !important"><?php echo $rowClientData['contact'] ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">CNIC No.</th>
                                    <th style="border: none !important"><?php echo $rowClientData['cnic_no'] ?></th>
                                </tr>

                            </thead>
                        </table>
                    </div>
                </div><br><br>




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