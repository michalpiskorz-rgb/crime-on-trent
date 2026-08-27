<?php

require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query("
    SELECT
        c.id,
        c.slug,
        cat.name_en AS category,
        s.name AS status
    FROM cases c
    LEFT JOIN categories cat ON c.category_id = cat.id
    LEFT JOIN statuses s ON c.status_id = s.id
    ORDER BY c.id DESC
");

$cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cases — xcrime Admin</title>

    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
</head>

<body>

<div class="page">

    <!-- Navbar -->
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">

            <a class="navbar-brand" href="index.php">
                xcrime Admin
            </a>

        </div>
    </header>

    <div class="page-wrapper">

        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">

                <div class="row g-2 align-items-center">

                    <div class="col">
                        <h2 class="page-title">
                            Cases
                        </h2>
                    </div>

                    <div class="col-auto ms-auto">
                        <a href="case-add.php" class="btn btn-primary">
                            + Add case
                        </a>
                    </div>

                </div>

            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">

                <div class="card">

                    <div class="table-responsive">

                        <table class="table table-vcenter card-table">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Case</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php foreach ($cases as $case): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($case['id']) ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($case['slug']) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($case['category'] ?? '—') ?>
                                    </td>

                                    <td>

                                        <?php if ($case['status'] === 'published'): ?>

                                            <span class="badge bg-green-lt">
                                                Published
                                            </span>

                                        <?php elseif ($case['status'] === 'draft'): ?>

                                            <span class="badge bg-yellow-lt">
                                                Draft
                                            </span>

                                        <?php elseif ($case['status'] === 'declined'): ?>

                                            <span class="badge bg-red-lt">
                                                Declined
                                            </span>

                                        <?php else: ?>

                                            <span class="badge">
                                                <?= htmlspecialchars($case['status'] ?? 'Unknown') ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <a href="case-edit.php?id=<?= $case['id'] ?>"
                                           class="btn btn-sm btn-outline-primary">

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

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

</body>
</html>
