<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - Attendance Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f8fc;
        }

        .header {
            background: #1769aa;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            font-size: 24px;
        }

        .logout {
            background: white;
            color: #1769aa;
            padding: 9px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .container {
            padding: 40px;
        }

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .welcome h1 {
            color: #1769aa;
            margin-bottom: 10px;
        }

        .welcome p {
            color: #555;
            font-size: 16px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        .card h3 {
            color: #1769aa;
            margin-bottom: 12px;
        }

        .card p {
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #1769aa;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn:hover {
            background: #0d4f82;
        }

        @media (max-width: 800px) {
            .cards {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

<div class="header">

    <h2>Attendance Management System</h2>

    <a href="logout.php" class="logout">Logout</a>

</div>

<div class="container">

    <div class="welcome">

        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

        <p>
            Student Attendance Portal - Manage students and attendance easily.
        </p>

    </div>

    <div class="cards">

        <div class="card">
            <h3>Students</h3>
            <p>Manage student details</p>
            <a href="students.php" class="btn">View Students</a>
        </div>

        <div class="card">
            <h3>Attendance</h3>
            <p>Mark student attendance</p>
            <a href="attendance.php" class="btn">Attendance</a>
        </div>

        <div class="card">
            <h3>Reports</h3>
            <p>View attendance reports</p>
            <a href="reports.php" class="btn">View Reports</a>
        </div>

        <div class="card">
            <h3>Contact</h3>
            <p>Contact information</p>
            <a href="contact.php" class="btn">Contact</a>
        </div>
            <div class="card">
        <h3>Fingerprint</h3>
        <p>Mark attendance with finger</p>
        <a href="finger-attendance.php" class="btn">Scan Finger</a>
    </div>

    <div class="card">
        <h3>Face Recognition</h3>
        <p>Mark attendance with face</p>
        <a href="face-attendance.php" class="btn">Scan Face</a>
    </div>

    </div>

</div>

</body>
</html>