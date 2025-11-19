<?php
    include('../_stream/config.php');
    session_start();
        if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Options</h5>
            </div>
        </div>
        <!-- end row --><br><br><br><br>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-8">
                <div class="card m-b-30">
                    <div class="card-body">
                        
                        <h4 class="mt-0 header-title animate__animated animate__bounce" align="center">Please select from below</h4><hr>
                        <?php
	                        echo '
		                        <div align="center">
                                <a href="store_stock.php" class="btn btn-success btn-lg p-5 animate__animated animate__bounce" style="font-size: 18px">Store Item (Qty Based)</a>
		                        	<a href="line_stock.php" class="btn btn-info btn-lg p-5 animate__animated animate__bounce" style="font-size: 18px">Line Item (Line Based)</a>
		                        </div>
	                        ';
                        ?>

                    </div>
                </div>
            </div> <!-- end col -->
            <div class="col-md-2"></div>
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
</body>

</html>