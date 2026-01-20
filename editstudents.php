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

// Check if ID parameter is passed in URL (e.g., editstudent.php?id=123)
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student details based on ID
    $sql = "SELECT * FROM students WHERE student_id='$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $address = $row['address'];
        $class = $row['class'];

        // Add CSS style for the form and elements
        echo "
        <style>
            body {
                background-color: #f0f0f0; /* Light gray background for the page */
            }
            .form-container {
                max-width: 500px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .form-container h2 {
                color: #1E90FF; /* Dark blue color for headings */
                margin-bottom: 20px;
            }
            .form-container input[type='text'], .form-container input[type='submit'], .form-container select {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 3px;
                box-sizing: border-box;
            }
            .form-container input[type='submit'] {
                background-color: #1E90FF; /* Dark blue background for submit button */
                color: #fff;
                cursor: pointer;
            }
            .form-container input[type='submit']:hover {
                background-color: #0e74b8; /* Darker blue color on hover */
            }
            .form-container .success-message, .form-container .error-message {
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 3px;
            }
            .success-message {
                background-color: #dff0d8; /* Light green background for success message */
                border: 1px solid #3c763d;
                color: #3c763d;
            }
            .error-message {
                background-color: #f2dede; /* Light red background for error message */
                border: 1px solid #a94442;
                color: #a94442;
            }
        </style>
        ";

        // Display edit form
        echo "
        <div class='form-container'>
            <h2>Edit Student Details</h2>
            <form method='POST' action=''>
                <input type='hidden' name='student_id' value='$student_id'>
                First Name: <input type='text' name='first_name' value='$first_name' placeholder='Enter your First Name'><br>
                Last Name: <input type='text' name='last_name' value='$last_name' placeholder='Enter your Last Name'><br>
                Gender: <select name='gender'>
                            <option value='Male' " . ($gender == 'Male' ? 'selected' : '') . ">Male</option>
                            <option value='Female' " . ($gender == 'Female' ? 'selected' : '') . ">Female</option>
                            
                        </select><br>
                Address: <input type='text' name='address' value='$address' placeholder='Enter your Address'><br>
                Class: <select name='class'>
                            <option value='First BCA' " . ($class == 'First BCA' ? 'selected' : '') . ">First BCA</option>
                            <option value='Second BCA' " . ($class == 'Second BCA' ? 'selected' : '') . ">Second BCA</option>
                            <option value='Third BCA' " . ($class == 'Third BCA' ? 'selected' : '') . ">Third BCA</option>
                        </select><br>
                <input type='submit' value='Update'>
            </form>
        </div>";

        // Process form submission for updating student details
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $student_id = $_POST['student_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $class = $_POST['class'];

            // SQL query to update data in the table
            $update_sql = "UPDATE students SET first_name='$first_name', last_name='$last_name', gender='$gender', address='$address', class='$class' WHERE student_id='$student_id'";

            if ($conn->query($update_sql) === TRUE) {
                echo "<div class='success-message'>Record updated successfully</div>";
            } else {
                echo "<div class='error-message'>Error updating record: " . $conn->error . "</div>";
            }
        }
    } else {
        echo "Student not found";
    }
} else {
    echo "Student ID not provided";
}

$conn->close();
?>
