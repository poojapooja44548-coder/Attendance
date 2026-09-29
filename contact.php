<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us - Attendance Management System</title>

    <style>

        /* ---------------- BASIC RESET ---------------- */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }


        /* ---------------- BODY ---------------- */

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #eaf4ff, #ffffff, #e8f3ff);
            color: #333;
        }


        /* ---------------- HEADER ---------------- */

        .header {
            width: 100%;
            height: 75px;
            background: #1769aa;
            color: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 55px;

            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15);
        }


        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }


        .logo-icon {
            width: 45px;
            height: 45px;

            background: white;
            color: #1769aa;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;
            font-weight: bold;
        }


        .logo-section h2 {
            font-size: 23px;
            letter-spacing: 0.3px;
        }


        .home-btn {
            text-decoration: none;
            background: white;
            color: #1769aa;

            padding: 11px 22px;

            border-radius: 7px;

            font-size: 15px;
            font-weight: bold;

            transition: 0.3s;
        }


        .home-btn:hover {
            background: #eaf4ff;
            transform: translateY(-2px);
        }


        /* ---------------- MAIN AREA ---------------- */

        .main {
            width: 100%;
            max-width: 1200px;

            margin: auto;

            padding: 55px 30px 40px;
        }


        /* ---------------- PAGE TITLE ---------------- */

        .page-title {
            text-align: center;
            margin-bottom: 50px;
        }


        .page-title h1 {
            color: #1769aa;
            font-size: 40px;
            margin-bottom: 12px;
        }


        .page-title p {
            color: #666;
            font-size: 17px;
        }


        .line {
            width: 75px;
            height: 4px;

            background: #1769aa;

            margin: 18px auto 0;

            border-radius: 10px;
        }


        /* ---------------- CONTACT CONTAINER ---------------- */

        .contact-container {
            width: 100%;

            display: flex;

            background: white;

            border-radius: 18px;

            overflow: hidden;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }


        /* ---------------- LEFT SECTION ---------------- */

        .contact-left {
            width: 42%;

            background: linear-gradient(145deg, #1769aa, #0d4f82);

            color: white;

            padding: 50px 40px;

            position: relative;
        }


        .contact-left::after {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border: 35px solid rgba(255, 255, 255, 0.06);

            border-radius: 50%;

            right: -80px;
            bottom: -70px;
        }


        .contact-left h2 {
            font-size: 30px;
            margin-bottom: 15px;
        }


        .contact-left .description {
            color: #e7f3ff;

            font-size: 15px;

            line-height: 1.8;

            margin-bottom: 38px;
        }


        /* ---------------- INFORMATION ---------------- */

        .contact-info {
            display: flex;

            align-items: flex-start;

            gap: 18px;

            margin-bottom: 30px;

            position: relative;

            z-index: 2;
        }


        .icon {
            width: 55px;
            height: 55px;

            min-width: 55px;

            background: white;

            color: #1769aa;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }


        .info-text h3 {
            font-size: 17px;

            margin-bottom: 7px;
        }


        .info-text p {
            color: #e4f2ff;

            font-size: 14px;

            line-height: 1.6;
        }


        /* ---------------- RIGHT SECTION ---------------- */

        .contact-right {
            width: 58%;

            padding: 50px 55px;

            background: #ffffff;
        }


        .contact-right h2 {
            color: #1769aa;

            font-size: 29px;

            margin-bottom: 10px;
        }


        .contact-right .sub-text {
            color: #777;

            font-size: 15px;

            line-height: 1.7;

            margin-bottom: 35px;
        }


        /* ---------------- CARDS ---------------- */

        .details-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 22px;
        }


        .detail-card {
            min-height: 170px;

            background: #f7fbff;

            border: 1px solid #e0edf8;

            border-radius: 13px;

            padding: 28px 22px;

            transition: 0.3s;

            position: relative;

            overflow: hidden;
        }


        .detail-card::before {
            content: "";

            position: absolute;

            left: 0;
            top: 0;

            width: 5px;
            height: 100%;

            background: #1769aa;
        }


        .detail-card:hover {
            transform: translateY(-5px);

            box-shadow: 0 8px 20px rgba(23, 105, 170, 0.15);

            border-color: #b9d8ef;
        }


        .detail-icon {
            font-size: 30px;

            margin-bottom: 15px;
        }


        .detail-card h3 {
            color: #1769aa;

            font-size: 17px;

            margin-bottom: 9px;
        }


        .detail-card p {
            color: #555;

            font-size: 14px;

            line-height: 1.6;
        }


        /* ---------------- BOTTOM MESSAGE ---------------- */

        .bottom-box {
            margin-top: 35px;

            background: #eef7ff;

            border: 1px solid #d5eafa;

            border-radius: 12px;

            padding: 22px;

            text-align: center;
        }


        .bottom-box h3 {
            color: #1769aa;

            margin-bottom: 8px;

            font-size: 18px;
        }


        .bottom-box p {
            color: #666;

            font-size: 14px;

            line-height: 1.6;
        }


        /* ---------------- FOOTER ---------------- */

        .footer {
            text-align: center;

            padding: 25px;

            color: #777;

            font-size: 14px;
        }


        /* ---------------- RESPONSIVE DESIGN ---------------- */

        @media (max-width: 900px) {

            .contact-container {
                flex-direction: column;
            }


            .contact-left,
            .contact-right {
                width: 100%;
            }


            .contact-left {
                padding: 40px 30px;
            }


            .contact-right {
                padding: 40px 30px;
            }

        }


        @media (max-width: 600px) {

            .header {
                height: auto;

                padding: 18px 20px;

                gap: 15px;
            }


            .logo-section h2 {
                font-size: 17px;
            }


            .logo-icon {
                width: 38px;
                height: 38px;

                font-size: 19px;
            }


            .home-btn {
                padding: 9px 13px;

                font-size: 13px;
            }


            .main {
                padding: 35px 15px;
            }


            .page-title h1 {
                font-size: 30px;
            }


            .page-title p {
                font-size: 14px;
            }


            .details-grid {
                grid-template-columns: 1fr;
            }


            .contact-left h2,
            .contact-right h2 {
                font-size: 25px;
            }

        }

    </style>

</head>


<body>


    <!-- ================= HEADER ================= -->

    <header class="header">

        <div class="logo-section">

            <div class="logo-icon">
                ✓
            </div>

            <h2>
                Attendance Management System
            </h2>

        </div>


        <a href="home.php" class="home-btn">
            ← Home
        </a>

    </header>



    <!-- ================= MAIN ================= -->

    <main class="main">


        <!-- PAGE TITLE -->

        <div class="page-title">

            <h1>
                Contact Us
            </h1>

            <p>
                Get in touch with us for information and support
            </p>

            <div class="line"></div>

        </div>



        <!-- ================= CONTACT CONTAINER ================= -->

        <div class="contact-container">


            <!-- LEFT SIDE -->

            <section class="contact-left">

                <h2>
                    Get In Touch
                </h2>


                <p class="description">

                    Welcome to the Attendance Management System.
                    For any information, queries or support related
                    to the project, please use the contact details
                    provided here.

                </p>



                <!-- COLLEGE -->

                <div class="contact-info">

                    <div class="icon">
                        🏫
                    </div>

                    <div class="info-text">

                        <h3>
                            College
                        </h3>

                        <p>
                            Sri Balamurukan Arts and Science
                            for Women College
                        </p>

                    </div>

                </div>



                <!-- DEPARTMENT -->

                <div class="contact-info">

                    <div class="icon">
                        💻
                    </div>

                    <div class="info-text">

                        <h3>
                            Department
                        </h3>

                        <p>
                            B.Sc Computer Science
                        </p>

                    </div>

                </div>



                <!-- EMAIL -->

                <div class="contact-info">

                    <div class="icon">
                        📧
                    </div>

                    <div class="info-text">

                        <h3>
                            Email
                        </h3>

                        <p>
                            admin@attendance.com
                        </p>

                    </div>

                </div>



                <!-- PHONE -->

                <div class="contact-info">

                    <div class="icon">
                        📞
                    </div>

                    <div class="info-text">

                        <h3>
                            Phone
                        </h3>

                        <p>
                            +91 98765 43210
                        </p>

                    </div>

                </div>

            </section>



            <!-- RIGHT SIDE -->

            <section class="contact-right">

                <h2>
                    Contact Information
                </h2>


                <p class="sub-text">

                    The following information provides the
                    official academic and contact details
                    associated with this Attendance Management
                    System project.

                </p>



                <div class="details-grid">


                    <!-- COLLEGE CARD -->

                    <div class="detail-card">

                        <div class="detail-icon">
                            🏫
                        </div>

                        <h3>
                            College
                        </h3>

                        <p>
                            Sri Balamurukan Arts and Science
                            for Women College
                        </p>

                    </div>



                    <!-- DEPARTMENT CARD -->

                    <div class="detail-card">

                        <div class="detail-icon">
                            💻
                        </div>

                        <h3>
                            Department
                        </h3>

                        <p>
                            B.Sc Computer Science
                        </p>

                    </div>



                    <!-- EMAIL CARD -->

                    <div class="detail-card">

                        <div class="detail-icon">
                            📧
                        </div>

                        <h3>
                            Email Address
                        </h3>

                        <p>
                            admin@attendance.com
                        </p>

                    </div>



                    <!-- PHONE CARD -->

                    <div class="detail-card">

                        <div class="detail-icon">
                            📞
                        </div>

                        <h3>
                            Phone Number
                        </h3>

                        <p>
                            +91 98765 43210
                        </p>

                    </div>

                </div>



                <!-- BOTTOM BOX -->

                <div class="bottom-box">

                    <h3>
                        Attendance Management System
                    </h3>

                    <p>
                        A web-based system designed to manage
                        student attendance records efficiently
                        using PHP and MySQL.
                    </p>

                </div>

            </section>

        </div>

    </main>



    <!-- ================= FOOTER ================= -->

    <footer class="footer">

        © 2026 Attendance Management System
        | B.Sc Computer Science

    </footer>


</body>

</html>