<?php
include '../_stream/config.php';
session_start();

if (empty($_SESSION["user"])) {
    header("LOCATION:../index.php");
    exit();
}

    date_default_timezone_set('Asia/Karachi');
    $currentDate = date('Y-m-d');

    $client_id = $_POST["customer"];
    // $client_id = $_GET["customer"];
    $getClientData= mysqli_query($connect, "SELECT * FROM client_tbl WHERE client_id = '$client_id'");
    $fetch = mysqli_fetch_assoc($getClientData);


    echo $fetch['old_remaining'];

?>