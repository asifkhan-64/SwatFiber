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
                
                <h5 class="page-title">Inventory</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Inventory List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Date of Purchase</th>
                                    <th class="text-center"> Edit <i class="fa fa-edit"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retInventory = mysqli_query($connect, "SELECT * FROM inventory_details ORDER BY inventory_id DESC");

                                $iterationUser = 1;
                                $admin = 'Admininistration';
                                $Technician = 'Technician';
                                $Accounts = 'Accounts';
                                

                                $active = 'Active';
                                $inActive = 'In-Active';

                                while ($inventoryRow = mysqli_fetch_assoc($retInventory)) {
                                        echo '
                                        <tr>
                                            <td>'.$iterationUser++.'.'.'</td>
                                            <td>'.$inventoryRow['item_name'].'</td>
                                            <td>'.$inventoryRow['item_qty'].'</td>
    
                                            <td>'.$inventoryRow['price'].'</td>
                                            <td>'.$inventoryRow['date_of_purchase'].'</td>
                                            
                                            <td>
                                                <a href="./inventory_edit.php?id='.$inventoryRow['inventory_id'].'" type="button" class="btn text-white btn-primary waves-effect waves-light">View</a>
                                            </td>                                        
                                        </tr>';
                                    
                                }
                                            // <a type="button" href="#" class="btn text-white btn-danger waves-effect waves-light changeUserStatus" id="change" data-id='.$inventoryRow['id'].'>Delete</a>
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