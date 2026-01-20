<?php
session_start();
include "connection.php";

/* ================== STRONG SESSION GUARD ================== */
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

/* ================== FETCH ADMIN ================== */
$stmt = $con->prepare("SELECT profile_pic FROM admins WHERE id=?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

if (!$admin) {
    die("Admin not found");
}

$profilePic = !empty($admin['profile_pic'])
    ? "uploads/" . $admin['profile_pic']
    : "assets/default-avatar.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Settings | EduTrack</title>

<style>
*{
    box-sizing:border-box;
    font-family:'Segoe UI',Tahoma,sans-serif;
}

body{
    margin:0;
    background:linear-gradient(135deg,#e9f0ff,#f8fbff);
}

.container{
    max-width:520px;
    margin:70px auto;
    background:#fff;
    padding:35px;
    border-radius:14px;
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

h2{
    text-align:center;
    color:#1e293b;
    margin-bottom:25px;
}

.avatar{
    text-align:center;
    margin-bottom:20px;
}

.avatar img{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #cbd5e1;
}

label{
    font-size:14px;
    font-weight:600;
    color:#334155;
    display:block;
    margin-bottom:6px;
}

input{
    width:100%;
    height:44px;
    padding:10px 12px;
    border-radius:8px;
    border:1px solid #cbd5e1;
    margin-bottom:10px;
    font-size:14px;
}

input:focus{
    outline:none;
    border-color:#3b82f6;
    box-shadow:0 0 0 2px rgba(59,130,246,.2);
}

small{
    display:block;
    color:#64748b;
    margin-top:-5px;
    margin-bottom:15px;
}

.actions{
    display:flex;
    gap:12px;
    margin-top:25px;
}

button{
    flex:1;
    height:45px;
    border-radius:8px;
    border:none;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}

.btn-blue{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    color:white;
}

.btn-red{
    background:#f1f5f9;
    border:1px solid #cbd5e1;
    color:#334155;
}

.btn-red:hover{
    background:#e2e8f0;
}
</style>
</head>

<body>

<div class="container">
    <h2>⚙ Admin Settings</h2>

    <div class="avatar">
        <img id="preview" src="<?= $profilePic ?>">
    </div>

    <form method="POST" action="update_admin.php" enctype="multipart/form-data">

        <label>New Password</label>
        <input type="password" name="password" placeholder="Leave empty to keep current password">
        <small>Password will remain unchanged if left empty</small>

        <label>Profile Picture</label>
        <input type="file" name="profile_pic" accept="image/*" onchange="previewImage(event)">

        <div class="actions">
            <button type="submit" class="btn-blue">💾 Save Changes</button>
            <a href="admin.php" style="flex:1;text-decoration:none;">
                <button type="button" class="btn-red">Cancel</button>
            </a>
        </div>

    </form>
</div>

<script>
function previewImage(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
