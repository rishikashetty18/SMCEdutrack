<?php
session_start();
include "connection.php";

/* AUTH CHECK */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}

$faculty_id = $_GET['fid'] ?? null;
$request_id = $_GET['rid'] ?? null;

if (!$faculty_id || !$request_id) {
  die("Invalid request");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $newPassword = $_POST['new_password'];
  $hash = password_hash($newPassword, PASSWORD_DEFAULT);

  // Update faculty password
  $stmt = $con->prepare("UPDATE facreg SET password=? WHERE id=?");
  $stmt->bind_param("si", $hash, $faculty_id);
  $stmt->execute();

  // Mark request completed
  $stmt = $con->prepare(
    "UPDATE faculty_password_requests SET status='completed' WHERE id=?"
  );
  $stmt->bind_param("i", $request_id);
  $stmt->execute();

  header("Location: faculty_password_requests.php?success=1");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Faculty Password</title>
<style>
body{
  font-family:Arial;
  background:#f4f7fc;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}
.card{
  background:#fff;
  padding:30px;
  width:380px;
  border-radius:10px;
  box-shadow:0 10px 30px rgba(0,0,0,.15);
}
input{
  width:100%;
  padding:10px;
  margin-top:10px;
}
button{
  margin-top:15px;
  padding:10px;
  width:100%;
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:6px;
}
</style>
</head>

<body>
<div class="card">
  <h3>Reset Faculty Password</h3>

  <form method="POST">
    <label>New Password</label>
    <input type="password" name="new_password" required>

    <button>Reset Password</button>
  </form>
</div>
</body>
</html>
