<?php
session_start();
include "connection.php";

/* ===== ADMIN AUTH CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

$res = mysqli_query(
    $con,
    "SELECT * FROM student_password_requests WHERE status='pending'"
);

$requestCount = mysqli_num_rows($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Password Requests | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  font-family:Arial, sans-serif;
  background:#f4f7fc;
}

/* HEADER */
.header{
  max-width:1000px;
  margin:30px auto 10px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.header h2{
  margin:0;
}

/* BUTTON */
.btn-dashboard{
  background:#2563eb;
  color:#fff;
  padding:10px 16px;
  border-radius:8px;
  text-decoration:none;
  font-size:14px;
}

.btn-dashboard:hover{
  background:#1e40af;
}

/* TABLE */
table{
  width:100%;
  max-width:1000px;
  margin:20px auto;
  border-collapse:collapse;
  background:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}

th,td{
  padding:14px;
  text-align:left;
}

th{
  background:#2563eb;
  color:#fff;
}

tr:nth-child(even){
  background:#f8fafc;
}

/* ACTION BUTTON */
.action-btn{
  background:#22c55e;
  color:#fff;
  padding:8px 14px;
  text-decoration:none;
  border-radius:6px;
  font-size:14px;
}

.action-btn:hover{
  background:#16a34a;
}

/* EMPTY STATE */
.empty{
  max-width:1000px;
  margin:60px auto;
  background:#fff;
  padding:40px;
  text-align:center;
  border-radius:14px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.empty p{
  color:#6b7280;
  margin-top:10px;
}
</style>
</head>

<body>

<div class="header">
  <h2>Student Password Reset Requests</h2>
  <a href="admin.php" class="btn-dashboard">← Dashboard</a>
</div>

<?php if ($requestCount > 0): ?>

<table>
  <tr>
    <th>Student ID</th>
    <th>Requested On</th>
    <th>Action</th>
  </tr>

  <?php while($row = mysqli_fetch_assoc($res)): ?>
  <tr>
    <td><?= htmlspecialchars($row['student_id']) ?></td>
    <td><?= htmlspecialchars($row['request_time']) ?></td>
    <td>
      <a class="action-btn"
         href="admin_reset_student_password.php?id=<?= $row['id'] ?>&student_id=<?= $row['student_id'] ?>">
        Reset Password
      </a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<?php else: ?>

<div class="empty">
  <h3>No Pending Requests 🎉</h3>
  <p>There are currently no student password reset requests.</p>
  <br>
  <a href="admin.php" class="btn-dashboard">Go to Dashboard</a>
</div>

<?php endif; ?>

</body>
</html>
