<?php
include '../_stream/config.php';
session_start();
if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
}


$userAlreadyinDatabase = '';
$expenseNotAdded = '';


if (isset($_POST["addComplaint"])) {
	$client_id = $_POST['client_id'];
	$tech_id = $_POST['tech_id'];
	$complaint_desc = $_POST['complaint_desc'];

    $getUser = $_SESSION["user"];
        $getUserQuery = mysqli_query($connect, "SELECT * FROM login_user WHERE email = '$getUser'");
        $fetch_getUserQuery = mysqli_fetch_assoc($getUserQuery);
        $addedBy = $fetch_getUserQuery['id'];

    $getComplaint = mysqli_query($connect, "SELECT * FROM complaint WHERE client_id = '$client_id' ");
    $fetch_status = mysqli_fetch_assoc($getComplaint);

    if ($fetch_status['complaint_status'] == '1') {
        $userAlreadyinDatabase = "<div class='alert alert-danger'>Complaint already exists for this user!";
    }else {
        $createComplaint = mysqli_query($connect, "INSERT INTO complaint(client_id, tech_id, complaint_desc, added_by)VALUES('$client_id', '$tech_id', '$complaint_desc', '$addedBy')");

        if (!$createComplaint) {
            $expenseNotAdded = "<div class='alert alert-danger'>Complaint not added! Try Again.</div>";
        } else {
            header("LOCATION: complaint_list.php");
        }
    }

	
}

include '../_partials/header.php'
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-title">Complaint</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Add Complaint</h4>
                        <form method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">User</label>
                                <div class="col-sm-4">
                                <?php
                                $selectExpenseCat = mysqli_query($connect, "SELECT * FROM client_tbl");
                                    $optionsCategory = '<select class="form-control designation" name="client_id" required="" style="width:100%">';
                                      while ($rowCat = mysqli_fetch_assoc($selectExpenseCat)) {
                                        $optionsCategory.= '<option value='.$rowCat['client_id'].'>'.$rowCat['user_id'].' - '.$rowCat['name'].'</option>';
                                      }
                                    $optionsCategory.= "</select>";
                                echo $optionsCategory;
                                ?>
                                </div>

                                <label class="col-sm-2 col-form-label">Technician</label>
                                <div class="col-sm-4">
                                <?php
                                $selectUsers = mysqli_query($connect, "SELECT * FROM login_user WHERE user_role = '2' AND status = '1'");
                                    $optionUsers = '<select class="form-control designations" name="tech_id" required="" style="width:100%">';
                                      while ($rowCat = mysqli_fetch_assoc($selectUsers)) {
                                        $optionUsers.= '<option value='.$rowCat['id'].'>'.$rowCat['name'].' - 0'.$rowCat['contact'].'</option>';
                                      }
                                    $optionUsers.= "</select>";
                                echo $optionUsers;
                                ?>
                                </div>
                            </div>
                            

                            <div class="form-group row">
                           

                                <label class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea id="textarea" class="form-control" maxlength="225" rows="1" name="complaint_desc" placeholder="Complaint Description" required=""></textarea>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <?php include '../_partials/cancel.php'?>
                                    <button type="submit" name="addComplaint" class="btn btn-primary waves-effect waves-light">Add Complaint</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <h3 align="center">
                    <?php echo $userAlreadyinDatabase; ?>
                </h3>
                <h3 align="center">
                    <?php echo $expenseNotAdded; ?>
                </h3>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container fluid -->
</div> <!-- Page content Wrapper -->
</div> <!-- content -->
<?php include '../_partials/footer.php'?>
</div>
<!-- End Right content here -->
</div>
<!-- END wrapper -->
<!-- jQuery  -->
<?php include '../_partials/jquery.php'?>
<!-- App js -->
<?php include '../_partials/app.php'?>
<?php include '../_partials/datetimepicker.php'?>
<script type="text/javascript">
$(".form_datetime").datetimepicker({
    format: "yyyy-mm-dd hh:ii"
});
</script>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">
$('.designation').select2({
    placeholder: 'Select an option',
    allowClear: true

});

$('.attendant').select2({
    placeholder: 'Select an option',
    allowClear: true

});

$('.designations').select2({
        placeholder: 'Select an option',
        allowClear: true

    });
</script>
<script type="text/javascript">
function checkDoctor() {
    let desg = document.querySelector('#designation');
    if (desg.value.toLowerCase() == 'doctor') {

        document.querySelector('#visitcharges').style.display = '';
    } else {
        document.querySelector('#visitcharges').style.display = 'none';


    }

}
</script>
</body>

</html>