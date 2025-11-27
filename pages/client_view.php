<?php
    include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }
    $userAlreadyinDatabase = '';
    $userNotAdded = '';
    $userAdded = '';

    $id = $_GET['id'];
    $getClientData = mysqli_query($connect, "SELECT * FROM client_tbl WHERE client_id = '$id'");
    $fetUserData = mysqli_fetch_assoc($getClientData);
    
    

    include('../_partials/header.php') 
?>
<!-- Top Bar End -->
<div class="page-content-wrapper ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                
                <h5 class="page-title">Client Images</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <form method="POST" enctype="multipart/form-data">

                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <h4 class="mt-0 header-title text-center">Modem (lat/lan)</h4>
                            </div>
                            <div class="col-4">
                                <h4 class="mt-0 header-title text-center">CNIC Front</h4>
                            </div>
                            <div class="col-4">
                                <h4 class="mt-0 header-title text-center">CNIC Back</h4>
                            </div>
                        </div>

                        <div class="form-group row"> 
                            <?php
                            if (empty($fetUserData['modem_image'])) {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce" align="center">
                                    <img src="../assets/sample.png" width="100%" height="auto" > 
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce" align="center">
                                    <img src="../__images/'.$fetUserData['modem_image'].' "width="100%" style="border-radius: 10px; box-shadow: 3px 3px 3px 3px #ccc" height="auto" >
                                    
                                </div>
                                ';
                            }
                            ?>

                            <?php
                            if (empty($fetUserData['cnic_front'])) {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce" align="center">
                                    <img src="../assets/sample.png" width="100%" height="auto" > 
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce" align="center">
                                    <img src="../__images/'.$fetUserData['cnic_front'].' "width="100%" style="border-radius: 10px; box-shadow: 3px 3px 3px 3px #ccc" height="auto" >
                                    
                                </div>
                                ';
                            }
                            ?>

                            <?php
                            if (empty($fetUserData['cnic_back'])) {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce" align="center">
                                    <img src="../assets/sample.png" width="100%" height="auto" > 
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center animate__animated animate__bounce grow" align="center">
                                    <img src="../__images/'.$fetUserData['cnic_back'].' "width="100%" style="border-radius: 10px; box-shadow: 3px 3px 3px 3px #ccc" height="auto" >
                                </div>
                                ';
                            }
                            ?>
                        </div>

                        <hr>

                        <div class="form-group row"> 
                            <?php
                            if (empty($fetUserData['modem_image'])) {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_modem_client.php?id='.$id.'" class="btn btn-secondary">Add Image</a>
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_modem_client.php?id='.$id.'" class="btn btn-warning">Update Image</a>
                                    
                                </div>
                                ';
                            }
                            ?>

                            <?php
                            if (empty($fetUserData['cnic_front'])) {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_cnicfront_client.php?id='.$id.'" class="btn btn-secondary">Add Image</a>
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_cnicfront_client.php?id='.$id.'" class="btn btn-primary">Update Image</a>
                                </div>
                                ';
                            }
                            ?>

                            <?php
                            if (empty($fetUserData['cnic_back'])) {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_cnicback_client.php?id='.$id.'" class="btn btn-secondary">Add Image</a>
                                </div>
                                ';
                            }else {
                                echo '
                                <div class="col-sm-4 text-center" align="center">
                                    <a href="upload_cnicback_client.php?id='.$id.'" class="btn btn-success">Update Image</a>
                                </div>
                                ';
                            }
                            ?>
                        </div>



                    </div>
                </div>

                </form>
                
                <h3 align="center">
                    <?php echo $userAlreadyinDatabase; ?>
                </h3>
                <h3 align="center">
                    <?php echo $userAdded; ?>
                </h3>
                <h3 align="center">
                    <?php echo $userNotAdded; ?>
                </h3>
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
<script>
$(document).ready(function() {
    $('form').parsley();
});
</script>
<script type="text/javascript" src="../assets/js/select2.min.js"></script>
<script type="text/javascript">

$('.select2').select2({
    placeholder: 'Select Option',
    allowClear: true

});

$('.area').select2({
    placeholder: 'Select Option',
    allowClear: true

});
</script>

<script src="https://unpkg.com/imask"></script>
<script>
    const phoneInput = document.getElementById('phone-mask');
    const maskOptions = {
        mask: '{92}0000000000'
    };
    const mask = IMask(phoneInput, maskOptions);
</script>
</body>

</html>