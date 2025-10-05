<?php
// This is some sample form for login
/* <form action="" method="post">
    <input type="text" name="username" placeholder="Enter username" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Submit">
</form>
 */
/*
    <?php
    session_start90;
    if (!isset($_SESSION)){
    header(Location: localhost:8080/mruhacks/login.php"); 
    }
    ?>
  */
require_once __DIR__ . '../../../database/linkDB.php';
session_start();
if (! empty($_POST) ) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $dbPath = __DIR__ . '/link.db';
        $db = new linkDB($dbPath);
        $action = $_POST['action'] ?? null;
        $user = $db->getUserID($_POST['username']);
        if (password_verify($_POST['password']) == $user['userPassword']){
            $_SESSION['user_id'] = $user;
        }
    }

}
?>
<html>
    <body>
<form action="" method="post">
    <input type="text" name="username" placeholder="Enter username" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Submit">
</form>
</body>
</html>