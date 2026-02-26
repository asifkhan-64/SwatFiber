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
                
                <h5 class="page-title">De-Active Clients</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">De-Active Clients List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UserID</th>
                                    <th>Name</th>
                                    <th>F/Name</th>
                                    <th>Package</th>
                                    <th>Contact</th>
                                    <th>Date</th>
                                    <?php if($fetchUserRole['user_role'] == 1 || $fetchUserRole['user_role'] == 4){?><th class="text-center"> Added By </th><?php } ?>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> Edit </th><?php } ?>
                                    <th class="text-center"> Images </th>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> Payments </th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retrieveUsers = mysqli_query($connect, "SELECT client_tbl.*, area.area_name, package_list.package_name, package_list.package_price, installation_type.ins_type, installation_type.ins_price, login_user.name AS staffName FROM `client_tbl`
                                INNER JOIN area ON area.id = client_tbl.area_id
                                INNER JOIN package_list ON package_list.p_id = client_tbl.package_id
                                INNER JOIN installation_type ON installation_type.ins_id = client_tbl.ins_id
                                INNER JOIN login_user ON login_user.id = client_tbl.added_by
                                WHERE client_tbl.added_by = '$addedBy' AND client_tbl.client_status = '0' ORDER BY client_tbl.doc DESC");

                                $iterationUser = 1;
                                $admin = 'Admininistration';
                                $Technician = 'Technician';
                                $Accounts = 'Accounts';
                                

                                $active = 'Active';
                                $inActive = 'In-Active';

                                while ($userRow = mysqli_fetch_assoc($retrieveUsers)) {
                                        $allContact = explode('-', $userRow['contact']);
                                        

                                        echo '
                                        <tr>
                                            <td>'.$iterationUser++.'.'.'</td>
                                            <td><strong>'.$userRow['user_id'].'</strong></td>
                                            <td>'.$userRow['name'].'</td>
                                            <td>'.$userRow['father_name'].'</td>
                                            <td>'.$userRow['package_name'].'- Rs. '.$userRow['package_price'].'</td>
                                            <td><a href="tel:'.$allContact[0].$allContact[1].'" style="decoration: none !important; color: black" class="Blondie"><strong>'.$userRow['contact'].'</strong></a></td>
                                            <td>'.$userRow['doc'].'</td>';
                                            if($fetchUserRole['user_role'] == 1 || $fetchUserRole['user_role'] == 4){
                                                echo '<td>'.$userRow['staffName'].'</td>';
                                            }

                                            if($fetchUserRole['user_role'] == 4){}else {
                                            echo '
                                                    <td>
                                                        <a href="./client_edit.php?id='.$userRow['client_id'].'" type="button" class="btn text-white btn-success waves-effect waves-light">Edit</a>
                                                    </td>   ';
                                            }
                                            echo '
                                            
                                                

                                            <td>
                                                <a href="./client_view.php?id='.$userRow['client_id'].'" type="button" class="btn text-white btn-primary waves-effect waves-light">View</a>
                                            </td>';

                                            if($fetchUserRole['user_role'] == 4){}else {
                                                if ($userRow['payment_status'] === '0') {
                                                    echo '
                                                    <td>
                                                        <a href="./add_payment.php?client_id='.$userRow['client_id'].'" type="button" class="btn text-white btn-secondary waves-effect waves-light">Pay</a>
                                                    </td>';
                                                }else {
                                                    echo '<td>Pay <i class="fa fa-check"></i></td>';
                                                }
                                            }

                                            echo '
                                            
                                            
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