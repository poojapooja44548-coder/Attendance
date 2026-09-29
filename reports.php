<?php
session_start();
include "db.php";
if (!isset($_SESSION['username'])) { header("Location: index.php"); exit(); }

$tot_q = mysqli_query($conn, "SELECT COUNT(DISTINCT attendance_date) as tot FROM attendance");
$tot_row = mysqli_fetch_assoc($tot_q);
$total_working_days = (int)$tot_row['tot'];
if($total_working_days < 1) $total_working_days = 8;

$sql = "SELECT s.id, s.roll_no, s.name, s.department, s.year, 
COUNT(DISTINCT CASE WHEN a.status='Present' THEN a.attendance_date END) AS present_days,
SUM(CASE WHEN a.status='Present' AND a.type='Face' THEN 1 ELSE 0 END) AS face_days, 
SUM(CASE WHEN a.status='Present' AND a.type='Finger' THEN 1 ELSE 0 END) AS finger_days 
FROM students s 
LEFT JOIN attendance a ON s.id = a.student_id 
GROUP BY s.id ORDER BY s.roll_no ASC";

$result = mysqli_query($conn, $sql);
$total_students=0; $total_present=0; $total_absent=0; $students_report=array();
while($row=mysqli_fetch_assoc($result)){ 
    $total_students++; 
    $present = (int)$row['present_days'];
    $total_present += $present; 
    $row['total_days'] = $total_working_days; 
    $row['absent_days'] = $total_working_days - $present;
    if($row['absent_days'] < 0) $row['absent_days'] = 0;
    $total_absent += (int)$row['absent_days']; 
    $row['percentage'] = ($total_working_days>0) ? ($present/$total_working_days)*100 : 0; 
    if($row['percentage'] > 100) $row['percentage'] = 100;
    $students_report[]=$row; 
}
$overall_total = $total_students * $total_working_days;
$overall_percentage = $overall_total>0 ? ($total_present/$overall_total)*100 : 0;

$present_q = mysqli_query($conn, "SELECT s.roll_no, s.name, s.department, s.year, a.attendance_date, a.type, a.status FROM attendance a JOIN students s ON s.id=a.student_id WHERE a.status='Present' ORDER BY a.attendance_date DESC");

