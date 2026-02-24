<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    

    $getUser = $_SESSION["user"];
    $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
    $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
    $addedBy = $fetch_getUserQuery['id'];

    include('../_partials/header.php'); 
?>
<link href="../assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Dues Payment</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Dues Payment List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UserID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Paid Amount</th>
                                    <th>All Dues</th>
                                    <th>Rem</th>
                                    <th>Date</th>
                                    <th class="text-center"><i class="fa fa-file-invoice"> </i></th>
                                    <th class="text-center"><i class="fab fa-whatsapp">    </i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retrieveUsers = mysqli_query($connect, "SELECT pay_done.*, client_tbl.name, client_tbl.user_id, client_tbl.contact FROM `pay_done`
                                        INNER JOIN client_tbl ON client_tbl.client_id = pay_done.client_id
                                        WHERE pay_done.added_by = '$addedBy' AND pay_done.pay_status = '0' ORDER BY pay_done.pay_date DESC");

                                $iterationUser = 1;
                                $admin = 'Admininistration';
                                $Technician = 'Technician';
                                $Accounts = 'Accounts';
                                

                                $active = 'Active';
                                $inActive = 'In-Active';

                                while ($userRow = mysqli_fetch_assoc($retrieveUsers)) {
                                        $allContact = explode('-', $userRow['contact']);
                                        $idUser = $userRow["added_by"];
                                        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE id = '$idUser'");
                                        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
                                        $nameUser = $fetch_getUserQuery['name'];
                                    
                                        

                                        echo '
                                        <tr>
                                            <td>'.$iterationUser++.'.'.'</td>
                                            <td><strong>'.$userRow['user_id'].'</strong></td>
                                            <td>'.$userRow['name'].'</td>
                                            <td><a href="tel:'.$allContact[0].$allContact[1].'" style="decoration: none !important; color: black" class="Blondie"><strong>'.$userRow['contact'].'</strong></a></td>
                                            <td>'.$userRow['amount'].'</td>
                                            <td>'.$userRow['dues'].'</td>
                                            <td>'.$userRow['rem_amount'].'</td>
                                            <td>'.$userRow['date_pay'].'</td>
                                            
                                            <td class="text-center">
                                                <a href="paid_bill_print.php?invoice='.$userRow['p_id'].'&client_id='.$userRow['client_id'].'" 
                                                    class="btn btn-info" 
                                                    target="_blank" 
                                                    rel="noopener noreferrer">
                                                        
                                                    <i class="fa fa-file-invoice"></i>
                                                </a>
                                            </td>
                                            ';

                                            $getCompanyDEtails = mysqli_query($connect, "SELECT * FROM `shop_info`");
                                            $rowCompanyDetails = mysqli_fetch_assoc($getCompanyDEtails);
                                            $explodeContact = explode("-", $userRow['contact']);
                                            $phone_number = $explodeContact[0] . $explodeContact[1]; // Concatenate country code and rest of the number


                                            // 2. Construct the payment details message string
                                            $message = "Hello " . $userRow['name'] . ",%0A%0AYour bill payment details are as follows:";
                                            
                                            // Package Details
                                            $message .= "%0ATotal Payment: PKR " . number_format($userRow['dues']);

                                            // Payment Date
                                            $message .= "%0APaid Amount: PKR " . number_format($userRow['amount']);
                                            
                                            // Add Package Amount
                                            $message .= "%0ARemaining Amount: PKR " . number_format($userRow['rem_amount']);

                                            // Add Installation Amount
                                            $message .= "%0A%0APayment Date: " . $userRow['date_pay'];

                                            // Add Cable Amount
                                            $message .= "%0A%0AReceived By: " . $fetch_getUserQuery['name'];

                                           
                                            // Add Closing Message
                                            $message .= ".%0A%0AThank you for your business!";

                                            // Company Info
                                            $message .= "%0A%0ARegards,%0A" . $rowCompanyDetails['shop_title']. ", ".$rowCompanyDetails['shop_name'];
                                            $message .= "%0AAddress: " . $rowCompanyDetails['shop_address'];
                                            $message .= "%0AContact: 0" . $rowCompanyDetails['shop_contact'];
                                            
                                            // NOTE: The message is already URL-encoded enough using %0A for new lines. 
                                            // You could wrap the whole $message in urlencode() for maximum safety.
                                            // $encoded_message = urlencode($message);

                                            // 3. Construct the full WhatsApp URL
                                            $whatsapp_url = "https://wa.me/92{$phone_number}?text={$message}";

                                            echo '

                                            <td>
                                                <a href="'.$whatsapp_url.'" 
                                                    class="btn btn-success" 
                                                    target="_blank" 
                                                    rel="noopener noreferrer"
                                                    style="background-color: #25D366; border-color: #25D366;">
                                                        
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            </td>                                        
                                        </tr>';
                                    
                                }
                                            // <a type="button" href="#" class="btn text-white btn-danger waves-effect waves-light changeUserStatus" id="change" data-id='.$userRow['id'].'>Delete</a>
                                            // <a class="btn text-white btn-danger waves-effect waves-light changeUserStatus" id="deleteAccount" type="button" href="">Delete</a>
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
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

<!-- Buttons examples -->
        <?php include('../_partials/buttons.php') ?>

<!-- Responsive examples -->
        <?php include('../_partials/responsive.php') ?>

<!-- Datatable init js -->
<script src="../assets/pages/datatables.init.js"></script>
<!-- App js -->
        <?php include('../_partials/app.php') ?>

<!-- Sweet-Alert  -->
        <?php 
        // include('../_partials/sweetalert.php')
         ?>


<script type="text/javascript" src="../assets/package/dist/sweetalert2.all.min.js"></script>
</body>

</html>