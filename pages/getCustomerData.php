<?php
include '../_stream/config.php';
session_start();

if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
    exit();
}

date_default_timezone_set('Asia/Karachi');
$currentDate = date('Y-m-d');

$client_id = $_POST["customer"];
// $client_id = $_GET["customer"];
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
    echo $amount + $old_dues;
    }else {
       echo $old_dues;
   }
?>