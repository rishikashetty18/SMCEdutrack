<?php
session_start();
include "connection.php";

/* ===== SECURITY CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

/* ===== FETCH PENDING REQUESTS ===== */
$sql = "
SELECT r.id AS request_id,
       r.faculty_id,
       r.requested_at,
       f.username AS faculty_username
FROM faculty_password_requests r
JOIN facreg f ON r.faculty_id = f.id
WHERE r.status='pending'
ORDER BY r.requested_at DESC
";

$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Password Requests | EduTrack</title>

<style>
body{
  font-family:Arial, sans-serif;
  background:#f4f7fc;
  margin:0;
}

/* ===== HEADER ===== */
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:20px;
  background:#ffffff;
  box-shadow:0 2px 6px rgba(0,0,0,0.05);
}

.header h2{
  margin:0;
}

.dashboard-btn{
  background:#2563eb;
  color:#fff;
  padding:10px 16px;
  border-radius:8px;
  text-decoration:none;
  font-size:14px;
}
.dashboard-btn:hover{
  background:#1d4ed8;
}

/* ===== TABLE ===== */
.container{
  padding:20px;
}

table{
  width:100%;
  border-collapse:collapse;
  background:#fff;
  border-radius:10px;
  overflow:hidden;
}

th, td{
  padding:14px;
  border-bottom:1px solid #ddd;
  text-align:center;
}

th{
  background:#1E90FF;
  color:#fff;
}

.btn-reset{
  background:#ef4444;
  color:#fff;
  padding:6px 14px;
  border-radius:6px;
  text-decoration:none;
  font-size:13px;
}
.btn-reset:hover{
  background:#dc2626;
}

.no-data{
  padding:20px;
  color:#6b7280;
}
</style>
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="header">
  <h2>🔐 Faculty Password Reset Requests</h2>
  <a href="admin.php" class="dashboard-btn">← Back to Dashboard</a>
</div>

<!-- ===== TABLE ===== -->
<div class="container">
<table>
<tr>
  <th>Faculty Username</th>
  <th>Requested At</th>
  <th>Action</th>
</tr>

<?php if ($result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($row['faculty_username']) ?></td>
    <td><?= $row['requested_at'] ?></td>
    <td>
      <a class="btn-reset"
         href="admin_faculty_reset_password.php?fid=<?= $row['faculty_id'] ?>&rid=<?= $row['request_id'] ?>">
         Reset Password
      </a>
    </td>
  </tr>
  <?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="3" class="no-data">No pending requests</td>
</tr>
<?php endif; ?>

</table>
</div>

</body>
</html>
