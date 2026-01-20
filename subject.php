<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "test"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and retrieve form data
    $subjectCode = isset($_POST["subjectCode"]) ? $_POST["subjectCode"] : "";
    $subjectName = isset($_POST["subjectName"]) ? $_POST["subjectName"] : "";
    $description = isset($_POST["description"]) ? $_POST["description"] : "";

    // Check if all required fields are filled
    if (!empty($subjectCode) && !empty($subjectName) && !empty($description)) {
        // Prepare SQL statement
        $sql = "INSERT INTO subjects (subject_code, subject_name, description)
        VALUES ('$subjectCode', '$subjectName', '$description')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

$conn->close();
?>
