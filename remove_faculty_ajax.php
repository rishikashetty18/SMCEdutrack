<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("DB Error");
}

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM facreg WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
