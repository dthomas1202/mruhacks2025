<?php
// This is some sample form for login
/* <form action="" method="post">
    <input type="text" name="username" placeholder="Enter username" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Submit">
</form>
 */
require_once __DIR__ . '/linkDB.php';
session_start();
if (! empty($_POST) ) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $dbPath = __DIR__ . '/link.db';
        $db = new linkDB($dbPath);
        $action = $_POST['action'] ?? null;
        $user = $db->getUserID($_POST['username']);
        if (password_verify($_POST['password',]))
    }

}
?>