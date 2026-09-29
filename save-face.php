<?php
include "db.php";
$name = isset($_GET['name']) ? trim($_GET['name']) : '';
if($name == ''){
  echo "Name illa da";
  exit();
}
$safe = mysqli_real_escape_string($conn, $name);
$today = date('Y-m-d');

$q = mysqli_query($conn, "SELECT id FROM students WHERE name LIKE '%$safe%' LIMIT 1");
if(mysqli_num_rows($q) == 0){
  echo "Student illa da";
  exit();
}
$row = mysqli_fetch_assoc($q);
$sid = $row['id'];

$chk = mysqli_query($conn, "SELECT id FROM attendance WHERE student_id='$sid' AND attendance_date='$today'");
if(mysqli_num_rows($chk) > 0){
  echo "Already Present da";
  exit();
}

mysqli_query($conn, "INSERT INTO attendance (student_id, attendance_date, status, type) VALUES ('$sid', '$today', 'Present', 'Face')");
echo "ok Save Aayiduchu da Pooja - $name ku Present vilunthuduchu da!";
?>