<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {

        // ✅ FULL SESSION (THIS IS THE KEY)
        $_SESSION['status']     = 'admin';
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['username']   = $admin['email'];

        header("Location: admin.php");
        exit;

    } else {
        echo "<script>alert('Invalid admin credentials'); window.location.href='admin_login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
  box-sizing:border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

body{
  margin:0;
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  background:linear-gradient(135deg,#2563eb,#1e40af);
}

/* ===== CARD ===== */
.login-card{
  width:380px;
  background:#fff;
  padding:30px 28px;
  border-radius:14px;
  box-shadow:0 20px 40px rgba(0,0,0,0.2);
  text-align:center;
}

/* ===== LOGO ===== */
.logo{
  font-size:26px;
  font-weight:700;
  color:#2563eb;
  margin-bottom:5px;
}
.subtitle{
  font-size:14px;
  color:#64748b;
  margin-bottom:25px;
}

/* ===== INPUTS ===== */
.input-group{
  text-align:left;
  margin-bottom:15px;
}

.input-group label{
  font-size:14px;
  font-weight:600;
  margin-bottom:6px;
  display:block;
}

.input-group input{
  width:100%;
  height:44px;
  padding:10px 14px;
  border-radius:8px;
  border:1px solid #cbd5e1;
  font-size:14px;
}

.input-group input:focus{
  outline:none;
  border-color:#2563eb;
  box-shadow:0 0 0 2px rgba(37,99,235,0.15);
}

/* ===== BUTTONS ===== */
.btn{
  width:100%;
  height:44px;
  border:none;
  border-radius:8px;
  font-size:15px;
  cursor:pointer;
}

.btn-login{
  background:#2563eb;
  color:#fff;
  margin-top:10px;
}

.btn-login:hover{
  background:#1e40af;
}

/* ===== DIVIDER ===== */
.divider{
  margin:20px 0;
  font-size:13px;
  color:#94a3b8;
  position:relative;
}

.divider::before,
.divider::after{
  content:"";
  height:1px;
  width:40%;
  background:#e5e7eb;
  position:absolute;
  top:50%;
}
.divider::before{left:0}
.divider::after{right:0}

/* ===== BOTTOM BUTTONS ===== */
.alt-buttons{
  display:flex;
  gap:12px;
}

.btn-student{
  background:#22c55e;
  color:#fff;
}

.btn-faculty{
  background:#f97316;
  color:#fff;
}

.btn-student:hover{background:#16a34a}
.btn-faculty:hover{background:#ea580c}

/* ===== FOOTER TEXT ===== */
.footer-text{
  margin-top:18px;
  font-size:12px;
  color:#64748b;
}
</style>
</head>

<body>

<div class="login-card">

  <div class="logo">EduTrack</div>
  <div class="subtitle">Admin Panel Login</div>

  <form method="POST" action="admin_login.php">

    <div class="input-group">
      <label>Admin Email</label>
      <input type="email" name="email" placeholder="admin@example.com" required>
    </div>

    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password" placeholder="Enter password" required>
    </div>

    <button class="btn btn-login" type="submit">Login</button>

  </form>

  <div class="divider">OR</div>

  <div class="alt-buttons">
    <button class="btn btn-student" onclick="location.href='student_login.php'">
      Student
    </button>
    <button class="btn btn-faculty" onclick="location.href='faculty_login.php'">
      Faculty
    </button>
  </div>

  <div class="footer-text">
    © <?= date("Y") ?> EduTrack. All rights reserved.
  </div>

</div>

</body>
</html>
