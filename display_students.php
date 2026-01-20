<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $studentCode = $_POST["student_code"];
    $firstName = $_POST["firstname"];
    $middleName = $_POST["middlename"];
    $lastName = $_POST["lastname"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $classId = $_POST["class_id"];

    // Prepare SQL statement
    
    $sql="INSERT INTO `students`(`student_code`, `first_name`, `middle_name`, `last_name`, `gender`, `address`,
     `class_id`) VALUES ('$studentCode','$firstName','$middleName','$lastName',' $gender',' $address','$classId')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
