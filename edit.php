<?php

session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}


/* GET STUDENT ID */

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = intval($_GET['id']);


/* UPDATE STUDENT */

if (isset($_POST['update_student'])) {

    $name       = mysqli_real_escape_string($conn, $_POST['name']);
    $roll_no    = mysqli_real_escape_string($conn, $_POST['roll_no']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $year       = mysqli_real_escape_string($conn, $_POST['year']);
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE students SET
            name='$name',
            roll_no='$roll_no',
            department='$department',
            year='$year',
            gender='$gender',
            phone='$phone',
            email='$email'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {

        header("Location: students.php");
        exit();

    } else {

        $error = mysqli_error($conn);

    }
}


/* GET CURRENT STUDENT */

$sql = "SELECT * FROM students WHERE id=$id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {

    echo "Student not found.";
    exit();

}

$student = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Student - Attendance Management System</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #eef2f7;
}

.container {
    width: 650px;
    max-width: 94%;
    margin: 50px auto;
}

.card {
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.10);
}

h1 {
    color: #173f7a;
    margin-bottom: 8px;
}

.subtitle {
    color: #666;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 17px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    color: #333;
}

input,
select {
    width: 100%;
    height: 45px;
    border: 1px solid #ccd4df;
    border-radius: 7px;
    padding: 0 12px;
    font-size: 15px;
    outline: none;
}

input:focus,
select:focus {
    border-color: #1769d1;
}

.buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

button,
.cancel {
    padding: 12px 20px;
    border: none;
    border-radius: 7px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
}

button {
    background: #1769d1;
    color: white;
}

button:hover {
    background: #0d56b3;
}

.cancel {
    background: #ddd;
    color: #333;
}

.error {
    background: #ffe7e7;
    color: #c62828;
    padding: 12px;
    border-radius: 7px;
    margin-bottom: 20px;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h1>✏ Edit Student</h1>

<p class="subtitle">
Update student details
</p>

<?php if (isset($error)) { ?>

<div class="error">
    <?php echo $error; ?>
</div>

<?php } ?>

<form method="POST">

<div class="form-group">

<label>Student Name</label>

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($student['name']); ?>"
required>

</div>


<div class="form-group">

<label>Roll Number</label>

<input
type="text"
name="roll_no"
value="<?php echo htmlspecialchars($student['roll_no']); ?>"
required>

</div>


<div class="form-group">

<label>Department</label>

<select name="department" required>

<option value="B.Sc Computer Science"
<?php
if ($student['department'] == 'B.Sc Computer Science')
    echo 'selected';
?>>
B.Sc Computer Science
</option>

</select>

</div>


<div class="form-group">

<label>Year</label>

<select name="year" required>

<option value="I Year"
<?php
if ($student['year'] == 'I Year')
    echo 'selected';
?>>
I Year
</option>

<option value="II Year"
<?php
if ($student['year'] == 'II Year')
    echo 'selected';
?>>
II Year
</option>

<option value="III Year"
<?php
if ($student['year'] == 'III Year')
    echo 'selected';
?>>
III Year
</option>

</select>

</div>


<div class="form-group">

<label>Gender</label>

<select name="gender" required>

<option value="Female"
<?php
if ($student['gender'] == 'Female')
    echo 'selected';
?>>
Female
</option>

<option value="Male"
<?php
if ($student['gender'] == 'Male')
    echo 'selected';
?>>
Male
</option>

</select>

</div>


<div class="form-group">

<label>Phone</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($student['phone']); ?>"
required>

</div>


<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($student['email']); ?>"
required>

</div>


<div class="buttons">

<button type="submit" name="update_student">
✓ Update Student
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