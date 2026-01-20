<?php
// Database connection
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root";
$password = "";
$dbname = "sample"; // Replace "sample" with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID parameter is passed in URL (e.g., removefaculty.php?id=123)
if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];

    // SQL query to delete faculty details based on ID
    $delete_sql = "DELETE FROM faculty WHERE id='$faculty_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Faculty ID not provided";
}

$conn->close();
?>
