<?php

require_once __DIR__ . '/../config/db.php';

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

        cat.name AS category_name,
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


    <!-- Tabler -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
    >


    <!-- DataTables -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css"
    >


    <!-- DataTables Responsive -->

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.dataTables.min.css"
    >


    <style>

        #casesTable {
            width: 100% !important;
        }

        #casesTable td {
            vertical-align: middle;
        }

        .case-title {
            max-width: 350px;
        }

        .case-title-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

    </style>

</head>


<body>


<div class="page">


    <!-- Page header -->

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



    <!-- Page body -->

    <div class="page-body">

        <div class="container-xl">


            <div class="card">


                <div class="card-header">

                    <h3 class="card-title">
                        Cases
                    </h3>

                </div>


                <div class="card-body">


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

                                    <?= (int)$case['id'] ?>

                                </td>


                                <!-- Date -->

                                <td>

                                    <?= htmlspecialchars(
                                        $case['event_date'] ?? ''
                                    ) ?>

                                </td>


                                <!-- Title PL -->

                                <td class="case-title">

                                    <div class="case-title-text">

                                        <?= htmlspecialchars(
                                            $case['title_pl']
                                        ) ?>

                                    </div>

                                </td>


                                <!-- Title EN -->

                                <td class="case-title">

                                    <div class="case-title-text">

                                        <?= htmlspecialchars(
                                            $case['title_en']
                                        ) ?>

                                    </div>

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
                                        href="case-edit.php?id=<?= (int)$case['id'] ?>"
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



<!-- Tabler -->

<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>


<!-- DataTables -->

<script
    src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js">
</script>


<!-- DataTables Responsive -->

<script
    src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js">
</script>



<script>

document.addEventListener('DOMContentLoaded', function () {


    new DataTable('#casesTable', {

        responsive: true,

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],


        order: [
            [0, 'desc']
        ],


        columnDefs: [

            {
                orderable: false,
                targets: 8
            }

        ],


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
