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

let previousView = null;

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

function registerMapSession(id, lat, lng) {
    const marker = L.circleMarker([lat, lng], {
        radius: 20,
        color: "cyan",
        fillColor: "black",
        fillOpacity: 0.7,
    });

    marker.customData = [id];

    marker.on("click", () => { // Only if clicked when not in group
        console.log("Clicked marker ID #" + marker.customData[0]);
        markerClick(marker.customData[0])
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
            marker.setStyle({color: "cyan", fillColor: 'black'}); // Selected
        } else {
            marker.setStyle({color: "white", fillColor: 'black'});
        }
        
    });
}

function queryCardsInfo(id) {
    let cardDiv = document.createElement("div");
    cardDiv.className = "sessionCard";
    cardDiv.setAttribute("sessionID", id);
    cardDiv.onmouseover = function(){zoomMarker(id)};
    cardDiv.onclick = function(){markerClick(id)};

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

        if (id == CURRENT_SESSION) {
            
            cardDiv.classList.add("sessionCardActive");
        }

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

function querySessionInfo(id) {
    let sUser = document.getElementById("infoUser")
    let sSubject = document.getElementById("infoSubject")
    let sTime = document.getElementById("infoTime")
    let sTraffic = document.getElementById("infoTraffic")
    let sDesc = document.getElementById("infoDesc")
    let sMod = document.getElementById("sessionMod")
    let sModEdit = document.getElementById("sessionModEdit")
    let sModId = document.getElementById("sessionModId")

    var req = new XMLHttpRequest();
    req.responseType = 'json';
    req.open('GET', "api/session.php?id=" + id, true);
    req.onload = function() {
        var jsonResponse = req.response;
        sSubject.innerHTML = jsonResponse["subject"]
        
        startTimeTxt = tConvert(jsonResponse["startTime"])
        endTimeTxt = tConvert(jsonResponse["endTime"])

        sTime.innerHTML = startTimeTxt + " to " + endTimeTxt

        sDesc.innerHTML = jsonResponse["description"]

        primaryUser = jsonResponse["primaryUser"]

        if (primaryUser == USER_ID) {
            sMod.value = "Destroy"
            sModEdit.value = "destroy"
            sModId.value = id
        } else if (id == CURRENT_SESSION) {
            sMod.value = "Leave"
            sModEdit.value = "leave"
            sModId.value = id
        } else {
            sMod.value = "Join"
            sModEdit.value = "join"
            sModId.value = id
        }

        trafficTxt = jsonResponse["traffic"]
        if (trafficTxt == 0) {
            sTraffic.innerHTML = "<div class='trafficRed'></div><br>LOCKED IN"
        } else if (trafficTxt == 1) {
            sTraffic.innerHTML = "<div class='trafficYellow'></div><br>Chat and Work"
        } else {
            sTraffic.innerHTML = "<div class='trafficGreen'></div><br>Yap Session"
        }

        var req2 = new XMLHttpRequest();
        req2.responseType = 'json';
        req2.open('GET', "api/users.php?id=" + primaryUser, true);
        req2.onload = function() {
            var jsonResponse = req2.response;

            sUser.innerHTML = jsonResponse["userName"]
        }
        req2.send(null);
    };
    req.send(null);
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

function markerClick(id) {
    zoomMarker(id, 17);
    previousView = null;

    querySessionInfo(id)
    setDisplaySidebar(false, false, true);
    document.getElementById("sidebarCards").innerHTML = "";

    updateSelectedMarkers([id])
}

function zoomMarker(id, zoom=16) {
    marker = markers.getLayers().find(e => e.customData[0] == id)
    if (!marker) {
        console.warn("No marker found for id", id);
        return;
    }

    if (!previousView) {
        previousView = {
            center: map.getCenter(),
            zoom: map.getZoom(),
        };
    }

    // Pan and zoom to the marker
    latlng = marker.getLatLng();
    map.setView(latlng, zoom, { animate: true });
}

function unzoomMarker() {
    if (previousView) {
        map.setView(previousView.center, previousView.zoom, { animate: true });
        previousView = null;
    }
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
