<?php
session_start();
include "connection.php";

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'student') {
    echo "<script>alert('Student should login first');window.location.href='student_login.php';</script>";
    exit;
}

$student_id = $_SESSION['student_id'];
$class = $_SESSION['class_of_student'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard | EduTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* ===== GLOBAL ===== */
*{
  box-sizing:border-box;
  font-family:Arial, sans-serif;
}
body{
  margin:0;
  background:#f4f7fc;
}

/* ===== HEADER ===== */
.header{
  background:linear-gradient(135deg,#2563eb,#1e40af);
  color:#fff;
  padding:25px;
}
.header h1{
  margin:0;
}
.header p{
  opacity:.9;
}

/* ===== CONTAINER ===== */
.container{
  max-width:1200px;
  margin:auto;
  padding:25px;
}

/* ===== INFO CARDS ===== */
.info-cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
  margin-bottom:30px;
}

.card{
  background:#fff;
  padding:20px;
  border-radius:14px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}
.card h4{
  margin:0;
  color:#6b7280;
  font-size:14px;
}
.card p{
  margin-top:8px;
  font-size:20px;
  font-weight:bold;
  color:#111827;
}

/* ===== TABLE ===== */
.table-card{
  background:#fff;
  border-radius:14px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  overflow:hidden;
}

.table-card h3{
  padding:18px;
  margin:0;
  border-bottom:1px solid #e5e7eb;
}

table{
  width:100%;
  border-collapse:collapse;
}

th,td{
  padding:12px;
  text-align:center;
}

th{
  background:#2563eb;
  color:#fff;
  font-size:14px;
}

tr:nth-child(even){
  background:#f9fafb;
}

/* ===== EMPTY ===== */
.empty{
  text-align:center;
  padding:40px;
  color:#6b7280;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
  <h1>Welcome, <?= $_SESSION['first_name']; ?> 👋</h1>
  <p>Student Dashboard</p>
</div>

<!-- MAIN -->
<div class="container">

  <!-- INFO -->
  <div class="info-cards">
    <div class="card">
      <h4>Student ID</h4>
      <p><?= $student_id ?></p>
    </div>
    <div class="card">
      <h4>Name</h4>
      <p><?= $_SESSION['first_name']." ".$_SESSION['last_name'] ?></p>
    </div>
    <div class="card">
      <h4>Class</h4>
      <p><?= $class ?></p>
    </div>
  </div>

  <!-- MARKS -->
  <div class="table-card">
    <h3>Academic Performance</h3>

<?php
$query = "SELECT * FROM result 
          WHERE student_id='$student_id' AND class='$class'";
$res = $con->query($query);

if ($res->num_rows > 0) {
    echo "<table>
    <tr>
      <th>Semester</th>
      <th>Subject</th>
      <th>Internal 1</th>
      <th>Internal 2</th>
      <th>Assignment</th>
      <th>Seminar</th>
      <th>Marks Obtained</th>
      <th>Total</th>
      <th>Percentage</th>
      <th>Grade</th>
    </tr>";

    while($row = $res->fetch_assoc()){
        echo "<tr>
        <td>{$row['semester']}</td>
        <td>{$row['subject']}</td>
        <td>{$row['internal1']}</td>
        <td>{$row['internal2']}</td>
        <td>{$row['assignment']}</td>
        <td>{$row['seminar']}</td>
        <td>{$row['marks_obtained']}</td>
        <td>40</td>
        <td>{$row['percentage']}</td>
        <td>{$row['grade']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='empty'>
            <h3>No Marks Available</h3>
            <p>Your marks have not been entered yet.</p>
          </div>";
}
?>

  </div>
</div>

</body>
</html>
