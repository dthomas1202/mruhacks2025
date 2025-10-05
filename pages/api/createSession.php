<?php
session_start();
require_once "../include/linkDB.php";
if (!isset($_SESSION["userName"])) {
    header("Location: login.php");
    exit;
}

$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);

function pis($name) {
    return isset($_POST[$name]);
}
function gpd($name) {
    return $_POST[$name];
}

if (pis("long") && pis("lat") && pis("subject") && pis("traffic") && pis("desc") && pis("sTime") && pis("eTime")) {
    $ret = $db->createSession(gpd("long"), gpd("lat"), gpd("subject"), gpd("traffic"), gpd("desc"), $_SESSION["userID"], gpd("sTime"), gpd("eTime"));

    if (isset($ret["id"]))
    $db->joinSession($_SESSION["userID"], $ret["id"]);
}


header("Location: ../map.php");
die();
?>