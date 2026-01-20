<?php
session_start();
include "connection.php";

/* ===== AUTH CHECK ===== */
if (
    !isset($_SESSION['status']) ||
    $_SESSION['status'] !== 'admin' ||
    !isset($_SESSION['admin_id'])
) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}

$adminId = $_SESSION['admin_id'];

/* ===== INPUT ===== */
$password = trim($_POST['password'] ?? '');
$errors = [];

/* ===== PASSWORD VALIDATION ===== */
if ($password !== '' && strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}

/* ===== PROFILE PICTURE ===== */
$profilePicName = null;

if (!empty($_FILES['profile_pic']['name'])) {

    $allowedTypes = ['image/jpeg','image/png','image/jpg','image/webp'];
    if (!in_array($_FILES['profile_pic']['type'], $allowedTypes)) {
        $errors[] = "Only JPG, PNG or WEBP images allowed.";
    }

    if ($_FILES['profile_pic']['size'] > 2 * 1024 * 1024) {
        $errors[] = "Image must be under 2MB.";
    }

    if (empty($errors)) {

        // Ensure upload folder exists
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $profilePicName = "admin_" . $adminId . "." . $ext;

        move_uploaded_file(
            $_FILES['profile_pic']['tmp_name'],
            "uploads/" . $profilePicName
        );
    }
}

/* ===== STOP IF ERRORS ===== */
if (!empty($errors)) {
    echo "<script>alert('".implode("\\n", $errors)."'); window.history.back();</script>";
    exit;
}

/* ===== BUILD UPDATE QUERY ===== */
$query = "UPDATE admins SET id=id";
$params = [];
$types  = "";

/* Password update */
if ($password !== '') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query .= ", password=?";
    $params[] = $hashed;
    $types .= "s";
}

/* Profile picture update */
if ($profilePicName) {
    $query .= ", profile_pic=?";
    $params[] = $profilePicName;
    $types .= "s";

    // 🔥 UPDATE SESSION (IMPORTANT)
    $_SESSION['profile_pic'] = $profilePicName;
}

$query .= " WHERE id=?";
$params[] = $adminId;
$types .= "i";

/* ===== EXECUTE ===== */
$stmt = $con->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();

/* ===== REDIRECT ===== */
header("Location: admin.php?updated=1");
exit;
