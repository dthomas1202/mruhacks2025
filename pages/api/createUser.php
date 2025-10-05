<?php
require_once "../include/linkDB.php";
$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);

function pis($name) {
    return isset($_POST[$name]);
}
function gpd($name) {
    return $_POST[$name];
}

if (pis("name") && pis("email") && pis("password") && pis("subjects") && pis("skills") && pis("traffic") && pis("time")) {
    $db->createUser(gpd("name"), gpd("email"), gpd("password"), gpd("subjects"), gpd("skills"), gpd("traffic"), gpd("time"));

}


header("Location: ../map.php");
die();
?>