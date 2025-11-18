<?php
include('../_stream/config.php');

session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}

$notAdded = '';


if (isset($_POST['selectCustomer'])) {
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];

    header("LOCATION: report_expense_generate.php?datefrom=" . $datefrom . "&dateto=" . $dateto . "");
}


include('../_partials/header.php')
?>
<link rel="stylesheet" type="text/css" href="../assets/bootstrap-datetimepicker.css">
<!-- Top Bar End -->
<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Expenses Report</h5>
            </div>
        </div>

        <?php
        $last_monday_timestamp = strtotime('last Monday');

        $date = date('Y-m-d', $last_monday_timestamp);
        $year = date('Y', $last_monday_timestamp);
        $day_name = date('l', $last_monday_timestamp);

        $todayDate = date('Y-m-d');
        ?>

        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Date From</label>
                                <div class="col-sm-4">
                                    <input type="date" name="datefrom" value="<?= $date; ?>" placeholder="" class="form-control pull-right" required="">
                                </div>

                                <label class="col-sm-2 col-form-label">Date To</label>
                                <div class="col-sm-4">
                                    <input type="date" name="dateto" value="<?= $todayDate; ?>" class="form-control pull-right" required="">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <?php include('../_partials/cancel.php') ?>
                                    <button type="submit" name="selectCustomer" class="btn btn-primary waves-effect waves-light">Generate Report</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <h3>
                        <?php echo $notAdded; ?>
                    </h3>
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
<!-- App js -->
<?php include('../_partials/app.php') ?>
<?php include('../_partials/datetimepicker.php') ?>

<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii"
    });
</script>

<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">
    $('.comp').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
</script>
</body>

</html>