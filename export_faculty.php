<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ===== GET SEARCH ===== */
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $conn->real_escape_string(trim($_GET['search']));
}

/* ===== FILTERED QUERY ===== */
$sql = "SELECT id, username, class
        FROM facreg
        WHERE username LIKE '%$search%'
           OR class LIKE '%$search%'
        ORDER BY id ASC";

$result = $conn->query($sql);

/* ===== FORCE DOWNLOAD (NO CACHE) ===== */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=faculty_export_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

/* ===== EXCEL HEADERS ===== */
echo "ID\tFaculty Name\tCourse\n";

/* ===== DATA ===== */
while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t";
    echo $row['username'] . "\t";
    echo $row['class'] . "\n";
}
exit;
