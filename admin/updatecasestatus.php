<?php
session_start();
error_reporting(0);
if(isset($_SESSION['admin']) && $_SESSION['admin']==true) {
  require_once "../functions/database_functions.php";
  $conn = db_connect();
  $id = trim($_POST['id']);
  $status = $_POST['selectedValue'];

  $conn = db_connect();
  if($status=="CLOSED") {
    $query = "UPDATE cases SET status='$status', priority='LOW PRIORITY', prioritynumber='4' WHERE ID='$id'";
  }
  else {
    $query = "UPDATE cases SET status='$status' WHERE ID='$id'";
  }
  $result = mysqli_query($conn, $query);
  header('Content-type: application/json');
  echo json_encode($result);
}
else {
  header("Location: ../index.php");
}
?>