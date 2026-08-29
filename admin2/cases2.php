<?php

// Show PHP errors while testing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Database connection
require_once __DIR__ . '/../config/db.php';


// Get cases
$sql = "
    SELECT
        c.id,
        c.event_date,
        c.title_pl,
        c.title_en,
        cat.name_en AS category_name,
        c.location,
        s.name AS status_name,
        c.updated_at

    FROM cases AS c

    LEFT JOIN categories AS cat
        ON cat.id = c.category_id

    LEFT JOIN statuses AS s
        ON s.id = c.status_id

    ORDER BY c.id DESC
";


$stmt = $pdo->query($sql);

$cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cases</title>


    <!--
        DataTables
    -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css"
    >


    <!--
        DataTables Responsive
    -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.dataTables.min.css"
    >


    <style>

        body {

            font-family: Arial, Helvetica, sans-serif;

            margin: 30px;

        }


        /*
        ---------------------------------------------------------
        DataTables compact
        ---------------------------------------------------------
        */

        table.dataTable.compact thead th,
        table.dataTable.compact thead td {

            padding: 4px;

        }


        table.dataTable.compact tbody th,
        table.dataTable.compact tbody td {

            padding: 4px;

        }


        /*
        ---------------------------------------------------------
        Page width
        ---------------------------------------------------------
        */

        .container {

            max-width: 1400px;

            margin: 0 auto;

        }


        /*
        ---------------------------------------------------------
        Title
        ---------------------------------------------------------
        */

        h1 {

            margin-bottom: 25px;

        }


        /*
        ---------------------------------------------------------
        Long titles
        ---------------------------------------------------------
        */

        .title-cell {

            max-width: 350px;

        }

    </style>

</head>


<body>


<div class="container">


    <h1>
        Cases
    </h1>


    <table
        id="casesTable"
        class="display compact nowrap"
        style="width:100%"
    >


        <thead>

            <tr>

                <th>ID</th>

                <th>Date</th>

                <th>Title PL</th>

                <th>Title EN</th>

                <th>Category</th>

                <th>Location</th>

                <th>Status</th>

                <th>Updated</th>

                <th>Actions</th>

            </tr>

        </thead>


        <tbody>


        <?php foreach ($cases as $case): ?>


            <tr>


                <!-- ID -->

                <td>

                    <?= (int) $case['id'] ?>

                </td>


                <!-- Event date -->

                <td>

                    <?= htmlspecialchars(
                        $case['event_date'] ?? ''
                    ) ?>

                </td>


                <!-- Polish title -->

                <td class="title-cell">

                    <?= htmlspecialchars(
                        $case['title_pl']
                    ) ?>

                </td>


                <!-- English title -->

                <td class="title-cell">

                    <?= htmlspecialchars(
                        $case['title_en']
                    ) ?>

                </td>


                <!-- Category -->

                <td>

                    <?= htmlspecialchars(
                        $case['category_name'] ?? '—'
                    ) ?>

                </td>


                <!-- Location -->

                <td>

                    <?= htmlspecialchars(
                        $case['location'] ?? '—'
                    ) ?>

                </td>


                <!-- Status -->

                <td>

                    <?= htmlspecialchars(
                        $case['status_name'] ?? '—'
                    ) ?>

                </td>


                <!-- Updated -->

                <td>

                    <?= htmlspecialchars(
                        $case['updated_at']
                    ) ?>

                </td>


                <!-- Actions -->

                <td>

                    <a
                        href="case-edit.php?id=<?= (int) $case['id'] ?>"
                    >
                        Edit
                    </a>

                </td>


            </tr>


        <?php endforeach; ?>


        </tbody>


    </table>


</div>



<!--
=================================================================
DataTables
=================================================================
-->

<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>



<!--
=================================================================
Responsive
=================================================================
-->

<script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>



<script>

document.addEventListener('DOMContentLoaded', function () {


    new DataTable('#casesTable', {


        /*
        ---------------------------------------------------------
        Responsive
        ---------------------------------------------------------
        */

        responsive: true,


        /*
        ---------------------------------------------------------
        Default number of rows
        ---------------------------------------------------------
        */

        pageLength: 25,


        /*
        ---------------------------------------------------------
        Number of rows selector
        ---------------------------------------------------------
        */

        lengthMenu: [

            [10, 25, 50, 100],

            [10, 25, 50, 100]

        ],


        /*
        ---------------------------------------------------------
        Default sorting
        ---------------------------------------------------------
        */

        order: [

            [0, 'desc']

        ],


        /*
        ---------------------------------------------------------
        Actions cannot be sorted
        ---------------------------------------------------------
        */

        columnDefs: [

            {

                orderable: false,

                targets: 8

            }

        ]


    });


});

</script>


</body>

</html>
```
