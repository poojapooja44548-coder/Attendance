<?php
session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$today = isset($_POST['attendance_date']) ? $_POST['attendance_date'] :date('Y-m-d');

if (isset($_POST['save_attendance'])) {

    $date = $_POST['attendance_date'];

    foreach ($_POST['status'] as $student_id => $status) {

        $student_id = (int)$student_id;

        if ($status != "Present" && $status != "Absent") {
            continue;
        }

        $check = mysqli_query(
            $conn,
            "SELECT id FROM attendance
             WHERE student_id='$student_id'
             AND attendance_date='$date'"
        );

        if ($check && mysqli_num_rows($check) > 0) {

            mysqli_query(
                $conn,
                "UPDATE attendance
                 SET status='$status'
                 WHERE student_id='$student_id'
                 AND attendance_date='$date'"
            );

        } else {

            mysqli_query(
                $conn,
                "INSERT INTO attendance
                (student_id, attendance_date, status)
                VALUES ('$student_id', '$date', '$status')"
            );
        }
    }

    $message = "✓ Attendance saved successfully!";
}

$students = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY roll_no ASC"
);

$today = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mark Attendance</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f6fb;
            color: #333;
        }


        /* HEADER */

        .header {
            background: #1769aa;
            color: white;
            padding: 18px 45px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
        }

        .header h2 {
            font-size: 23px;
        }

        .home-btn {
            text-decoration: none;
            background: white;
            color: #1769aa;

            padding: 10px 20px;

            border-radius: 7px;

            font-weight: bold;
        }


        /* MAIN */

        .container {
            max-width: 1200px;
            margin: auto;

            padding: 40px 25px;
        }


        /* TITLE */

        .title-box {
            background: white;

            padding: 30px;

            border-radius: 14px;

            box-shadow: 0 4px 18px rgba(0,0,0,0.08);

            margin-bottom: 25px;
        }

        .title-box h1 {
            color: #1769aa;
            font-size: 32px;

            margin-bottom: 8px;
        }

        .title-box p {
            color: #777;
            font-size: 15px;
        }


        /* MESSAGE */

        .message {
            background: #dff6e5;
            color: #18733a;

            border-left: 5px solid #20a050;

            padding: 14px 18px;

            border-radius: 7px;

            margin-bottom: 20px;

            font-weight: bold;
        }


        /* ATTENDANCE BOX */

        .attendance-box {
            background: white;

            padding: 30px;

            border-radius: 14px;

            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }


        /* DATE AREA */

        .top-area {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

            flex-wrap: wrap;

            gap: 15px;
        }


        .date-area {
            display: flex;

            align-items: center;

            gap: 12px;
        }

        .date-area label {
            font-weight: bold;
            color: #444;
        }

        .date-area input {
            padding: 11px 14px;

            border: 1px solid #ccd8e3;

            border-radius: 7px;

            font-size: 14px;

            outline: none;
        }

        .date-area input:focus {
            border-color: #1769aa;
        }


        /* LEGEND */

        .legend {
            display: flex;

            gap: 15px;

            align-items: center;
        }

        .legend span {
            padding: 7px 13px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }

        .present-legend {
            background: #dff6e5;
            color: #16803c;
        }

        .absent-legend {
            background: #ffe1e1;
            color: #d62929;
        }


        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: separate;

            border-spacing: 0;

            overflow: hidden;

            border: 1px solid #e0e7ee;

            border-radius: 10px;
        }

        th {
            background: #1769aa;

            color: white;

            padding: 15px;

            text-align: left;

            font-size: 14px;
        }

        td {
            padding: 15px;

            border-bottom: 1px solid #e7edf2;

            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f7fbff;
        }


        /* STUDENT NAME */

        .student-name {
            font-weight: bold;

            color: #333;
        }


        /* STATUS SELECT */

        .status-select {
            padding: 10px 14px;

            border-radius: 25px;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;

            outline: none;

            min-width: 120px;

            border: 2px solid;
        }


        /* PRESENT */

        .status-select.present {
            background: #dff6e5;

            color: #16803c;

            border-color: #38b865;
        }


        /* ABSENT */

        .status-select.absent {
            background: #ffe1e1;

            color: #d62929;

            border-color: #ed5b5b;
        }


        /* SAVE BUTTON */

        .button-area {
            text-align: right;

            margin-top: 25px;
        }

        .save-btn {
            background: #1769aa;

            color: white;

            border: none;

            padding: 13px 28px;

            border-radius: 7px;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }

        .save-btn:hover {
            background: #0d4f82;

            transform: translateY(-2px);
        }


        /* EMPTY */

        .empty {
            text-align: center;

            padding: 30px;

            color: #777;
        }


        /* RESPONSIVE */

        @media (max-width: 700px) {

            .header {
                padding: 18px 20px;
            }

            .header h2 {
                font-size: 17px;
            }

            .container {
                padding: 25px 15px;
            }

            .attendance-box {
                padding: 18px;
            }

            .top-area {
                align-items: flex-start;
            }

            .date-area {
                flex-direction: column;
                align-items: flex-start;
            }

            .legend {
                flex-wrap: wrap;
            }

        }

    </style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <h2>
        Attendance Management System
    </h2>

    <a href="home.php" class="home-btn">
        ← Home
    </a>

