<?php
session_start();
error_reporting(0);
if(isset($_SESSION['assistant']) && $_SESSION['assistant']==true) {
  require_once "../functions/database_functions.php";
  $conn = db_connect();
  $id = trim($_POST['id']);
  $status = $_POST['selectedstatusValue'];
  $date = $_POST['selectedDateValue'];
  $time = $_POST['selectedTimeValue'];
  $conn = db_connect();

  if($status == "POSTPONED") {
	  $query = "UPDATE appointment SET status='$status', date='$date', time='$time' WHERE ID='$id'";
	  $result = mysqli_query($conn, $query);
  }
  else {
	  $query = "UPDATE appointment SET status='$status' WHERE ID='$id'";
	  $result = mysqli_query($conn, $query);
  }
  header('Content-type: application/json');
  echo json_encode($result);
}
else {
  header("Location: ../index.php");
}
?>