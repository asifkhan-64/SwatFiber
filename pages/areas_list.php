<?php
    include('../_stream/config.php');
    session_start();
        if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    $alreadyAdded = '';
    $added = '';
    $error= '';

    if (isset($_POST['addArea'])) {
        $areaName = $_POST['areaName'];

        $countQuery = mysqli_query($connect, "SELECT COUNT(*)AS countedAreas FROM area WHERE area_name = '$areaName'");
        $fetch_countQuery = mysqli_fetch_assoc($countQuery);


        if ($fetch_countQuery['countedAreas'] == 0) {
            $insertQuery = mysqli_query($connect, "INSERT INTO area(area_name)VALUES('$areaName')");
            if (!$insertQuery) {
                $error = 'Not Added! Try agian!';
            }else {
                $added = '
                <div class="alert alert-primary" role="alert">
                                Area Added!
                             </div>';
            }
        }else {
            $alreadyAdded = '<div class="alert alert-dark" role="alert">
                                Area Already Added!
                             </div>';
        }
    }


    include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Areas</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <?php
            if($fetchUserRole['user_role'] == 4){}else {
            ?>
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="Area" type="text" value="" id="example-text-input" name="areaName" required="">
                                </div>
                            </div><hr>
                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="addArea">Add Area</button>
                                </div>
                            </div>
                        </form>
                        <h5 align="center"><?php echo $error ?></h5>
                        <h5 align="center"><?php echo $added ?></h5>
                        <h5 align="center"><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
            </div>
            <?php }?>

            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Area Details</h4>
                       
                        <table id="datatable" class="table dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <?php if($fetchUserRole['user_role'] == 4){}else { ?><th class="text-center"> <i class="fa fa-edit"></i></th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $retArea = mysqli_query($connect, "SELECT * FROM area");
                                $iteration = 1;

                                while ($rowArea = mysqli_fetch_assoc($retArea)) {
                                    echo '
                                    <tr>
                                        <td>'.$iteration++.'</td>
                                        <td>'.$rowArea['area_name'].'</td>';
                                         if($fetchUserRole['user_role'] == 4){}else {
                                         echo '<td class="text-center"><a href="area_edit.php?id='.$rowArea['id'].'" type="button" class="btn text-white btn-warning waves-effect waves-light">Edit</a></td>';
                                         }
                                         echo '
                                        
                                    </tr>
                                    ';
                                }
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