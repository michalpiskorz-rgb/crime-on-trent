// --------------------------------------------------
// MAP
// --------------------------------------------------

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
// LANGUAGE
// --------------------------------------------------

let currentLanguage = 'en';

const langEn = document.getElementById('lang-en');
const langPl = document.getElementById('lang-pl');


// --------------------------------------------------
// MARKERS
// --------------------------------------------------

const markers = [];

let casesData = [];


// --------------------------------------------------
// LOAD CASES
// --------------------------------------------------

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

        console.log(
            'Cases loaded from API:',
            cases
        );

        casesData = cases;

        displayCases();

    })

    .catch(error => {

        console.error(
            'Could not load cases:',
            error
        );

    });


// --------------------------------------------------
// DISPLAY CASES
// --------------------------------------------------

function displayCases() {

    // Remove existing markers
    markers.forEach(marker => {
        map.removeLayer(marker);
    });

    markers.length = 0;


    casesData.forEach(caseItem => {

        const latitude =
            parseFloat(caseItem.latitude);

        const longitude =
            parseFloat(caseItem.longitude);


        if (
            !Number.isFinite(latitude) ||
            !Number.isFinite(longitude)
        ) {

            console.warn(
                'Invalid coordinates:',
                caseItem
            );

            return;
        }


        if (!caseItem.icon_url) {

            console.warn(
                'No icon:',
                caseItem
            );

            return;
        }


        // --------------------------------------------------
        // ICON
        // --------------------------------------------------

        const icon = L.icon({

            iconUrl: caseItem.icon_url,

            iconSize: [50, 65],

            iconAnchor: [25, 65],

            popupAnchor: [0, -65]

        });


        // --------------------------------------------------
        // MARKER
        // --------------------------------------------------

        const marker = L.marker(
            [latitude, longitude],
            {
                icon: icon
            }
        );


        // --------------------------------------------------
        // POPUP
        // --------------------------------------------------

        marker.bindPopup(
            createPopup(caseItem)
        );


        marker.addTo(map);

        markers.push(marker);

    });


    // --------------------------------------------------
    // FIT MAP
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

}


// --------------------------------------------------
// CREATE POPUP
// --------------------------------------------------

function createPopup(caseItem) {

    const title =
        currentLanguage === 'pl'
            ? caseItem.title_pl
            : caseItem.title_en;


    const description =
        currentLanguage === 'pl'
            ? caseItem.description_pl
            : caseItem.description_en;


    const category =
        currentLanguage === 'pl'
            ? caseItem.category_pl
            : caseItem.category_en;


    const date =
        formatDate(
            caseItem.event_date
        );


    const location =
        caseItem.location || '';


    return `

        <div class="case-popup">

            <h3>
                ${escapeHtml(title || '')}
            </h3>


            ${
                category
                    ? `
                    <div>
                        <strong>
                            ${
                                currentLanguage === 'pl'
                                    ? 'Kategoria'
                                    : 'Category'
                            }:
                        </strong>

                        ${escapeHtml(category)}
                    </div>
                    `
                    : ''
            }


            ${
                date
                    ? `
                    <div>
                        <strong>
                            ${
                                currentLanguage === 'pl'
                                    ? 'Data'
                                    : 'Date'
                            }:
                        </strong>

                        ${escapeHtml(date)}
                    </div>
                    `
                    : ''
            }


            ${
                location
                    ? `
                    <div>
                        <strong>
                            ${
                                currentLanguage === 'pl'
                                    ? 'Miejsce'
                                    : 'Location'
                            }:
                        </strong>

                        ${escapeHtml(location)}
                    </div>
                    `
                    : ''
            }


            ${
                description
                    ? `
                    <p>
                        ${escapeHtml(description)}
                    </p>
                    `
                    : ''
            }

        </div>

    `;

}


// --------------------------------------------------
// CHANGE LANGUAGE
// --------------------------------------------------

function setLanguage(language) {

    currentLanguage = language;


    langEn.classList.toggle(
        'active',
        language === 'en'
    );

    langPl.classList.toggle(
        'active',
        language === 'pl'
    );


    // Update existing popups
    casesData.forEach((caseItem, index) => {

        if (markers[index]) {

            markers[index].setPopupContent(
                createPopup(caseItem)
            );

        }

    });

}


// --------------------------------------------------
// BUTTONS
// --------------------------------------------------

langEn.addEventListener(
    'click',
    () => setLanguage('en')
);

langPl.addEventListener(
    'click',
    () => setLanguage('pl')
);


// --------------------------------------------------
// DATE
// --------------------------------------------------

function formatDate(dateString) {

    if (!dateString) {
        return '';
    }


    const date = new Date(
        dateString + 'T00:00:00'
    );


    if (Number.isNaN(date.getTime())) {
        return dateString;
    }


    if (currentLanguage === 'pl') {

        return date.toLocaleDateString(
            'pl-PL'
        );

    }


    return date.toLocaleDateString(
        'en-GB'
    );

}


// --------------------------------------------------
// HTML ESCAPE
// --------------------------------------------------

function escapeHtml(value) {

    return String(value)

        .replaceAll('&', '&amp;')

        .replaceAll('<', '&lt;')

        .replaceAll('>', '&gt;')

        .replaceAll('"', '&quot;')

        .replaceAll("'", '&#039;');

}
