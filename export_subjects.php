<?php
include "connection.php";

$search = $_GET['search'] ?? "";

/* Excel headers */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=subject_assignments.xls");

$sql = "
SELECT course, year_of_course, semester,
       subject_code, subject_name,
       faculty_name, faculty_id
FROM subjects
WHERE
  subject_code LIKE '%$search%' OR
  subject_name LIKE '%$search%' OR
  faculty_name LIKE '%$search%' OR
  course LIKE '%$search%'
ORDER BY course, year_of_course, semester
";

$result = $con->query($sql);

echo "Course\tYear\tSemester\tSubject Code\tSubject Name\tFaculty Name\tFaculty ID\n";

while($row = $result->fetch_assoc()){
  echo
    $row['course']."\t".
    $row['year_of_course']."\t".
    $row['semester']."\t".
    $row['subject_code']."\t".
    $row['subject_name']."\t".
    $row['faculty_name']."\t".
    $row['faculty_id']."\n";
}
