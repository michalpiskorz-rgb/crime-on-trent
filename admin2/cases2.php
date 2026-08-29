<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/db.php';


/*
|--------------------------------------------------------------------------
| Get cases
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        c.id,
        c.event_date,
        c.title_pl,
        c.title_en,
        c.location,
        c.updated_at,

        cat.name_en AS category_name,
        s.name AS status_name

    FROM cases c

    LEFT JOIN categories cat
        ON cat.id = c.category_id

    LEFT JOIN statuses s
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

    <title>Cases - Admin</title>


    <!-- =========================================================
         TABLER
    ========================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
    >


    <!-- =========================================================
         DATATABLES
    ========================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css"
    >


    <!-- =========================================================
         DATATABLES RESPONSIVE
    ========================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.dataTables.min.css"
    >


    <style>

        /*
        |--------------------------------------------------------------------------
        | Table
        |--------------------------------------------------------------------------
        */

        #casesTable {

            width: 100% !important;

        }


        /*
        |--------------------------------------------------------------------------
        | Vertical alignment
        |--------------------------------------------------------------------------
        */

        #casesTable td {

            vertical-align: middle;

        }


        /*
        |--------------------------------------------------------------------------
        | Titles
        |--------------------------------------------------------------------------
        */

        .case-title {

            max-width: 350px;

        }


        .case-title-text {

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        .case-location {

            max-width: 200px;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

        }

    </style>


</head>



<body>


<div class="page">


    <!-- =========================================================
         PAGE HEADER
    ========================================================== -->

    <div class="page-header d-print-none">

        <div class="container-xl">

            <div class="row g-2 align-items-center">


                <div class="col">


                    <div class="page-pretitle">

                        Administration

                    </div>


                    <h2 class="page-title">

                        Cases

                    </h2>


                </div>



                <div class="col-auto ms-auto">


                    <a
                        href="case-edit.php"
                        class="btn btn-primary"
                    >

                        + Add Case

                    </a>


                </div>


            </div>

        </div>

    </div>



    <!-- =========================================================
         PAGE BODY
    ========================================================== -->

    <div class="page-body">

        <div class="container-xl">


            <div class="card">


                <!-- =================================================
                     CARD HEADER
                ================================================== -->

                <div class="card-header">


                    <h3 class="card-title">

                        Cases database

                    </h3>


                </div>



                <!-- =================================================
                     CARD BODY
                ================================================== -->

                <div class="card-body">


                    <div class="table-responsive">


                        <table
                            id="casesTable"
                            class="display responsive nowrap"
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



                                    <!-- DATE -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $case['event_date'] ?? ''
                                        ) ?>

                                    </td>



                                    <!-- TITLE PL -->

                                    <td class="case-title">


                                        <div class="case-title-text">

                                            <?= htmlspecialchars(
                                                $case['title_pl']
                                            ) ?>

                                        </div>


                                    </td>



                                    <!-- TITLE EN -->

                                    <td class="case-title">


                                        <div class="case-title-text">

                                            <?= htmlspecialchars(
                                                $case['title_en']
                                            ) ?>

                                        </div>


                                    </td>



                                    <!-- CATEGORY -->

                                    <td>


                                        <?= htmlspecialchars(
                                            $case['category_name'] ?? '—'
                                        ) ?>


                                    </td>



                                    <!-- LOCATION -->

                                    <td class="case-location">


                                        <?= htmlspecialchars(
                                            $case['location'] ?? '—'
                                        ) ?>


                                    </td>



                                    <!-- STATUS -->

                                    <td>


                                        <?= htmlspecialchars(
                                            $case['status_name'] ?? '—'
                                        ) ?>


                                    </td>



                                    <!-- UPDATED -->

                                    <td>


                                        <?= htmlspecialchars(
                                            $case['updated_at']
                                        ) ?>


                                    </td>



                                    <!-- ACTIONS -->

                                    <td>


                                        <a
                                            href="case-edit.php?id=<?= (int) $case['id'] ?>"
                                            class="btn btn-sm btn-primary"
                                        >

                                            Edit

                                        </a>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                            </tbody>


                        </table>


                    </div>


                </div>


            </div>


        </div>

    </div>


</div>



<!-- =============================================================
     TABLER JAVASCRIPT
============================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>



<!-- =============================================================
     DATATABLES JAVASCRIPT
============================================================= -->

<script
    src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js">
</script>



<!-- =============================================================
     DATATABLES RESPONSIVE JAVASCRIPT
============================================================= -->

<script
    src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js">
</script>



<!-- =============================================================
     DATATABLES INITIALIZATION
============================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    new DataTable('#casesTable', {


        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        responsive: true,


        /*
        |--------------------------------------------------------------------------
        | Number of rows
        |--------------------------------------------------------------------------
        */

        pageLength: 25,


        lengthMenu: [

            [10, 25, 50, 100],

            [10, 25, 50, 100]

        ],


        /*
        |--------------------------------------------------------------------------
        | Default sorting
        |--------------------------------------------------------------------------
        */

        order: [

            [0, 'desc']

        ],


        /*
        |--------------------------------------------------------------------------
        | Actions column cannot be sorted
        |--------------------------------------------------------------------------
        */

        columnDefs: [

            {

                orderable: false,

                targets: 8

            }

        ],


        /*
        |--------------------------------------------------------------------------
        | Language
        |--------------------------------------------------------------------------
        */

        language: {

            search: 'Search:',

            lengthMenu: 'Show _MENU_ cases',

            info: 'Showing _START_ to _END_ of _TOTAL_ cases',

            infoEmpty: 'No cases available',

            zeroRecords: 'No matching cases found',

            paginate: {

                first: 'First',

                last: 'Last',

                next: 'Next',

                previous: 'Previous'

            }

        }


    });


});

</script>


</body>

</html>
```
