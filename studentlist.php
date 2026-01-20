<?php
// DB Connection
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ================= SEARCH ================= */
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

/* ================= PAGINATION ================= */
$limit = 8;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

/* ================= QUERY ================= */
$sql = "SELECT * FROM students 
        WHERE first_name LIKE '%$search%' 
        OR last_name LIKE '%$search%' 
        OR class LIKE '%$search%'
        LIMIT $start, $limit";

$result = $conn->query($sql);

/* ================= TOTAL RECORDS ================= */
$totalResult = $conn->query("SELECT COUNT(*) total FROM students");
$totalRow = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRow / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student List</title>

<style>
body{font-family:Arial;background:#f4f7fc;margin:0}

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
  outline:none;
}

.search-input-wrapper input:focus{
  border-color:#2563eb;
  box-shadow:0 0 0 2px rgba(37,99,235,.15);
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

td:nth-child(3),
td:nth-child(4),
td:nth-child(6){
  text-align:left;
}

tbody tr:nth-child(even){background:#f2f2f2}
tbody tr:hover{background:#eef4ff}

img{
  width:42px;
  height:42px;
  border-radius:50%;
  object-fit:cover;
}

/* ===== PAGINATION ===== */
.pagination{
  text-align:center;
  margin:20px;
}

.pagination a{
  padding:6px 10px;
  margin:2px;
  background:#ddd;
  text-decoration:none;
}

.pagination a.active{
  background:#4070f4;
  color:#fff;
}
</style>
</head>

<body>

<div class="header">
  <h2>🎓 Student List</h2>
  <div>
    <a href="admin.php" class="btn btn-grey">⬅ Dashboard</a>
    <a href="addstudent.php" class="btn btn-blue">+ Add Student</a>
  </div>
</div>

<div class="search-container">
  <form method="GET" class="search-form">
    <div class="search-input-wrapper">
      <input
        type="text"
        id="searchInput"
        name="search"
        placeholder="Search by ID, Name, or Class"
        value="<?php echo htmlspecialchars($search); ?>"
        oninput="toggleClear()"
      >
      <span class="clear-btn" id="clearBtn" onclick="clearSearch()">✖</span>
    </div>

    <button type="submit" class="btn-search">🔍 Search</button>

    <a href="export_students.php?search=<?php echo urlencode($search); ?>" class="btn-export">
      ⬇ Export Excel
    </a>
  </form>
</div>

<table>
<tr>
  <th>Photo</th>
  <th>ID</th>
  <th>First Name</th>
  <th>Last Name</th>
  <th>Gender</th>
  <th>Address</th>
  <th>Class</th>
  <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
  <td>
    <?php if(!empty($row['photo']) && file_exists("uploads/".$row['photo'])) { ?>
      <img src="uploads/<?= $row['photo'] ?>">
    <?php } else { ?>
      <img src="images/default.png">
    <?php } ?>
  </td>

  <td><?= $row['student_id'] ?></td>
  <td><?= htmlspecialchars($row['first_name']) ?></td>
  <td><?= htmlspecialchars($row['last_name']) ?></td>
  <td><?= htmlspecialchars($row['gender']) ?></td>
  <td><?= htmlspecialchars($row['address']) ?></td>
  <td><?= htmlspecialchars($row['class']) ?></td>

  <td>
    <a class="btn btn-green" href="editstudent.php?id=<?= $row['student_id'] ?>">Edit</a>
    <a class="btn btn-red" onclick="return confirm('Delete this student?')" 
       href="removestudent.php?id=<?= $row['student_id'] ?>">Remove</a>
  </td>
</tr>
<?php } ?>
</table>

<div class="pagination">
<?php for($i=1;$i<=$totalPages;$i++){ ?>
<a class="<?= $page==$i?'active':'' ?>" href="?page=<?= $i ?>&search=<?= $search ?>">
  <?= $i ?>
</a>
<?php } ?>
</div>

<script>
function toggleClear(){
  const i=document.getElementById("searchInput");
  document.getElementById("clearBtn").style.display=i.value?"block":"none";
}
function clearSearch(){
  window.location.href="studentlist.php";
}
window.onload=toggleClear;
</script>

</body>
</html>
