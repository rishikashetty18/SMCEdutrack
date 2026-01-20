<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 🔹 Get form values
    $course   = $_POST['course'];
    $yoc      = $_POST['year_of_course'];
    $semester = $_POST['semester'];
    $scode    = $_POST['subject_code'];
    $sname    = $_POST['subject_name'];
    $fid      = $_POST['f_id'];

    /* ===============================
       1️⃣ Fetch faculty name by ID
    =============================== */
    $facStmt = $con->prepare("
        SELECT full_name 
        FROM facreg 
        WHERE id = ?
    ");
    $facStmt->bind_param("i", $fid);
    $facStmt->execute();
    $facRes = $facStmt->get_result();

    if ($facRes->num_rows === 0) {
        header("Location: subform.php?status=invalid_faculty");
        exit;
    }

    $faculty = $facRes->fetch_assoc();
    $fname = $faculty['full_name'];

    /* ===============================
       2️⃣ Check duplicate assignment
       (subject_code + faculty_id + semester)
    =============================== */
    $checkStmt = $con->prepare("
        SELECT id 
        FROM subjects 
        WHERE subject_code = ? 
          AND faculty_id = ? 
          AND semester = ?
    ");
    $checkStmt->bind_param("sis", $scode, $fid, $semester);
    $checkStmt->execute();
    $checkRes = $checkStmt->get_result();

    if ($checkRes->num_rows > 0) {
        header("Location: subform.php?status=duplicate");
        exit;
    }

    /* ===============================
       3️⃣ Insert subject assignment
    =============================== */
    $insertStmt = $con->prepare("
        INSERT INTO subjects
        (course, year_of_course, semester, subject_code, subject_name, faculty_name, faculty_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $insertStmt->bind_param(
        "ssssssi",
        $course,
        $yoc,
        $semester,
        $scode,
        $sname,
        $fname,
        $fid
    );

    if ($insertStmt->execute()) {
        header("Location: subform.php?status=success");
    } else {
        header("Location: subform.php?status=error");
    }

    exit;
}
?>
