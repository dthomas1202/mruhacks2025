<?PHP
session_start();
require_once "./include/linkDB.php";
$dbPath = '../database/link.db';
if (!isset($_SESSION["userName"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php require("static/head.php");?>
<link rel="stylesheet" href="css/createSession.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>
<body>
<?php require("static/header.php");?>
    <main>
        <div class="container">
            <p id="newSessionTitle">Create New Session</p>
            <form action="api/createSession.php" method="POST">
                <div id="uphalf">
                    <div id="studyvisible">
                        <div id="studyDiv">
                            <label for="study">Study topic</label>
                            <input type="text" id="study" placeholder="E.g. Web 2, Database 1" name="subject" required>
                        </div>
                        <!-- <div id="visibleDiv">
                            <label for="visible">Visibility</label>
                            <select id="visible" name="">
                                <option value="public">Public</option>
                                <option value="placeholder">Placeholder</option>
                            </select>
                        </div> -->
                    </div>
                    <div id="timeTraffic">
                        <div id="timeDiv">
                            <div id="startTimeDiv">
                                <label for="startTime">Start time</label>
                                <input type="time" id="startTime" name="sTime" required>
                            </div>
                            <div id="endTimeDiv">
                                <label for="endTime">End time</label>
                                <input type="time" id="endTime" name="eTime" required>
                            </div>
                        </div>

                        <div class="traffic">
                            <label>Traffic Light</label>
                            <div class="lights">
                                <div class="greenLight">
                                    <input type="radio" value="2" name="traffic" required onclick="setFocusDisplay(true, false, false);">
                                    <span class="light" id="green"></span>
                                </div>
                                <div class="yellowLight">
                                    <input type="radio" value="1" id="yellow" name="traffic" required onclick="setFocusDisplay(false, true, false);">
                                    <span class="light"></span>
                                </div>
                                <div class="redLight">
                                    <input type="radio" value="0" id="red" name="traffic" required onclick="setFocusDisplay(false, false, true);">
                                    <span class="light"></span>
                                </div>
                            </div>

                            <p class="status" id="noneSelected">Select level of focus</p>
                            <p class="status" for="green" id="greenStatus">"Yap Session"</p>
                            <p class="status" for="yellow" id="yellowStatus">"Chat and work"</p>
                            <p class="status" for="red" id="redStatus">"I need to LOCK IN"</p>
                        </div>
                    </div>
                </div>

                <div class="location">
                    <label for="map">Location</label>
                    <div id="map"></div>
                </div>
                <div class="description">
                    <label for="textDescription">Description</label>
                    <textarea required type="input" id="textDescription"
                        placeholder="Enter a description/details about the study session" name="desc"></textarea>
                </div>

                <input type="hidden" id="latitude" name="lat" required readonly />
                <input type="hidden" id="longitude" name="long" required readonly />
                <input id="createSession" type="submit" value="Create Session">
            </form>

        </div>


    </main>
    
</body>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
function setFocusDisplay(g, y, r, i=false) {
    document.getElementById("noneSelected").style.display = i ? "inline" : "none"; 
    document.getElementById("greenStatus").style.display = g ? "inline" : "none"; 
    document.getElementById("yellowStatus").style.display = y ? "inline" : "none"; 
    document.getElementById("redStatus").style.display = r ? "inline" : "none"; 
}

setFocusDisplay(false, false, false, true)


const map = L.map("map");

L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
maxZoom: 18,
attribution:
    '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
}).addTo(map);

let selectedMarker = null;

// Handle map click
map.on("click", function (e) {
    const { lat, lng } = e.latlng;

    // If a marker already exists, move it; otherwise create one
    if (selectedMarker) {
        selectedMarker.setLatLng(e.latlng);
    } else {
        selectedMarker = L.marker(e.latlng).addTo(map);
    }

    // Fill form fields
    document.getElementById("latitude").value = lat.toFixed(6);
    document.getElementById("longitude").value = lng.toFixed(6);
});

async function setMapToApproximateLocation() {
    try {
        // Use a free, no-auth IP geolocation API
        const res = await fetch("https://ipapi.co/json/");
        const data = await res.json();

        if (data && data.latitude && data.longitude) {
            const { latitude, longitude, city, country_name } = data;
            map.setView([latitude, longitude], 10); // zoom level 10 = city scale
        } else {
            console.warn("Could not determine location, defaulting to center of US");
            map.setView([39.5, -98.35], 4);
        }
    } catch (err) {
        console.error("Failed to fetch IP location:", err);
        map.setView([39.5, -98.35], 4);
    }
}
setMapToApproximateLocation()
</script>
</html>
