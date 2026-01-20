<?php
$conn = new mysqli("localhost", "root", "", "sample");

$id = (int)$_POST['id'];
$username = $_POST['username'];
$class = $_POST['class'];

/* Handle password */
if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("UPDATE facreg SET password='$password' WHERE id=$id");
}

/* Handle photo */
if (!empty($_FILES['photo']['name'])) {
    $photo = time().'_'.$_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/".$photo);
    $conn->query("UPDATE facreg SET photo='$photo' WHERE id=$id");
}

/* Update name + class */
$conn->query("UPDATE facreg SET class='$class' WHERE id=$id");

header("Location: facultylist.php");
exit;
