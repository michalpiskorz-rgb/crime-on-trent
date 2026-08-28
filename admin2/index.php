<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';


// --------------------------------------------------
// STATISTICS
// --------------------------------------------------

$totalCases = (int) $pdo
    ->query("SELECT COUNT(*) FROM cases")
    ->fetchColumn();

$publishedCases = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM cases c
        LEFT JOIN statuses s ON c.status_id = s.id
        WHERE s.name = 'published'
    ")
    ->fetchColumn();

$draftCases = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM cases c
        LEFT JOIN statuses s ON c.status_id = s.id
        WHERE s.name = 'draft'
    ")
    ->fetchColumn();

$totalCategories = (int) $pdo
    ->query("SELECT COUNT(*) FROM categories")
    ->fetchColumn();


// --------------------------------------------------
// RECENT CASES
// --------------------------------------------------

$stmt = $pdo->query("
    SELECT
        c.id,
        c.slug,
        c.title_en,
        c.event_date,
        cat.name_en AS category,
        s.name AS status

    FROM cases c

    LEFT JOIN categories cat
        ON c.category_id = cat.id

    LEFT JOIN statuses s
        ON c.status_id = s.id

    ORDER BY c.id DESC

    LIMIT 5
");

$recentCases = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>xcrime — Admin</title>

    <!-- Tabler -->
    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

    <!-- xcrime custom CSS -->
    <link
        href="assets/css/admin.css"
        rel="stylesheet">

</head>


<body>

<div class="page">


    <!-- ==================================================
         SIDEBAR
         ================================================== -->

    <aside class="navbar navbar-vertical navbar-expand-lg">

        <div class="container-fluid">


            <!-- LOGO -->

            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>


            <!-- MENU -->

            <div class="collapse navbar-collapse show">

                <ul class="navbar-nav pt-lg-3">


                    <!-- DASHBOARD -->

                    <li class="nav-item active">

                        <a class="nav-link"
                           href="index.php">

                            <span class="nav-link-icon">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M5 12l-2 0l9-9l9 9l-2 0"/>

                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7"/>

                                </svg>

                            </span>

                            <span class="nav-link-title">
                                Dashboard
                            </span>

                        </a>

                    </li>


                    <!-- CASES -->

                    <li class="nav-item">

                        <a class="nav-link"
                           href="cases.php">

                            <span class="nav-link-title">
                                All Cases
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="case-add.php">

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

                        <a class="nav-link"
                           href="categories.php">

                            <span class="nav-link-title">
                                Categories
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="statuses.php">

                            <span class="nav-link-title">
                                Statuses
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="sources.php">

                            <span class="nav-link-title">
                                Sources
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="images.php">

                            <span class="nav-link-title">
                                Images
                            </span>

                        </a>

                    </li>


                    <!-- ACCOUNT -->

                    <li class="nav-item mt-4">

                        <a class="nav-link"
                           href="logout.php">

                            <span class="nav-link-title">
                                Logout
                            </span>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </aside>


    <!-- ==================================================
         MAIN
         ================================================== -->

    <div class="page-wrapper">


        <!-- HEADER -->

        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Dashboard
                        </h2>

                        <div class="text-secondary">
                            Welcome to xcrime Admin
                        </div>

                    </div>


                    <div class="col-auto ms-auto">

                        <a
                            href="case-add.php"
                            class="btn btn-primary"
                        >
                            Add Case
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- BODY -->

        <div class="page-body">

            <div class="container-xl">


                <!-- STATISTICS -->

                <div class="row row-deck row-cards">


                    <div class="col-sm-6 col-lg-3">

                        <div class="card dashboard-card">

                            <div class="card-body">

                                <div class="subheader">
                                    Cases
                                </div>

                                <div class="h1 mb-0">
                                    <?= $totalCases ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-6 col-lg-3">

                        <div class="card dashboard-card">

                            <div class="card-body">

                                <div class="subheader">
                                    Published
                                </div>

                                <div class="h1 mb-0 text-green">
                                    <?= $publishedCases ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-6 col-lg-3">

                        <div class="card dashboard-card">

                            <div class="card-body">

                                <div class="subheader">
                                    Drafts
                                </div>

                                <div class="h1 mb-0 text-yellow">
                                    <?= $draftCases ?>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-6 col-lg-3">

                        <div class="card dashboard-card">

                            <div class="card-body">

                                <div class="subheader">
                                    Categories
                                </div>

                                <div class="h1 mb-0">
                                    <?= $totalCategories ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- RECENT CASES -->

                <div class="card mt-3">

                    <div class="card-header">

                        <h3 class="card-title">
                            Recent Cases
                        </h3>

                        <div class="card-actions">

                            <a
                                href="cases.php"
                                class="btn btn-primary btn-sm"
                            >
                                View all cases
                            </a>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-vcenter card-table">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Case</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th></th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php foreach ($recentCases as $case): ?>

                                <tr>

                                    <td>
                                        <?= (int) $case['id'] ?>
                                    </td>


                                    <td>

                                        <div class="fw-bold">

                                            <?= htmlspecialchars(
                                                $case['title_en'] ?: $case['slug'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                        <div class="text-secondary">

                                            <?= htmlspecialchars(
                                                $case['slug'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $case['category'] ?? '—',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $case['event_date'] ?? '—',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

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

                                        <?php else: ?>

                                            <span class="badge bg-secondary-lt">

                                                <?= htmlspecialchars(
                                                    $case['status'] ?? '—',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <a
                                            href="case-edit.php?id=<?= (int) $case['id'] ?>"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            Edit
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <?php if (count($recentCases) === 0): ?>

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center text-secondary py-5"
                                    >
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


<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
