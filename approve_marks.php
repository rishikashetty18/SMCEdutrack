<?php
session_start();
include "connection.php";

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

/* ===== FETCH SUBMITTED MARKS WITH FACULTY NAME ===== */
$q = $con->prepare("
    SELECT DISTINCT
        r.class,
        r.semester,
        r.subject,
        f.full_name AS faculty_name,
        f.id AS faculty_id
    FROM result r
    JOIN subjects s ON s.subject_name = r.subject
    JOIN facreg f ON f.id = s.faculty_id
    WHERE r.status = 'submitted'
");
$q->execute();
$res = $q->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve Marks</title>
<style>
body{font-family:Arial;background:#f4f7fc;padding:20px}
h2{margin-bottom:15px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:10px;border-bottom:1px solid #ddd;text-align:center}
th{background:#2563eb;color:#fff}
a.btn{
  padding:6px 12px;
  background:#16a34a;
  color:#fff;
  border-radius:6px;
  text-decoration:none;
  font-size:14px;
}
.empty{
  padding:20px;
  background:#fff;
  border-radius:8px;
  text-align:center;
  color:#555;
}
.top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:15px;
}
.back-btn{
  background:#1e40af;
  color:#fff;
  border:none;
  padding:8px 14px;
  border-radius:6px;
  font-size:14px;
  cursor:pointer;
  font-weight:600;
}
.back-btn:hover{
  background:#1d4ed8;
}

</style>
</head>

<body>

<div class="top">
  <h2>📊 Marks Pending Approval</h2>
  <button class="back-btn" onclick="location.href='admin.php'">
  ⬅ Back to Dashboard
</button>

</div>

<?php if ($res->num_rows === 0): ?>
  <div class="empty">
    ✅ No pending marks for approval
  </div>
<?php else: ?>

<table>
<tr>
  <th>Class</th>
  <th>Semester</th>
  <th>Subject</th>
  <th>Faculty</th>
  <th>Action</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['class']) ?></td>
  <td><?= $r['semester'] ?></td>
  <td><?= htmlspecialchars($r['subject']) ?></td>
  <td><?= htmlspecialchars($r['faculty_name']) ?></td>
  <td>
    <a class="btn"
       href="view_marks.php?class=<?= urlencode($r['class']) ?>&semester=<?= $r['semester'] ?>&subject=<?= urlencode($r['subject']) ?>">
       View & Approve
    </a>
  </td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

</body>
</html>
