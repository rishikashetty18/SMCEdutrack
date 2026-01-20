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

// Get student ID from URL
$student_id = $_GET['id'];

// SQL query to fetch student details by student ID
$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-container label {
            display: block;
            margin-top: 10px;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-container input[type="submit"] {
            background-color: #1E90FF;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .form-container input[type="submit"]:hover {
            background-color: #1c7ed6;
        }
    </style>

    <div class="form-container">
        <h2>Edit Student</h2>
        <form method="POST" action="studentlist.php">
            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
            
            <label for="student_id">Student ID:</label>
            <input type="text" readonly name="student_id_display" value="<?php echo $row['student_id']; ?>">
            
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>">
            
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>">
            
            <label for="class">Class:</label>
            <input type="text" name="class" value="<?php echo $row['class']; ?>">
            
            <input type="submit" value="Update Student">
        </form>
    </div>

    <?php
} else {
    echo "Student not found.";
}

$conn->close();
?>
