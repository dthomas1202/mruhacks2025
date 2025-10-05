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
        color: "green",
        fillColor: "green",
        fillOpacity: 0.8,
    });

    marker.customData = [id];

    marker.on("click", () => { // Only if clicked when not in group
        console.log("Clicked marker ID #" + marker.customData[0]);
        markerClick(marker.customData)
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

function setDisplaySidebar(init, cards, info) {
    document.getElementById("sidebarInit").style.display = init ? "block" : "none"; 
    document.getElementById("sidebarCards").style.display = cards ? "block" : "none"; 
    document.getElementById("sidebarInfo").style.display = info ? "block" : "none"; 
}

function tConvert(time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time.pop();
    time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  return time.join (''); // return adjusted time or original string
}

function updateSelectedMarkers(activeIds) {
    markers.getLayers().forEach((marker, _) => {
        if (activeIds.includes(marker.customData[0])) {
            marker.setStyle({color: "red", fillColor: 'red'}); // Selected
        } else {
            marker.setStyle({color: "green", fillColor: 'green'});
        }
        
    });
}

function queryCardsInfo(id) {
    let cardDiv = document.createElement("div");
    cardDiv.className = "sessionCard";
    cardDiv.setAttribute("sessionID", id);

    var req = new XMLHttpRequest();
    req.responseType = 'json';
    req.open('GET', "api/session.php?id=" + id, true);
    req.onload = function() {
        var jsonResponse = req.response;
        subjectTxt = jsonResponse["subject"]
        trafficTxt = jsonResponse["traffic"]
        startTimeTxt = tConvert(jsonResponse["startTime"])
        endTimeTxt = tConvert(jsonResponse["endTime"])
        primaryUser = jsonResponse["primaryUser"]

        trafficDiv = document.createElement("div");
        trafficDiv.className = "cardTraffic";
        trafficDiv.classList.add("cardTraffic");

        if (trafficTxt == 0) {
            trafficDiv.classList.add("trafficRed");
        } else if (trafficTxt == 1) {
            trafficDiv.classList.add("trafficYellow");
        } else {
            trafficDiv.classList.add("trafficGreen");
        }

        cardDiv.appendChild(trafficDiv);

        subject = document.createElement("p");
        subject.className = "sessionSubject";
        subject.innerHTML = subjectTxt
        cardDiv.appendChild(subject);

        var req2 = new XMLHttpRequest();
        req2.responseType = 'json';
        req2.open('GET', "api/users.php?id=" + primaryUser, true);
        req2.onload = function() {
            var jsonResponse = req2.response;

            user = document.createElement("p");
            user.className = "sessionUser";
            user.innerHTML = jsonResponse["userName"]
            cardDiv.appendChild(user);
        }
        req2.send(null);
        
        time = document.createElement("p");
        time.className = "sessionTime";
        time.innerHTML = startTimeTxt + " to<br>" + endTimeTxt
        cardDiv.appendChild(time);
    };
    req.send(null);

    document.getElementById("sidebarCards").appendChild(cardDiv)
}

function clusterClick(data) {
    // data: array of [id]
    setDisplaySidebar(false, true, false);
    document.getElementById("sidebarCards").innerHTML = "";

    activeIds = [];
    data.forEach((customData, _) => {
        queryCardsInfo(customData[0]);
        activeIds.push(customData[0])
    });

    updateSelectedMarkers(activeIds)
}

function markerClick(data) {
    // data: [id]
    setDisplaySidebar(false, false, true);
    document.getElementById("sidebarCards").innerHTML = "";

    updateSelectedMarkers([data[0]])
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
    clusterClick(clusterData);
});

setMapToApproximateLocation();
registerMapSessions();

