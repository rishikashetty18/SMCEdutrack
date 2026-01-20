<?php
session_start();
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studid = $_POST['student_id'];
    $pwd    = $_POST['password'];

    $stmt = $con->prepare(
        "SELECT * FROM students WHERE student_id=?"
    );
    $stmt->bind_param("s", $studid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($pwd, $row['password'])) {

            $_SESSION['status'] = 'student';
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['class_of_student'] = $row['class'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];

            header("Location: studenthomepage.php");
            exit;

        } else {
            $error = "Invalid Student ID or Password";
        }

    } else {
        $error = "Invalid Student ID or Password";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Login | EduTrack</title>

<style>
body{
  margin:0;
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  background:linear-gradient(135deg,#2563eb,#1e40af);
  font-family:Arial, sans-serif;
}

.card{
  background:#fff;
  width:380px;
  padding:35px;
  border-radius:14px;
  box-shadow:0 15px 35px rgba(0,0,0,.2);
  text-align:center;
}

.logo{
  font-size:26px;
  font-weight:700;
  color:#2563eb;
}

.subtitle{
  color:#64748b;
  margin-bottom:25px;
}

input{
  width:100%;
  height:44px;
  padding:10px;
  margin-bottom:15px;
  border:1px solid #cbd5e1;
  border-radius:8px;
  font-size:14px;
}

input:focus{
  outline:none;
  border-color:#2563eb;
}

button{
  width:100%;
  height:44px;
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:8px;
  font-size:15px;
  cursor:pointer;
}

button:hover{
  background:#1e40af;
}

.switch{
  margin-top:18px;
  display:flex;
  gap:10px;
}

.switch a{
  flex:1;
  padding:10px;
  border-radius:8px;
  text-decoration:none;
  color:#fff;
  font-size:14px;
}

.admin{background:#64748b}
.faculty{background:#f97316}

.error{
  background:#fee2e2;
  color:#991b1b;
  padding:10px;
  border-radius:6px;
  margin-bottom:15px;
  font-size:14px;
}
</style>
</head>

<body>

<div class="card">
  <div class="logo">🎓 EduTrack</div>
  <div class="subtitle">Student Login</div>

  <?php if(isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="student_id" placeholder="Student ID" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
    <p style="margin-top:10px;">
  <a href="student_forgot_password.php" style="color:#2563eb;text-decoration:none;">
    Forgot Password?
  </a>
</p>

  </form>

  <div class="switch">
    <a href="admin_login.php" class="admin">Admin</a>
    <a href="faculty_login.php" class="faculty">Faculty</a>
  </div>

</div>

</body>
</html>
