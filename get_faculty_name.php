<?php
include "connection.php";

$fid = $_GET['fid'] ?? '';

$stmt = $con->prepare("SELECT full_name FROM facreg WHERE id = ?");
$stmt->bind_param("i", $fid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['full_name'];
}
