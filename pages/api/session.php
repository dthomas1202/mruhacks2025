<?php
require("../include/linkDB.php");
$dbPath = '../../database/link.db';
$db = new linkDB($dbPath);

$sessionList = $db->getSession();

$newSessionList = [];
if (isset($_GET["loc"])) {
    foreach ($sessionList as $s) {
        array_push($newSessionList, [
            "sessionID" => $s["sessionID"],
            "longitude" => $s["longitude"],
            "latitude" => $s["latitude"]
        ]);
    }

} else {
    $newSessionList = $sessionList;
}


echo json_encode($newSessionList);
?>