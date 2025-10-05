<?PHP

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
</head>
<body>
    <?php require("static/header.php");?>

    <main>
        <div id="map"></div>

        <div id="sidebar">
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
                <p>Username</p>
                <p>Subject</p>
                <p>Time</p>
                <p><span class="trafficRed"></span> - Locked In</p>
                <p>Description</p>
                <button type="button" id="sessionJoin">Join</button>
                <button type="button" id="sessionLeave">Leave</button>
            </div>
        </div>

        <div id="sidebarCreateSession">
            <button type="button" id="sessionCreate">Create Session</button>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="js/map.js"></script>
</body>
</html>
