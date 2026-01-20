<!-- logic part -->


<?php
session_start();
include "connection.php";

/* 🔐 Allow ONLY admin */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$success = $error = "";

/* ===== HANDLE RESET ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $newPass  = trim($_POST['new_password']);

    if ($username === "" || $newPass === "") {
        $error = "All fields are required";
    } else {

        // Check faculty exists
        $stmt = $con->prepare("SELECT id FROM facreg WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {

            $hashed = password_hash($newPass, PASSWORD_DEFAULT);

            // Update password
            $upd = $con->prepare(
                "UPDATE facreg SET password=? WHERE username=?"
            );
            $upd->bind_param("ss", $hashed, $username);

            if ($upd->execute()) {
                $success = "Password reset successfully";
            } else {
                $error = "Failed to update password";
            }

        } else {
            $error = "Faculty username not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Faculty Password | EduTrack</title>
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
  background:#f4f7fc;
}
.card{
  width:420px;
  background:#fff;
  border-radius:14px;
  padding:35px 30px;
  box-shadow:0 20px 50px rgba(0,0,0,.15);
}
h2{
  text-align:center;
  margin-bottom:10px;
  color:#2563eb;
}
.subtitle{
  text-align:center;
  color:#6b7280;
  font-size:14px;
  margin-bottom:20px;
}
label{
  display:block;
  font-weight:600;
  margin:12px 0 6px;
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

.msg{
  text-align:center;
  font-size:14px;
  margin-bottom:10px;
}
.success{color:#16a34a}
.error{color:#ef4444}

.back{
  text-align:center;
  margin-top:15px;
}
.back a{
  text-decoration:none;
  color:#2563eb;
  font-size:14px;
}
.back a:hover{text-decoration:underline}
</style>
</head>

<body>

<div class="card">
<h2>🔐 Reset Faculty Password</h2>
<div class="subtitle">Admin Control Panel</div>

<?php if($success): ?>
  <div class="msg success"><?= $success ?></div>
<?php endif; ?>

<?php if($error): ?>
  <div class="msg error"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
  <label>Faculty Username</label>
  <input type="text" name="username" placeholder="e.g. prajna" required>

  <label>New Password</label>
  <input type="password" name="new_password" placeholder="Enter new password" required>

  <button class="btn">Reset Password</button>
</form>

<div class="back">
  <a href="admin.php">← Back to Dashboard</a>
</div>
</div>

</body>
</html>
