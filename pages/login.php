<?php
session_start();
require_once "./include/linkDB.php";

$message = "";
// -----------LOGOUT LINK-------
// <a class="logout" href="logout.php">Logout</a>
// ------------

// -----THIS IS THE HEADER FOR ALL PROTECTED PAGES-----
// session_start();
// require_once "linkDB.php";
// if (!isset($_SESSION["userName"])) {
//     header("Location: login.php");
//     exit;
// }


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        // connect to the DB
        $dbPath = __DIR__ . '../database/link.db';
        $db = new linkDB($dbPath);
        // verify credentials
        if ($db->checkPwd($username, $password)) {
            $userid = $db->getUserID($username);
            $user = $db->getUsers($userid);
            $_SESSION = $user;
            $_SESSION["userName"] = $username;
            // Where we send people successful login
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }

    } catch (PDOException $e) {
        $message = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        input {
            width: 100%;
            padding: .5rem;
            margin: .5rem 0;
        }
        button {
            background: #007BFF;
            border: none;
            color: white;
            padding: .5rem;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>

