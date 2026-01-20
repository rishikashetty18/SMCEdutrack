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
    
    $firstname = $_POST["first_name"];
    $middleName = $_POST["middle_name"];
    $lastName = $_POST["lastname"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    

    // Prepare SQL statement
    
    $sql="INSERT INTO `faculty`(`firstname`, `middle_name`, `last_name`, `gender`, `address`,
     `class_id`) VALUES ('$studentCode','$firstName','$middleName','$lastName',' $gender',' $address','$classId')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
