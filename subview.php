<?php
include "connection.php";

/* ===== SEARCH ===== */
$search = $_GET['search'] ?? "";

/* ===== QUERY ===== */
$sql = "
SELECT id, course, year_of_course, semester,
       subject_code, subject_name,
       faculty_name, faculty_id
FROM subjects
WHERE
  subject_code LIKE '%$search%' OR
  subject_name LIKE '%$search%' OR
  faculty_name LIKE '%$search%' OR
  course LIKE '%$search%'
ORDER BY course, year_of_course, semester
";

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Subject Assignments</title>

<style>
body{
  font-family:Arial;
  background:#f4f7fc;
  margin:0;
}

/* ===== HEADER ===== */
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:20px;
}

.btn{
  padding:10px 16px;
  border-radius:6px;
  color:#fff;
  text-decoration:none;
}

.btn-blue{background:#4070f4}
.btn-grey{background:#6b7280}
.btn-green{background:#22c55e}
.btn-red{background:#ef4444}

/* ===== SEARCH BAR ===== */
.search-container{
  margin:10px 20px 25px 20px;
}

.search-form{
  display:flex;
  align-items:center;
  gap:12px;
}

.search-input-wrapper{
  position:relative;
}

.search-input-wrapper input{
  width:320px;
  height:42px;
  padding:0 38px 0 14px;
  font-size:15px;
  border:1px solid #cbd5e1;
  border-radius:8px;
}

.search-input-wrapper input:focus{
  border-color:#2563eb;
  outline:none;
}

.clear-btn{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  color:#64748b;
  display:none;
}

.clear-btn:hover{color:#ef4444}

.btn-search{
  height:42px;
  padding:0 18px;
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:8px;
  cursor:pointer;
}

.btn-search:hover{background:#1d4ed8}

.btn-export{
  height:42px;
  padding:0 18px;
  background:#16a34a;
  color:#fff;
  border-radius:8px;
  text-decoration:none;
  display:flex;
  align-items:center;
}

.btn-export:hover{background:#15803d}

/* ===== TABLE ===== */
table{
  width:100%;
  border-collapse:collapse;
  background:#fff;
}

th{
  background:#1E90FF;
  color:#fff;
  padding:12px;
  text-align:center;
}

td{
  padding:12px;
  border-bottom:1px solid #ddd;
  text-align:center;
}

td:nth-child(2),
td:nth-child(3){
  text-align:left;
}

tbody tr:nth-child(even){background:#f2f2f2}
tbody tr:hover{background:#eef4ff}
</style>
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="header">
  <h2>📘 Subject Assignments</h2>
  <div>
    <a href="admin.php" class="btn btn-grey">⬅ Dashboard</a>
    <a href="subform.php" class="btn btn-blue">+ Assign Subject</a>
  </div>
</div>

<!-- ===== SEARCH ===== -->
<div class="search-container">
  <form method="GET" class="search-form">
    <div class="search-input-wrapper">
      <input
        type="text"
        id="searchInput"
        name="search"
        placeholder="Search by subject, faculty, or course"
        value="<?= htmlspecialchars($search) ?>"
        oninput="toggleClear()"
      >
      <span class="clear-btn" id="clearBtn" onclick="clearSearch()">✖</span>
    </div>

    <button class="btn-search">🔍 Search</button>

    <a href="export_subjects.php?search=<?= urlencode($search) ?>"
       class="btn-export">
       ⬇ Export Excel
    </a>
  </form>
</div>

<!-- ===== TABLE ===== -->
<table>
<tr>
  <th>Course</th>
  <th>Subject Code</th>
  <th>Subject Name</th>
  <th>Semester</th>
  <th>Faculty</th>
  <th>Actions</th>
</tr>

<?php if($result->num_rows > 0){ while($row = $result->fetch_assoc()){ ?>
<tr>
  <td><?= htmlspecialchars($row['course']) ?></td>
  <td><?= htmlspecialchars($row['subject_code']) ?></td>
  <td><?= htmlspecialchars($row['subject_name']) ?></td>
  <td><?= htmlspecialchars($row['semester']) ?></td>
  <td><?= htmlspecialchars($row['faculty_name']) ?></td>
  <td>
    <a class="btn btn-green" href="editsubject.php?id=<?= $row['id'] ?>">Edit</a>
    <a class="btn btn-red"
   href="javascript:void(0)"
   onclick="showConfirm('removesubject.php?id=<?= $row['id'] ?>')">
   Remove
</a>
  </td>
</tr>
<?php }} else { ?>
<tr>
  <td colspan="6" style="text-align:center;">No assignments found</td>
</tr>
<?php } ?>
</table>

<script>
function toggleClear(){
  const input = document.getElementById("searchInput");
  document.getElementById("clearBtn").style.display =
    input.value ? "block" : "none";
}

function clearSearch(){
  window.location.href = "subview.php";
}

window.onload = toggleClear;
</script>


<!-- for remove button -->
<script>
function confirmDelete() {
    return confirm("Do you want to remove this subject assignment?");
}
</script>


<div id="confirmBox" style="display:none; position:fixed; inset:0;
background:rgba(0,0,0,.4); align-items:center; justify-content:center;">

  <div style="background:#fff; padding:25px; border-radius:8px; width:320px; text-align:center;">
    <h3 style="margin-top:0;">Confirm Delete</h3>
    <p>Do you want to remove this subject assignment?</p>

    <div style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
      <button onclick="confirmYes()" style="background:#ef4444;color:#fff;border:none;padding:8px 14px;border-radius:6px;">Yes</button>
      <button onclick="confirmNo()" style="background:#6b7280;color:#fff;border:none;padding:8px 14px;border-radius:6px;">No</button>
    </div>
  </div>
</div>
<script>
let deleteUrl = "";

function showConfirm(url){
  deleteUrl = url;
  document.getElementById("confirmBox").style.display = "flex";
}

function confirmYes(){
  window.location.href = deleteUrl;
}

function confirmNo(){
  document.getElementById("confirmBox").style.display = "none";
}
</script>

</body>
</html>
