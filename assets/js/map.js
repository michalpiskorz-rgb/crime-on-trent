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
// LOAD CASES FROM API
// --------------------------------------------------

const markers = [];

fetch('/api/cases.php')
    .then(response => {

        if (!response.ok) {
            throw new Error(
                `API error: ${response.status}`
            );
        }

        return response.json();
    })

    .then(cases => {

        console.log('Cases loaded from API:', cases);


        cases.forEach(caseItem => {

            // Check coordinates
            const latitude = parseFloat(caseItem.latitude);
            const longitude = parseFloat(caseItem.longitude);

            if (
                !Number.isFinite(latitude) ||
                !Number.isFinite(longitude)
            ) {
                console.warn(
                    'Invalid coordinates for case:',
                    caseItem
                );

                return;
            }


            // Check icon
            if (!caseItem.icon_url) {

                console.warn(
                    'No icon for case:',
                    caseItem
                );

                return;
            }


            // Create icon
            const icon = L.icon({

                iconUrl: caseItem.icon_url,

                iconSize: [50, 65],

                iconAnchor: [25, 65],

                popupAnchor: [0, -65]

            });


            // Create marker
            const marker = L.marker(
                [latitude, longitude],
                {
                    icon: icon
                }
            );


            // Popup
            marker.bindPopup(`

                <div>

                    <h3>
                        ${escapeHtml(
                            caseItem.slug
                        )}
                    </h3>

                    <strong>Category:</strong>

                    ${escapeHtml(
                        caseItem.category_en || ''
                    )}

                    <br>

                    <strong>Case ID:</strong>

                    ${caseItem.id}

                </div>

            `);


            marker.addTo(map);

            markers.push(marker);

        });


        // --------------------------------------------------
        // FIT MAP TO MARKERS
        // --------------------------------------------------

        if (markers.length > 0) {

            const group =
                L.featureGroup(markers);

            map.fitBounds(
                group.getBounds(),
                {
                    padding: [50, 50]
                }
            );

        }

    })

    .catch(error => {

        console.error(
            'Could not load cases:',
            error
        );

    });


// --------------------------------------------------
// BASIC HTML ESCAPE
// --------------------------------------------------

function escapeHtml(value) {

    return String(value)

        .replaceAll('&', '&amp;')

        .replaceAll('<', '&lt;')

        .replaceAll('>', '&gt;')

        .replaceAll('"', '&quot;')

        .replaceAll("'", '&#039;');

}
