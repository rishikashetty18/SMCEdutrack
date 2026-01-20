<?php
session_start();
include "connection.php";

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

/* ===== GET & VALIDATE PARAMS ===== */
if (!isset($_GET['class'], $_GET['semester'], $_GET['subject'])) {
    die("Invalid access");
}

$class    = urldecode($_GET['class']);
$semester = (int)$_GET['semester'];
$subject  = urldecode($_GET['subject']);

/* ===== FETCH MARKS ===== */
$stmt = $con->prepare("
    SELECT student_id, first_name, last_name,
           internal1, internal2, seminar, assignment,
           marks_obtained, percentage, grade, status
    FROM result
    WHERE class=? AND semester=? AND subject=?
");
$stmt->bind_param("sis", $class, $semester, $subject);
$stmt->execute();
$res = $stmt->get_result();

/* ===== APPROVE ===== */
if (isset($_POST['approve'])) {
    $stmt = $con->prepare("
        UPDATE result
        SET status='approved'
        WHERE class=? AND semester=? AND subject=?
    ");
    $stmt->bind_param("sis", $class, $semester, $subject);
    $stmt->execute();

    echo "<script>alert('Marks approved successfully'); window.location.href='approve_marks.php';</script>";
    exit;
}

/* ===== REJECT ===== */
if (isset($_POST['reject'])) {
    $stmt = $con->prepare("
        UPDATE result
        SET status='rejected'
        WHERE class=? AND semester=? AND subject=?
    ");
    $stmt->bind_param("sis", $class, $semester, $subject);
    $stmt->execute();

    echo "<script>alert('Marks sent back to faculty'); window.location.href='approve_marks.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>View & Approve Marks</title>
<style>
body{font-family:Arial;background:#f4f7fc;padding:20px}
h2{margin-bottom:15px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:center}
th{background:#2563eb;color:#fff}
.btn{padding:10px 16px;border:none;border-radius:6px;font-size:14px;cursor:pointer}
.approve{background:#16a34a;color:#fff}
.reject{background:#dc2626;color:#fff}
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
  <h2><?= htmlspecialchars($class) ?> | Sem <?= $semester ?> | <?= htmlspecialchars($subject) ?></h2>
  <button class="back-btn" onclick="location.href='approve_marks.php'">
  ⬅ Back to Dashboard
</button>
</div>

<table>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>I1</th>
  <th>I2</th>
  <th>Sem</th>
  <th>Asg</th>
  <th>Total</th>
  <th>%</th>
  <th>Grade</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr>
  <td><?= $r['student_id'] ?></td>
  <td><?= $r['first_name']." ".$r['last_name'] ?></td>
  <td><?= $r['internal1'] ?></td>
  <td><?= $r['internal2'] ?></td>
  <td><?= $r['seminar'] ?></td>
  <td><?= $r['assignment'] ?></td>
  <td><?= $r['marks_obtained'] ?></td>
  <td><?= $r['percentage'] ?></td>
  <td><?= $r['grade'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<form method="post" style="margin-top:20px">
  <button class="btn approve" name="approve">✅ Approve Marks</button>
  <button class="btn reject" name="reject">❌ Reject Marks</button>
</form>

</body>
</html>
