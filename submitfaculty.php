<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = $_POST['name'];
    $username  = strtolower(trim($_POST['name'])); // simple username
    $class     = $_POST['class'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* ===== PHOTO UPLOAD ===== */
    $photoName = null;

    if (!empty($_FILES['photo']['name'])) {
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        $photoName = time() . "_" . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photoName);
    }

    $stmt = $conn->prepare(
        "INSERT INTO facreg (full_name, username, password, class, photo)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("sssss", $full_name, $username, $password, $class, $photoName);

    if ($stmt->execute()) {
        header("Location: facultylist.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
