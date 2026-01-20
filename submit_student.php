<?php
$conn = new mysqli("localhost","root","","sample");
if ($conn->connect_error) {
    echo "Database error";
    exit;
}

$student_id = $_POST['student_id'];
$first_name = $_POST['first_name'];
$last_name  = $_POST['last_name'];
$gender     = $_POST['gender'];
$address    = $_POST['address'];
$class      = $_POST['class'];


/* PHOTO UPLOAD */
$photo = "";
if (!empty($_FILES['photo']['name'])) {
    $photo = time() . "_" . $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
}

$sql = "INSERT INTO students 
(student_id, first_name, last_name, gender, address, class)
VALUES 
('$student_id','$first_name','$last_name','$gender','$address','$class')";

if ($conn->query($sql)) {
    echo "success";
} else {
    echo "error";
}
?>
