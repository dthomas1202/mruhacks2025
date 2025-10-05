<?php
require("../include/linkDB.php");
$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);



$newSessionList = [];
if (isset($_GET["loc"])) {
    $sessionList = $db->getSession();

    foreach ($sessionList as $s) {
        array_push($newSessionList, [
            "sessionID" => $s["sessionID"],
            "longitude" => $s["longitude"],
            "latitude" => $s["latitude"]
        ]);
    }

} elseif (isset($_GET["id"])) {
    $sessionList = $db->getSession($_GET["id"]);

    $newSessionList = $sessionList;
}


echo json_encode($newSessionList);
?>