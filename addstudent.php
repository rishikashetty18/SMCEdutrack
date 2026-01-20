<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) die("DB Error");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $gender     = $_POST['gender'];
    $address    = $_POST['address'];
    $class      = $_POST['class'];
    $pwd        = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    /* ================= PHOTO UPLOAD ================= */

    $photoName = "default.png";
    $uploadDir = "uploads/students/";

    if (!empty($_FILES['photo']['name'])) {

        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {

            $photoName = $student_id . "." . $ext;
            $targetPath = $uploadDir . $photoName;

            move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
        }
    }

    /* ================= INSERT ================= */

    $sql = "INSERT INTO students 
        (student_id, first_name, last_name, gender, address, class, password, photo)
        VALUES 
        ('$student_id','$first_name','$last_name','$gender','$address','$class','$pwd','$photoName')";

    if ($conn->query($sql)) {
        echo "<script>alert('Student added successfully'); window.location='studentlist.php';</script>";
    } else {
        echo "<script>alert('Error saving record');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EduTrack | Add Student</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    font-family: "Segoe UI", sans-serif;
    background: linear-gradient(135deg, #eef2ff, #f8fafc);
    margin: 0;
}
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.form-card {
    width: 720px;
    background: #fff;
    padding: 30px 35px;
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}
.form-card h2 {
    text-align: center;
    color: #1e3a8a;
    margin-bottom: 25px;
}
.section-title {
    font-size: 14px;
    font-weight: 700;
    margin: 20px 0 10px;
    border-left: 4px solid #4070f4;
    padding-left: 10px;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group.full {
    grid-column: span 2;
}
.form-group label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
}
.form-group input,
.form-group select {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
}
.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #4070f4;
    box-shadow: 0 0 0 2px rgba(64,112,244,.15);
}
.preview {
    text-align: center;
    margin-top: 10px;
}
.preview img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e5e7eb;
}
.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}
.btn {
    padding: 11px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}
.btn-submit { background: #4070f4; color: #fff; }
.btn-submit:hover { background: #1e40af; }
.btn-reset { background: #9ca3af; color: #fff; }
.btn-back {
    background: #e5e7eb;
    padding: 11px 18px;
    text-decoration: none;
    color: #111;
    border-radius: 8px;
}
@media(max-width:768px){
    .form-card { width: 95%; }
    .form-grid { grid-template-columns: 1fr; }
}
</style>
</head>

<body>

<div class="form-wrapper">
<div class="form-card">

<h2>Add Student</h2>

<form method="post" id="addStudentForm" enctype="multipart/form-data">

<div class="section-title">Personal Information</div>
<div class="form-grid">
    <div class="form-group">
        <label>Student ID</label>
        <input type="text" id="student_id" name="student_id" required>
    </div>

    <div class="form-group">
        <label>Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div class="form-group">
        <label>First Name</label>
        <input type="text" id="first_name" name="first_name" required>
    </div>

    <div class="form-group">
        <label>Last Name</label>
        <input type="text" id="last_name" name="last_name" required>
    </div>

    <div class="form-group full">
        <label>Address</label>
        <input type="text" id="address" name="address" required>
    </div>
</div>

<div class="section-title">Academic Details</div>
<div class="form-grid">
    <div class="form-group">
        <label>Class</label>
        <select id="class" name="class" required>
            <option value="">Select Class</option>
            <option>First BCA</option>
            <option>Second BCA</option>
            <option>Third BCA</option>
            <option>First BCom</option>
            <option>Second BCom</option>
            <option>Third BCom</option>
            <option>First BA</option>
            <option>Second BA</option>
            <option>Third BA</option>
        </select>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" id="pwd" name="pwd" required>
    </div>
</div>

<div class="section-title">Profile Photo</div>
<div class="form-group full">
    <input type="file" name="photo" accept="image/*" onchange="previewImage(event)">
    <div class="preview">
        <img id="previewImg" src="uploads/students/default.png">
    </div>
</div>

<div class="form-actions">
    <a href="studentlist.php" class="btn-back">← Back</a>
    <div>
        <button type="reset" class="btn btn-reset">Reset</button>
        <button type="submit" class="btn btn-submit">Save Student</button>
    </div>
</div>

</form>
</div>
</div>

<script>
document.getElementById("addStudentForm").addEventListener("submit", function(e) {
    if (document.getElementById("pwd").value.length < 6) {
        alert("Password must be at least 6 characters");
        e.preventDefault();
    }
});

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById("previewImg").src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
