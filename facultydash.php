<?php
session_start();
include "connection.php";

/* AUTH CHECK */
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'faculty') {
    header("Location: faculty_login.php");
    exit;
}

$faculty_id = $_SESSION['faculty_id'];

/* ================= DASHBOARD METRICS ================= */
$q1 = $con->prepare("SELECT COUNT(*) total FROM subjects WHERE faculty_id=?");
$q1->bind_param("i",$faculty_id);
$q1->execute();
$totalSubjects = $q1->get_result()->fetch_assoc()['total'];

$q2 = $con->prepare("
  SELECT COUNT(DISTINCT CONCAT(course,'-',year_of_course)) total
  FROM subjects WHERE faculty_id=?
");
$q2->bind_param("i",$faculty_id);
$q2->execute();
$totalClasses = $q2->get_result()->fetch_assoc()['total'];

$q3 = $con->prepare("
  SELECT COUNT(*) total FROM students 
  WHERE class IN (
    SELECT DISTINCT CONCAT(
      CASE year_of_course
        WHEN 'First Year' THEN 'First'
        WHEN 'Second Year' THEN 'Second'
        WHEN 'Third Year' THEN 'Third'
      END,
      ' ',
      UPPER(course)
    )
    FROM subjects WHERE faculty_id=?
  )
");
$q3->bind_param("i",$faculty_id);
$q3->execute();
$totalStudents = $q3->get_result()->fetch_assoc()['total'];

$q4 = $con->prepare("
  SELECT COUNT(*) total FROM result
  WHERE subject IN (
    SELECT subject_name FROM subjects WHERE faculty_id=?
  )
");
$q4->bind_param("i",$faculty_id);
$q4->execute();
$totalMarks = $q4->get_result()->fetch_assoc()['total'];

/* ================= FETCH ASSIGNED SUBJECTS ================= */
$sql = "
SELECT id, course, year_of_course, semester, subject_name
FROM subjects
WHERE faculty_id = ?
ORDER BY course, year_of_course, semester
";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$res = $stmt->get_result();

/* BUILD MENU */
$menu = [];
while ($row = $res->fetch_assoc()) {
    $menu[$row['course']][$row['year_of_course']][$row['semester']][] = $row;
}

/* ================= CHART DATA ================= */
/* Students per Class */
$classData = [];
$res = $con->query("
  SELECT class, COUNT(*) total FROM students
  WHERE class IN (
      SELECT DISTINCT CONCAT(
        CASE year_of_course
          WHEN 'First Year' THEN 'First'
          WHEN 'Second Year' THEN 'Second'
          WHEN 'Third Year' THEN 'Third'
        END, ' ', UPPER(course)
      ) 
      FROM subjects WHERE faculty_id=$faculty_id
  )
  GROUP BY class
");
while($r=$res->fetch_assoc()){
    $classData[]=$r;
}

/* Subjects per Semester */
$semData = [];
$stmt = $con->prepare("
  SELECT semester, COUNT(*) total
  FROM subjects WHERE faculty_id=?
  GROUP BY semester
");
$stmt->bind_param("i",$faculty_id);
$stmt->execute();
$rs = $stmt->get_result();
while($r=$rs->fetch_assoc()){
    $semData[] = $r;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Faculty Dashboard | EduTrack</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ---------------- GLOBAL RESET ---------------- */
body{margin:0;font-family:'Inter',sans-serif;background:#0f172a;color:#e2e8f0;transition:0.3s}
*{box-sizing:border-box}

/* ---------------- NAVBAR ---------------- */
.navbar{
    height:60px;background:linear-gradient(90deg,#2563eb,#7c3aed);
    color:white;display:flex;align-items:center;padding:0 20px;gap:15px;box-shadow:0 2px 6px rgba(0,0,0,0.2);position:relative;z-index:100;
}
.hamburger{cursor:pointer;color:white;font-size:26px;display:flex;align-items:center}

/* ---------------- SIDEBAR ---------------- */
.sidebar{
    width:260px;background:#1e293b;height:100vh;position:fixed;top:60px;left:0;padding-top:10px;border-right:1px solid #334155;transition:width 0.25s ease;overflow:hidden;
}
.sidebar.collapsed{width:60px}
.sidebar h3{padding:10px 20px;margin:0;font-size:15px}
.menu-btn,.menu-item{width:100%;display:flex;align-items:center;gap:12px;padding:12px 20px;border:none;background:transparent;color:inherit;cursor:pointer;font-weight:500;text-align:left;transition:0.2s}
.menu-btn:hover,.menu-item:hover{background:#334155}
.menu-icon{font-size:22px}
.sidebar.collapsed .text{display:none}
.sidebar.collapsed .menu-btn:hover::after,.sidebar.collapsed .menu-item:hover::after{content:attr(data-label);position:absolute;left:65px;background:#1e293b;padding:6px 10px;border-radius:6px;white-space:nowrap;font-size:13px;box-shadow:0 0 10px rgba(0,0,0,0.4)}
.submenu{display:none;overflow:hidden;transition:height 0.3s ease}
.submenu.show{display:block}
.arrow{margin-left:auto;transition:transform 0.25s ease}
.rotate{transform:rotate(90deg)}

/* ---------------- MAIN ---------------- */
.main{margin-left:260px;padding:30px;transition:margin-left 0.25s ease}
.sidebar.collapsed ~ .main{margin-left:60px}

/* ---------------- LIGHT MODE ---------------- */
.light-mode{background:#f4f7fc;color:#0f172a}
.light-mode .sidebar{background:#fff;color:#1e293b;border-right:1px solid #cbd5e1}
.light-mode .menu-btn:hover,.light-mode .menu-item:hover{background:#e2e8f0}
.light-mode .navbar{color:white}

/* ---------------- THEME TOGGLE ---------------- */
.theme-toggle{cursor:pointer;display:flex;align-items:center;font-size:24px;transition:transform 0.3s}
.theme-toggle:hover{transform:rotate(20deg)}

/* ---------------- DASHBOARD CARDS ---------------- */
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-top:20px}
.card{background:#1e293b;color:white;padding:20px;border-radius:12px;display:flex;align-items:center;gap:15px;box-shadow:0 4px 10px rgba(0,0,0,0.12);opacity:0;transform:translateY(10px);transition:0.3s}
.light-mode .card{background:white;color:#1e293b}
.card .icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;background:#eef2ff;color:#2563eb}
.card .text h4{margin:0;font-size:13px;font-weight:600;color:#64748b}
.card .text h2{margin:4px 0 0;font-size:22px;font-weight:700;color:#0f172a}
.card.show{opacity:1;transform:translateY(0)}

/* ---------------- CHARTS ---------------- */
.chart-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:25px;margin-top:25px}
.chart-box{background:#1e293b;padding:20px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.12)}
.light-mode .chart-box{background:white;color:#1e293b}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
<span class="material-icons hamburger" onclick="toggleSidebar()">menu</span>
<span style="font-weight:700;font-size:18px;">EduTrack</span>
<span id="themeIcon" class="material-icons theme-toggle">dark_mode</span>
<div style="margin-left:auto;display:flex;align-items:center;gap:10px;">
<span><?= htmlspecialchars($_SESSION['username']) ?></span>
<a href="logoutfaculty.php" style="color:white;text-decoration:none;">Logout</a>
</div>
</div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
<h3>My Classes</h3>
<?php foreach ($menu as $course => $years): ?>
<button class="menu-btn" onclick="toggleMenu('<?= $course ?>')" data-label="<?= $course ?>">
<span class="material-icons menu-icon">library_books</span>
<span class="text"><?= $course ?></span>
<span class="material-icons arrow" id="arrow-<?= $course ?>">chevron_right</span>
</button>
<div class="submenu" id="<?= $course ?>">
<?php foreach ($years as $year => $sems): ?>
<button class="menu-btn" onclick="toggleMenu('<?= md5($year) ?>')" data-label="<?= $year ?>">
<span class="material-icons menu-icon">calendar_month</span>
<span class="text"><?= $year ?></span>
<span class="material-icons arrow" id="arrow-<?= md5($year) ?>">chevron_right</span>
</button>
<div class="submenu" id="<?= md5($year) ?>">
<?php foreach ($sems as $sem => $subjects): ?>
<?php foreach ($subjects as $sub): ?>
<button class="menu-item" data-label="Semester <?= $sem ?> – <?= $sub['subject_name'] ?>" onclick="location.href='result.php?subject_id=<?= $sub['id'] ?>'">
<span class="material-icons menu-icon">book</span>
<span class="text">Semester <?= $sem ?> – <?= $sub['subject_name'] ?></span>
</button>
<?php endforeach; ?>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>

<!-- MAIN -->
<div class="main">
<h2>Dashboard</h2>
<p>Overview of your assigned academic details</p>
<br>
<div class="cards" id="cards">
<div class="card"><div class="icon">📘</div><div class="text"><h4>Subjects Assigned</h4><h2><?= $totalSubjects ?></h2></div></div>
<div class="card"><div class="icon">🏫</div><div class="text"><h4>Classes Handled</h4><h2><?= $totalClasses ?></h2></div></div>
<div class="card"><div class="icon">🎓</div><div class="text"><h4>Students</h4><h2><?= $totalStudents ?></h2></div></div>
<div class="card"><div class="icon">📝</div><div class="text"><h4>Marks Entered</h4><h2><?= $totalMarks ?></h2></div></div>
</div>

<!-- Charts -->
<div class="chart-row">
<div class="chart-box">
<h3>Students per Class</h3>
<canvas id="studentsChart"></canvas>
</div>
<div class="chart-box">
<h3>Subjects per Semester</h3>
<canvas id="subjectChart"></canvas>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sidebar
function toggleSidebar(){document.getElementById("sidebar").classList.toggle("collapsed");}
function toggleMenu(id){let submenu=document.getElementById(id);let arrow=document.getElementById("arrow-"+id);submenu.classList.toggle("show");arrow.classList.toggle("rotate");}

// Theme
const root=document.body;
const themeIcon=document.getElementById("themeIcon");
if(localStorage.getItem("theme")==="light"){root.classList.add("light-mode");themeIcon.textContent="light_mode";}
themeIcon.onclick=()=>{root.classList.toggle("light-mode");const isLight=root.classList.contains("light-mode");themeIcon.textContent=isLight?"light_mode":"dark_mode";localStorage.setItem("theme",isLight?"light":"dark");}

// Animate cards
document.querySelectorAll(".card").forEach((card,index)=>{setTimeout(()=>card.classList.add("show"),index*150);});

// Chart.js
new Chart(document.getElementById('studentsChart'),{
type:'bar',
data:{
labels:<?= json_encode(array_column($classData,'class')) ?>,
datasets:[{label:'Students',data:<?= json_encode(array_column($classData,'total')) ?>,backgroundColor:'#6366f1'}]
},
options:{responsive:true,plugins:{legend:{labels:{color:root.classList.contains("light-mode")?"#0f172a":"#e2e8f0"}}},scales:{x:{ticks:{color:root.classList.contains("light-mode")?"#0f172a":"#e2e8f0"}},y:{ticks:{color:root.classList.contains("light-mode")?"#0f172a":"#e2e8f0"}}}}
});

new Chart(document.getElementById('subjectChart'),{
type:'pie',
data:{
labels:<?= json_encode(array_column($semData,'semester')) ?>,
datasets:[{data:<?= json_encode(array_column($semData,'total')) ?>,backgroundColor:['#22c55e','#3b82f6','#f97316','#ef4444']}]
},
options:{plugins:{legend:{labels:{color:root.classList.contains("light-mode")?"#0f172a":"#e2e8f0"}}}}
});
</script>
</body>
</html>
