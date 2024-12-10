<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "lab_5b"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password storage
    $role = $_POST['role'];

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $matric, $name, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Registration successful!</p>"; 
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&family=Cabin:wght@400&display=swap');

        body {
            background-color: #111;
            color: #fff;
            font-family: 'Cabin', sans-serif;
            text-align: center;
            padding: 50px 0;
        }

        h2 {
            font-family: 'Ubuntu', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #00fffc;
        }

        form {
            background: #222;
            border-radius: 8px;
            padding: 40px;
            width: 400px;
            margin: 0 auto;
            box-shadow: 0 0 15px rgba(0, 255, 253, 0.3);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.2rem;
            font-weight: 700;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 2px solid #444;
            background: #333;
            color: #fff;
            font-size: 1rem;
        }

        input:focus, select:focus {
            border-color: #00fffc;
            outline: none;
            background: #444;
            box-shadow: 0 0 10px rgba(0, 255, 253, 0.5);
        }

        button {
            background-color: #00fffc;
            color: #111;
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00a4a2;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #00fffc;
            text-decoration: none;
            font-size: 1rem;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Animation for the form */
        @keyframes form-entry {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        form {
            animation: form-entry 1s ease-out;
        }
    </style>
</head>
<body>

    <h2>Registration Form</h2>

    <form action="" method="POST">
        <label for="matric">Matric Number:</label>
        <input type="text" id="matric" name="matric" required><br>

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="Student">Student</option>
            <option value="Teacher">Teacher</option>
        </select><br>

        <button type="submit">Register</button>
    </form>

    <br>
    <a href="login.php">Go to Login</a>

</body>
</html>
