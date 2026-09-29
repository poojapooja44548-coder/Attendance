<?php
include 'db.php';

$message = "";

if (isset($_POST['add_student'])) {

    $name       = trim($_POST['name']);
    $roll_no    = trim($_POST['roll_no']);
    $department = trim($_POST['department']);
    $year       = trim($_POST['year']);
    $gender     = trim($_POST['gender']);
    $phone      = trim($_POST['phone']);
    $email      = trim($_POST['email']);

    if ($name == "" || $roll_no == "" || $department == "" || $year == "" || $gender == "" || $phone == "" || $email == "") {
        $message = "Please fill all fields.";
    } else {

        $sql = "INSERT INTO students 
                (name, roll_no, department, year, gender, phone, email)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $name,
                $roll_no,
                $department,
                $year,
                $gender,
                $phone,
                $email
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: students.php");
                exit();
            } else {
                $message = "Error: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Student - Attendance Management System</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:linear-gradient(135deg,#eaf4ff,#f7fbff);
    min-height:100vh;
}

.header{
    background:linear-gradient(135deg,#064b9b,#0878d1);
    color:white;
    padding:22px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.15);
}

.header h1{
    font-size:25px;
}

.header p{
    margin-top:5px;
    font-size:13px;
    opacity:.9;
}

.back-btn{
    background:white;
    color:#075bb5;
    padding:11px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

.container{
    width:90%;
    max-width:850px;
    margin:40px auto;
}

.card{
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 8px 30px rgba(0,70,140,.12);
}

.card h2{
    color:#064b9b;
    margin-bottom:8px;
}

.card .subtitle{
    color:#777;
    margin-bottom:28px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group.full{
    grid-column:1 / 3;
}

label{
    font-weight:bold;
    color:#333;
    margin-bottom:8px;
}

input,select{
    width:100%;
    padding:13px 15px;
    border:1px solid #d5dce5;
    border-radius:9px;
    outline:none;
    font-size:15px;
    background:#fafcff;
}

input:focus,select:focus{
    border-color:#0878d1;
    box-shadow:0 0 0 3px rgba(8,120,209,.1);
}

.buttons{
    margin-top:30px;
    display:flex;
    gap:15px;
}

button{
    border:none;
    background:linear-gradient(135deg,#075bb5,#0785df);
    color:white;
    padding:14px 28px;
    border-radius:9px;
    font-size:15px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:.9;
}

.cancel{
    background:#e9eef5;
    color:#444;
    text-decoration:none;
    padding:14px 25px;
    border-radius:9px;
    font-weight:bold;
}

.error{
    background:#ffe5e5;
    color:#c62828;
    padding:13px;
    border-radius:8px;
    margin-bottom:20px;
}

@media(max-width:650px){
    .form-grid{
        grid-template-columns:1fr;
    }

    .form-group.full{
        grid-column:1;
    }

    .header{
        padding:20px;
    }

    .container{
        width:94%;
    }

    .card{
        padding:22px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div>
        <h1>Attendance Management System</h1>
        <p>Student Attendance Portal</p>
    </div>

    <a href="students.php" class="back-btn">← Back</a>
</div>

<div class="container">

<div class="card">

    <h2>➕ Add New Student</h2>
    <p class="subtitle">Enter the student details below</p>

    <?php if($message != ""): ?>
        <div class="error">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <div class="form-grid">

            <div class="form-group">
                <label>Student Name</label>
                <input type="text" name="name"
                       placeholder="Enter student name" required>
            </div>

            <div class="form-group">
                <label>Roll Number</label>
                <input type="text" name="roll_no"
                       placeholder="Enter roll number" required>
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="B.Sc Computer Science">
                        B.Sc Computer Science
                    </option>
                    <option value="BCA">BCA</option>
                    <option value="B.Com">B.Com</option>
                    <option value="BBA">BBA</option>
                </select>
            </div>

            <div class="form-group">
                <label>Year</label>
                <select name="year" required>
                    <option value="">Select Year</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone"
                       placeholder="Enter phone number"
                       maxlength="15" required>
            </div>

            <div class="form-group full">
                <label>Email</label>
                <input type="email" name="email"
                       placeholder="Enter email address" required>
            </div>

        </div>

        <div class="buttons">

            <button type="submit" name="add_student">
                ➕ Add Student
            </button>

            <a href="students.php" class="cancel">
                Cancel
            </a>

        </div>

    </form>

</div>
</div>

</body>
</html>