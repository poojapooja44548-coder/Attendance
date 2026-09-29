<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - Attendance Management System</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #eef2f7;
            color: #172b4d;
        }

        /* ================= NAVBAR ================= */

        .navbar {
            width: 100%;
            min-height: 92px;
            background: linear-gradient(135deg, #173d78, #214f91);
            color: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 15px 42px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.18);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 290px;
        }

        .brand-icon {
            font-size: 40px;
        }

        .brand-text {
            font-size: 25px;
            font-weight: bold;
            line-height: 1.05;
        }

        /* ================= MENU ================= */

        .menu {
            display: flex;
            align-items: center;
            gap: 27px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;

            padding: 15px 4px;
            position: relative;

            transition: 0.3s;
        }

        .menu a:hover {
            color: #c8ddff;
        }

        .menu a.active {
            color: #ffffff;
        }

        .menu a.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 4px;
            height: 3px;
            background: #78b4ff;
            border-radius: 5px;
        }

        /* ================= ADMIN ================= */

        .admin {
            display: flex;
            align-items: center;
            gap: 9px;

            font-size: 15px;
            font-weight: bold;

            min-width: 110px;
            justify-content: flex-end;
        }

        .admin-icon {
            width: 38px;
            height: 38px;

            border-radius: 50%;
            background: #dce8f7;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #355b8d;
            font-size: 20px;
        }

        /* ================= MAIN ================= */

        .container {
            width: 92%;
            max-width: 1450px;
            margin: 45px auto;
        }

        .main-card {
            background: white;
            border-radius: 16px;

            padding: 30px;

            box-shadow: 0 8px 30px rgba(31, 58, 93, 0.10);
        }

        /* ================= TITLE ================= */

        .top-section {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            margin-bottom: 28px;
        }

        .heading h1 {
            font-size: 42px;
            color: #10264d;
            margin-bottom: 8px;
        }

        .heading p {
            color: #666;
            font-size: 19px;
        }

        /* ================= ADD BUTTON ================= */

        .add-btn {
            background: #1769d1;
            color: white;

            border: none;
            border-radius: 8px;

            padding: 15px 23px;

            font-size: 16px;
            font-weight: bold;

            text-decoration: none;

            cursor: pointer;

            box-shadow: 0 4px 10px rgba(23,105,209,0.20);

            transition: 0.3s;
        }

        .add-btn:hover {
            background: #0d56b3;
            transform: translateY(-1px);
        }

        /* ================= FILTERS ================= */

        .filters {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 14px;

            margin-bottom: 30px;
        }

        .search-box {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #777;
            font-size: 23px;
        }

        .search-box input {
            width: 100%;
            height: 55px;

            border: 1px solid #ccd2dc;
            border-radius: 9px;

            outline: none;

            padding: 0 18px 0 49px;

            font-size: 16px;

            background: #fff;
        }

        .search-box input:focus {
            border-color: #2876d3;
            box-shadow: 0 0 0 3px rgba(40,118,211,0.08);
        }

        .filters select {
            width: 100%;
            height: 55px;

            border: 1px solid #ccd2dc;
            border-radius: 9px;

            background: white;

            padding: 0 14px;

            font-size: 16px;
            font-weight: 600;

            color: #222;

            outline: none;
        }

        .export-btn {
            height: 55px;

            padding: 0 20px;

            border-radius: 9px;

            border: 1px solid #ccd2dc;

            background: white;

            color: #555;

            font-size: 16px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.3s;
        }

        .export-btn:hover {
            background: #f2f6fb;
        }

        /* ================= TABLE ================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;

            border: 1px solid #d9dee7;
            border-radius: 10px;
        }

        table {
            width: 100%;
            min-width: 1100px;

            border-collapse: collapse;
        }

        thead {
            background: #173f7a;
        }

        th {
            color: white;

            padding: 19px 16px;

            text-align: left;

            font-size: 16px;

            white-space: nowrap;
        }

        td {
            padding: 16px;

            font-size: 15px;

            border-bottom: 1px solid #e3e7ec;

            white-space: nowrap;

            color: #1f2937;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #edf5ff;
        }

        /* ================= ID ================= */

        .id-number {
            font-weight: bold;
            color: #526174;
        }

        .student-name {
            font-weight: bold;
            color: #172b4d;
        }

        .roll-number {
            font-weight: bold;
            color: #315b8e;
        }

        /* ================= ACTION ================= */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .edit-btn {
            background: #1769d1;
            color: white;

            padding: 9px 14px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 14px;
            font-weight: bold;

            transition: 0.3s;
        }

        .edit-btn:hover {
            background: #0c56b3;
        }

        .delete-btn {
            background: #df3d42;
            color: white;

            padding: 9px 14px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 14px;
            font-weight: bold;

            transition: 0.3s;
        }

        .delete-btn:hover {
            background: #bd252b;
        }

        /* ================= EMPTY ================= */

        .empty {
            text-align: center;
            padding: 40px !important;
            color: #777;
            font-size: 17px;
        }

        /* ================= BOTTOM ================= */

        .bottom-section {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-top: 25px;
        }

        .entries {
            color: #666;
            font-size: 15px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-btn {
            min-width: 48px;
            height: 42px;

            padding: 0 14px;

            border: 1px solid #ccd2dc;

            background: white;

            border-radius: 8px;

            color: #555;

            font-size: 15px;

            cursor: pointer;
        }

        .page-number {
            min-width: 42px;
            height: 42px;

            border: none;

            background: #1769d1;

            color: white;

            border-radius: 7px;

            font-weight: bold;

            font-size: 15px;
        }

        /* ================= FOOTER ================= */

        .footer {
            text-align: center;

            margin: 25px 0 35px;

            color: #666;

            font-size: 14px;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 1200px) {

            .navbar {
                flex-wrap: wrap;
                gap: 15px;
            }

            .menu {
                order: 3;
                width: 100%;
                justify-content: center;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 700px) {

            .container {
                width: 95%;
                margin: 25px auto;
            }

            .main-card {
                padding: 18px;
            }

            .top-section {
                flex-direction: column;
                gap: 20px;
            }

            .heading h1 {
                font-size: 30px;
            }

            .heading p {
                font-size: 16px;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .bottom-section {
                flex-direction: column;
                gap: 18px;
                align-items: flex-start;
            }

            .brand-text {
                font-size: 21px;
            }

            .menu {
                gap: 13px;
                flex-wrap: wrap;
            }

        }

    </style>

</head>

<body>

<!-- ================= NAVBAR ================= -->

<div class="navbar">

    <div class="brand">

        <div class="brand-icon">🎓</div>

        <div class="brand-text">
            Attendance Management<br>
            System
        </div>

    </div>


    <div class="menu">

        <a href="home.php">
            🏠 Dashboard
        </a>

        <a href="students.php" class="active">
            👤 Students
        </a>

        <a href="attendance.php">
            📅 Attendance
        </a>

        <a href="reports.php">
            📊 Reports
        </a>

        <a href="settings.php">
            ⚙ Settings
        </a>

    </div>


    <div class="admin">

        <div class="admin-icon">
            👤
        </div>

        <span>Admin⌄</span>

    </div>

</div>


<!-- ================= MAIN ================= -->

<div class="container">

    <div class="main-card">


        <!-- TITLE -->

        <div class="top-section">

            <div class="heading">

                <h1>
                    Student Attendance List
                </h1>

                <p>
                    Manage and view all student attendance records
                </p>

            </div>


            <!--
                If your Add Student page is named differently,
                change add.php below to that filename.
            -->

            <a href="add_student.php" class="add-btn">
                ＋ Add Student
            </a>

        </div>


        <!-- ================= FILTERS ================= -->

        <div class="filters">


            <div class="search-box">

                <span class="search-icon">
                    🔍
                </span>

                <input
                    type="text"
                    id="studentSearch"
                    placeholder="Search by name, roll no, or email..."
                    onkeyup="searchStudents()"
                >

            </div>


            <select id="departmentFilter"
                    onchange="filterStudents()">

                <option value="">
                    Department: All
                </option>

                <option value="B.Sc Computer Science">
                    B.Sc Computer Science
                </option>

            </select>


            <select id="yearFilter"
                    onchange="filterStudents()">

                <option value="">
                    Year: All
                </option>

                <option value="I Year">
                    I Year
                </option>

                <option value="II Year">
                    II Year
                </option>

                <option value="III Year">
                    III Year
                </option>

            </select>


            <button class="export-btn"
                    onclick="exportTable()">

                ⬇ Export CSV

            </button>


        </div>


        <!-- ================= TABLE ================= -->

        <div class="table-wrapper">

            <table id="studentTable">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Department</th>
                        <th>Year</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td class="id-number">
                            <?php echo htmlspecialchars($row['id']); ?>
                        </td>

                        <td class="student-name">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </td>

                        <td class="roll-number">
                            <?php echo htmlspecialchars($row['roll_no']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['department']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['year']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['gender']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['phone']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['email']); ?>
                        </td>

                        <td>

                            <div class="action-buttons">

                                <a
                                    href="edit.php?id=<?php echo $row['id']; ?>"
                                    class="edit-btn"
                                >
                                    ✎ Edit
                                </a>


                                <a
                                    href="delete.php?id=<?php echo $row['id']; ?>"
                                    class="delete-btn"
                                    onclick="return confirm('Delete pannidava?')"
                                >
                                    🗑 Delete
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="9" class="empty">
                            No students found
                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>


        <!-- ================= BOTTOM ================= -->

        <div class="bottom-section">

            <div class="entries">

                Showing
                <strong id="entryCount">0</strong>
                entries

            </div>


            <div class="pagination">

                <button class="page-btn">
                    Previous
                </button>

                <button class="page-number">
                    1
                </button>

                <button class="page-btn">
                    Next
                </button>

            </div>

        </div>


    </div>


    <!-- FOOTER -->

    <div class="footer">

        Attendance Management System © 2024 · v1.2.0

    </div>

</div>


<!-- ================= JAVASCRIPT ================= -->

<script>

function searchStudents() {

    filterStudents();

}


function filterStudents() {

    let search =
        document.getElementById("studentSearch")
        .value
        .toLowerCase();

    let department =
        document.getElementById("departmentFilter")
        .value
        .toLowerCase();

    let year =
        document.getElementById("yearFilter")
        .value
        .toLowerCase();

    let table =
        document.getElementById("studentTable");

    let rows =
        table
        .getElementsByTagName("tbody")[0]
        .getElementsByTagName("tr");

    let count = 0;


    for (let i = 0; i < rows.length; i++) {

        let cells = rows[i].getElementsByTagName("td");

        if (cells.length < 9) {
            continue;
        }


        let name =
            cells[1].innerText.toLowerCase();

        let roll =
            cells[2].innerText.toLowerCase();

        let dept =
            cells[3].innerText.toLowerCase();

        let studentYear =
            cells[4].innerText.toLowerCase();

        let email =
            cells[7].innerText.toLowerCase();


        let searchMatch =
            name.includes(search) ||
            roll.includes(search) ||
            email.includes(search);


        let departmentMatch =
            department === "" ||
            dept === department;


        let yearMatch =
            year === "" ||
            studentYear === year;


        if (
            searchMatch &&
            departmentMatch &&
            yearMatch
        ) {

            rows[i].style.display = "";

            count++;

        } else {

            rows[i].style.display = "none";

        }

    }


    document.getElementById("entryCount").innerText = count;

}


function exportTable() {

    let table =
        document.getElementById("studentTable");

    let rows =
        table.querySelectorAll("tr");

    let csv = [];


    for (let i = 0; i < rows.length; i++) {

        if (
            rows[i].style.display === "none"
        ) {
            continue;
        }

        let cols =
            rows[i].querySelectorAll("th, td");

        let row = [];


        for (let j = 0; j < cols.length; j++) {

            if (j === cols.length - 1) {
                continue;
            }

            let text =
                cols[j].innerText
                .replace(/"/g, '""');

            row.push('"' + text + '"');

        }

        csv.push(row.join(","));

    }


    let csvFile =
        new Blob(
            [csv.join("\n")],
            { type: "text/csv" }
        );


    let downloadLink =
        document.createElement("a");

    downloadLink.download =
        "student_list.csv";

    downloadLink.href =
        window.URL.createObjectURL(csvFile);

    downloadLink.style.display =
        "none";

    document.body.appendChild(downloadLink);

    downloadLink.click();

    document.body.removeChild(downloadLink);

}


window.onload = function() {

    filterStudents();

};

</script>


</body>
</html>