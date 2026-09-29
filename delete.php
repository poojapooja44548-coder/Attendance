<?php

session_start();
include "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "DELETE FROM students WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: students.php");
        exit();
    } else {
        echo "Delete Error: " . mysqli_error($conn);
    }

} else {

    header("Location: students.php");
    exit();

}

?>