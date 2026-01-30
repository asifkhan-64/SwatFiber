<?php
    include('../_stream/config.php');
    session_start();
        if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }


    include('../_partials/header.php');
?>
<link href="../assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">

<style>

    .blink-dot {
  height: 20px;
  width: 20px;
  border-radius: 50%;
  display: inline-block;
  /* The animation: name | duration | timing-function | iteration-count */
  animation: blinker 1.5s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

    .pulse-light {
  width: 20px;
  height: 20px;
  background-color: #198754; /* Bootstrap Success Green */
  border-radius: 50%;
  box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
  animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
  0% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
  }
  70% {
    transform: scale(1);
    box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
  }
  100% {
    transform: scale(0.95);
    box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
  }
}
</style>

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
                        <h4 class="mt-0 header-title">Complaint List</h4>
                        <table id="datatable" class="table  dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Client</th>
                                    <th>Contact</th>
                                    <th>Technician</th>
                                    <th>Description</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th class="text-center"> <i class="fa fa-edit"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $selectComplaints = mysqli_query($connect, "SELECT complaint.*, client_tbl.*, client_tbl.name AS clientName, login_user.* FROM `complaint`
                                INNER JOIN client_tbl ON client_tbl.client_id = complaint.client_id
                                INNER JOIN login_user ON login_user.id = complaint.tech_id
                                ORDER BY complaint.com_id DESC");
                                $iteration = 1;

                                while ($rowClient = mysqli_fetch_assoc($selectComplaints)) {
                                    echo '
                                        <tr>
                                            <td>'.$iteration++.'</td>
                                            <td>'.$rowClient['user_id'].'</td>
                                            <td>'.$rowClient['clientName'].'</td>
                                            <td><a href="tel:+'.$rowClient['contact'].'" class="Blondie">+'.$rowClient['contact'].'<a/></td>
                                            <td>'.$rowClient['name'].'</td>
                                            <td>'.$rowClient['complaint_desc'].'</td>
                                            <td>'.$rowClient['address'].'</td>';

                                            if ($rowClient['complaint_status'] == '1') {
                                                echo '
                                                <td>
                                                    <div class="d-flex align-items-center p-3">
                                                        <span class="blink-dot bg-danger"></span>
                                                    </div>
                                                </td>';
                                            } else {
                                               echo '
                                                <td>
                                                    <div class="d-flex align-items-center p-3">
                                                        <span class="pulse-light"></span>
                                                    </div>
                                                </td>';
                                            }

                                            echo '
                                            <td class="text-center">
                                                <a href="complaint_edit.php?id='.$rowClient['com_id'].'" type="button" class="btn text-white btn-warning waves-effect waves-light btn-sm">Edit</a>
                                            </td>
                                            ';
                                            

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