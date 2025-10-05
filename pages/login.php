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
        $dbPath = __DIR__ . '../../database/link.db';
        $db = new linkDB($dbPath);
        // verify credentials
        if ($db->checkPwd($username, $password)) {
            $userid = $db->getUserID($username);
            $user = $db->getUsers($userid);
            $_SESSION = $user;
            $_SESSION["userName"] = $username;
            // Where we send people successful login
            header("Location: map.php");
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
        <?php require("static/head.php");?>

    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/signin.css">
</head>
<body>
        <?php require("static/header.php");?>

    <h1>TeleLink</h1>
    <p>
        Log into TeleLink
    </p>
    <form method="POST" action="">
        <label for="username"> Username: </label> <br>
        <input type="text" id="username" name="username" placeholder="Username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required> <br>
        <button type="submit">Login</button>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </form>


</body>
</html>

