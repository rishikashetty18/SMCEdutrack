<?php
session_start();
include "connection.php";

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
// profile pic
$adminId = $_SESSION['admin_id'];

$stmt = $con->prepare("SELECT profile_pic FROM admins WHERE id=?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

$avatar = (!empty($admin['profile_pic']) && file_exists("uploads/".$admin['profile_pic']))
    ? "uploads/".$admin['profile_pic']
    : "assets/default-avatar.png";


/* ===== DASHBOARD COUNTS ===== */
$studentCount = mysqli_fetch_assoc(
  mysqli_query($con, "SELECT COUNT(*) AS total FROM students")
)['total'];

$facultyCount = mysqli_fetch_assoc(
  mysqli_query($con, "SELECT COUNT(*) AS total FROM facreg")
)['total'];

$subjectCount = mysqli_fetch_assoc(
  mysqli_query($con, "SELECT COUNT(*) AS total FROM subjects")
)['total'];

// for FACULTY PASSWORD REQUESTS
$pendingReq = mysqli_fetch_assoc(
  mysqli_query(
    $con,
    "SELECT COUNT(*) AS total 
     FROM faculty_password_requests 
     WHERE status='pending'"
  )
)['total'];

//for STUDENTS PASSWORD REQUESTS
$pendingStudentReq = mysqli_fetch_assoc(
  mysqli_query(
    $con,
    "SELECT COUNT(*) AS total 
     FROM student_password_requests 
     WHERE status='pending'"
  )
)['total'];


$pendingMarks = mysqli_fetch_assoc(
    mysqli_query($con, "
        SELECT COUNT(DISTINCT class, semester, subject) AS total
        FROM result
        WHERE status='submitted'
    ")
)['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EduTrack | Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
  font-family:Arial, sans-serif;
  background:#f4f7fc;
  transition:.3s;
}
body.dark{background:#0f172a;color:#e5e7eb}

/* ===== NAVBAR ===== */
.navbar{
  height:60px;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 20px;
  box-shadow:0 2px 8px rgba(0,0,0,.05);
}
body.dark .navbar{background:#020617}
.logo_item{
  display:flex;align-items:center;gap:10px;
  font-size:20px;font-weight:700;color:#2563eb;
}
.logo_item i{font-size:26px;cursor:pointer}
.navbar_content{display:flex;align-items:center;gap:20px}

/* ===== PROFILE ===== */
.profile-dropdown{position:relative}
.profile{width:36px;height:36px;border-radius:50%;cursor:pointer}
.dropdown{
  position:absolute;right:0;top:45px;
  background:#fff;border-radius:6px;
  box-shadow:0 5px 15px rgba(0,0,0,.15);
  display:none;overflow:hidden;
}
body.dark .dropdown{background:#020617}
.dropdown a{
  display:block;padding:10px 14px;
  text-decoration:none;color:#111;font-size:14px;
}
.dropdown a:hover{background:#e0e7ff}
.profile-dropdown:hover .dropdown{display:block}

/* ===== SIDEBAR ===== */
.sidebar{
  position:fixed;top:60px;left:0;
  width:240px;height:100%;
  background:#fff;padding:15px;transition:.3s;
}
body.dark .sidebar{background:#020617}
.sidebar.close{width:70px}
.menu_items{list-style:none}
.nav_link{
  display:flex;align-items:center;gap:12px;
  padding:12px;margin-bottom:6px;
  color:#111;text-decoration:none;border-radius:6px;
}
.nav_link:hover,.nav_link.active{
  background:#2563eb;color:#fff;
}
.sidebar.close .nav_link span{display:none}

/* ===== MAIN ===== */
.dashboard{
  margin-left:240px;
  padding:25px;
  transition:.3s;
}
.sidebar.close ~ .dashboard{margin-left:70px}
.page-title{font-size:26px;margin-bottom:6px}
.page-subtitle{color:#6b7280;margin-bottom:25px}

/* ===== CARDS ===== */
.card-container{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  margin-bottom:30px;
}
@media(max-width:1200px){
  .card-container{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
  .card-container{grid-template-columns:1fr}
}
.dash-card{
  background:#fff;
  padding:20px;
  border-radius:12px;
  display:flex;
  align-items:center;
  gap:15px;
  cursor:pointer;
  transition:.3s;
}
body.dark .dash-card{background:#020617}
.dash-card:hover{transform:translateY(-4px)}
.dash-card i{font-size:32px;color:#2563eb}
.dash-card h3{font-size:24px}

/* ===== ALERT CARD ===== */
.alert-card{
  border-left:6px solid #ef4444;
  background:#fff5f5;
}
.alert-card i{color:#ef4444}

/* ===== CHART & ACTIVITY ===== */
.chart-box,.activity-box{
  background:#fff;
  padding:20px;
  border-radius:12px;
}
body.dark .chart-box,
body.dark .activity-box{background:#020617}
.activity-box ul{margin-top:10px}
.activity-box li{margin-bottom:8px}

/* ===== BIG SYSTEM OVERVIEW ===== */
.full-chart{
  width:100%;
  margin-top:30px;
}

.full-chart canvas{
  height:350px !important;
}

/* Improve chart box look */
.chart-box h3{
  font-size:20px;
  margin-bottom:15px;
}

.card-container{
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:20px;
  margin-bottom:30px;
}

/* Tablets */
@media (max-width: 1024px){
  .card-container{
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Mobile */
@media (max-width: 600px){
  .card-container{
    grid-template-columns: 1fr;
  }
}



</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo_item">
    <i class='bx bx-menu' id="sidebarOpen"></i>
    EduTrack
  </div>
  <div class="navbar_content">
    <i class='bx bx-moon' id="darkLight"></i>
    <div class="profile-dropdown">
      <img src="<?= $avatar ?>" class="profile">

      <div class="dropdown">
        <a href="admin_profile.php">👤 Profile</a>
        <a href="admin_settings.php">⚙️ Settings</a>
        <a href="logout.php">🚪 Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<nav class="sidebar">
  <ul class="menu_items">
    <li><a class="nav_link active"><i class='bx bx-home'></i><span>Dashboard</span></a></li>
    <li><a href="studentlist.php" class="nav_link"><i class='bx bx-user'></i><span>Students</span></a></li>
    <li><a href="facultylist.php" class="nav_link"><i class='bx bx-user-voice'></i><span>Faculty</span></a></li>
    <li><a href="subform.php" class="nav_link"><i class='bx bx-book'></i><span>Subjects</span></a></li>
  </ul>
</nav>

<!-- MAIN CARDS-->
<section class="dashboard">
  <h2 class="page-title">Welcome, Admin 👋</h2>
  <p class="page-subtitle">EduTrack system overview</p>

  <div class="card-container">
    <div class="dash-card" onclick="location.href='studentlist.php'">
      <i class='bx bx-user'></i>
      <div><h3><?= $studentCount ?></h3><p>Students</p></div>
    </div>

    <div class="dash-card" onclick="location.href='facultylist.php'">
      <i class='bx bx-user-voice'></i>
      <div><h3><?= $facultyCount ?></h3><p>Faculty</p></div>
    </div>

    <div class="dash-card" onclick="location.href='subform.php'">
      <i class='bx bx-book'></i>
      <div><h3><?= $subjectCount ?></h3><p>Subjects</p></div>
    </div>

    <div class="dash-card alert-card"
         onclick="location.href='faculty_password_requests.php'">
      <i class='bx bx-lock-alt'></i>
      <div><h3><?= $pendingReq ?></h3><p>Password Requests</p></div>
    </div>

    <div class="dash-card alert-card"
     onclick="location.href='admin_student_reset_requests.php'">
  <i class='bx bx-user-lock'></i>
  <div>
    <h3><?= $pendingStudentReq ?></h3>
    <p>Student Password Requests</p>
  </div>
</div>


   <div class="dash-card alert-card"
     onclick="location.href='approve_marks.php'">
  <i class='bx bx-upload'></i>
  <div>
    <h3><?= $pendingMarks ?></h3>
    <p>Pending Marks</p>
    <small>Subjects Waiting</small>
  </div>
</div>



  </div>

  <!-- SYSTEM OVERVIEW (BIG) -->
<div class="chart-box full-chart">
  <h3>System Overview</h3>
  <canvas id="overviewChart"></canvas>
</div>

<!-- ACTIVITY -->
<div class="activity-box">
  <h4>Recent Activity</h4>
  <ul>
    <li>New student registered</li>
    <li>Faculty profile updated</li>
    <li>Subject assigned</li>
  </ul>
</div>
</section>

<script>
document.getElementById("sidebarOpen").onclick=()=>{
  document.querySelector(".sidebar").classList.toggle("close");
};
const toggle=document.getElementById("darkLight");
if(localStorage.getItem("theme")==="dark")
  document.body.classList.add("dark");
toggle.onclick=()=>{
  document.body.classList.toggle("dark");
  localStorage.setItem("theme",
    document.body.classList.contains("dark")?"dark":"light");
};
//chart
new Chart(document.getElementById('overviewChart'),{
  type:'bar',
  data:{
    labels:['Students','Faculty','Subjects'],
    datasets:[{
      label:'Count',
      data:[<?= $studentCount ?>,<?= $facultyCount ?>,<?= $subjectCount ?>],
      backgroundColor:['#2563eb','#22c55e','#ef4444'],
      borderRadius:8
    }]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{
      legend:{display:false}
    },
    scales:{
      y:{
        beginAtZero:true
      }
    }
  }
});

</script>

</body>
</html>
