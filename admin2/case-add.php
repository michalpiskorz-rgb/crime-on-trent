<?php

require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';

/*
 * Pobieramy kategorie
 */
$categories = $pdo->query("
    SELECT id, name_pl, name_en
    FROM categories
    ORDER BY name_en ASC
")->fetchAll();

/*
 * Pobieramy statusy
 */
$statuses = $pdo->query("
    SELECT id, name
    FROM statuses
    ORDER BY id ASC
")->fetchAll();


/*
 * Zapis formularza
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $slug = trim($_POST['slug'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $status_id = (int)($_POST['status_id'] ?? 0);

    if ($slug === '') {

        $error = 'Slug is required.';

    } elseif ($category_id <= 0) {

        $error = 'Please select a category.';

    } elseif ($status_id <= 0) {

        $error = 'Please select a status.';

    } else {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO cases
                    (slug, category_id, status_id)
                VALUES
                    (:slug, :category_id, :status_id)
            ");

            $stmt->execute([
                ':slug' => $slug,
                ':category_id' => $category_id,
                ':status_id' => $status_id
            ]);

            $success = 'Case added successfully.';

            /*
             * Czyścimy formularz
             */
            $slug = '';

        } catch (PDOException $e) {

            $error = 'Could not save the case.';
        }
    }
}

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>xcrime — Add Case</title>

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


                    <li class="nav-item">

                        <a class="nav-link" href="cases.php">
                            <span class="nav-link-title">
                                Cases
                            </span>
                        </a>

                    </li>


                    <li class="nav-item active">

                        <a class="nav-link" href="case-add.php">
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


        <!-- HEADER -->

        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Add Case
                        </h2>

                    </div>

                </div>

            </div>

        </div>


        <!-- BODY -->

        <div class="page-body">

            <div class="container-xl">

                <?php if ($error): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                <?php endif; ?>


                <?php if ($success): ?>

                    <div class="alert alert-success">
                        <?= htmlspecialchars(
                            $success,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                <?php endif; ?>


                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">
                            New Case
                        </h3>

                    </div>


                    <div class="card-body">

                        <form method="post">


                            <!-- SLUG -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Slug
                                </label>

                                <input
                                    type="text"
                                    name="slug"
                                    class="form-control"
                                    placeholder="e.g. john-smith-murder-2026"
                                    value="<?= htmlspecialchars(
                                        $slug ?? '',
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

                                    <option value="">
                                        Select category
                                    </option>

                                    <?php foreach ($categories as $category): ?>

                                        <option
                                            value="<?= (int)$category['id'] ?>"
                                            <?= (
                                                isset($_POST['category_id'])
                                                && (int)$_POST['category_id']
                                                === (int)$category['id']
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

                                    <option value="">
                                        Select status
                                    </option>

                                    <?php foreach ($statuses as $status): ?>

                                        <option
                                            value="<?= (int)$status['id'] ?>"
                                            <?= (
                                                isset($_POST['status_id'])
                                                && (int)$_POST['status_id']
                                                === (int)$status['id']
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


                            <!-- BUTTONS -->

                            <div class="mt-4">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    Add Case

                                </button>


                                <a
                                    href="cases.php"
                                    class="btn btn-link">

                                    Cancel

                                </a>

                            </div>


                        </form>

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
