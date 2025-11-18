<?php
  include("_stream/config.php");
  
  $valid_login = "";
  if (isset($_POST["log_in_session"])) {
    $user_email = $_POST["user_email"];
    $user_password = $_POST["user_password"];

    $result_query = mysqli_query($connect, "SELECT * FROM login_user WHERE email='$user_email' AND password='$user_password'");
    //  = mysqli_query($connect, $select_user_query);
    
    $fetch_userQuery = mysqli_fetch_assoc($result_query);

    if (empty($fetch_userQuery)) {
        $valid_login = '<div class="alert alert-danger" style="background:#D52520; color:white" role="alert">Enter a valid Login</div>';
    } else {
        $user_status = $fetch_userQuery['status'];
        $user_role = $fetch_userQuery['user_role'];
        $id = $fetch_userQuery['id'];

        if ($user_status == 1) {
            session_start();
            $_SESSION["user"] = $user_email;
            $_SESSION["id"] = $id;
            if ($user_role == '1') {
                header("LOCATION:pages/dashboard.php");
            } elseif ($user_role == '2') {
                header("LOCATION:pages/dashboard.php");
            } elseif ($user_role == '3') {
                header("LOCATION:pages/patient_active_view.php");
            } elseif ($user_role == '4') {
                header("LOCATION:pages/lab_test_list_view.php");
            } elseif ($user_role == '5') {
                header("LOCATION:pages/pharmacy_list.php");
            } elseif ($user_role == '6') {
                header("LOCATION:pages/dashboardCustom.php");
            } elseif ($user_role == '7') {
                header("LOCATION:pages/groundFloorDBoard.php");
            } elseif ($user_role == '8') {
                header("LOCATION:pages/doctorDashboard.php");
            } elseif ($user_role == '9') {
                header("LOCATION:pages/anestheticDashboard.php");
            } elseif ($user_role == '10') {
                header("LOCATION:pages/moDashboard.php");
            } elseif ($user_role == '11') {
                header("LOCATION:__lab/dashboard.php");
            } elseif ($user_role == '12') {
                header("LOCATION:__lab/dashboard.php");
            }
        } else {
            $valid_login = ' <div class="alert alert-danger" style="background:#D52520; color:white" role="alert">
            Access Denied. You have been restricted.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Swat Fiber</title>
        <meta content="Swat Fiber" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="assets/logo.png">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style1.css" rel="stylesheet" type="text/css">

    </head>


    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Begin page -->
        <div class="accountbg">
            
            <div class="content-center">
                <div class="content-desc-center">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 col-md-8">
                                <div class="card" style="box-shadow: 3px 3px 15px 3px #E7EAED; opacity: 0.95 !important;">
                                    <div class="card-body">
                                        <h3 class="text-center mt-0 m-b-15">
                                            <a  class="logo logo-admin"><img src="assets/logo.png" width="18%" height="20%"> <h3 style="font-family: Lucida Handwriting">Swat Fiber</h3></a>
                                        </h3>
                
                                        <!-- <h4 class="text-muted text-center font-18"><b>Sign In</b></h4> -->
                                        <hr>
                
                                        <div class="p-2">
                                            <form class="form-horizontal m-t-20" method="POST">
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <input class="form-control" name="user_email" type="text" required="" placeholder="E-Mail">
                                                    </div>
                                                </div>
                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        <input class="form-control" name="user_password" type="password" required="" placeholder="Password">
                                                    </div>
                                                </div>                
                                                <div class="form-group text-center row m-t-20 mt-5">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit" name="log_in_session">Log In</button>
                                                    </div>
                                                </div>
                
                                                <div class="form-group m-t-10 mb-0 row">
                                                   
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                 <button type="button"  class="btn btn-primary waves-effect waves-light d-none" id="alertify-error">Click me</button>
                            <h5 align="center"><?php echo $valid_login ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

         <!-- Alertify js -->
        <script src="assets/plugins/alertify/js/alertify.js"></script>
         <script src="assets/pages/alertify-init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>


        

    </body>
</html>