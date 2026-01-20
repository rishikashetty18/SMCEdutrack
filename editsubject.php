<?php
include "connection.php";

$id = $_GET['id'] ?? 0;
if (!$id) {
    header("Location: subview.php");
    exit;
}

/* ===== FETCH EXISTING RECORD ===== */
$stmt = $con->prepare("
    SELECT course, year_of_course, semester,
           subject_code, subject_name,
           faculty_name, faculty_id
    FROM subjects
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: subview.php");
    exit;
}

$data = $result->fetch_assoc();

/* ===== UPDATE ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $course  = $_POST['course'];
    $yoc     = $_POST['year_of_course'];
    $sem     = $_POST['semester'];
    $scode   = $_POST['subject_code'];
    $sname   = $_POST['subject_name'];
    $fname   = $_POST['faculty_name'];
    $fid     = $_POST['faculty_id'];

    $update = $con->prepare("
        UPDATE subjects
        SET course = ?, year_of_course = ?, semester = ?,
            subject_code = ?, subject_name = ?,
            faculty_name = ?, faculty_id = ?
        WHERE id = ?
    ");

    $update->bind_param(
        "ssssssii",
        $course, $yoc, $sem,
        $scode, $sname,
        $fname, $fid,
        $id
    );

    if ($update->execute()) {
        header("Location: subview.php?status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Subject Assignment</title>

<style>
body{
  font-family:Arial;
  background:#f4f7fc;
  margin:0;
}

.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:20px;
}

.btn{
  padding:10px 16px;
  border-radius:6px;
  color:#fff;
  text-decoration:none;
}

.btn-grey{background:#6b7280}
.btn-blue{background:#4070f4}

.card{
  max-width:600px;
  margin:30px auto;
  background:#fff;
  padding:30px;
  border-radius:10px;
  box-shadow:0 8px 20px rgba(0,0,0,.08);
}

.card h3{
  text-align:center;
  color:#1E90FF;
  margin-bottom:20px;
}

.form-group{
  margin-bottom:15px;
}

label{
  display:block;
  font-weight:600;
  margin-bottom:6px;
}

input, select{
  width:100%;
  height:42px;
  padding:8px 12px;
  border:1px solid #cbd5e1;
  border-radius:6px;
}

input:focus, select:focus{
  outline:none;
  border-color:#2563eb;
  box-shadow:0 0 0 2px rgba(37,99,235,.15);
}

.btn-save{
  background:#22c55e;
  border:none;
  color:#fff;
  padding:12px;
  width:100%;
  border-radius:6px;
  font-size:15px;
  cursor:pointer;
}

.btn-save:hover{background:#16a34a}
</style>
</head>

<body>

<div class="header">
  <h2>✏ Edit Subject Assignment</h2>
  <a href="subview.php" class="btn btn-grey">⬅ Back</a>
</div>

<div class="card">
  <h3>Edit Assignment</h3>

  <form method="POST">

    <div class="form-group">
      <label>Course</label>
      <input type="text" name="course" value="<?= htmlspecialchars($data['course']) ?>" required>
    </div>

    <div class="form-group">
      <label>Year of Course</label>
      <input type="text" name="year_of_course" value="<?= htmlspecialchars($data['year_of_course']) ?>" required>
    </div>

    <div class="form-group">
      <label>Semester</label>
      <input type="text" name="semester" value="<?= htmlspecialchars($data['semester']) ?>" required>
    </div>

    <div class="form-group">
      <label>Subject Code</label>
      <input type="text" name="subject_code" value="<?= htmlspecialchars($data['subject_code']) ?>" required>
    </div>

    <div class="form-group">
      <label>Subject Name</label>
      <input type="text" name="subject_name" value="<?= htmlspecialchars($data['subject_name']) ?>" required>
    </div>

    <div class="form-group">
      <label>Faculty Name</label>
      <input type="text" name="faculty_name" value="<?= htmlspecialchars($data['faculty_name']) ?>" required>
    </div>

    <div class="form-group">
      <label>Faculty ID</label>
      <input type="number" name="faculty_id" value="<?= htmlspecialchars($data['faculty_id']) ?>" required>
    </div>

    <button class="btn-save">💾 Update Assignment</button>

  </form>
</div>

</body>
</html>
