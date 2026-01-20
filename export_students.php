<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) die("Connection failed");

$search = $_GET['search'] ?? "";

/* EXACT MATCH EXPORT */
$sql = "
SELECT * FROM students
WHERE
  student_id = '$search'
  OR first_name = '$search'
  OR last_name = '$search'
  OR class = '$search'
";

$result = $conn->query($sql);

/* Excel headers */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=students_export.xls");

echo "ID\tFirst Name\tLast Name\tGender\tAddress\tClass\n";

while ($row = $result->fetch_assoc()) {
    echo
        $row['student_id'] . "\t" .
        $row['first_name'] . "\t" .
        $row['last_name'] . "\t" .
        $row['gender'] . "\t" .
        $row['address'] . "\t" .
        $row['class'] . "\n";
}
exit; // 🔑 VERY IMPORTANT
