const map = L.map('map', {
    zoomControl: true
}).setView([53.0027, -2.1794], 12);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }
).addTo(map);


// Homicide marker
const homicideIcon = L.icon({
    iconUrl: 'assets/icons/homicide.png',
    iconSize: [50, 65],
    iconAnchor: [25, 65],
    popupAnchor: [0, -65]
});


// Test marker
L.marker([53.0027, -2.1794], {
    icon: homicideIcon
})
.addTo(map)
.bindPopup(`
    <strong>Test marker</strong><br>
    Homicide
`);

const markerIcons = {
    homicide: ...,
    burglary: ...,
    arson: ...,
    assault: ...,
    robbery: ...,
    theft: ...,
    vehicle_theft: ...,
    vandalism: ...,
    car_accident: ...,
    weapons: ...,
    drugs_alcohol: ...,
    dui: ...,
    fraud: ...,
    disturbing_peace: ...,
    sex_crimes: ...
};
