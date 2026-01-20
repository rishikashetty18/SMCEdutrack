<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch faculty by username
    $stmt = $con->prepare("SELECT * FROM facreg WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {

            $_SESSION['status']     = 'faculty';
            $_SESSION['username']   = $row['username'];
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['class']      = $row['class'];

            header("Location: facultydash.php");
            exit;

        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Faculty not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Login | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box}
body{
  margin:0;
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  font-family:Arial, sans-serif;
  background:linear-gradient(135deg,#2563eb,#1e40af);
}
.card{
  width:380px;
  background:#fff;
  border-radius:14px;
  padding:35px 30px;
  box-shadow:0 25px 60px rgba(0,0,0,.2);
  text-align:center;
}
.logo{
  font-size:26px;
  font-weight:700;
  color:#2563eb;
}
.subtitle{
  color:#6b7280;
  margin-bottom:25px;
}
label{
  display:block;
  text-align:left;
  font-weight:600;
  margin:12px 0 5px;
}
input{
  width:100%;
  height:44px;
  padding:10px;
  border-radius:8px;
  border:1px solid #cbd5e1;
  font-size:14px;
}
input:focus{
  outline:none;
  border-color:#2563eb;
}
.btn{
  width:100%;
  height:45px;
  margin-top:18px;
  border:none;
  border-radius:10px;
  background:#2563eb;
  color:#fff;
  font-size:15px;
  cursor:pointer;
}
.btn:hover{background:#1d4ed8}

/* 🔹 Forgot password */
.forgot{
  text-align:right;
  margin-top:6px;
}
.forgot a{
  font-size:13px;
  color:#2563eb;
  text-decoration:none;
}
.forgot a:hover{
  text-decoration:underline;
}

.switch{
  display:flex;
  gap:10px;
  margin-top:15px;
}
.switch a{
  flex:1;
  padding:10px;
  border-radius:8px;
  text-decoration:none;
  color:#fff;
  font-size:14px;
}
.student{background:#22c55e}
.admin{background:#f97316}

.error{
  color:#ef4444;
  font-size:14px;
  margin-top:10px;
}
.footer{
  margin-top:20px;
  font-size:12px;
  color:#6b7280;
}
</style>
</head>

<body>

<div class="card">
  <div class="logo">EduTrack</div>
  <div class="subtitle">Faculty Portal Login</div>

  <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="POST">
    <label>Faculty Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <!-- 🔹 Forgot Password Link -->
    <div class="forgot">
      <a href="faculty_forgot_password.php">Forgot Password?</a>
    </div>

    <button class="btn">Login</button>
  </form>

  <div class="switch">
    <a href="student_login.php" class="student">Student</a>
    <a href="admin_login.php" class="admin">Admin</a>
  </div>

  <div class="footer">
    © 2026 EduTrack. All rights reserved.
  </div>
</div>

</body>
</html>
