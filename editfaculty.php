<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int)$_GET['id'];

/* Fetch faculty from facreg */
$stmt = $conn->prepare("SELECT * FROM facreg WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div style='
        max-width:600px;
        margin:80px auto;
        padding:20px;
        background:#fee2e2;
        color:#991b1b;
        border-radius:6px;
        text-align:center;
        font-family:Arial;
    '>Faculty member not found</div>";
    exit;
}

$faculty = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Faculty</title>
<style>
body {
  font-family: Arial;
  background:#f4f7fc;
}
.container {
  max-width:520px;
  margin:60px auto;
  background:#fff;
  padding:25px;
  border-radius:8px;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}
h2 {
  text-align:center;
  color:#1E90FF;
}
.form-group {
  margin-bottom:15px;
}
label {
  display:block;
  font-weight:600;
  margin-bottom:6px;
}
input, select {
  width:100%;
  padding:10px;
  border:1px solid #ccc;
  border-radius:5px;
}
.btn {
  width:100%;
  padding:12px;
  border:none;
  border-radius:6px;
  cursor:pointer;
}
.btn-save {
  background:#1E90FF;
  color:#fff;
}
.btn-back {
  margin-top:10px;
  background:#6b7280;
  color:#fff;
}
</style>
</head>

<body>

<div class="container">
  <h2>✏ Edit Faculty</h2>

  <form action="updatefaculty.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $faculty['id']; ?>">

    <div class="form-group">
      <label>Faculty Name</label>
      <input type="text" name="username"
             value="<?php echo htmlspecialchars($faculty['username']); ?>" required>
    </div>

    <div class="form-group">
      <label>Course</label>
      <select name="class" required>
        <option value="BCA"  <?php if($faculty['class']=="BCA")  echo "selected"; ?>>BCA</option>
        <option value="Bcom" <?php if($faculty['class']=="Bcom") echo "selected"; ?>>BCom</option>
        <option value="BA"   <?php if($faculty['class']=="BA")   echo "selected"; ?>>BA</option>
      </select>
    </div>

    <div class="form-group">
      <label>New Password (leave blank to keep same)</label>
      <input type="password" name="password">
    </div>

    <div class="form-group">
      <label>Faculty Photo</label>
      <input type="file" name="photo">
    </div>

    <button class="btn btn-save" type="submit">Update Faculty</button>
    <button class="btn btn-back" type="button"
            onclick="window.location.href='facultylist.php'">
      ← Back to Faculty List
    </button>
  </form>
</div>

</body>
</html>
