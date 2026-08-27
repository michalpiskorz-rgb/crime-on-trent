const map = L.map('map', {
    zoomControl: true
}).setView([53.0027, -2.1794], 12);


// --------------------------------------------------
// OPENSTREETMAP
// --------------------------------------------------

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }
).addTo(map);


// --------------------------------------------------
// MARKER ICONS
// --------------------------------------------------

const markerIcons = {};

const iconNames = [
    'arson',
    'assault',
    'burglary',
    'disturbing_the_peace',
    'drugs_alcohol_violations',
    'dui',
    'fraud',
    'homicide',
    'motor_vehicle_theft',
    'robbery',
    'sex_crimes',
    'theft',
    'vandalism',
    'road_accident',
    'weapons'
];

iconNames.forEach(name => {
    markerIcons[name] = L.icon({
        iconUrl: `assets/icons/${name}.png`,
        iconSize: [50, 65],
        iconAnchor: [25, 65],
        popupAnchor: [0, -65]
    });
});



// --------------------------------------------------
// TEST CASES
// --------------------------------------------------

const testCases = [
    {
        id: 1,
        title: "Incident on Stafford Street",
        category: "homicide",
        date: "2025-06-14",
        latitude: 53.0027,
        longitude: -2.1794,
        description: "A fictional homicide case created for testing the Crime on Trent map."
    },

    {
        id: 2,
        title: "Burglary at Hanley Shop",
        category: "burglary",
        date: "2025-08-03",
        latitude: 53.0038,
        longitude: -2.1762,
        description: "A fictional burglary reported during the early morning hours."
    },

    {
        id: 3,
        title: "Vehicle Collision",
        category: "road_accident",
        date: "2025-09-21",
        latitude: 52.9985,
        longitude: -2.1851,
        description: "A fictional road traffic collision used to test the accident marker."
    },

    {
        id: 4,
        title: "Fire at Abandoned Building",
        category: "arson",
        date: "2025-11-07",
        latitude: 53.0061,
        longitude: -2.1718,
        description: "A fictional arson incident involving an abandoned property."
    },

    {
        id: 5,
        title: "Disturbance in Hanley",
        category: "disturbing_the_peace",
        date: "2026-01-18",
        latitude: 53.0102,
        longitude: -2.1755,
        description: "A fictional public disturbance created for map testing."
    }
];


// --------------------------------------------------
// ADD MARKERS
// --------------------------------------------------

const markers = [];

testCases.forEach(caseItem => {

    const icon = markerIcons[caseItem.category];

    if (!icon) {
        console.warn(
            `No marker icon found for category: ${caseItem.category}`
        );
        return;
    }

    const marker = L.marker(
    [caseItem.latitude, caseItem.longitude],
    {
        icon: icon
    }
)
.addTo(map)
.bindPopup(`
        <div>
            <h3>${caseItem.title}</h3>

            <strong>Category:</strong>
            ${caseItem.category}

            <br>

            <strong>Date:</strong>
            ${caseItem.date}

            <p>
                ${caseItem.description}
            </p>
        </div>
    `);
    markers.push(marker);
});

if (markers.length > 0) {
    const group = L.featureGroup(markers);

    map.fitBounds(group.getBounds(), {
        padding: [50, 50]
    });
}
