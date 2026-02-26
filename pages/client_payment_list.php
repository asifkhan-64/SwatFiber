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
                <h5 class="page-title animate__animated animate__bounce">Clients Payment</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Clients Payment List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Package</th>
                                    <th>Installation</th>
                                    <th>Cable</th>
                                    <th>Other</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>DOP</th>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> Edit <i class="fa fa-edit"></i></th><?php } ?>
                                    <th class="text-center"> View <i class="fa fa-eye"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retrieveUsers = mysqli_query($connect, "SELECT client_payments.*, client_tbl.name, client_tbl.contact, client_tbl.added_by FROM `client_payments`
                                INNER JOIN client_tbl ON client_tbl.client_id = client_payments.client_id
                                WHERE client_tbl.added_by = '$addedBy' ORDER BY client_tbl.client_id DESC");

                                $iterationUser = 1;
                                $admin = 'Admininistration';
                                $Technician = 'Technician';
                                $Accounts = 'Accounts';
                                

                                $active = 'Active';
                                $inActive = 'In-Active';

                                while ($userRow = mysqli_fetch_assoc($retrieveUsers)) {
                                        

                                        echo '
                                        <tr>
                                            <td>'.$iterationUser++.'.'.'</td>
                                            <td><strong>'.$userRow['name'].'</strong></td>
                                            <td>'.$userRow['contact'].'</td>
                                            <td>Pkr. '.$userRow['package_amount'].'</td>
                                            <td>Pkr. '.$userRow['installation_amount'].'</td>
                                            <td>Pkr. '.$userRow['cable_amount'].'</td>
                                            <td>Pkr. '.$userRow['other_charges'].'</td>
                                            <td>Pkr. '.$userRow['discount_charges'].'</td>
                                            <td>Pkr. '.$userRow['total_charges'].'</td>
                                            <td>'.$userRow['dop'].'</td>';
                                            if($fetchUserRole['user_role'] == 4){}else {
                                            echo '
                                            <td>
                                                <a href="./client_payment_edit.php?client_id='.$userRow['client_id'].'" type="button" class="btn text-white btn-success waves-effect waves-light">Edit</a>
                                            </td>';
                                            }
                                            echo '      

                                            <td>
                                                <a href="./client_payment_view.php?client_id='.$userRow['client_id'].'" type="button" class="btn text-white btn-primary waves-effect waves-light">View</a>
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