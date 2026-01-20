<?php
include "connection.php";

$token = $_GET['token'];

$stmt = $con->prepare(
 "SELECT * FROM password_resets 
  WHERE token=? AND expires_at > NOW()"
);
$stmt->bind_param("s", $token);
$stmt->execute();

if ($stmt->get_result()->num_rows !== 1) {
    die("Invalid or expired token");
}
?>

<form method="POST">
  <input type="password" name="password" placeholder="New password" required>
  <button>Reset Password</button>
</form>
