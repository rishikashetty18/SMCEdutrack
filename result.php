<?php
session_start();
include "connection.php";

/* ================= AUTH ================= */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit;
}

if (!isset($_GET['subject_id'])) {
    die("Invalid access");
}

$faculty_id = $_SESSION['faculty_id'];
$subject_id = (int)$_GET['subject_id'];

/* ================= SUBJECT VALIDATION ================= */
$stmt = $con->prepare("SELECT * FROM subjects WHERE id=? AND faculty_id=?");
$stmt->bind_param("ii", $subject_id, $faculty_id);
$stmt->execute();
$subRes = $stmt->get_result();

if ($subRes->num_rows === 0) {
    echo "<script>alert('Your subject is not registered for this semester');
          window.location.href='facultydash.php';</script>";
    exit;
}

$subject = $subRes->fetch_assoc();

/* ================= CLASS & SEM ================= */
$yearMap = ['First Year'=>'First','Second Year'=>'Second','Third Year'=>'Third'];
$classOfStudents = $yearMap[$subject['year_of_course']] . " " . strtoupper($subject['course']);
$semester = (int)$subject['semester'];
$subjectName = $subject['subject_name'];

/* ================= FETCH STUDENTS ================= */
$stmt = $con->prepare("SELECT * FROM students WHERE class=?");
$stmt->bind_param("s", $classOfStudents);
$stmt->execute();
$students = $stmt->get_result();

