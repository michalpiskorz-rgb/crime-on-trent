<?php

require_once __DIR__ . '/includes/db.php';

/*
 * Sprawdzamy ID sprawy
 */
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid case ID.');
}


/*
 * Obsługa formularza
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $slug = trim($_POST['slug'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $status_id = (int) ($_POST['status_id'] ?? 0);

    if ($slug === '') {
        die('Slug cannot be empty.');
    }

    $sql = "
        UPDATE cases
        SET
            slug = :slug,
            category_id = :category_id,
            status_id = :status_id
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':slug' => $slug,
        ':category_id' => $category_id,
        ':status_id' => $status_id,
        ':id' => $id
    ]);

    /*
     * Po zapisaniu wracamy do listy
     */
    header('Location: cases.php');
    exit;
}


/*
 * Pobieramy sprawę
 */
$stmt = $pdo->prepare("
    SELECT
        id,
        slug,
        category_id,
        status_id
    FROM cases
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$case = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$case) {
    die('Case not found.');
}


/*
 * Pobieramy kategorie
 */
$stmt = $pdo->query("
    SELECT
        id,
        name_pl,
        name_en
    FROM categories
    ORDER BY name_en
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
 * Pobieramy statusy
 */
$stmt = $pdo->query("
    SELECT
        id,
        name
    FROM statuses
    ORDER BY id
");

$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        Edit Case — xcrime
    </title>

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

                        <a class="nav-link"
                           href="index.php">

                            <span class="nav-link-title">
                                Dashboard
                            </span>

                        </a>

                    </li>


                    <li class="nav-item active">

                        <a class="nav-link"
                           href="cases.php">

                            <span class="nav-link-title">
                                Cases
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


                    <li class="nav-item mt-3">

                        <div class="nav-link disabled">

                            <span class="nav-link-title">
                                DATA
                            </span>

                        </div>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="#">

                            <span class="nav-link-title">
                                Categories
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="#">

                            <span class="nav-link-title">
                                Statuses
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="#">

                            <span class="nav-link-title">
                                Sources
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link"
                           href="#">

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


        <!-- HEADER -->

        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Edit Case
                        </h2>

                        <div class="text-secondary mt-1">
                            Case #<?= (int) $case['id'] ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- BODY -->

        <div class="page-body">

            <div class="container-xl">

                <div class="row row-cards">

                    <div class="col-lg-8">

                        <form method="post"
                              class="card">

                            <div class="card-header">

                                <h3 class="card-title">
                                    Case details
                                </h3>

                            </div>


                            <div class="card-body">


                                <!-- SLUG -->

                                <div class="mb-3">

                                    <label class="form-label">
                                        Slug
                                    </label>

                                    <input
                                        type="text"
                                        name="slug"
                                        class="form-control"
                                        value="<?= htmlspecialchars(
                                            $case['slug'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        required>

                                </div>


                                <!-- CATEGORY -->

                                <div class="mb-3">

                                    <label class="form-label">
                                        Category
                                    </label>

                                    <select
                                        name="category_id"
                                        class="form-select"
                                        required>

                                        <?php foreach ($categories as $category): ?>

                                            <option
                                                value="<?= (int) $category['id'] ?>"
                                                <?= (
                                                    (int) $category['id']
                                                    ===
                                                    (int) $case['category_id']
                                                )
                                                ? 'selected'
                                                : ''
                                                ?>>

                                                <?= htmlspecialchars(
                                                    $category['name_en'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                —
                                                <?= htmlspecialchars(
                                                    $category['name_pl'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <!-- STATUS -->

                                <div class="mb-3">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select
                                        name="status_id"
                                        class="form-select"
                                        required>

                                        <?php foreach ($statuses as $status): ?>

                                            <option
                                                value="<?= (int) $status['id'] ?>"
                                                <?= (
                                                    (int) $status['id']
                                                    ===
                                                    (int) $case['status_id']
                                                )
                                                ? 'selected'
                                                : ''
                                                ?>>

                                                <?= htmlspecialchars(
                                                    ucfirst($status['name']),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                            </div>


                            <div class="card-footer d-flex">

                                <a
                                    href="cases.php"
                                    class="btn btn-link">

                                    Cancel

                                </a>


                                <button
                                    type="submit"
                                    class="btn btn-primary ms-auto">

                                    Save changes

                                </button>

                            </div>

                        </form>

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
