// Init:
// sessionID
// longitude
// latitude

// On click:
// send: array of sessionID
//

// Initialize map (no key needed)
const map = L.map("map")
const markers = L.markerClusterGroup();

async function setMapToApproximateLocation() {
    try {
        // Use a free, no-auth IP geolocation API
        const res = await fetch("https://ipapi.co/json/");
        const data = await res.json();

        if (data && data.latitude && data.longitude) {
            const { latitude, longitude, city, country_name } = data;
            map.setView([latitude, longitude], 10); // zoom level 10 = city scale

            L.circleMarker([latitude, longitude], {
                radius: 8,
                fillColor: "blue",
                fillOpacity: 0.7,
                color: "white",
                weight: 2,
            }).addTo(map);
        } else {
            console.warn("Could not determine location, defaulting to center of US");
            map.setView([39.5, -98.35], 4);
        }
    } catch (err) {
        console.error("Failed to fetch IP location:", err);
        map.setView([39.5, -98.35], 4);
    }
}

function registerMapSession(id, lat, lng) {
    const marker = L.circleMarker([lat, lng], {
        radius: 6,
        color: "red",
        fillColor: "red",
        fillOpacity: 0.8,
    });

    marker.customData = {id};

    marker.on("click", () => { // Only if clicked when not in group
        console.log("Clicked marker ID #" + marker.customData[0]);
        //TODO
    });

    markers.addLayer(marker);
}

async function registerMapSessions() {
    var req = new XMLHttpRequest();
    req.responseType = 'json';
    req.open('GET', "api/session.php?loc", true);
    req.onload  = function() {
        var jsonResponse = req.response;

        jsonResponse.forEach((data, _) => {
        registerMapSession(data["sessionID"], data["latitude"], data["longitude"]);

        });
    };
    req.send(null);
}

// Add OpenStreetMap tiles
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
}).addTo(map);


// Add clustering group to map
map.addLayer(markers);

// Listen for cluster clicks
markers.on("clusterclick", (event) => {
    const clusterMarkers = event.layer.getAllChildMarkers();
    const clusterData = clusterMarkers.map((m) => m.customData);
    console.log("Cluster clicked IDs", clusterData);
    //TODO
});

setMapToApproximateLocation();
registerMapSessions();

