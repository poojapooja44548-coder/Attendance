<?php
include 'db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Attendance_Report.xls");
?>
<table border="1">
<tr>
<th>Roll No</th>
<th>Name</th>
<th>Total</th>
<th>Present</th>
<th>Absent</th>
<th>Percentage</th>
</tr>
<?php
$students = $conn->query("SELECT * FROM students");
while($s = $students->fetch_assoc()){
    $sid = $s['id'];
    
    $q1 = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$sid");
    $r1 = $q1->fetch_assoc();
    $total = $r1['c'];

    $q2 = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE student_id=$sid AND status='Present'");
    $r2 = $q2->fetch_assoc();
    $present = $r2['c'];

    $absent = $total - $present;
    if($total > 0){ $per = round(($present/$total)*100,2); } else { $per = 0; }

    echo "<tr>";
    echo "<td>".$s['roll_no']."</td>";
    echo "<td>".$s['name']."</td>";
    echo "<td>".$total."</td>";
    echo "<td>".$present."</td>";
    echo "<td>".$absent."</td>";
    echo "<td>".$per."%</td>";
    echo "</tr>";
}
?>
</table>