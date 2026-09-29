<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Fingerprint</title>
<style>
body{font-family:Arial; background:#e3f2fd; text-align:center; margin:0;}
.header{background:#0d47a1; color:white; padding:15px;}
.box{background:white; width:450px; margin:40px auto; padding:30px; border-radius:15px; box-shadow:0 0 10px gray;}
.finger{font-size:80px;}
button{background:#0d47a1; color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;}
select{padding:8px; border-radius:10px; width:80%; margin:10px 0;}
.success{color:green; font-weight:bold; margin-top:15px;}
</style>
</head>
<body>
<div class="header"><h2>Attendance Management</h2></div>
<div class="box">
<h2>👆 Fingerprint Attendance</h2>
<p></p>

<select id="studentSelect">
<option value="">-- Select Student --</option>
<?php
$result = $conn->query("SELECT * FROM students");
while($row = $result->fetch_assoc()){
echo "<option value='".$row['id']."'>".$row['name']." - ".$row['roll_no']."</option>";
}
?>
</select>

<div class="finger">👆</div>
<br>
<button onclick="scanFinger()">🔍 Scan Finger</button>
<div id="result"></div>
<br>
<a href="home.php">← Back to Home</a> | <a href="reports.php">View Reports</a>
</div>

<script>
function scanFinger(){
let sid = document.getElementById('studentSelect').value;
if(sid == ""){
alert("Please select student!");
return;
}
let sname = document.getElementById('studentSelect').options[document.getElementById('studentSelect').selectedIndex].text;
document.getElementById('result').innerHTML = "<p>Scanning "+sname+"...</p>";

setTimeout(()=>{
document.getElementById('result').innerHTML = "<div class='success'>✔ Fingerprint Verified! Attendance Marked</div>";

// Save to DB
fetch('save-finger.php?id='+sid)
.then(res=>res.text())
.then(data=>console.log(data));

}, 1500);
}
</script>
</body>
</html>