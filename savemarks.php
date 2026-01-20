<?php
// Database connection
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root";
$password = "";
$dbname = "sample";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Iterate over each student's marks and update the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['internal1'] as $student_id => $internal1) {
        $internal2 = $_POST['internal2'][$student_id];
        $seminar = $_POST['seminar'][$student_id];
        $assignment = $_POST['assignment'][$student_id];

        $sql = "UPDATE students SET Internal1='$internal1', Internal2='$internal2', Seminar='$seminar', Assignment='$assignment' WHERE student_id='$student_id'";
        $conn->query($sql);
    }

    echo "Marks updated successfully.";
}

$conn->close();
?>
