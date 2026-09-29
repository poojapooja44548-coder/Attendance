<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>College Settings - Sri Bala Murugan</title>
<style>
body{
  margin:0;
  font-family: Arial, sans-serif;
  background: url('collage.jpeg') no-repeat center center;
  background-size: 100% 100%;
  height:100vh;
  overflow:hidden;
}
.overlay{
  background: rgba(0,0,0,0.15);
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
}
.card{
  background: rgba(255,255,255,0.95);
  padding:30px;
  border-radius:15px;
  width:450px;
  box-shadow: 0 5px 25px rgba(0,0,0,0.3);
}
.card h2{
  margin:0;
  color:#1e3a8a;
}
.card p{
  color:gray;
  margin-top:5px;
}
label{
  display:block;
  margin-top:15px;
  font-weight:bold;
  font-size:14px;
}
input{
  width:94%;
  padding:12px;
  border-radius:8px;
  border:1px solid #ccc;
  margin-top:6px;
  font-size:14px;
}
button{
  background:#1d4ed8;
  color:white;
  border:none;
  padding:12px;
  border-radius:8px;
  width:100%;
  margin-top:25px;
  font-size:16px;
  cursor:pointer;
}
button:hover{
  background:#1e40af;
}
.small{
  text-align:center;
  font-size:12px;
  color:green;
  margin-top:12px;
}
</style>
</head>
<body>
<div class="overlay">
  <div class="card">
    <h2>General Settings</h2>
    <p>College Management</p>
    
    <label>College Name</label>
    <input type="text" value="Sri Bala Murugan Arts and Science College">
    
    <label>Admin Email</label>
    <input type="text" value="admin@sribalamurugan.edu.in">
    
    <label>Principal</label>
    <input type="text" value="Dr. R. Kumar">
    
    <button>Save Settings</button>
    <div class="small">Secure - Last updated: Today</div>
  </div>
</div>
</body>
</html>