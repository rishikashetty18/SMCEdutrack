<?php
include "connection.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $student_id = $_POST['student_id'];
    $last_name  = $_POST['last_name'];

    /* ---- CHECK IF STUDENT EXISTS ---- */
    $check = $con->prepare(
        "SELECT * FROM students WHERE student_id=? AND last_name=?"
    );
    $check->bind_param("ss", $student_id, $last_name);
    $check->execute();
    $res = $check->get_result();

    if($res->num_rows > 0){

        // Insert request
        $req = $con->prepare(
            "INSERT INTO student_password_requests (student_id) VALUES (?)"
        );
        $req->bind_param("s", $student_id);
        $req->execute();

        $success = "Password reset request sent. Contact Admin.";

    } else {
        $error = "Student details not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<style>
body{
  background:#1e40af;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
  font-family:Arial;
}
.card{
  background:#fff;
  padding:30px;
  border-radius:12px;
  width:360px;
}
input,button{
  width:100%;
  height:42px;
  margin-bottom:12px;
}
button{
  background:#2563eb;
  color:#fff;
  border:none;
  cursor:pointer;
}
button:hover{
  background:#1e40af;
}
.back-btn{
  background:#e5e7eb;
  color:#111;
}
.back-btn:hover{
  background:#d1d5db;
}
.success{color:green;}
.error{color:red;}
</style>
</head>
<body>

<div class="card">
<h3>Forgot Password</h3>

<?php if(isset($success)): ?>
  <p class="success"><?= $success ?></p>
<?php endif; ?>

<?php if(isset($error)): ?>
  <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
  <input type="text" name="student_id" placeholder="Student ID" required>
  <input type="text" name="last_name" placeholder="Last Name" required>
  <button type="submit">Submit Request</button>
</form>

<!-- ✅ BACK BUTTON (shows after submit) -->
<?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <button class="back-btn"
          onclick="location.href='student_login.php'">
    ← Back to Login
  </button>
<?php endif; ?>

</div>

</body>
</html>
