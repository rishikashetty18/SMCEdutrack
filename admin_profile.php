<?php
session_start();
include "connection.php";

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}

/* ===== ADMIN DETAILS (TEMP STATIC – CAN BE DB LATER) ===== */
$adminName  = $_SESSION['admin_name'] ?? "Administrator";
$adminEmail = $_SESSION['admin_email'] ?? "admin@edutrack.com";
$adminRole  = "System Administrator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
body{
  font-family:Arial, sans-serif;
  background:#f4f7fc;
  margin:0;
}
body.dark{
  background:#0f172a;
  color:#e5e7eb;
}

/* ===== HEADER ===== */
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:20px;
}

.btn{
  padding:10px 16px;
  border-radius:6px;
  text-decoration:none;
  color:#fff;
}
.btn-grey{background:#6b7280}

/* ===== CARD ===== */
.profile-card{
  max-width:500px;
  margin:40px auto;
  background:#fff;
  padding:30px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  text-align:center;
}
body.dark .profile-card{background:#020617}

.profile-card img{
  width:110px;
  height:110px;
  border-radius:50%;
  margin-bottom:15px;
}

.profile-card h2{
  margin-bottom:5px;
}

.profile-card p{
  color:#6b7280;
  margin-bottom:20px;
}

/* ===== DETAILS ===== */
.detail{
  display:flex;
  justify-content:space-between;
  padding:10px 0;
  border-bottom:1px solid #e5e7eb;
}
body.dark .detail{border-color:#334155}

.detail span:first-child{
  font-weight:600;
}

/* ===== ACTIONS ===== */
.actions{
  margin-top:25px;
  display:flex;
  gap:12px;
  justify-content:center;
}
.btn-blue{background:#2563eb}
.btn-red{background:#ef4444}
</style>
</head>

<body>

<div class="header">
  <h2>👤 Admin Profile</h2>
  <a href="admin.php" class="btn btn-grey">⬅ Dashboard</a>
</div>

<div class="profile-card">
  <img src="images/profile.jpg" alt="Admin">
  <h2><?= htmlspecialchars($adminName) ?></h2>
  <p><?= $adminRole ?></p>

  <div class="detail">
    <span>Email</span>
    <span><?= htmlspecialchars($adminEmail) ?></span>
  </div>

  <div class="detail">
    <span>Role</span>
    <span><?= $adminRole ?></span>
  </div>

  <div class="detail">
    <span>Status</span>
    <span>Active</span>
  </div>

  <div class="actions">
    <a href="admin_settings.php" class="btn btn-blue">⚙️ Settings</a>
    <a href="logout.php" class="btn btn-red">🚪 Logout</a>
  </div>
</div>

<script>
/* Dark mode sync */
if(localStorage.getItem("theme")==="dark"){
  document.body.classList.add("dark");
}
</script>

</body>
</html>
