<?php

require_once __DIR__ . '/includes/db.php';

/*
 * Pobieramy sprawy razem z kategorią i statusem.
 */
$sql = "
    SELECT
        c.id,
        c.slug,
        cat.name_pl AS category_pl,
        cat.name_en AS category_en,
        s.name AS status
    FROM cases c
    LEFT JOIN categories cat
        ON c.category_id = cat.id
    LEFT JOIN statuses s
        ON c.status_id = s.id
    ORDER BY c.id DESC
";

$stmt = $pdo->query($sql);
$cases = $stmt->fetchAll();

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>xcrime — Cases</title>

    <!-- Tabler -->
    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>

<body>

<div class="page">

    <!-- SIDEBAR -->

    <aside class="navbar navbar-vertical navbar-expand-lg">

        <div class="container-fluid">

            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>

            <div class="collapse navbar-collapse show">

                <ul class="navbar-nav pt-lg-3">

                    <li class="nav-item">

                        <a class="nav-link" href="index.php">

                            <span class="nav-link-title">
                                Dashboard
                            </span>

                        </a>

                    </li>


                    <!-- CASES -->

                    <li class="nav-item active">

                        <a class="nav-link" href="cases.php">

                            <span class="nav-link-title">
                                Cases
                            </span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" href="#">

                            <span class="nav-link-title">
                                Add Case
                            </span>

                        </a>

                    </li>


                    <!-- DATA -->

                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span class="nav-link-title">
                                DATA
                            </span>

                        </div>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#">

                            <span class="nav-link-title">
                                Categories
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#">

                            <span class="nav-link-title">
                                Statuses
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#">

                            <span class="nav-link-title">
                                Sources
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#">

                            <span class="nav-link-title">
                                Images
                            </span>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </aside>


    <!-- MAIN -->

    <div class="page-wrapper">


        <!-- PAGE HEADER -->

        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Cases
                        </h2>

                        <div class="text-secondary mt-1">
                            All cases
                        </div>

                    </div>


                    <div class="col-auto ms-auto">

                        <a href="#" class="btn btn-primary">

                            Add Case

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- PAGE BODY -->

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

                                    <th class="w-1">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php foreach ($cases as $case): ?>

                                <tr>

                                    <td>
                                        <?= (int) $case['id'] ?>
                                    </td>


                                    <td>

                                        <div class="fw-bold">

                                            <?= htmlspecialchars(
                                                $case['slug'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    </td>


                                    <td>

                                        <?php if ($case['category_pl']): ?>

                                            <?= htmlspecialchars(
                                                $case['category_pl'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        <?php else: ?>

                                            <span class="text-secondary">
                                                —
                                            </span>

                                        <?php endif; ?>

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

                                            <span class="badge bg-secondary-lt">
                                                —
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <a href="#"
                                           class="btn btn-sm">

                                            Edit

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <?php if (count($cases) === 0): ?>

                                <tr>

                                    <td colspan="5"
                                        class="text-center text-secondary">

                                        No cases found.

                                    </td>

                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Tabler JS -->

<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
