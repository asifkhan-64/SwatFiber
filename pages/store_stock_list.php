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
                <h5 class="page-title">Store Stock</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Store Stock List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Condition</th>
                                    <th>Price</th>
                                    <th>Date of Purchase</th>
                                    <th>Description</th>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> <i class="fa fa-edit"></i></th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $retStoreItems = mysqli_query($connect, "SELECT store_stock.*, sl_items.* FROM `store_stock`
                                                 INNER JOIN sl_items ON sl_items.sl_id = store_stock.item_id
                                                 ORDER BY store_stock.date_of_purchase DESC");
                                $iteration = 1;

                                while ($rowStock = mysqli_fetch_assoc($retStoreItems)) {
                                    echo '
                                        <tr>
                                            <td>'.$iteration++.'</td>
                                            <td>'.$rowStock['item_name'].'</td>
                                            <td> <span class="badge badge-info" style="font-size: 16px">'.$rowStock['item_qty'].'</span></td>
                                            <td>'.$rowStock['item_condition'] .'</td>
                                            <td>'.$rowStock['price'] .'</td>
                                            <td>'.$rowStock['date_of_purchase'] .'</td>
                                            <td>'.$rowStock['item_description'] .'</td>
                                            ';

                                            if($fetchUserRole['user_role'] == 4){}else {
                                            echo '
                                            <td class="text-center">
                                                <a href="store_stock_edit.php?id='.$rowStock['store_st_id'].'" type="button" class="btn text-white btn-warning waves-effect waves-light btn-sm">Edit</a>
                                            </td>
                                            ';
                                            }
                                            

                                            echo '
                                        </tr>
                                    ';
                                }
                                            // <td class="text-center"><a href="./user_edit.php" type="button" class="btn text-white btn-warning waves-effect 
                                            //waves-light">Edit</a></td>
                                ?>
                                
                                    
                            </tbody>
                        </table>
                        <script type="text/javascript">
        function deleteme(delid){
          if (confirm("Do you want to discharge patient?")) {
            window.location.href = 'temporary_disable.php?del_id=' + delid +'';
            return true;
          }
        }
      </script>
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
</body>

</html>