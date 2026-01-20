<?php
include "connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete subject assignment
    $stmt = $con->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// 🔁 Redirect back to subject list
header("Location: subview.php");
exit;
