<?php
session_start();
require_once "../include/linkDB.php";
if (!isset($_SESSION["userName"])) {
    header("Location: login.php");
    exit;
}

$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);

if (isset($_POST["edit"]) && isset($_POST["id"])) {
    $edit = $_POST["edit"];
    $id = $_POST["id"];

    if ($edit == "destroy") {
        $db->destroySession($id);
    } elseif ($edit == "leave") {
        $db->leaveSession($_SESSION["userID"]);
    } elseif ($edit == "join") {
        $db->joinSession($_SESSION["userID"], $id);
    }
}


header("Location: ../map.php");
die();
?>