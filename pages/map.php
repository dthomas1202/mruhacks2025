<?PHP
session_start();
require_once "./include/linkDB.php";
$dbPath = '../database/link.db';
if (!isset($_SESSION["userName"])) {
    header("Location: login.php");
    exit;
}

$db = new linkDB($dbPath);

$currentSession = $db->getUsers($_SESSION["userID"])["currentSession"];

if (!isset($currentSession)) {
    $currentSession = "null";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("static/head.php");?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css"/>

    <link rel="stylesheet" href="css/map.css">
    <link rel="stylesheet" href="css/common.css">
    <script>
        const USER_ID = <?=$_SESSION["userID"]?>;
        const CURRENT_SESSION = <?=$currentSession?>;
        console.log("USER_ID: " + USER_ID)
        console.log("CURRENT_SESSION: " + CURRENT_SESSION)
    </script>
</head>
<body>
    <?php require("static/header.php");?>

    <main>
        <div id="map"></div>

        <div id="sidebar" onmouseleave="unzoomMarker()">
            <div id="sidebarInit">
                <h3>Please Select a Session in the Map</h3>
            </div>
            <div id="sidebarCards">
                <!-- <div class="sessionCard">
                    <div class="trafficRed cardTraffic"></div>
                    <p class="sessionSubject">Subject</p>
                    <p class="sessionTime">10:00am to<br> 3:00pm</p>
                    <p class="sessionUser">Username</p>
                </div>
                <div class="sessionCard">
                    <div class="trafficYellow cardTraffic"></div>
                    <p class="sessionSubject">Subject</p>
                    <p class="sessionTime">10:00am to<br> 3:00pm</p>
                    <p class="sessionUser">Username</p>
                </div>
                <div class="sessionCard">
                    <div class="trafficGreen cardTraffic"></div>
                    <p class="sessionSubject">Subject</p>
                    <p class="sessionTime">10:00am to<br> 3:00pm</p>
                    <p class="sessionUser">Username</p>
                </div> -->
            </div>
            <div id="sidebarInfo">
                <p id="infoUser">Username</p>
                <p id="infoSubject">Subject</p>
                <p id="infoTime">Time</p>
                <p id="infoTraffic"><span class="trafficRed"></span> - Locked In</p>
                <p id="infoDesc">Description</p>
                <form action="api/modSession.php" method="post">
                    <input type="hidden" id="sessionModEdit" name="edit" value="">
                    <input type="hidden" id="sessionModId" name="id" value="">
                    <input type="submit" id="sessionMod" value="">
                </form>
            </div>
        </div>

        <div id="sidebarCreateSession">
            <a href="createSession.php" class="button">Create Session</a>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="js/map.js"></script>
</body>
</html>
