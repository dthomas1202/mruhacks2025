<?php
session_start();
require_once "./include/linkDB.php";
$dbPath = '../database/link.db';
if (!isset($_SESSION["userName"])) {
    header("Location: index.php");
    exit;
}
$message = "";
// -----------LOGOUT LINK-------
// <a class="logout" href="logout.php">Logout</a>
// ------------

// -----THIS IS THE HEADER FOR ALL PROTECTED PAGES-----
// session_start();
// require_once "linkDB.php";
// if (!isset($_SESSION["userName"])) {
//     header("Location: index.php");
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

    <link rel="stylesheet" href="css/newuser.css">

        <?php require("static/head.php");?>

    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="css/map.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
        <?php require("static/header.php");?>

    <h1>TeleLink Profile</h1>
    <p>Hello <?=$_SESSION['userName'];?></p>
    <p>
        Your preferred traffic: <?=$_SESSION['preferredTraffic'];?>
    </p>
    <p>
        Your email: <?=$_SESSION['userEmail'];?>
    </p>
    <p> Your subjects: <?=$_SESSION['userSubject'];?> </p>
    <p> Your skills: <?$_SESSION['userSkills'];?></p>
    <p> Your preferred study times: <?=$_SESSION['preferredTimes'];?></p>





</body>
</html>
