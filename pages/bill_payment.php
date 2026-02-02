<?php
include '../_stream/config.php';
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$getUser = $_SESSION["user"];
$getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
$fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
$addedBy = $fetch_getUserQuery['id'];


$userAlreadyinDatabase = '';
$expenseNotAdded = '';

if (isset($_POST["addExpense"])) {
	$client_id = $_POST['client_id'];
    $dues = $_POST['dues'];
    $paidAmount = $_POST['paidAmount'];
    $remainingAmount = $_POST['remainingAmount'];
    $billdate = $_POST['billdate'];
    $billDescription = $_POST['billDescription'];
    $paymentBy = $_POST['paymentBy'];

    $getclient_tbl = mysqli_query($connect, "SELECT * FROM `client_tbl` WHERE client_id = '$client_id'");
    $fetchgetclient_tbl = mysqli_fetch_assoc($getclient_tbl);

    $oldRem = $fetchgetclient_tbl['old_remaining'];

    

    $newRem = $oldRem - $paidAmount;

    if ($newRem <= 0) {
        $rem = 0;
    }else {
        $rem = $newRem;
    }

    $getNewDate = $fetchgetclient_tbl['new_billing_date'];


    $getUser = $_SESSION["user"];
    $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
    $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
    $addedBy = $fetch_getUserQuery['id'];

    date_default_timezone_set('Asia/Karachi');
    $currentDate = date('Y-m-d');

    $newBillingDate = $fetchgetclient_tbl['new_billing_date'];
    $lastPaymentDate = $fetchgetclient_tbl['last_paid_date'];
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

        $explode = explode('.', $billingMonths);
        $monthsExploded = $explode[1];

        $getDOC = $fetchgetclient_tbl['doc'];

        $currentDate;

        $explodegetDOC = explode('-', $getDOC);
        $DOCyear = $explodegetDOC[0];
        $DOCmonth = $explodegetDOC[1];
        $DOCDate = $explodegetDOC[2];

        $explodeCurrentDate = explode('-', $currentDate);
        $currentYearNew = $explodeCurrentDate[0];
        $currentMonthNew = $explodeCurrentDate[1];
        $currentDateNew = $explodeCurrentDate[2];

        $newFormatDate = $currentYearNew . '-' . $currentMonthNew . '-' . $DOCDate;

        if ($monthsExploded < 80) {
            $dateObject = new DateTime($getDOC);

            $dateObject->modify('+1 month');

            $newDateForNewBill = $dateObject->format('Y-m-d');

            $updateNewBillingDate = mysqli_query($connect, "UPDATE client_tbl SET new_billing_date = '$newFormatDate' WHERE client_id = '$client_id'");
        }else {        
            $dateObject = new DateTime($getDOC);

            $dateObject->modify('+2 month');

            $newDateForNewBill = $dateObject->format('Y-m-d');

            $updateNewBillingDate = mysqli_query($connect, "UPDATE client_tbl SET new_billing_date = '$newFormatDate' WHERE client_id = '$client_id'");

        }
    }

    if ($oldRem === $dues) {
        // $updateClientTableRemAmount = mysqli_query($connect, "UPDATE client_tbl SET old_remaining = '$rem' WHERE client_id = '$client_id'");
        $updateClientTableRemAmount = mysqli_query($connect, "UPDATE client_tbl SET old_remaining = '$remainingAmount' WHERE client_id = '$client_id'");
    }else {
        // $updateClientTableRemAmount = mysqli_query($connect, "UPDATE client_tbl SET old_remaining = '$rem', last_paid_date = '$currentDate' WHERE client_id = '$client_id'");
        $updateClientTableRemAmount = mysqli_query($connect, "UPDATE client_tbl SET old_remaining = '$remainingAmount', last_paid_date = '$currentDate' WHERE client_id = '$client_id'");
    }



    $createPayment = mysqli_query($connect, "INSERT INTO pay_done(client_id, amount, added_by, rem_amount, dues, date_pay, bill_desc, payment_method)VALUES('$client_id', '$paidAmount', '$addedBy', '$remainingAmount', '$dues', '$billdate', '$billDescription', '$paymentBy')");


	if (!$createPayment) {
		$expenseNotAdded = "Payment not added! Try Again.";
	} else {
		header("LOCATION:bill_payment_list.php");
	}
}

