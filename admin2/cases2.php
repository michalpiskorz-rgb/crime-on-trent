```php
<?php

require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("
    SELECT
        id,
        slug
    FROM cases
    ORDER BY id DESC
");

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
        .dataTables_wrapper {
            width: 100%;
        }

        table.dataTable thead th {
            white-space: nowrap;
        }

        .case-title {
            max-width: 350px;
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

            </div>

        </div>
    </div>


    <!-- Page body -->
    <div class="page-body">

        <div class="container-xl">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Cases database
                    </h3>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            id="casesTable"
                            class="display responsive nowrap"
                            style="width:100%"
                        >

                            <thead>

                                <tr>
                                    <th>ID</th>
                                    <th>Slug</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>


                            <tbody>

                            <?php foreach ($cases as $case): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($case['id']) ?>
                                    </td>

                                    <td class="case-title">
                                        <?= htmlspecialchars($case['slug']) ?>
                                    </td>

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

</div>


<!-- Tabler -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>

<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>


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
                targets: 2
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
