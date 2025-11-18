<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$alreadyAdded = '';
$added = '';
$error = '';

$id = $_GET['id'];
$getTillReportDetails = mysqli_query($connect, "SELECT * FROM till_two_reports WHERE two_id = '$id'");
$rowReportDetails = mysqli_fetch_assoc($getTillReportDetails);

if (isset($_POST['editTillReport'])) {
    $report_date = $_POST['report_date'];
    $z_report = $_POST['z_report'];
    $daily_sale = $_POST['daily_sale'];
    $card_payment = $_POST['card_payment'];
    $cash_payment = $_POST['cash_payment'];
    $refund_payment = $_POST['refund_payment'];
    $total_amount = $_POST['total_amount'];
    $extra = $_POST['extra'];

    $updateTillReport = mysqli_query($connect, "UPDATE till_two_reports SET report_date = '$report_date', z_report = '$z_report', daily_sale = '$daily_sale', card_payment = '$card_payment', cash_payment = '$cash_payment', refund_payment = '$refund_payment', total_amount = '$total_amount', extra = '$extra' WHERE two_id = '$id'");
    if (!$updateTillReport) {
        $error = 'Not Updated! Try again!';
    } else {
        header("LOCATION: till_two_list.php");
    }
    

    
}



include('../_partials/header.php');
?>
<script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styling for better focus visibility */
        input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.45); 
            /* Tailwind's blue-500 equivalent */
        }
        /* Style for the non-editable result fields */
        .result-field {
            background-color: #f0f4f8; /* Light gray background */
            font-weight: 600;
            color: #1f2937; /* Darker text color */
        }
    </style>
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Generate Till 2 Report (Edit)</h5>
            </div>
        </div>
        <br>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <!-- <div class="w-full max-w-4xl bg-white shadow-xl rounded-xl p-6 sm:p-1   md:p-6 lg:p-8 mx-auto"> -->
                            <h3 class="text-1xl font-bold text-blue-600 mb-6 border-b pb-2">Daily Sale & Payment Tracker Till 2 Report</h3>
                            
                            <form id="paymentForm" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                                <!-- Row 1: Date & Z Report # -->
                                <div class="space-y-6">
                                    <div class="flex flex-col">
                                        <label for="date" class="text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <input type="date" id="date" name="report_date" value="<?php echo $rowReportDetails['report_date']; ?>" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150" required>
                                    </div>
                                    <div class="flex flex-col">
                                        <label for="zReport" class="text-sm font-medium text-gray-700 mb-1">Z Report #</label>
                                        <input type="number" id="zReport" value="<?php echo $rowReportDetails['z_report']; ?>" name="z_report" min="0" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150" required>
                                    </div>
                                </div>

                                <!-- Input Fields for Calculation -->
                                <div class="space-y-6">
                                    <div class="flex flex-col">
                                        <label for="dailySale" class="text-sm font-medium text-gray-700 mb-1">Daily Sale</label>
                                        <!-- IMPORTANT: onkeyup triggers calculation every time a key is pressed/released -->
                                        <input type="number" id="dailySale" name="daily_sale" placeholder="Daily Sale Amount" required value="<?php echo $rowReportDetails['daily_sale']; ?>" min="0" step="0.01" onkeyup="calculatePayments()" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label for="cardPayment" class="text-sm font-medium text-gray-700 mb-1">Card Payment</label>
                                        <input type="number" id="cardPayment" name="card_payment" placeholder="Card Payment Amount" required value="<?php echo $rowReportDetails['card_payment']; ?>" min="0" step="0.01" onkeyup="calculatePayments()" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150">
                                    </div>

                                    <div class="flex flex-col">
                                        <label for="cashPayment" class="text-sm font-medium text-gray-700 mb-1">Cash Payment</label>
                                        <input type="number" id="cashPayment" name="cash_payment" placeholder="Cash Payment Amount" required value="<?php echo $rowReportDetails['cash_payment']; ?>" min="0" step="0.01" onkeyup="calculatePayments()" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150">
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <label for="refundPayment" class="text-sm font-medium text-gray-700 mb-1">Refund Payment</label>
                                        <!-- Note: For correct financial calculation, refunds should ideally be negative, but we'll treat it as a positive number being included in the sum first, based on your formula: (Card + Cash + Refund) -->
                                        <input type="number" id="refundPayment" name="refund_payment" placeholder="Refund Amount" required value="<?php echo $rowReportDetails['refund_payment']; ?>" min="0" step="0.01" onkeyup="calculatePayments()" class="p-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150">
                                    </div>
                                </div>

                                <!-- Output/Result Fields -->
                                <div class="md:col-span-2 space-y-6 pt-4 border-t border-gray-200">
                                    <h2 class="text-xl font-bold text-gray-800">Results</h2>

                                    <!-- Total Amount (Total Payment) -->
                                    <div class="flex flex-col">
                                        <label for="totalAmount" class="text-sm font-medium text-gray-700 mb-1">Total Payment (Card + Cash + Refund)</label>
                                        <input type="text" id="totalAmount" name="total_amount" value="0.00" readonly class="result-field p-3 border border-blue-400 rounded-lg">
                                    </div>

                                    <!-- Extra (Variance) -->
                                    <div class="flex flex-col">
                                        <label for="extra" class="text-sm font-medium text-gray-700 mb-1">Extra (Total Payment - Daily Sale)</label>
                                        <input type="text" id="extra" name="extra" value="0.00" readonly class="result-field p-3 border border-blue-400 rounded-lg">
                                    </div>

                                    
                                </div>

                                <div class="space-y-6"></div>
                                <div class="space-y-6">

                                    <div class="flex flex-col w-100">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light" name="editTillReport">Edit Till Report</button>
                                    </div>
                                    <div class="flex flex-col w-100">
                                        <?php include('../_partials/cancel.php') ?><br />
                                    </div>
                                </div>
                                
                            </form>
                            <h5 align="center"><?php echo $error ?></h5>
                            <h5 align="center"><?php echo $added ?></h5>
                            <h5 align="center"><?php echo $alreadyAdded ?></h5>
                            <!-- </div> -->
                    </div>
                </div>
            </div>
            
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

<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript"></script>
    <script>
        
        function calculatePayments() {
            // Helper function to safely parse input values as floats, defaulting to 0
            const getValue = (id) => parseFloat(document.getElementById(id).value) || 0;

            const dailySale = getValue('dailySale');
            const cardPayment = getValue('cardPayment');
            const cashPayment = getValue('cashPayment');
            const refundPayment = getValue('refundPayment');

            // 1. Calculate Total Payment
            // Formula: Card Payment + Cash Payment + Refund
            const totalPayment = cardPayment + cashPayment + refundPayment;

            // 2. Calculate Extra (Variance)
            // Formula: Total Payment - Daily Sale
            const extra = totalPayment - dailySale;

            // Update the output fields, formatted to two decimal places
            document.getElementById('totalAmount').value = totalPayment.toFixed(2);
            document.getElementById('extra').value = extra.toFixed(2);
        }

        // Initialize calculation on page load to ensure initial '0.00' values are set
        window.onload = function() {
            calculatePayments();
        };
    </script>

</body>

</html>