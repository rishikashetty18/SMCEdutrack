<?php
session_start();
include "connection.php";

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'faculty') {
    die("Unauthorized");
}

if (!isset($_GET['subject_id'])) {
    die("Invalid request");
}

$faculty_id = $_SESSION['faculty_id'];
$subject_id = (int)$_GET['subject_id'];

/* Validate subject ownership */
$stmt = $con->prepare(
    "SELECT * FROM subjects WHERE id=? AND faculty_id=?"
);
$stmt->bind_param("ii", $subject_id, $faculty_id);
$stmt->execute();
$sub = $stmt->get_result()->fetch_assoc();

if (!$sub) {
    die("Invalid subject");
}

/* Resolve class */
$yearMap = [
    'First Year'  => 'First',
    'Second Year' => 'Second',
    'Third Year'  => 'Third'
];

$class = $yearMap[$sub['year_of_course']] . " " . strtoupper($sub['course']);
$semester = $sub['semester'];
$subject  = $sub['subject_name'];

/* Fetch marks */
$stmt = $con->prepare(
    "SELECT student_id, first_name, last_name,
            internal1, internal2, seminar, assignment,
            marks_obtained, percentage, grade
     FROM result
     WHERE class=? AND semester=? AND subject=?"
);
$stmt->bind_param("sis", $class, $semester, $subject);
$stmt->execute();
$res = $stmt->get_result();

/* Excel headers */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Marks_{$subject}.xls");

echo "Student ID\tFirst Name\tLast Name\tI1\tI2\tSem\tAsg\tTotal\t%\tGrade\n";

while ($row = $res->fetch_assoc()) {
    echo implode("\t", $row) . "\n";
}
