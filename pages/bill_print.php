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



//     $getTillReport = mysqli_query($connect, "SELECT * FROM `till_one_reports` WHERE report_date BETWEEN '$datefrom' AND '$dateto'");

//     $fetch_getTillReport = mysqli_fetch_assoc($getTillReport);

date_default_timezone_set('Asia/Karachi');
    $today = new DateTime();
    
    // --- Input and Database Fetch (IMPROVED SECURITY) ---
    $output = '';
    
    // Sanitize and cast input to integer. Use null-coalescing to handle missing POST data safely.
    // $client_id = isset($newClient) ? (int)$newClient : 0; 

    // ** FIX 1 (Security): Use placeholder '?' in the query string. **
    $stmt = mysqli_prepare($connect, "SELECT * FROM client_tbl WHERE client_id = ?");
    
    if ($stmt) {
        // Bind the client ID parameter as an integer ('i')
        mysqli_stmt_bind_param($stmt, "i", $client_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Get the result set
        $query_result = mysqli_stmt_get_result($stmt);
        
        // Fetch the single user data array
        $userData = mysqli_fetch_assoc($query_result);
        
        // Close the statement
        mysqli_stmt_close($stmt);

    } else {
        $output = "Database preparation error.";
        echo $output;
        exit();
    }
    
    // --- Billing Calculation Function ---

    /**
     * Calculates the billing details based on a 30-day cycle from the last paid date.
     * @param array $user User data array. Expects keys: 'last_paid_date', 'updated_bill_payment', 'unpaid_balance', 'old_remaining'.
     * @param DateTime $today The current date.
     * @return array Calculation details.
     */
    function calculateBill(array $user, DateTime $today): array {
        
        // --- Data Extraction and Casting ---
        $monthlyPrice = (float) ($user['updated_bill_payment'] ?? 0);
        $unpaidBalance = (float) ($user['unpaid_balance'] ?? 0);
        $oldRem = (float) ($user['old_remaining'] ?? 0);
        $daysInCycle = 30; // Fixed billing cycle length

        // Handle potential NULL or invalid dates
        try {
            // The 'last_paid_date' is the anchor for the billing cycle
            if (empty($user['last_paid_date'])) {
                return ['total_bill' => 0, 'error' => 'Last paid date is missing.'];
            }
            $connectionDate = new DateTime($user['last_paid_date']);
        } catch (Exception $e) {
            // Handle case where date field is null or invalid
            return ['total_bill' => 0, 'error' => 'Invalid last_paid_date format.'];
        }

        // 1. Find the start of the current cycle and the next expiry date
        $cycleStart = clone $connectionDate;
        $nextExpiry = clone $connectionDate;
        $nextExpiry->modify("+$daysInCycle days");

        // Loop forward by 30 days until $nextExpiry is AFTER $today.
        while ($nextExpiry <= $today) {
            $cycleStart->modify("+$daysInCycle days");
            $nextExpiry->modify("+$daysInCycle days");
        }
        
        // 2. Determine Status and Days Remaining
        $isExpired = $today >= $nextExpiry;
        $daysUntilExpiry = 0;

        if ($isExpired) {
             $status = 'Expired';
        } else {
             $status = 'Active';
             // The difference in days from $today to the expiry date
             $daysUntilExpiry = $today->diff($nextExpiry)->days;
        }
        
        // 3. Total Due
        if ($daysUntilExpiry < 10 || $isExpired) {
                $totalPreviousDues = $unpaidBalance + $oldRem;
                $totalBill = $monthlyPrice + $totalPreviousDues;
            } else {
                // Otherwise, show 0, formatted.
                $totalBill = $oldRem;
            }
        

        return [
            'total_bill' => $totalBill,
            'status' => $status,
            'days_until_expiry' => $daysUntilExpiry
        ];
    }
    
    // --- Final Output Generation ---
    if ($userData) {
        $billing_details = calculateBill($userData, $today);
        
        // Check for date error
        if (isset($billing_details['error'])) {
            $output = "Calculation Error: " . $billing_details['error'];
        } else {
            // FIX 2 (Logic): Extract variables for the conditional check
            $totalBillAmount = $billing_details['total_bill'];
            $daysUntilExpiry = $billing_details['days_until_expiry'];
            $isExpired = $billing_details['status'] === 'Expired';
            
            // Conditional Logic: Show amount if less than 10 days until expiry OR if the service is already expired.
            if ($daysUntilExpiry < 10 || $isExpired) {
                // Output the full total bill amount, formatted.
                $output = $totalBillAmount;
            } else {
                // Otherwise, show 0, formatted.
                $output = $totalBillAmount;
            }
        }
    }

    $daysAhead = $daysUntilExpiry; 


    // --- Method 1: Using the modern and preferred DateTime object ---
    // 1. Create a DateTime object for today.
    $dateToday = new DateTime();

    // 2. Use the modify method, inserting the variable dynamically.
    $dateToday->modify("+{$daysAhead} days");

    // --- Method 2: Using the simpler, older strtotime() function ---
    // This is concise but less flexible for complex operations.
    $dateXDays = strtotime("+{$daysAhead} days");

    $expireBillDate = date('Y-m-d', $dateXDays);

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
                                    <th style="border: none !important"><?php echo $currentDateNew ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Date Due</th>
                                    <th style="border: none !important"><?php echo $expireBillDate ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Package</th>
                                    <th style="border: none !important"><?php echo $rowClientData['package_name'] ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Date</th>
                                    <th style="border: none !important"><?php echo $currentDateNewNoDay ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important">Total Amount</th>
                                    <th style="border: none !important"><?php echo $output ?></th>
                                </tr>

                                <tr style="border: none !important">
                                    <th style="border: none !important; color: red">Payable amount after due Date: </th>
                                    <th style="border: none !important"><?php echo $output ?></th>
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