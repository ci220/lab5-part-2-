<?php
// Start session
session_start();

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

// Initialize error message
$error_message = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Query to check credentials
    $sql = "SELECT password FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['matric'] = $matric; // Store matric in session
            header("Location: userlist.php"); // Redirect to a welcome page
            exit();
        } else {
            $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
        }
    } else {
        $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 2px solid #444;
            background: #333;
            color: #fff;
            font-size: 1rem;
        }

        input:focus {
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

        p {
            text-align: center;
            color: red;
            font-weight: 600;
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

    <h2>Login Form</h2>

    <form action="" method="POST">
        <label for="matric">Matric Number:</label>
        <input type="text" id="matric" name="matric" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <?php
    if (!empty($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>

    <br>
    <a href="register.php">Go to Register</a>

</body>
</html>