$absent_q = mysqli_query($conn, "
SELECT s.roll_no, s.name, s.department, s.year, d.attendance_date, '' as type, 'Absent' as status
FROM (SELECT DISTINCT attendance_date FROM attendance WHERE attendance_date IS NOT NULL) d
CROSS JOIN students s
LEFT JOIN attendance a ON a.student_id = s.id AND a.attendance_date = d.attendance_date AND a.status='Present'
WHERE a.id IS NULL
ORDER BY d.attendance_date DESC, s.roll_no ASC
");

$present_records_count = mysqli_num_rows($present_q);
$absent_records_count = mysqli_num_rows($absent_q);

function getDateVal($r){ if(!empty($r['attendance_date'])) return $r['attendance_date']; return date('Y-m-d'); }
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Attendance Reports - Face + Finger</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI, Arial} body{background:#f4f8ff}
.header{height:60px;background:#1e3a8a;color:#fff;display:flex;justify-content:space-between;align-items:center;padding:0 25px}
.home-btn{background:#fff;color:#1e3a8a;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:bold}
.container{max-width:1200px;margin:auto;padding:20px}
.summary{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;margin-bottom:20px}
.summary-card{background:#fff;padding:15px 18px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:flex;justify-content:space-between;align-items:center;border-left:4px solid #1e3a8a;cursor:pointer;transition:0.3s}
.summary-card.present-card{border-left-color:#22c55e} .summary-card.absent-card{border-left-color:#ef4444} .summary-card.percent-card{border-left-color:#f59e0b}
.icon-box{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px}
.icon-box.blue{background:#dbeafe} .icon-box.green{background:#dcfce7} .icon-box.red{background:#fee2e2} .icon-box.yellow{background:#fef3c7}
.card-info{text-align:right} .card-number{font-size:24px;font-weight:800;color:#1e3a8a} .card-title{font-size:11px;color:#64748b;font-weight:600;margin-top:2px}
.overall-box{background:#fff;padding:18px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-bottom:20px}
.progress-bg{width:100%;height:12px;background:#e2e8f0;border-radius:20px;overflow:hidden;margin-top:10px} .progress-fill{height:100%;background:linear-gradient(90deg,#2563eb,#1e3a8a);border-radius:20px}
.table-container{background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,0.06);overflow-x:auto}
table{width:100%;border-collapse:collapse} th{background:#1e3a8a;color:#fff;padding:10px 8px;text-align:left;font-size:11px} td{padding:10px 8px;border-bottom:1px solid #f1f5f9;font-size:12px}
.present{background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:20px;font-weight:700;font-size:11px}
.absent{background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:20px;font-weight:700;font-size:11px}
.face{background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:20px;font-weight:700;font-size:11px}
.finger{background:#dbeafe;color:#1e40af;padding:4px 10px;border-radius:20px;font-weight:700;font-size:11px}
.modal{display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999}
.modal-content{background:#fff;margin:3% auto;padding:25px;border-radius:12px;width:92%;max-width:900px;max-height:85vh;overflow:auto}
.close{float:right;font-size:30px;cursor:pointer;color:#999}
</style></head><body>
<div class="header"><h3>Attendance Management System</h3><a href="home.php" class="home-btn">Home</a></div>
<div class="container">
<h1 style="text-align:center;color:#1e3a8a;margin-bottom:20px">Attendance Reports - <?php echo $total_working_days; ?> Days</h1>
<div class="summary">
<div class="summary-card" onclick="document.getElementById('modal1').style.display='block'"><div class="icon-box blue">👨‍🎓</div><div class="card-info"><div class="card-number"><?php echo $total_students; ?></div><div class="card-title">Total Students</div></div></div>
<div class="summary-card present-card" onclick="document.getElementById('modal2').style.display='block'"><div class="icon-box green">🟢</div><div class="card-info"><div class="card-number"><?php echo $total_present; ?></div><div class="card-title">Present Records</div></div></div>
<div class="summary-card absent-card" onclick="document.getElementById('modal3').style.display='block'"><div class="icon-box red">🔴</div><div class="card-info"><div class="card-number"><?php echo $total_absent; ?></div><div class="card-title">Absent Records</div></div></div>
<div class="summary-card percent-card" onclick="document.getElementById('modal4').style.display='block'"><div class="icon-box yellow">📊</div><div class="card-info"><div class="card-number"><?php echo number_format($overall_percentage,1); ?>%</div><div class="card-title">Overall</div></div></div>
</div>
<div class="overall-box">
<div style="display:flex;justify-content:space-between"><h3 style="color:#1e3a8a;font-size:14px">📈 Overall Attendance Performance</h3><b style="color:#1e3a8a"><?php echo number_format($overall_percentage,1); ?>% (<?php echo $total_present; ?> / <?php echo $overall_total; ?>)</b></div>
<div class="progress-bg"><div class="progress-fill" style="width:<?php echo $overall_percentage; ?>%"></div></div>
</div>
</div>
<div style="text-align:left; margin:10px 20px;"><a href="export-excel.php" style="background:green; color:white; padding:10px 18px; text-decoration:none; border-radius:8px; font-weight:bold;">📥 Download Excel</a></div>
<div class="table-container">
<h3 style="color:#1e3a8a;margin-bottom:12px;font-size:14px">Student Attendance Details - Face + Finger</h3>
<table>
<tr><th>#</th><th>Roll No</th><th>Figure</th><th>Student Name</th><th>Dept</th><th>Year</th><th>Total</th><th>📷 Face</th><th>👆 Finger</th><th>🟢 Present</th><th>🔴 Absent</th><th>Performance</th></tr>
<?php $n=1; foreach($students_report as $r){ ?>
<tr><td><?php echo $n++; ?></td><td><b><?php echo $r['roll_no']; ?></b></td><td><img src="collage.jpeg" width="30" height="30" style="border-radius:50%"></td><td><b><?php echo $r['name']; ?></b></td><td><?php echo $r['department']; ?></td><td><?php echo $r['year']; ?></td><td><?php echo $r['total_days']; ?></td><td><span class="face">📷 <?php echo $r['face_days']; ?></span></td><td><span class="finger">👆 <?php echo $r['finger_days']; ?></span></td><td><span class="present">🟢 <?php echo $r['present_days']; ?></span></td><td><span class="absent">🔴 <?php echo $r['absent_days']; ?></span></td><td><?php echo number_format($r['percentage'],0); ?>%</td></tr>
<?php } ?>
</table>
</div>
</div>
<div id="modal1" class="modal"><div class="modal-content"><span class="close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span><h2>👨‍🎓 Total Students - <?php echo $total_students; ?></h2><br><table><tr><th>#</th><th>Roll No</th><th>Name</th><th>Dept</th><th>Year</th><th>Present</th><th>Absent</th><th>%</th></tr><?php $k=1; foreach($students_report as $s){ echo "<tr><td>".$k++."</td><td>".$s['roll_no']."</td><td>".$s['name']."</td><td>".$s['department']."</td><td>".$s['year']."</td><td>🟢 ".$s['present_days']."</td><td>🔴 ".$s['absent_days']."</td><td>".number_format($s['percentage'],1)."%</td></tr>"; } ?></table></div></div>
<div id="modal2" class="modal"><div class="modal-content"><span class="close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span><h2>🟢 Present Records - <?php echo $present_records_count; ?></h2><br><table><tr><th>Roll No</th><th>Name</th><th>Dept</th><th>📅 Date</th><th>Type</th><th>Status</th></tr><?php if($present_q){ while($p=mysqli_fetch_assoc($present_q)){ $d=getDateVal($p); $t=$p['type']; $emoji=($t=='Finger')?'👆':(($t=='Face')?'📷':'📝'); echo "<tr><td>".$p['roll_no']."</td><td>".$p['name']."</td><td>".$p['department']."</td><td>".$d."</td><td>".$emoji." ".$t."</td><td><span class='present'>🟢 Present</span></td></tr>"; } } ?></table></div></div>
<div id="modal3" class="modal"><div class="modal-content"><span class="close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span><h2>🔴 Absent Records - <?php echo $absent_records_count; ?></h2><br><table><tr><th>Roll No</th><th>Name</th><th>Dept</th><th>📅 Date</th><th>Type</th><th>Status</th></tr><?php if($absent_q){ while($a=mysqli_fetch_assoc($absent_q)){ $d=getDateVal($a); echo "<tr><td>".$a['roll_no']."</td><td>".$a['name']."</td><td>".$a['department']."</td><td>".$d."</td><td>-</td><td><span class='absent'>🔴 Absent</span></td></tr>"; } } ?></table></div></div>
<div id="modal4" class="modal"><div class="modal-content"><span class="close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span><h2>📊 Overall - <?php echo number_format($overall_percentage,1); ?>%</h2><br><div class="progress-bg"><div class="progress-fill" style="width:<?php echo $overall_percentage; ?>%"></div></div><br><p><b>Total:</b> <?php echo $total_students; ?> | <b>Present:</b> <?php echo $total_present; ?> | <b>Absent:</b> <?php echo $total_absent; ?></p></div></div>
<script>window.onclick=function(e){ if(e.target.classList.contains('modal')){ e.target.style.display='none'; } }</script>
</body></html>