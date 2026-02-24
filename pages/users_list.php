<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    
    include('../_partials/header.php'); 
?>
<link href="../assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Users</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Users List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Password</th>
                                    <th class="text-center"> Edit <i class="fa fa-edit"></i></th>
                                    <th class="text-center"> Images <i class="fa fa-image"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retrieveUsers = mysqli_query($connect, "SELECT * FROM login_user");

                                $iterationUser = 1;
                                $admin = 'Admin';
                                $Technician = 'Field Staff';
                                $Accounts = 'Accountant';
                                $Partner = 'Partner';
                                

                                $active = 'Active';
                                $inActive = 'In-Active';

                                while ($userRow = mysqli_fetch_assoc($retrieveUsers)) {
                                    if ($userRow['user_role'] === '1' || $userRow['user_role'] === '2' || $userRow['user_role'] === '3' || $userRow['user_role'] == '4' || $userRow['user_role'] == '5' || $userRow['user_role'] == '6' || $userRow['user_role'] == '7' || $userRow['user_role'] == '10' || $userRow['user_role'] == '11' || $userRow['user_role'] == '12') {
                                        echo '
                                        <tr>
                                            <td>'.$iterationUser++.'.'.'</td>
                                            <td>'.$userRow['name'].'</td>
                                            <td>'.$userRow['email'].'</td>';
                                            if ($userRow['user_role'] == '1') {
                                                echo '<td>'.$admin.'</td>';
                                            }elseif ($userRow['user_role'] == '2') {
                                                echo '<td>'.$Technician.'</td>';   
                                            }elseif ($userRow['user_role'] == '3') {
                                                echo '<td>'.$Accounts.'</td>';   
                                            }
    
    
    
                                            if ($userRow['status'] == '1') {
                                                echo '<td>'.$active.'</td>';
                                            }else {
                                                echo '<td>'.$inActive.'</td>';
                                            }
                                            echo'
                                            <td>'.$userRow['password'].'</td>
                                            
                                            <td>
                                                <a href="./user_edit.php?id='.$userRow['id'].'" type="button" class="btn text-white btn-success waves-effect waves-light">Edit</a>
                                            </td>      

                                            <td>
                                                <a href="./user_view.php?id='.$userRow['id'].'" type="button" class="btn text-white btn-primary waves-effect waves-light">View</a>
                                            </td>                                        
                                        </tr>';
                                    }
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