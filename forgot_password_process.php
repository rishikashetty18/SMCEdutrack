<?php
include "connection.php";

$email = $_POST['email'];

// Check faculty
$stmt = $con->prepare("SELECT * FROM facreg WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    $stmt = $con->prepare(
      "INSERT INTO password_resets (email, token, expires_at)
       VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $email, $token, $expires);
    $stmt->execute();

    // SEND EMAIL (or echo for testing)
    echo "Reset link: 
    http://localhost/edutrack/reset_password.php?token=$token";
} else {
    echo "Email not found";
}
