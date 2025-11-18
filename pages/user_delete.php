<?php
 include('../_stream/config.php');
    session_start();
    if (empty($_SESSION["user"])) {
        header("LOCATION:../index.php");
    }

    // header('Content-type: application/json; charset=UTF-8');
    // $id = $_GET['delete'];

    // $response = array();

    // if ($_POST['delete']) {
    // 	$id = $_POST['delete'];

    // 	$queryDeactivateUser = mysqli_query($connect, "UPDATE login_user SET status = '1' WHERE id = '$id'");

    // 	if($queryDeactivateUser) {
    // 		$response['status'] = 'success';
    // 		$response['message'] = 'User Deactivated';
    // 	}else {
    // 		$response['status'] = 'error';
    // 		$response['message'] = 'Unable to delete User';
    // 	}
    // 	echo json_encode($response);
    // }

    // $id = $_GET['id'];
	// $Deletequery = mysqli_query($connect, "UPDATE login_user SET status = '0' WHERE id = '$id'");
	// header("LOCATION:users_list.php");

?>