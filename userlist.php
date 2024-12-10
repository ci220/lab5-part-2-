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

// Handle DELETE operation
if (isset($_GET['delete'])) {
    $matric = $_GET['delete'];
    $sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        $success_message = "User with Matric $matric deleted successfully.";
    }
    $stmt->close();
}

// Handle UPDATE operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $role, $matric);
    if ($stmt->execute()) {
        $success_message = "User with Matric $matric updated successfully.";
    }
    $stmt->close();
}

// Query to fetch matric, name, and role from the users table
$sql = "SELECT matric, name, role AS accessLevel FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #111;
            color: #fff;
            font-family: 'Arial', sans-serif;
            padding: 50px;
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #00fffc;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 15px rgba(0, 255, 253, 0.2);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #444;
        }

        th {
            background-color: #00fffc;
            color: #111;
        }

        tr:nth-child(even) {
            background-color: #222;
        }

        .action-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-btns a {
            color: #00fffc;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #333;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .action-btns a:hover {
            background-color: #00a4a2;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .edit-form {
            text-align: center;
            background-color: #222;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            margin: 30px auto;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }

        .edit-form button {
            width: 100%;
            padding: 10px;
            background-color: #00fffc;
            border: none;
            color: #111;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-form button:hover {
            background-color: #00a4a2;
        }

        .logout-link {
            text-align: center;
            display: block;
            margin-top: 30px;
            color: #00fffc;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <h2>Users List</h2>

    <?php if (isset($success_message)) { ?>
        <p class="message"><?php echo $success_message; ?></p>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Access Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['matric']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['accessLevel']) . "</td>";
                    echo "<td class='action-btns'>
                            <a href='?edit=" . $row['matric'] . "'><i class='fas fa-edit'></i> Edit</a> |
                            <a href='?delete=" . $row['matric'] . "' onclick=\"return confirm('Are you sure you want to delete this user?')\"><i class='fas fa-trash'></i> Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Handle Edit form display
    if (isset($_GET['edit'])) {
        $matric = $_GET['edit'];
        $sql = "SELECT matric, name, role FROM users WHERE matric = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matric);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
    ?>
    <div class="edit-form">
        <h3>Edit User</h3>
        <form action="" method="POST">
            <input type="hidden" name="matric" value="<?php echo htmlspecialchars($row['matric']); ?>">
            <label for="matric">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br>
            <label for="matric">Access Level:</label>
            <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($row['role']); ?>" required><br>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
    <?php
        }
        $stmt->close();
    }
    ?>

    <a href="login.php" class="logout-link">Logout</a>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
