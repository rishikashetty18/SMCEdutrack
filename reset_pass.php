<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $securityQuestion = $_POST["security-question"];
    $answer = $_POST["answer"];
    $newPassword = $_POST["new-password"];

    // Here you can implement your logic to validate and process the password reset request
    // For demonstration purposes, I'll just display the submitted data
    echo "Email: " . $email . "<br>";
    echo "Security Question: " . $securityQuestion . "<br>";
    echo "Answer: " . $answer . "<br>";
    echo "New Password: " . $newPassword . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Form</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="security-question">Security Question:</label>
            <select id="security-question" name="security-question" required>
                <option value="Select">Select</option>
                <option value="PetName">What is your pet's name?</option>
                <option value="MotherMaidenName">What is your mother's maiden name?</option>
                <option value="BirthCity">In which city were you born?</option>
            </select>

            <label for="answer">Answer:</label>
            <input type="text" id="answer" name="answer" required>

            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