/* ================= SAVE MARKS ================= */
if(isset($_POST['save'])) {
    $studid  = $_POST['studid'];
    $fname   = $_POST['firstname'];
    $lname   = $_POST['lastname'];

    $i1 = min(10, max(0, (int)$_POST['internal1']));
    $i2 = min(10, max(0, (int)$_POST['internal2']));
    $sem = min(10, max(0, (int)$_POST['seminar']));
    $asg = min(10, max(0, (int)$_POST['assignment']));

    $total = $i1 + $i2 + $sem + $asg;
    $percent = round(($total/40)*100,2);

    $grade = $percent>=90?'A+':($percent>=80?'A':($percent>=70?'B+':($percent>=60?'B':($percent>=50?'C+':($percent>=35?'C':'F')))));

    // Check existing
    $stmt = $con->prepare("SELECT id FROM result WHERE student_id=? AND class=? AND semester=? AND subject=?");
    $stmt->bind_param("isis",$studid,$classOfStudents,$semester,$subjectName);
    $stmt->execute();
    $exists = $stmt->get_result();

    if($exists->num_rows===0){
        $stmt = $con->prepare("INSERT INTO result
            (student_id, first_name, last_name,
             internal1, internal2, seminar, assignment,
             marks_obtained, total_marks, percentage, grade,
             class, semester, subject, status)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $totalMarks = 40;
        $status = 'draft';

        // ✅ Corrected INSERT bind_param
        $stmt->bind_param(
            "issiiiiddsssiss",
            $studid,
            $fname,
            $lname,
            $i1,
            $i2,
            $sem,
            $asg,
            $total,
            $totalMarks,
            $percent,
            $grade,
            $classOfStudents,
            $semester,
            $subjectName,
            $status
        );

    } else {
        $stmt = $con->prepare("UPDATE result SET
             internal1=?, internal2=?, seminar=?, assignment=?,
             marks_obtained=?, percentage=?, grade=?, status='draft'
             WHERE student_id=? AND class=? AND semester=? AND subject=?");

        // ✅ Corrected UPDATE bind_param
        $stmt->bind_param(
            "iiiidssisis",
            $i1, $i2, $sem, $asg,
            $total, $percent, $grade,
            $studid, $classOfStudents, $semester, $subjectName
        );
    }
    $stmt->execute();
    echo "<script>alert('Marks saved (Draft)');</script>";
}

/* ================= SUBMIT FOR APPROVAL ================= */
if(isset($_POST['submit_for_approval'])){
    $stmt = $con->prepare("UPDATE result SET status='submitted' WHERE class=? AND semester=? AND subject=?");
    $stmt->bind_param("sis",$classOfStudents,$semester,$subjectName);
    $stmt->execute();
    echo "<script>alert('Marks submitted for admin approval');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Enter Marks</title>
<style>
body{font-family:Arial;background:#f4f7fc;padding:20px}
h2{margin-bottom:15px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:center}
th{background:#2563eb;color:#fff}
input{width:90px;padding:4px}
.invalid{border:2px solid red;background:#fee2e2}
.submit{background:#2563eb;color:#fff;border:none;padding:6px 12px}
.approve{margin-top:15px;background:#f59e0b;color:#fff;border:none;padding:10px 16px;border-radius:6px}
.top-bar{position:sticky;top:0;z-index:100;background:#f4f7fc;padding:10px;display:flex;justify-content:space-between;border-bottom:1px solid #ddd}
.btn{text-decoration:none;padding:8px 14px;border-radius:6px;font-weight:600;font-size:14px}
.dash-btn{background:#1e40af;color:#fff}
.export-btn{background:#16a34a;color:#fff}
</style>
</head>
<body>

<h2><?= $classOfStudents ?> | Semester <?= $semester ?> | <?= $subjectName ?></h2>

<div class="top-bar">
  <a href="facultydash.php" class="btn dash-btn">⬅ Dashboard</a>
  <a href="export_marks_excel.php?subject_id=<?= $subject_id ?>" class="btn export-btn">📥 Export Excel</a>
</div>

<table>
<tr>
<th>ID</th><th>First</th><th>Last</th>
<th>I1</th><th>I2</th><th>Sem</th><th>Asg</th>
<th>Total</th><th>%</th><th>Grade</th><th>Save</th>
</tr>

<?php while($s = $students->fetch_assoc()):
$stmt = $con->prepare("SELECT * FROM result WHERE student_id=? AND class=? AND semester=? AND subject=?");
$stmt->bind_param("isis",$s['student_id'],$classOfStudents,$semester,$subjectName);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();
?>

<form method="post">
<tr>
<td><input name="studid" value="<?= $s['student_id'] ?>" readonly></td>
<td><input name="firstname" value="<?= $s['first_name'] ?>" readonly></td>
<td><input name="lastname" value="<?= $s['last_name'] ?>" readonly></td>

<td><input type="number" name="internal1" max="10" value="<?= $r['internal1'] ?? 0 ?>" oninput="recalc(this)"></td>
<td><input type="number" name="internal2" max="10" value="<?= $r['internal2'] ?? 0 ?>" oninput="recalc(this)"></td>
<td><input type="number" name="seminar" max="10" value="<?= $r['seminar'] ?? 0 ?>" oninput="recalc(this)"></td>
<td><input type="number" name="assignment" max="10" value="<?= $r['assignment'] ?? 0 ?>" oninput="recalc(this)"></td>

<td><input class="total" value="<?= $r['marks_obtained'] ?? 0 ?>" readonly></td>
<td><input class="percent" value="<?= $r['percentage'] ?? 0 ?>" readonly></td>
<td><input class="grade" value="<?= $r['grade'] ?? '' ?>" readonly></td>

<td><button class="submit" name="save">Save</button></td>
</tr>
</form>

<?php endwhile; ?>
</table>

<form method="post">
<button class="approve" name="submit_for_approval">📤 Submit Marks for Admin Approval</button>
</form>

<script>
function recalc(el){
    const row = el.closest('tr');
    const i1 = parseInt(row.querySelector('input[name="internal1"]').value)||0;
    const i2 = parseInt(row.querySelector('input[name="internal2"]').value)||0;
    const sem = parseInt(row.querySelector('input[name="seminar"]').value)||0;
    const asg = parseInt(row.querySelector('input[name="assignment"]').value)||0;

    const total = i1+i2+sem+asg;
    row.querySelector('.total').value = total;

    const percent = ((total/40)*100).toFixed(2);
    row.querySelector('.percent').value = percent;

    let grade='';
    if(percent>=90) grade='A+';
    else if(percent>=80) grade='A';
    else if(percent>=70) grade='B+';
    else if(percent>=60) grade='B';
    else if(percent>=50) grade='C+';
    else if(percent>=35) grade='C';
    else grade='F';
    row.querySelector('.grade').value=grade;

    ['internal1','internal2','seminar','assignment'].forEach(name=>{
        const input=row.querySelector(`input[name="${name}"]`);
        if(parseInt(input.value)>10){ input.value=''; input.classList.add('invalid'); }
        else input.classList.remove('invalid');
    });
}
</script>

</body>
</html>
