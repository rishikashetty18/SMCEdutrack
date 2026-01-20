<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Subject</title>

<style>
body{
  font-family: "Segoe UI", Arial, sans-serif;
  background:#f4f7fc;
  margin:0;
}

/* ===== HEADER ===== */
.header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:24px;
}

.header h2{
  margin:0;
  font-size:22px;
  font-weight:600;
}

.btn{
  padding:10px 18px;
  border-radius:8px;
  color:#fff;
  text-decoration:none;
  font-size:14px;
  font-weight:500;
}

.btn-grey{background:#6b7280}
.btn-blue{background:#4070f4}

/* ===== PAGE WRAPPER ===== */
.page-wrapper{
  display:flex;
  justify-content:center;
  padding:20px;
}

/* ===== CARD ===== */
.card{
  width:100%;
  max-width:620px;
  background:#fff;
  padding:30px 34px;
  border-radius:14px;
  box-shadow:0 12px 28px rgba(0,0,0,0.08);
}

.card h3{
  margin:0;
  text-align:center;
  color:#2563eb;
  font-size:20px;
  font-weight:600;
}

.card p{
  text-align:center;
  color:#6b7280;
  margin:8px 0 26px;
  font-size:14px;
}

/* ===== FORM ===== */
.form-group{
  margin-bottom:18px;
}

label{
  display:block;
  margin-bottom:6px;
  font-size:14px;
  font-weight:600;
}

input, select{
  width:100%;
  height:44px;
  padding:10px 14px;
  border:1px solid #cbd5e1;
  border-radius:8px;
  font-size:14px;
}

input:focus, select:focus{
  outline:none;
  border-color:#2563eb;
  box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

input[readonly]{
  background:#f9fafb;
}

/* ===== BUTTON GROUP ===== */
.btn-group{
  display:flex;
  gap:14px;
  margin-top:28px;
}

.btn-submit{
  flex:1;
  background:#2563eb;
  border:none;
  color:#fff;
  padding:12px;
  border-radius:10px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.btn-submit:hover{background:#1d4ed8}

.btn-view{
  flex:1;
  background:#16a34a;
  border:none;
  color:#fff;
  padding:12px;
  border-radius:10px;
  cursor:pointer;
  font-size:15px;
  font-weight:600;
}

.btn-view:hover{background:#15803d}
</style>
</head>

<body>

<!-- ===== HEADER ===== -->
<div class="header">
  <h2>📘 Subject Assignment</h2>
  <a href="admin.php" class="btn btn-grey">⬅ Dashboard</a>
</div>

<!-- ===== CARD ===== -->
<div class="page-wrapper">
  <div class="card">
    <h3>Assign Subject to Faculty</h3>
    
    <form method="POST" action="sub.php">

      <div class="form-group">
        <label>Course</label>
        <select id="course" name="course" required>
          <option value="">Select Course</option>
          <option value="bca">BCA</option>
          <option value="ba">BA</option>
          <option value="bcom">BCOM</option>
        </select>
      </div>

      <div class="form-group">
        <label>Year of Course</label>
        <select id="year_of_course" name="year_of_course" required>
          <option value="">Select Year</option>
          <option>First Year</option>
          <option>Second Year</option>
          <option>Third Year</option>
        </select>
      </div>

      <div class="form-group">
        <label>Semester</label>
        <select id="semester" name="semester" required>
          <option value="">Select Semester</option>
        </select>
      </div>

      <div class="form-group">
        <label>Subject Code</label>
        <select id="subject_code" name="subject_code" required>
          <option value="">Select Subject Code</option>
        </select>
      </div>

      <div class="form-group">
        <label>Subject Name</label>
        <select id="subject_name" name="subject_name" required>
          <option value="">Select Subject Name</option>
        </select>
      </div>

      <div class="form-group">
        <label>Faculty ID</label>
        <input type="number" id="faculty_id" name="f_id"
               placeholder="Enter Faculty ID"
               required onblur="fetchFacultyName()">
      </div>

      <div class="form-group">
        <label>Faculty Name</label>
        <input type="text" id="faculty_name"
               name="faculty_name"
               readonly
               placeholder="Auto-filled">
      </div>

      <div class="btn-group">
        <button type="submit" class="btn-submit">✔ Assign Subject</button>
        <button type="submit" formaction="subview.php" class="btn-view">
          👁 View Assignments
        </button>
      </div>

    </form>
  </div>
</div>




<script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearSemesters = {
                "First Year": ["1", "2"],
                "Second Year": ["3", "4"],
                "Third Year": ["5", "6"]
            };

            const subjectCodes = {
                "bca": {
                    "First Year": {
                        "1": ["BCA101", "BCA102","BCA103","BCA104","BCA105","BCA106"],
                        "2": ["BCA107", "BCA108","BCA109","BCA110","BCA111","BCA112"]
                    },
                    "Second Year": {
                        "3": ["BCA113", "BCA114","BCA115", "BCA116","BCA117", "BCA118"],
                        "4": ["BCA119", "BCA120","BCA121", "BCA122","BCA123", "BCA124"]
                    },
                    "Third Year": {
                        "5": ["BCA125", "BCA126","BCA127", "BCA128","BCA129", "BCA130"],
                        "6": ["BCA131", "BCA132","BCA133", "BCA134","BCA135", "BCA136"]
                    }
                },
                "bcom": {
                    "First Year": {
                        "1": ["BCOM101", "BCOM102","BCOM103","BCOM104","BCOM105","BCOM106"],
                        "2": ["BCOM107", "BCOM108","BCOM109","BCOM110","BCOM111","BCOM112"]
                    },
                    "Second Year": {
                        "3": ["BCOM113", "BCOM114","BCOM115", "BCOM116","BCOM117", "BCOM118"],
                        "4": ["BCOM119", "BCOM120","BCOM121", "BCOM122","BCOM123", "BCOM124"]
                    },
                    "Third Year": {
                        "5": ["BCOM125", "BCOM126","BCOM127", "BCOM128","BCOM129", "BCOM130"],
                        "6": ["BCOM131", "BCOM132","BCOM133", "BCOM134","BCOM135", "BCOM136"]
                    }
                },
                "ba": {
                    "First Year": {
                        "1": ["BA101", "BA102","BA103","BA104","BA105","BA106"],
                        "2": ["BA107", "BA108","BA109","BA110","BA111","BA112"]
                    },
                    "Second Year": {
                        "3": ["BA113", "BA114","BA115", "BA116","BA117", "BA118"],
                        "4": ["BA119", "BA120","BA121", "BA122","BA123", "BA124"]
                    },
                    "Third Year": {
                        "5": ["BA125", "BA126","BA127", "BA128","BA129", "BA130"],
                        "6": ["BA131", "BA132","BA133", "BA134","BA135", "BA136"]
                    }
                }
            };

            const subjectNames = {
                "BCA101": "C Programming",
                "BCA102": "Kannada",
                "BCA103": "English",
                "BCA104": "Digital Fluency",
                "BCA105": "Maths",
                "BCA106": "Fundamentals of Computer",

                "BCA107": "Data Structure",
                "BCA108": "Java",
                "BCA109": "Kannada",
                "BCA110": "English",
                "BCA111": "EVS",
                "BCA112": "Mathemetics",

                "BCA113": "C#",
                "BCA114": "DBMS",
                "BCA115": "Operating System",
                "BCA116": "Kannada",
                "BCA117": "English",
                "BCA118": "Financial Environment",

                "BCA119": "CMA",
                "BCA120": "Python",
                "BCA121": "Constitution",
                "BCA122": "Kannada",
                "BCA123": "English",
                "BCA124": "Open source Tool",

                "BCA125": "R programming",
                "BCA126": "ADA",
                "BCA127": "Employability Skills",
                "BCA128": "Software Engineering",
                "BCA129": "Cloud Computing",
                "BCA130": "Digital Marketing",
                
                "BCA131": "Advanced Java",
                "BCA132": "PHP and MySQL",
                "BCA133": "Artificial Intelligence",
                "BCA134": "MAD",
                "BCA135": "WCMS",
                "BCA136": "PHP lab",

                "BCOM101": "Office Automation",
                "BCOM102": "Financial Accounting",
                "BCOM103": "English",
                "BCOM104": "Kannada",
                "BCOM105": "Digital Fluency",
                "BCOM106": "Principles of Marketing",
                "BCOM107": "Web Designing",
                "BCOM108": "Adu FA",
                "BCOM109": "Kannada",
                "BCOM110": "English",
                "BCOM111": "Corporate Admin",
                "BCOM112": "Law and Prove of Banking",
                "BCOM113": "Program in C",
                "BCOM114": "Corporate accounting",
                "BCOM115": "Business stat",
                "BCOM116": "Kannada",
                "BCOM117": "English",
                "BCOM118": "financial education and investment",
                "BCOM119": "Cost",
                "BCOM120": "Advanced corporate accounts",
                "BCOM121": "Business Reputory",
                "BCOM122": "Kannada",
                "BCOM123": "English",
                "BCOM124": "Constitution",
                "BCOM125": "Financial Institution and Markets",
                "BCOM126": "Retail Management",
                "BCOM127": "Financial Management",
                "BCOM128": "income Tax",
                "BCOM129": "GST",
                "BCOM130": "Employability Skills",

                "BCOM131": "Investment management",
                "BCOM132": "Advanced Financial management",
                "BCOM133": "E- commerce",
                "BCOM134": "Management accounting",
                "BCOM135": "customer relationship management",
                "BCOM136": "Income Tax Law ",

                "BA101": "Basic Economics-1",
                "BA102": "Kannada",
                "BA103": "English",
                "BA104": "Contemptory Indian Economy",
                "BA105": "Cultural heritage of India",
                "BA106": "Political history of karnataka-1",

                "BA107": "Basic Economics-2",
                "BA108": "Karnata Economy",
                "BA109": "Kannada",
                "BA110": "English",
                "BA111": "Political history of karnataka-2",
                "BA112": "EVS",

                "BA113": "Micro Economics",
                "BA114": "Mathemetics for Economics",
                "BA115": "Political history of India",
                "BA116": "Kannada",
                "BA117": "English",
                "BA118": "History of coastal Karnataka and kodagu",

                "BA119": "MacroEconoics",
                "BA120": "Statistics for Economics",
                "BA121": "Constitution",
                "BA122": "Kannada",
                "BA123": "English",
                "BA124": "History of madieval india",

                "BA125": "Public Economics",
                "BA126": "Development Economics",
                "BA127": "Indian Banking and Finance",
                "BA128": "History of western Civilization",
                "BA129": "History of eurpean",
                "BA130": "Employability skills",
                
                "BA131": "History of freedom movement and unification of karnataka",
                "BA132": "Environmental Economics",
                "BA133": "History of India",
                "BA134": "Indian public Finance",
                "BA135": "International Economics",
                "BA136": "Process of Urbanisation in India",


                // Add other subject code to name mappings here
            };
           

            

            const courseSelect = document.getElementById("course");
            const yearSelect = document.getElementById("year_of_course");
            const semesterSelect = document.getElementById("semester");
            const subjectCodeSelect = document.getElementById("subject_code");
            const subjectNameSelect = document.getElementById("subject_name");

            yearSelect.addEventListener("change", updateSemesters);
            courseSelect.addEventListener("change", updateSemesters);
            semesterSelect.addEventListener("change", updateSubjectCodes);
            subjectCodeSelect.addEventListener("change", updateSubjectNames);

            function updateSemesters() {
                const selectedYear = yearSelect.value;
                const semesters = yearSemesters[selectedYear] || [];
                
                // Clear current semester options
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                
                // Populate new semester options
                semesters.forEach(sem => {
                    const option = document.createElement("option");
                    option.value = sem;
                    option.textContent = sem;
                    semesterSelect.appendChild(option);
                });

                // Clear subject codes and subject names when year or course changes
                subjectCodeSelect.innerHTML = '<option value="">Select Subject Code</option>';
                subjectNameSelect.innerHTML = '<option value="">Select Subject Name</option>';
            }

            function updateSubjectCodes() {
                const selectedCourse = courseSelect.value;
                const selectedYear = yearSelect.value;
                const selectedSemester = semesterSelect.value;

                const subjectCodesForSelection = (subjectCodes[selectedCourse] &&
                                                  subjectCodes[selectedCourse][selectedYear] &&
                                                  subjectCodes[selectedCourse][selectedYear][selectedSemester]) || [];
                
                // Clear current subject code options
                subjectCodeSelect.innerHTML = '<option value="">Select Subject Code</option>';
                
                // Populate new subject code options
                subjectCodesForSelection.forEach(code => {
                    const option = document.createElement("option");
                    option.value = code;
                    option.textContent = code;
                    subjectCodeSelect.appendChild(option);
                });

                // Clear subject names when subject codes change
                subjectNameSelect.innerHTML = '<option value="">Select Subject Name</option>';
            }

            function updateSubjectNames() {
                const selectedSubjectCode = subjectCodeSelect.value;
                const subjectName = subjectNames[selectedSubjectCode] || '';
                const ssubjectName = subjectNames[selectedSubjectCode] || '';

                // Clear current subject name options
                subjectNameSelect.innerHTML = '<option value="">Select Subject Name</option>';
                

                // Populate new subject name option
                if (subjectName) {
                    const option = document.createElement("option");
                    option.value = subjectName;
                    option.textContent = subjectName;
                    option.selected = true; // Automatically select the option
                    subjectNameSelect.appendChild(option);
                }
               
            }
        });
    </script>

    <script>
function fetchFacultyName() {
    const fid = document.getElementById("faculty_id").value;

    if (!fid) return;

    fetch("get_faculty_name.php?fid=" + fid)
        .then(res => res.text())
        .then(name => {
            document.getElementById("faculty_name").value = name || "Not Found";
        });
}
</script>




</html>