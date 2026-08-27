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
 * ---------------------------------------------------------
 * ZAPIS FORMULARZA
 * ---------------------------------------------------------
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $slug = trim($_POST['slug'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $status_id = (int) ($_POST['status_id'] ?? 0);

    if ($slug === '') {
        die('Slug cannot be empty.');
    }


    /*
     * Rozpoczynamy transakcję.
     *
     * Dzięki temu cases i sources zostaną zapisane
     * razem.
     */
    $pdo->beginTransaction();

    try {

        /*
         * Aktualizacja sprawy
         */
        $stmt = $pdo->prepare("
            UPDATE cases
            SET
                slug = :slug,
                category_id = :category_id,
                status_id = :status_id
            WHERE id = :id
        ");

        $stmt->execute([
            ':slug' => $slug,
            ':category_id' => $category_id,
            ':status_id' => $status_id,
            ':id' => $id
        ]);


        /*
         * -------------------------------------------------
         * SOURCES
         * -------------------------------------------------
         *
         * Najprościej i najbezpieczniej:
         * usuwamy stare źródła tej sprawy
         * i zapisujemy aktualną listę.
         */

        $stmt = $pdo->prepare("
            DELETE FROM sources
            WHERE case_id = :case_id
        ");

        $stmt->execute([
            ':case_id' => $id
        ]);


        /*
         * Dodajemy źródła z formularza
         */

        $source_titles = $_POST['source_title'] ?? [];
        $source_urls = $_POST['source_url'] ?? [];


        $stmt = $pdo->prepare("
            INSERT INTO sources
                (case_id, title, url)
            VALUES
                (:case_id, :title, :url)
        ");


        foreach ($source_urls as $index => $url) {

            $url = trim($url);

            /*
             * Puste URL pomijamy
             */
            if ($url === '') {
                continue;
            }

            $title = trim($source_titles[$index] ?? '');

            /*
             * Jeśli tytuł jest pusty,
             * zapisujemy NULL.
             */
            if ($title === '') {
                $title = null;
            }


            $stmt->execute([
                ':case_id' => $id,
                ':title' => $title,
                ':url' => $url
            ]);
        }


        /*
         * Wszystko OK
         */
        $pdo->commit();


        /*
         * Wracamy do listy spraw
         */
        header('Location: cases.php');
        exit;


    } catch (Exception $e) {

        /*
         * Jeśli coś pójdzie nie tak,
         * cofamy wszystkie zmiany.
         */
        $pdo->rollBack();

        die(
            'Error saving case: ' .
            htmlspecialchars(
                $e->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            )
        );
    }
}


/*
 * ---------------------------------------------------------
 * POBIERAMY SPRAWĘ
 * ---------------------------------------------------------
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
 * ---------------------------------------------------------
 * POBIERAMY KATEGORIE
 * ---------------------------------------------------------
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
 * ---------------------------------------------------------
 * POBIERAMY STATUSY
 * ---------------------------------------------------------
 */

$stmt = $pdo->query("
    SELECT
        id,
        name
    FROM statuses
    ORDER BY id
");

$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
 * ---------------------------------------------------------
 * POBIERAMY SOURCES
 * ---------------------------------------------------------
 */

$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        url
    FROM sources
    WHERE case_id = :case_id
    ORDER BY id ASC
");

$stmt->execute([
    ':case_id' => $id
]);

$sources = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
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


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <aside class="navbar navbar-vertical navbar-expand-lg">

        <div class="container-fluid">


            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>


            <div class="collapse navbar-collapse show">

                <ul class="navbar-nav pt-lg-3">


                    <!-- Dashboard -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="index.php">

                            <span class="nav-link-title">
                                Dashboard
                            </span>

                        </a>

                    </li>


                    <!-- Cases -->

                    <li class="nav-item active">

                        <a
                            class="nav-link"
                            href="cases.php">

                            <span class="nav-link-title">
                                Cases
                            </span>

                        </a>

                    </li>


                    <!-- Add Case -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
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


                    <!-- Categories -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="#">

                            <span class="nav-link-title">
                                Categories
                            </span>

                        </a>

                    </li>


                    <!-- Statuses -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="#">

                            <span class="nav-link-title">
                                Statuses
                            </span>

                        </a>

                    </li>


                    <!-- Sources -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="#">

                            <span class="nav-link-title">
                                Sources
                            </span>

                        </a>

                    </li>


                    <!-- Images -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
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


    <!-- =====================================================
         MAIN
         ===================================================== -->

    <div class="page-wrapper">


        <!-- PAGE HEADER -->

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


        <!-- =================================================
             PAGE BODY
             ================================================= -->

        <div class="page-body">

            <div class="container-xl">

                <form method="post">


                    <div class="row row-cards">


                        <!-- =================================================
                             CASE DETAILS
                             ================================================= -->

                        <div class="col-lg-8">


                            <div class="card">


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


                            </div>


                        </div>


                        <!-- =================================================
                             SOURCES
                             ================================================= -->

                        <div class="col-lg-8">


                            <div class="card">


                                <div class="card-header">

                                    <h3 class="card-title">

                                        Sources

                                    </h3>

                                </div>


                                <div class="card-body">


                                    <div id="sources-container">


                                        <?php if (count($sources) > 0): ?>


                                            <?php foreach ($sources as $source): ?>


                                                <div class="source-row mb-4">

                                                    <div class="row g-2">


                                                        <!-- TITLE -->

                                                        <div class="col-md-4">

                                                            <label class="form-label">

                                                                Title

                                                            </label>

                                                            <input
                                                                type="text"
                                                                name="source_title[]"
                                                                class="form-control"
                                                                value="<?= htmlspecialchars(
                                                                    $source['title'] ?? '',
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                ) ?>">

                                                        </div>


                                                        <!-- URL -->

                                                        <div class="col-md-7">

                                                            <label class="form-label">

                                                                URL

                                                            </label>

                                                            <input
                                                                type="url"
                                                                name="source_url[]"
                                                                class="form-control"
                                                                value="<?= htmlspecialchars(
                                                                    $source['url'],
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                ) ?>"
                                                                placeholder="https://...">

                                                        </div>


                                                        <!-- REMOVE -->

                                                        <div class="col-md-1">

                                                            <label class="form-label">
                                                                &nbsp;
                                                            </label>

                                                            <button
                                                                type="button"
                                                                class="btn btn-outline-danger w-100 remove-source">

                                                                ×

                                                            </button>

                                                        </div>


                                                    </div>

                                                </div>


                                            <?php endforeach; ?>


                                        <?php else: ?>


                                            <!-- EMPTY SOURCE -->

                                            <div class="source-row mb-4">

                                                <div class="row g-2">


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Title
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="source_title[]"
                                                            class="form-control">

                                                    </div>


                                                    <div class="col-md-7">

                                                        <label class="form-label">
                                                            URL
                                                        </label>

                                                        <input
                                                            type="url"
                                                            name="source_url[]"
                                                            class="form-control"
                                                            placeholder="https://...">

                                                    </div>


                                                    <div class="col-md-1">

                                                        <label class="form-label">
                                                            &nbsp;
                                                        </label>

                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-danger w-100 remove-source">

                                                            ×

                                                        </button>

                                                    </div>


                                                </div>

                                            </div>


                                        <?php endif; ?>


                                    </div>


                                    <!-- ADD SOURCE -->

                                    <button
                                        type="button"
                                        id="add-source"
                                        class="btn btn-outline-primary">

                                        + Add source

                                    </button>


                                </div>


                            </div>


                        </div>


                        <!-- =================================================
                             BUTTONS
                             ================================================= -->

                        <div class="col-lg-8">


                            <div class="card">


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


                            </div>


                        </div>


                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
     ========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    const container =
        document.getElementById('sources-container');


    const addButton =
        document.getElementById('add-source');


    /*
     * Dodawanie nowego źródła
     */

    addButton.addEventListener('click', function () {


        const row =
            document.createElement('div');


        row.className =
            'source-row mb-4';


        row.innerHTML = `

            <div class="row g-2">

                <div class="col-md-4">

                    <label class="form-label">
                        Title
                    </label>

                    <input
                        type="text"
                        name="source_title[]"
                        class="form-control">

                </div>


                <div class="col-md-7">

                    <label class="form-label">
                        URL
                    </label>

                    <input
                        type="url"
                        name="source_url[]"
                        class="form-control"
                        placeholder="https://...">

                </div>


                <div class="col-md-1">

                    <label class="form-label">
                        &nbsp;
                    </label>

                    <button
                        type="button"
                        class="btn btn-outline-danger w-100 remove-source">

                        ×

                    </button>

                </div>

            </div>

        `;


        container.appendChild(row);

    });


    /*
     * Usuwanie źródła
     */

    container.addEventListener('click', function (event) {


        if (
            event.target.classList.contains('remove-source')
        ) {

            const row =
                event.target.closest('.source-row');


            if (row) {

                row.remove();

            }

        }

    });


});

</script>


<!-- Tabler JS -->

<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>


</body>

</html>
