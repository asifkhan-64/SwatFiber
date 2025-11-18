<?php
include('../_stream/config.php');
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$alreadyAdded = '';
$added = '';
$error = '';

$workerId = $_GET['id'];
$getWorkerData = mysqli_query($connect, "SELECT * FROM workers WHERE w_id = '$workerId'");
$fetchWorkerData = mysqli_fetch_assoc($getWorkerData);

if (isset($_POST['updateWorker'])) {
    $workerName = $_POST['workerName'];
    $workerContact = $_POST['workerContact'];
    $workerDailyHour = $_POST['workerDailyHour'];
    $workerHourlyRate = $_POST['workerHourlyRate'];
    $workerJoiningDate = $_POST['workerJoiningDate'];

    $countQuery = mysqli_query($connect, "SELECT COUNT(*)AS countedWorkers FROM workers WHERE worker_contact = '$workerContact'");
    $fetch_countQuery = mysqli_fetch_assoc($countQuery);

    if ($fetch_countQuery['countedWorkers'] == 0) {
        $insertWorker = mysqli_query($connect, "UPDATE workers SET worker_name = '$workerName', worker_contact = '$workerContact', worker_daily_hour = '$workerDailyHour', worker_hourly_rate = '$workerHourlyRate', worker_joining_date = '$workerJoiningDate' WHERE w_id = '$workerId'");
        if (!$insertWorker) {
            $error = '<div class="alert alert-dark" role="alert">
                                Worker Not Updated!
                             </div>';
        } else {
            header("LOCATION: worker_list.php");
        }
    } else {
        $alreadyAdded = '<div class="alert alert-dark" role="alert">
                                Worker Already Added!
                             </div>';
    }
}




include('../_partials/header.php');
?>

<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Worker Edit</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Name" type="text" id="example-text-input" name="workerName" required="" value="<?php echo $fetchWorkerData['worker_name']; ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Contact" type="number" id="example-text-input" name="workerContact" required="" value="0<?php echo $fetchWorkerData['worker_contact']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Worker Daily Hour</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Worker Daily Hour" type="number" id="example-text-input" step="any" name="workerDailyHour" required="" value="<?php echo $fetchWorkerData['worker_daily_hour']; ?>">
                                </div>

                                <label for="example-text-input" class="col-sm-2 col-form-label">Hourly Rate</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Hourly Rate" type="number" id="example-text-input" step="any" name="workerHourlyRate" required="" value="<?php echo $fetchWorkerData['worker_hourly_rate']; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Joining Date</label>
                                <div class="col-sm-4">
                                    <input class="form-control" placeholder="Joining Date" type="date" id="example-text-input" name="workerJoiningDate" required="" value="<?php echo $fetchWorkerData['worker_joining_date']; ?>">
                                </div>
                            </div>
                            
                            <hr>

                            <div class="form-group row">
                                <!-- <label for="example-password-input" class="col-sm-2 col-form-label"></label> -->
                                <div class="col-sm-12" align="right">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="updateWorker">Update Worker</button>
                                </div>
                            </div>
                        </form>
                        <h5 align="center"><?php echo $error ?></h5>
                        <h5 align="center"><?php echo $added ?></h5>
                        <h5 align="center"><?php echo $alreadyAdded ?></h5>
                    </div>
                </div>
            </div>
            
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

<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">
    $('.supplier').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
</script>
</body>

</html>