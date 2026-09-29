<?php
include "db.php";
date_default_timezone_set('Asia/Kolkata');
$student_id = isset($_GET['id']) ? $_GET['id'] : 0;
$date = date('Y-m-d');
$time = date('H:i:s');
$type = 'Finger';

if($student_id != 0){
    $check = mysqli_query($conn, "SELECT * FROM attendance WHERE student_id='$student_id' AND attendance_date='$date' AND type='$type'");
    if(mysqli_num_rows($check) == 0){
        $sql = "INSERT INTO attendance (student_id, attendance_date, attendance_time, status, type) VALUES ('$student_id', '$date', '$time', 'Present', '$type')";
        mysqli_query($conn, $sql);
    }
}
echo "success";
?>