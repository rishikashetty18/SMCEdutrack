<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) die("Connection failed");

/* ===== SEARCH ===== */
$search = $_GET['search'] ?? "";

/* ===== PAGINATION ===== */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

/* ===== DATA ===== */
$sql = "SELECT * FROM facreg 
        WHERE 
          id LIKE '%$search%'
          OR full_name LIKE '%$search%'
          OR class LIKE '%$search%'
        LIMIT $start, $limit";

$result = $conn->query($sql);

$countRes = $conn->query("
  SELECT COUNT(*) AS total 
  FROM facreg 
  WHERE 
    id LIKE '%$search%'
    OR full_name LIKE '%$search%'
    OR class LIKE '%$search%'
");

$countRow = $countRes->fetch_assoc();
$count = $countRow['total'];


$totalPages = ceil($count / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Faculty Directory</title>

<style>
body{font-family:Arial;background:#f4f7fc;margin:0}
.header{display:flex;justify-content:space-between;align-items:center;margin:20px}
.btn{padding:10px 16px;border-radius:6px;color:#fff;text-decoration:none}
.btn-blue{background:#4070f4}
.btn-grey{background:#6b7280}
.btn-green{background:#22c55e}
.btn-red{background:#ef4444}
table{width:100%;border-collapse:collapse;background:#fff}
th{background:#1E90FF;color:#fff;padding:12px}
td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
td:nth-child(3){text-align:left;padding-left:20px}
img{width:42px;height:42px;border-radius:50%;object-fit:cover}
.pagination{text-align:center;margin:20px}
.pagination a{padding:6px 10px;margin:2px;background:#ddd;text-decoration:none}
.pagination a.active{background:#4070f4;color:#fff}
/* ===== SEARCH BAR (IMPROVED) ===== */
.search-container {
  margin: 10px 20px 25px 20px;
}

.search-form {
  display: flex;
  align-items: center;
  gap: 12px;
}

.search-form input[type="text"] {
  width: 320px;
  height: 42px;
  padding: 0 14px;
  font-size: 15px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  outline: none;
  transition: border 0.2s, box-shadow 0.2s;
}

.search-form input[type="text"]:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}

/* Search Button */
.btn-search {
  height: 42px;
  padding: 0 18px;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  cursor: pointer;
}

.btn-search:hover {
  background: #1d4ed8;
}

/* Export Button */
.btn-export {
  height: 42px;
  padding: 0 18px;
  background: #16a34a;
  color: #fff;
  text-decoration: none;
  border-radius: 8px;
  font-size: 15px;
  display: flex;
  align-items: center;
}

.btn-export:hover {
  background: #15803d;
}

/* Search input with clear button */
.search-input-wrapper {
  position: relative;
}

.search-input-wrapper input {
  padding-right: 38px; /* space for ❌ */
}

/* Clear (X) button */
.clear-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 14px;
  color: #64748b;
  cursor: pointer;
  display: none;
}

.clear-btn:hover {
  color: #ef4444;
}

</style>
</head>

<body>

<div class="header">
  <h2>👩‍🏫 Faculty Directory</h2>
  <div>
    <a href="admin.php" class="btn btn-grey">⬅ Dashboard</a>
    <a href="addfaculty.php" class="btn btn-blue">+ Add Faculty</a>
  </div>
</div>

<div class="search-container">
  <form method="GET" class="search-form">
    <div class="search-input-wrapper">
  <input
    type="text"
    id="searchInput"
    name="search"
    placeholder="Search by ID, Name, or Course"
    value="<?php echo htmlspecialchars($search); ?>"
    oninput="toggleClear()"
  >
  <span class="clear-btn" id="clearBtn" onclick="clearSearch()">✖</span>
</div>

    <button type="submit" class="btn-search">
      🔍 Search
    </button>

    <a 
      href="export_faculty.php?search=<?php echo urlencode($search); ?>"
      class="btn-export">
      ⬇ Export Excel
    </a>
  </form>
</div>



<table>
<tr>
  <th>Photo</th>
  <th>ID</th>
  <th>Faculty Name</th>
  <th>Course</th>
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
  <td><?= $row['id'] ?></td>
  <td>
<?php
  echo htmlspecialchars(
      !empty($row['full_name']) ? $row['full_name'] : $row['username']
  );
?>
</td>

  <td><?= $row['class'] ?></td>
  <td>
    <a class="btn btn-green" href="editfaculty.php?id=<?= $row['id'] ?>">Edit</a>
    <a class="btn btn-red" onclick="deleteFaculty(<?= $row['id'] ?>)">Remove</a>
  </td>
</tr>
<?php } ?>
</table>

<div class="pagination">
<?php for($i=1;$i<=$totalPages;$i++){ ?>
<a class="<?= $page==$i?'active':'' ?>" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
<?php } ?>
</div>

<script>
function deleteFaculty(id){
  if(!confirm("Do you want to remove this faculty?")) return;
  fetch("delete_faculty.php",{
    method:"POST",
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:"id="+id
  }).then(r=>r.text()).then(res=>{
    if(res==="assigned") alert("Faculty assigned to subjects");
    else if(res==="success") location.reload();
    else alert("Delete failed");
  });
}
</script>
<script>
function toggleClear() {
  const input = document.getElementById("searchInput");
  const clearBtn = document.getElementById("clearBtn");
  clearBtn.style.display = input.value ? "block" : "none";
}

function clearSearch() {
  const input = document.getElementById("searchInput");
  input.value = "";
  window.location.href = "facultylist.php"; // reload full list
}

// Show clear button on page load if search exists
window.onload = toggleClear;
</script>


</body>
</html>
