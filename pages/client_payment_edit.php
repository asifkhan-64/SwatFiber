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


    if ($rowClientData['updated_bill_payment'] === '0') {
        $package_price = $rowClientData['package_price'];
    } else {
        $package_price = $rowClientData['updated_bill_payment'];
    }

    if (isset($_POST['editPayment'])) {
        $package = $_POST['package'];
        $installation = $_POST['installation'];
        $cable = $_POST['cable'];
        $other_charges = $_POST['other_charges'];
        $discount_charges = $_POST['discount_charges'];
        $total_charges = $_POST['total_charges'];
        
        $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

        $addPaymentQuery = mysqli_query($connect, "UPDATE `client_payments` SET `package_amount` = '$package', `installation_amount` = '$installation', `cable_amount` = '$cable', `other_charges` = '$other_charges', `discount_charges` = '$discount_charges', `total_charges` = '$total_charges', `added_by` = '$addedBy' WHERE `client_id` = '$client_id'");

        if ($addPaymentQuery) {
            $updateClientPaymentStatus = mysqli_query($connect, "UPDATE client_tbl SET payment_status = '1', updated_bill_payment = '$package' WHERE client_id = '$client_id'");

            header("LOCATION:client_payment_list.php");
        } else {
            echo '<script>
                alert("Payment Update Failed.");
                window.location.href="client_payment_edit.php?client_id='.$client_id.'";
            </script>';
        }
    }

    include('../_partials/header.php');
?>
<link href="../assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Edit Client Payment</h5>
            </div>
        </div>
        <!-- end row -->
        <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Client Details</h4>
                        <table id="datatablse" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th><?php echo $rowClientData['name'] ?></th>
                                </tr>

                                <tr>
                                    <th>Father Name</th>
                                    <th><?php echo $rowClientData['father_name'] ?></th>
                                </tr>

                                <tr>
                                    <th>Contact</th>
                                    <th><?php echo $rowClientData['contact'] ?></th>
                                </tr>

                                <tr>
                                    <th>Contact</th>
                                    <th><?php echo $rowClientData['cnic_no'] ?></th>
                                </tr>

                                <tr>
                                    <th>Address</th>
                                    <th><?php echo $rowClientData['area_name'].", ".$rowClientData['address'] ?></th>
                                </tr>

                                <tr>
                                    <th>Package</th>
                                    <th><?php echo $rowClientData['package_name'] ?></th>
                                </tr>

                                <tr>
                                    <th>Monthly</th>
                                    <th><?php echo "PKR: ".$package_price ?></th>
                                </tr>

                                <tr>
                                    <th>Installation Type</th>
                                    <th><?php echo $rowClientData['ins_type']." - PKR: ".$rowClientData['ins_price'] ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-md-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Payment Details</h4>
                        <table class="table datatable dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th><input type="text" name="package" id="package_amount" class="form-control" placeholder="Enter package amount" value="<?php echo $rowClientPaymentDetails['package_amount'] ?>"></th>
                                </tr>
                                <tr>
                                    <th>Installation</th>
                                    <th><input type="text" name="installation" id="installation_amount" class="form-control" placeholder="Enter installation amount" value="<?php echo $rowClientPaymentDetails['installation_amount'] ?>"></th>
                                </tr>
                                <tr>
                                    <th>Cable</th>
                                    <th><input type="text" name="cable" id="cable_amount" class="form-control" placeholder="Enter cable amount" value="<?php echo $rowClientPaymentDetails['cable_amount'] ?>" required></th>
                                </tr>
                                <tr>
                                    <th>Other Charges</th>
                                    <th><input type="text" name="other_charges" id="other_charges_amount" class="form-control" placeholder="Enter other charges amount" value="<?php echo $rowClientPaymentDetails['other_charges'] ?>" required></th>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <th><input type="text" name="discount_charges" id="discount_amount" class="form-control" placeholder="Enter discount charges amount" value="<?php echo $rowClientPaymentDetails['discount_charges'] ?>" required></th>
                                </tr>
                                <tr>
                                    <th>Total Charges</th>
                                    <th><input type="text" name="total_charges" id="total_charges_output" class="form-control" placeholder="Enter total charges amount" value="<?php echo $rowClientPaymentDetails['total_charges'] ?>" required readonly></th>
                                </tr>
                            </thead>
                        </table>

                        <div class="form-group row text-right">
                            <div class="col-sm-12 text-right">
                                <?php include '../_partials/cancel.php'?>
                                <button type="submit" name="editPayment" class="btn btn-primary waves-effect waves-light">Edit Payment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </form>
    </div><!-- container fluid -->
</div> <!-- Page content Wrapper -->
</div> <!-- content -->
<?php include('../_partials/footer.php') ?>

</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
<!-- jQuery  -->
        <?php include('../_partials/jquery.php') ?>

<!-- Required datatable js -->
        <?php include('../_partials/datatable.php') ?>

<!-- Buttons examples -->
        <?php include('../_partials/buttons.php') ?>

<!-- Responsive examples -->
        <?php include('../_partials/responsive.php') ?>

<!-- Datatable init js -->
        <?php include('../_partials/datatableInit.php') ?>


<!-- Sweet-Alert  -->
        <?php include('../_partials/sweetalert.php') ?>


<!-- App js -->
        <?php include('../_partials/app.php') ?>

<script>
    // Function to calculate the total charges
    function calculateTotal() {
        // 1. Get the values from the input fields
        // The '+' prefix converts the string value from the input into a number (float or integer)
        const packageAmount = +document.getElementById('package_amount').value || 0;
        const installationAmount = +document.getElementById('installation_amount').value || 0;
        const cableAmount = +document.getElementById('cable_amount').value || 0;
        const otherChargesAmount = +document.getElementById('other_charges_amount').value || 0;
        const discountAmount = +document.getElementById('discount_amount').value || 0;
        
        // 2. Perform the calculation
        // Total = (Package + Installation + Cable + Other Charges) - Discount
        const subTotal = packageAmount + installationAmount + cableAmount + otherChargesAmount;
        let totalCharges = subTotal - discountAmount;
        
        // Ensure the total doesn't go below zero (optional, but good practice)
        if (totalCharges < 0) {
            totalCharges = 0;
        }

        // 3. Update the Total Charges input field
        document.getElementById('total_charges_output').value = totalCharges; // .toFixed(2) for 2 decimal places
    }

    // --- Add Event Listeners ---
    
    // Get all the input fields that should trigger the calculation
    const inputs = [
        document.getElementById('package_amount'),
        document.getElementById('installation_amount'),
        document.getElementById('cable_amount'),
        document.getElementById('other_charges_amount'),
        document.getElementById('discount_amount')
    ];

    // Loop through each input and attach the calculateTotal function to the 'keyup' event
    inputs.forEach(input => {
        if (input) { // Check if the element exists
            input.addEventListener('keyup', calculateTotal);
            input.addEventListener('change', calculateTotal); // Also listen for 'change' (e.g., losing focus)
        }
    });

    // Run the calculation once on page load to set the initial total
    window.onload = calculateTotal;

</script>
</body>

</html>