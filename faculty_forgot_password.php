<?php
session_start();
include "connection.php";

/* ===== INIT MESSAGE (IMPORTANT FIX) ===== */
$message = "";

/* ===== HANDLE FORM SUBMISSION ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);

    // 1️⃣ Check if faculty exists
    $stmt = $con->prepare("SELECT id, username FROM facreg WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $faculty = $res->fetch_assoc();
        $faculty_id = $faculty['id'];

        // 2️⃣ Check if already requested
        $check = $con->prepare(
            "SELECT id FROM faculty_password_requests 
             WHERE faculty_id=? AND status='pending'"
        );
        $check->bind_param("i", $faculty_id);
        $check->execute();
        $checkRes = $check->get_result();

        if ($checkRes->num_rows === 0) {

            // 3️⃣ Insert new request
            $insert = $con->prepare(
                "INSERT INTO faculty_password_requests 
                (faculty_id, username, status, requested_at)
                VALUES (?, ?, 'pending', NOW())"
            );
            $insert->bind_param("is", $faculty_id, $username);
            $insert->execute();

            $message = "✅ Password reset request sent to admin.";

        } else {
            $message = "⚠️ You already have a pending request.";
        }

    } else {
        $message = "❌ Faculty username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password | EduTrack</title>
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
  width:400px;
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
  margin-bottom:20px;
  font-size:14px;
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

.note{
  background:#f1f5f9;
  padding:10px;
  border-radius:8px;
  font-size:13px;
  color:#475569;
  margin-bottom:15px;
}

.back{
  margin-top:15px;
}
.back a{
  font-size:14px;
  color:#2563eb;
  text-decoration:none;
}
.back a:hover{
  text-decoration:underline;
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
  <div class="subtitle">Faculty Password Recovery</div>

  <?php if(!empty($message)): ?>
    <div class="note"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="note">
    Enter your registered username.  
    Admin will help you reset your password.
  </div>

  <form method="POST">
    <label>Faculty Username</label>
    <input type="text" name="username" placeholder="Enter your username" required>

    <button class="btn">Request Reset</button>
  </form>

  <div class="back">
    <a href="faculty_login.php">← Back to Login</a>
  </div>

  <div class="footer">
    © 2026 EduTrack. All rights reserved.
  </div>
</div>

</body>
</html>
