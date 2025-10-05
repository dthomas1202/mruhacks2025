<?php
require("../include/linkDB.php");
$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);



$userList = [];
if (isset($_GET["id"])) {
    $userList = $db->getUsers($_GET["id"]);
}


echo json_encode($userList);
?>