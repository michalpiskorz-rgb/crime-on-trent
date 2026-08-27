<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crime on Trent</title>

    <!-- Leaflet -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100vh;
        }

        .leaflet-control-attribution {
            font-size: 10px;
        }
    </style>
</head>

<body>

<div id="map"></div>

<!-- Leaflet -->
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>

<script>
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
</script>

</body>
</html>