include '../_partials/header.php'
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Bill Payment</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Add Bill Payment</h4>
                        <form method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Client</label>
                                <div class="col-sm-4">
                                <?php
                                $selectExpenseCat = mysqli_query($connect, "SELECT * FROM client_tbl WHERE added_by = '$addedBy' ORDER BY name ASC");
                                    $optionsCategory = '<select class="form-control designation" name="client_id" id="customer_selection" required="" style="width:100%">';
                                    $optionsCategory .= '<option>Select Option</option>';
                                      while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                        $explodeContact = explode("-", $rowCat['contact']);
                                        $optionsCategory.= '<option value='.$rowCat['client_id'].'>'.$rowCat['user_id'].' - '.$rowCat['name'].'</option>';
                                      }
                                    $optionsCategory.= "</select>";
                                echo $optionsCategory;
                                ?>
                                </div>
                                <label for="example-text-input" class="col-sm-2 col-form-label">Total Dues </label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" readonly  name="dues" id="rem_dues" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Paid Amount </label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number"  name="paidAmount" id="paid_amount" required="">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Remaining Dues </label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="number" readonly  name="remainingAmount" id="remaining_amount" required="">
                                </div>

                                
                            </div>

                            <div class="form-group row">
                           <label class="col-sm-2 col-form-label">Date</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input class="form-control " type="date" name="billdate" placeholder="dd/mm/yyyy-hh:mm" required="">
                                    </div>
                                </div>

                                <label class="col-sm-2 col-form-label">Payment Method</label>
                                <div class="col-sm-4">
                                    <?php
                                        echo '<select class="form-control payment" style="width: 100%" name="paymentBy" required>
                                            <option></option>';
                                        echo '<option value="EasyPaisa">EasyPaisa</option>';
                                        echo '<option value="Cash">Cash</option>';
                                        echo '<option value="JazzCash">JazzCash</option>';
                                        echo '<option value="Bank">Bank</option>';

                                        echo '</select>';
                                    ?>
                                </div>

                                
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea id="textarea" class="form-control" maxlength="225" rows="2" name="billDescription" placeholder="Bill Description" required=""></textarea>
                                </div>
                                
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include '../_partials/cancel.php'?>
                                    <button type="submit" name="addExpense" class="btn btn-primary waves-effect waves-light">Add Payment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <h3 align="center">
                    <?php echo $userAlreadyinDatabase; ?>
                </h3>
                <h3 align="center">
                    <?php echo $expenseNotAdded; ?>
                </h3>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container fluid -->
</div> <!-- Page content Wrapper -->
</div> <!-- content -->
<?php include '../_partials/footer.php'?>
</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
<?php include '../_partials/jquery.php'?>
<!-- App js -->
<?php include '../_partials/app.php'?>
<?php include '../_partials/datetimepicker.php'?>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
    format: "yyyy-mm-dd hh:ii"
});
</script>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">
$('.designation').select2({
    placeholder: 'Select an option',
    allowClear: true

});

$('.attendant').select2({
    placeholder: 'Select an option',
    allowClear: true

});

$('.payment').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
</script>
<script type="text/javascript">
function checkDoctor() {
    let desg = document.querySelector('#designation');
    if (desg.value.toLowerCase() == 'doctor') {

        document.querySelector('#visitcharges').style.display = '';
    } else {
        document.querySelector('#visitcharges').style.display = 'none';


    }

}

    $(document).ready(function() {
        $('#customer_selection').change(function() {
        var customer_selection = $(this).val()
        $.ajax({
            url: "getCustomerData.php",
            method: "POST",
            data: {
            customer: customer_selection
            }, dataType: "text",
            success:function(response){
            // console.log(response)
            $('#rem_dues').val(response)
            },error:function(e){
            console.log(e)
            }
        });
        });
    });


  $(document).ready(function() {
    $('#rem_dues').val('0')
    $('#paid_amount').val('0')
    $('#remaining_amount').val('0')

    $('#paid_amount').keyup(function() {
      if (isNaN($(this).val()))
        return
      var paidAmount = parseFloat($(this).val())
      var oldDues = parseInt($('#rem_dues').val())
      console.log(paidAmount)
      var remainingAmount = oldDues - paidAmount
      $('#remaining_amount').val(remainingAmount)
      $('#remainingAmount').keyup(function() {
        $(this).val("")
        $('#remaining_amount').val("")
      });
    });
  });

</script>
</body>

</html>