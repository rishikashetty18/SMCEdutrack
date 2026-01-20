<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Faculty | EduTrack</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7fc;
    margin: 0;
    padding: 0;
}

/* ===== CARD ===== */
.container {
    max-width: 520px;
    margin: 60px auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

h1 {
    text-align: center;
    color: #1E90FF;
    margin-bottom: 20px;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 14px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

input[type="text"],
input[type="password"],
input[type="file"],
select {
    width: 100%;
    padding: 9px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input:focus,
select:focus {
    outline: none;
    border-color: #1E90FF;
}

/* ===== BUTTONS ===== */
.btn {
    width: 100%;
    padding: 11px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

.btn-submit {
    background: #1E90FF;
    color: white;
}

.btn-submit:hover {
    background: #0b70d1;
}

.btn-back {
    background: #ddd;
    color: #333;
    margin-top: 10px;
}

.btn-back:hover {
    background: #cfcfcf;
}

/* ===== PHOTO PREVIEW ===== */
.preview {
    text-align: center;
    margin-bottom: 10px;
}

.preview img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #1E90FF;
}
</style>

<script>
function validateForm() {
    let name = document.getElementById("name").value.trim();
    let password = document.getElementById("password").value.trim();

    if (name === "" || password === "") {
        alert("Please fill all required fields");
        return false;
    }
    return true;
}

function previewPhoto(event) {
    const img = document.getElementById("photoPreview");
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>

</head>
<body>

<div class="container">
    <h1>Faculty Information</h1>

    <form action="submitfaculty.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">

        <!-- PHOTO -->
        <div class="preview">
            <img id="photoPreview" src="images/default.png" alt="Faculty Photo">
        </div>

        <div class="form-group">
            <label>Faculty Photo</label>
            <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)">
        </div>

        <!-- NAME -->
        <div class="form-group">
            <label>Name *</label>
            <input type="text" id="name" name="name" required>
        </div>

        <!-- GENDER -->
        <div class="form-group">
            <label>Gender *</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>

        <!-- CLASS -->
        <div class="form-group">
            <label>Assigned Course *</label>
            <select name="class" required>
                <option value="">Select Course</option>
                <option>BCA</option>
                <option>BCom</option>
                <option>BA</option>
            </select>
        </div>

        <!-- PASSWORD -->
        <div class="form-group">
            <label>Password *</label>
            <input type="password" id="password" name="password" required>
        </div>

        <!-- SUBMIT -->
        <button type="submit" class="btn btn-submit">Add Faculty</button>

        <!-- BACK -->
        <button type="button" class="btn btn-back" onclick="window.location.href='facultylist.php'">
            ← Back to Faculty List
        </button>

    </form>
</div>

</body>
</html>
