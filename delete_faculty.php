<?php
$conn = new mysqli("localhost", "root", "", "sample");

$id = $_POST['id'] ?? 0;

/* Check subject dependency */
$check = $conn->query("SELECT * FROM subjects WHERE faculty_id=$id");
if ($check->num_rows > 0) {
    echo "assigned";
    exit;
}

$conn->query("DELETE FROM facreg WHERE id=$id");
echo "success";
