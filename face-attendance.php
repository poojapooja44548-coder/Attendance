<?php include "db.php"; $students = mysqli_query($conn, "SELECT * FROM students ORDER BY name ASC"); ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Face Scan</title>
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<style>
body{font-family:Segoe UI;background:linear-gradient(135deg,#fbcfe8 0%,#fce7f3 100%);margin:0;padding:15px;text-align:center;min-height:100vh}
.card{background:white;width:480px;max-width:95%;margin:15px auto;padding:16px;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,0.2)}
h3{margin:4px 0 12px 0;font-size:16px}
select{width:100%;padding:11px;border-radius:8px;border:1.5px solid #ccc;font-weight:bold}
#box{position:relative;width:100%;height:360px;background:#000;border-radius:12px;overflow:hidden;margin-top:12px;border:2px solid #ddd}
#video{width:100%;height:100%;object-fit:cover;background:#000}
#centerBox{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:190px;height:240px;border:3px solid #00ff00;border-radius:12px;box-shadow:0 0 14px #00ff00;pointer-events:none;transition:all 0.3s}
#centerBox.scan{border-color:#00ff00;box-shadow:0 0 25px #00ff00, inset 0 0 20px rgba(0,255,0,0.3);transform:translate(-50%,-50%) scale(1.05)}
#centerBox.wrong{border-color:red;box-shadow:0 0 14px red}
#scanline{position:absolute;left:0;width:100%;height:4px;background:linear-gradient(90deg,transparent,#00ff00,transparent);box-shadow:0 0 10px #00ff00;animation:scan 1.8s linear infinite}
@keyframes scan{0%{top:0}50%{top:95%}100%{top:0}}
.label{margin-top:10px;font-size:13px;color:#333}
.back{display:block;margin-top:6px;color:#1e3a8a;text-decoration:underline;font-size:14px}
#msg{margin-top:10px;font-weight:bold;font-size:13px;min-height:20px;padding:6px;border-radius:6px}
</style>
</head><body>
<div class="card">
<h3>Face Recognition - Center Scan</h3>
<select id="sname"><option value="">-- Select Student --</option><?php while($s=mysqli_fetch_assoc($students)){ echo "<option value='".$s['name']."'>".$s['name']." - ".$s['roll_no']."</option>"; } ?></select>

<div id="box">
<video id="video" autoplay muted playsinline></video>
<div id="centerBox"><div id="scanline"></div></div>
</div>

<div class="label">✅ Center Green Box</div>
<a class="back" href="home.php">← Back</a>
<div id="msg" style="background:#eef2ff;color:#1e3a8a">📷 Camera Starting da Pooja...</div>
</div>

<script>
const video=document.getElementById('video'), msg=document.getElementById('msg'), sname=document.getElementById('sname'), cbox=document.getElementById('centerBox');
let modelsReady=false, lastSave=0, locked=false, noFaceTimer=0;

function beepOk(){
 try{let ctx=new(window.AudioContext||window.webkitAudioContext)(),o=ctx.createOscillator(),g=ctx.createGain();o.connect(g);g.connect(ctx.destination);o.frequency.value=1000;g.gain.value=0.4;o.start();setTimeout(()=>o.frequency.value=1500,120);o.stop(ctx.currentTime+0.7);g.gain.exponentialRampToValueAtTime(0.01,ctx.currentTime+0.7);if(navigator.vibrate) navigator.vibrate([100,50,100]);}catch(e){}
}
function beepWrong(){
 try{let ctx=new(window.AudioContext||window.webkitAudioContext)(),o=ctx.createOscillator(),g=ctx.createGain();o.connect(g);g.connect(ctx.destination);o.frequency.value=180;g.gain.value=0.3;o.start();o.stop(ctx.currentTime+0.4);}catch(e){}
}

navigator.mediaDevices.getUserMedia({video:true}).then(s=>{
 video.srcObject=s;
 msg.innerHTML="✅ Camera Ready da Pooja - Face ah center la kaatu da!";
 startScan();
});

Promise.all([
 faceapi.nets.tinyFaceDetector.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models'),
 faceapi.nets.faceLandmark68Net.loadFromUri('https://justadudewhohacks.github.io/face-api.js/models')
]).then(()=>{modelsReady=true; msg.style.background="#dcfce7"; msg.style.color="green"; msg.innerHTML="✅ Ready da Pooja - Face ah center green box kulla kaatu da - Auto scan + present da!";});

function startScan(){
 setInterval(async()=>{
  if(video.paused || video.readyState<2 || !modelsReady) return;
  try{
   const dets=await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions({inputSize:224, scoreThreshold:0.5}));
   
   if(dets.length==0){
     noFaceTimer++;
     if(noFaceTimer>10){
       cbox.classList.add('wrong'); cbox.classList.remove('scan');
       msg.style.background="#fee2e2"; msg.style.color="red";
       msg.innerHTML="❌ WRONG da Pooja! Face kaatalana da!";
       if(noFaceTimer==11) beepWrong();
     }
     return;
   }
   
   noFaceTimer=0;
   // Face irukku da - center la irukka nu paarkanum da
   const videoRect=video.getBoundingClientRect();
   const boxW=190, boxH=240;
   const centerX=videoRect.width/2, centerY=videoRect.height/2;
   
   // Face detection la center ku pakkama irukka nu check
   let isCenter=false;
   for(let d of dets){
     let b=d.box || d.detection.box;
     let fx=(b.x+b.width/2)/video.videoWidth*videoRect.width;
     let fy=(b.y+b.height/2)/video.videoHeight*videoRect.height;
     if(Math.abs(fx-centerX)<70 && Math.abs(fy-centerY)<90){ isCenter=true; break; }
   }
   
   if(isCenter){
     cbox.classList.remove('wrong');
     cbox.classList.add('scan');
     let name=sname.value;
     if(!name){
       msg.style.background="#fef9c3"; msg.style.color="#a16207";
       msg.innerHTML="🟢 Face Scan Aayiduchu da! Name select pannu da Pooja - Present vilum da!";
     } else if(!locked && Date.now()-lastSave>4000){
       locked=true;
       beepOk();
       msg.style.background="#dcfce7"; msg.style.color="green";
       msg.innerHTML="🟢 SCAN OK da "+name+"! BEEP! Present viluthu da...";
       fetch('save-face.php?name='+encodeURIComponent(name)).then(r=>r.text()).then(t=>{
         beepOk();
         lastSave=Date.now();
         if(t.toLowerCase().includes('already')){
           msg.innerHTML="⚠️ Already Present da "+name+"! "+t;
         } else if(t.toLowerCase().includes('ok') || t.toLowerCase().includes('save') || t.toLowerCase().includes('present')){
           msg.innerHTML="✅ PRESENT Vilunthuduchu da "+name+" ku! 🎉 BEEP! "+t;
         } else {
           msg.innerHTML="❌ "+t;
         }
         setTimeout(()=>locked=false,2000);
       }).catch(()=>{locked=false;});
     } else if(locked){
       msg.innerHTML="✅ PRESENT Vilunthuduchu da "+name+" ku! Wait pannu da Pooja...";
     }
   } else {
     cbox.classList.remove('scan'); cbox.classList.add('wrong');
     msg.style.background="#fee2e2"; msg.style.color="red";
     msg.innerHTML="❌ WRONG da! Face ah center green box kulla correct ah vaa da Pooja!";
   }
   
  }catch(e){console.log(e);}
 },500);
}
</script>
</body></html>