</div>



<!-- MAIN -->

<div class="container">


    <!-- TITLE -->

    <div class="title-box">

        <h1>
            Mark Student Attendance
        </h1>

        <p>
            Select the attendance date and mark each student's
            attendance status.
        </p>

    </div>



    <!-- SUCCESS MESSAGE -->

    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>



    <!-- ATTENDANCE BOX -->

    <div class="attendance-box">

        <form method="POST">


            <!-- DATE + LEGEND -->

            <div class="top-area">


                <div class="date-area">

                    <label>
                        📅 Attendance Date
                    </label>

                    <input
                        type="date"
                        name="attendance_date"
                        value="<?php echo $today; ?>"
                        required
                    >

                </div>



                <div class="legend">

                    <span class="present-legend">
                        ● Present
                    </span>

                    <span class="absent-legend">
                        ● Absent
                    </span>

                </div>

            </div>



            <!-- TABLE -->

            <div class="table-wrapper">

                <table>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Roll No
                        </th>

                        <th>
                            Student Name
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Year
                        </th>

                        <th>
                            Attendance Status
                        </th>

                    </tr>


                    <?php

                    if (mysqli_num_rows($students) > 0) {

                        $number = 1;

                        while ($row = mysqli_fetch_assoc($students)) {

                    ?>

                    <tr>

                        <td>
                            <?php echo $number; ?>
                        </td>


                        <td>
                            <strong>
                                <?php
                                echo htmlspecialchars($row['roll_no']);
                                ?>
                            </strong>
                        </td>


                        <td class="student-name">

                            <?php
                            echo htmlspecialchars($row['name']);
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars($row['department']);
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars($row['year']);
                            ?>

                        </td>


                        <td>

                            <select
                                name="status[<?php echo $row['id']; ?>]"
                                class="status-select present"
                                onchange="changeStatus(this)"
                            >

                                <option value="Present">
                                    🟢 Present
                                </option>

                                <option value="Absent">
                                    🔴 Absent
                                </option>

                            </select>

                        </td>

                    </tr>

                    <?php

                            $number++;

                        }

                    } else {

                        echo "
                        <tr>
                            <td colspan='6' class='empty'>
                                No students available
                            </td>
                        </tr>";

                    }

                    ?>

                </table>

            </div>



            <!-- SAVE BUTTON -->

            <div class="button-area">

                <button
                    type="submit"
                    name="save_attendance"
                    class="save-btn"
                >

                    ✓ Save Attendance

                </button>

            </div>


        </form>

    </div>

</div>



<script>

function changeStatus(select) {

    if (select.value === "Present") {

        select.classList.remove("absent");

        select.classList.add("present");

    }

    else {

        select.classList.remove("present");

        select.classList.add("absent");

    }

}

</script>


</body>

</html>