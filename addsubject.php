<!DOCTYPE html>
<html lang="en">
<?php include "connection.php";?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #1E90FF;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input[type='text'],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .form-group input[type='submit'] {
            background-color: #1E90FF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .form-group input[type='submit']:hover {
            background-color: #0e74b8;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Subject Form</h2>
        <form method="POST" action="">
            <div class="form-group">

                <label for="subject_code">Subject Code:</label>
                <input type="text" id="subject_code" name="subject_code"
                 placeholder="Enter Your Subject Code" required>
            </div>

            <div class="form-group">
                <label for="subject_name">Subject Name:</label>
               <input type="text" id="subject_name" name="subject_name" 
               placeholder="Enter subject name">
            </div>
            

            
            <div class="form-group">
                <label for="faculty_name">Faculty Name:</label>
               <select>
                <?php
                $query = "SELECT * FROM facreg";
                $result = $con->query($query);

                while($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['username']}</option>";
                }
                echo "</select>";
                echo "<input type='text' name='course' value='{$row['class']}' readonly>"
                ?>

               

            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
                <input type="submit" value="View" formaction="fbcalist.php">
            </div>
        </form>
    </div>
</body>

</html>
