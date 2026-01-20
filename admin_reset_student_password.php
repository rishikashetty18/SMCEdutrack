<?php
session_start();
include "connection.php";

/* ===== ADMIN AUTH ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$request_id = $_GET['id'] ?? null;
$student_id = $_GET['student_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newpass = $_POST['new_password'];
    $hash = password_hash($newpass, PASSWORD_DEFAULT);

    $stmt = $con->prepare("UPDATE students SET password=? WHERE student_id=?");
    $stmt->bind_param("ss", $hash, $student_id);
    $stmt->execute();

    $stmt = $con->prepare(
        "UPDATE student_password_requests SET status='resolved' WHERE id=?"
    );
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Student Password | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
/* ===== GLOBAL FIXES ===== */
*, *::before, *::after{
  box-sizing:border-box;
}

body{
  margin:0;
  font-family:Arial, sans-serif;
  background:#f4f7fc;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}

/* ===== CARD ===== */
.card{
  width:480px;
  background:#fff;
  border-radius:18px;
  box-shadow:0 30px 70px rgba(0,0,0,.18);
  overflow:hidden;
}

/* ===== HEADER ===== */
.card-header{
  background:linear-gradient(135deg,#2563eb,#1e40af);
  padding:24px;
  color:#fff;
}

.card-header h2{
  margin:0;
  font-size:24px;
}

.card-header p{
  margin-top:6px;
  font-size:14px;
  opacity:.9;
}

/* ===== BODY ===== */
.card-body{
  padding:30px;
}

/* ===== INPUT FIELD ===== */
.field{
  position:relative;
  margin-bottom:26px;
}

.field input{
  width:100%;
  height:50px;
  padding:12px 52px 12px 16px;
  border-radius:10px;
  border:1.6px solid #cbd5e1;
  font-size:15px;
  background:#ffffff;
  color:#111827;
}

.field input::placeholder{
  color:#9ca3af;
}

.field input:focus{
  outline:none;
  border-color:#2563eb;
  box-shadow:0 0 0 2px rgba(37,99,235,.15);
}

/* ===== FIX CHROME AUTOFILL ===== */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
  -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
  -webkit-text-fill-color: #111827 !important;
  transition: background-color 5000s ease-in-out 0s;
}

/* ===== EYE ICON ===== */
.field i{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  font-size:20px;
  color:#6b7280;
  cursor:pointer;
  background:#f1f5f9;
  padding:7px;
  border-radius:8px;
}

.field i:hover{
  background:#e5e7eb;
}

/* ===== BUTTONS ===== */
.btn{
  width:100%;
  height:50px;
  border:none;
  border-radius:10px;
  font-size:15px;
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
}

.btn-primary{
  background:#2563eb;
  color:#fff;
}

.btn-primary:hover{
  background:#1e40af;
}

.btn-secondary{
  margin-top:14px;
  background:#e5e7eb;
  color:#111;
}

.btn-secondary:hover{
  background:#d1d5db;
}

/* ===== TOAST ===== */
.toast{
  position:fixed;
  top:30px;
  right:30px;
  background:#22c55e;
  color:#fff;
  padding:14px 18px;
  border-radius:10px;
  display:flex;
  align-items:center;
  gap:10px;
  box-shadow:0 15px 30px rgba(0,0,0,.25);
  animation:slideIn .4s ease;
}

@keyframes slideIn{
  from{opacity:0;transform:translateX(20px)}
  to{opacity:1;transform:translateX(0)}
}
</style>
</head>

<body>

<?php if(isset($success)): ?>
<div class="toast" id="toast">
  <i class='bx bx-check-circle'></i>
  Password reset successfully
</div>
<script>
  setTimeout(()=>document.getElementById("toast").remove(),3000);
</script>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h2>Reset Student Password</h2>
    <p>Set a new secure password for the student</p>
  </div>

  <div class="card-body">
    <form method="POST">
      <div class="field">
        <input type="password" id="pwd" name="new_password"
               placeholder="Enter New Password" required>
        <i class='bx bx-show' id="togglePwd"></i>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class='bx bx-lock-open'></i>
        Reset Password
      </button>
    </form>

    <button class="btn btn-secondary"
            onclick="location.href='admin_student_reset_requests.php'">
      <i class='bx bx-arrow-back'></i>
      Back to Requests
    </button>
  </div>
</div>

<script>
const pwd = document.getElementById("pwd");
const toggle = document.getElementById("togglePwd");

toggle.onclick = () => {
  if (pwd.type === "password") {
    pwd.type = "text";
    toggle.classList.replace("bx-show","bx-hide");
  } else {
    pwd.type = "password";
    toggle.classList.replace("bx-hide","bx-show");
  }
};
</script>

</body>
</html>
