<?php
include "connection.php"; // make sure $conn is defined here

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name !== '' && $email !== '' && $message !== '') {

        $stmt = $con->prepare(
            "INSERT INTO feedback (name, email, subject, message)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }

        $stmt->close();

    } else {
        echo json_encode(["status" => "invalid"]);
    }
}
?>
