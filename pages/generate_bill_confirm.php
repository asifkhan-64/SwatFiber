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

    // include '../_partials/header.php'
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
    <link rel="shortcut icon" href="../assets/logo.png">
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

                <div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="col-6 " style="border: 1px solid black;">
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

                    <div class="col-5" style="border: 1px solid black;">
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
                </div>

                <div class="col-1"></div>




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
<script type="text/javascript" src="../assets/print.js"></script>



<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
  window.addEventListener("load", window.print());
</script>
</body>

</html>