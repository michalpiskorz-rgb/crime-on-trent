<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';


// ==================================================
// CSRF TOKEN
// ==================================================

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// ==================================================
// VARIABLES
// ==================================================

$error = '';
$success = '';


// ==================================================
// IMPORT
// ==================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --------------------------------------------------
    // CSRF
    // --------------------------------------------------

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
        )
    ) {

        $error = 'Invalid request. Please try again.';

    } else {

        $json = trim($_POST['json_data'] ?? '');


        if ($json === '') {

            $error = 'Please paste JSON data.';

        } else {

            // --------------------------------------------------
            // Decode JSON
            // --------------------------------------------------

            $data = json_decode(
                $json,
                true
            );


            if (
                json_last_error() !== JSON_ERROR_NONE
            ) {

                $error =
                    'Invalid JSON: ' .
                    json_last_error_msg();

            } elseif (!is_array($data)) {

                $error =
                    'The JSON must contain an array of cases.';

            } elseif (count($data) === 0) {

                $error =
                    'The JSON array is empty.';

            } else {

                try {

                    // --------------------------------------------------
                    // START TRANSACTION
                    // --------------------------------------------------

                    $pdo->beginTransaction();


                    // --------------------------------------------------
                    // PREPARED STATEMENTS
                    // --------------------------------------------------

                    $categoryStmt = $pdo->prepare("
                        SELECT id
                        FROM categories
                        WHERE slug = ?
                           OR name_en = ?
                        LIMIT 1
                    ");


                    $statusStmt = $pdo->prepare("
                        SELECT id
                        FROM statuses
                        WHERE name = ?
                        LIMIT 1
                    ");


                    $checkSlugStmt = $pdo->prepare("
                        SELECT id
                        FROM cases
                        WHERE slug = ?
                        LIMIT 1
                    ");


                    $insertStmt = $pdo->prepare("
                        INSERT INTO cases (
                            slug,
                            latitude,
                            longitude,
                            event_date,
                            category_id,
                            status_id,
                            title_pl,
                            title_en,
                            description_pl,
                            description_en,
                            location,
                            tags,
                            links,
                            created_at,
                            updated_at
                        )
                        VALUES (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            NOW(),
                            NOW()
                        )
                    ");


                    $imported = 0;


                    // --------------------------------------------------
                    // PROCESS CASES
                    // --------------------------------------------------

                    foreach ($data as $index => $case) {

                        if (!is_array($case)) {

                            throw new Exception(
                                'Case #' . ($index + 1) .
                                ' is not a valid object.'
                            );

                        }


                        // --------------------------------------------------
                        // REQUIRED FIELDS
                        // --------------------------------------------------

                        $required = [
                            'slug',
                            'latitude',
                            'longitude',
                            'title_pl',
                            'title_en'
                        ];


                        foreach ($required as $field) {

                            if (
                                !array_key_exists(
                                    $field,
                                    $case
                                )
                            ) {

                                throw new Exception(
                                    'Case #' . ($index + 1) .
                                    ' is missing: ' . $field
                                );

                            }

                        }


                        // --------------------------------------------------
                        // BASIC DATA
                        // --------------------------------------------------

                        $slug =
                            trim((string) $case['slug']);

                        $latitude =
                            (float) $case['latitude'];

                        $longitude =
                            (float) $case['longitude'];

                        $eventDate =
                            !empty($case['event_date'])
                                ? $case['event_date']
                                : null;

                        $titlePl =
                            trim((string) $case['title_pl']);

                        $titleEn =
                            trim((string) $case['title_en']);

                        $descriptionPl =
                            $case['description_pl'] ?? null;

                        $descriptionEn =
                            $case['description_en'] ?? null;

                        $location =
                            $case['location'] ?? null;


                        // --------------------------------------------------
                        // CHECK SLUG
                        // --------------------------------------------------

                        $checkSlugStmt->execute([
                            $slug
                        ]);

                        if ($checkSlugStmt->fetch()) {

                            throw new Exception(
                                'Case already exists: ' .
                                $slug
                            );

                        }


                        // --------------------------------------------------
                        // CATEGORY
                        // --------------------------------------------------

                        $categoryId = null;

                        if (
                            !empty($case['categories']) &&
                            is_array($case['categories'])
                        ) {

                            $category =
                                trim(
                                    (string)
                                    $case['categories'][0]
                                );


                            $categoryStmt->execute([
                                $category,
                                $category
                            ]);


                            $categoryRow =
                                $categoryStmt->fetch(
                                    PDO::FETCH_ASSOC
                                );


                            if (!$categoryRow) {

                                throw new Exception(
                                    'Category not found: ' .
                                    $category
                                );

                            }


                            $categoryId =
                                (int) $categoryRow['id'];
                        }


                        // --------------------------------------------------
                        // STATUS
                        // --------------------------------------------------

                        $statusId = null;

                        if (!empty($case['status'])) {

                            $status =
                                trim(
                                    (string)
                                    $case['status']
                                );


                            $statusStmt->execute([
                                $status
                            ]);


                            $statusRow =
                                $statusStmt->fetch(
                                    PDO::FETCH_ASSOC
                                );


                            if (!$statusRow) {

                                throw new Exception(
                                    'Status not found: ' .
                                    $status
                                );

                            }


                            $statusId =
                                (int) $statusRow['id'];
                        }


                        // --------------------------------------------------
                        // TAGS
                        // --------------------------------------------------

                        $tags = null;

                        if (
                            isset($case['tags']) &&
                            is_array($case['tags'])
                        ) {

                            $tags = json_encode(
                                $case['tags'],
                                JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES
                            );
                        }


                        // --------------------------------------------------
                        // LINKS
                        // --------------------------------------------------

                        $links = null;

                        if (
                            isset($case['links']) &&
                            is_array($case['links'])
                        ) {

                            $links = json_encode(
                                $case['links'],
                                JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES
                            );
                        }


                        // --------------------------------------------------
                        // INSERT
                        // --------------------------------------------------

                        $insertStmt->execute([

                            $slug,

                            $latitude,

                            $longitude,

                            $eventDate,

                            $categoryId,

                            $statusId,

                            $titlePl,

                            $titleEn,

                            $descriptionPl,

                            $descriptionEn,

                            $location,

                            $tags,

                            $links

                        ]);


                        $imported++;
                    }


                    // --------------------------------------------------
                    // COMMIT
                    // --------------------------------------------------

                    $pdo->commit();


                    $success =
                        $imported .
                        ' case(s) imported successfully.';


                    // Clear textarea
                    $_POST['json_data'] = '';


                    // New CSRF token
                    $_SESSION['csrf_token'] =
                        bin2hex(random_bytes(32));

                } catch (Throwable $e) {

                    // --------------------------------------------------
                    // ROLLBACK
                    // --------------------------------------------------

                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }


                    $error =
                        $e->getMessage();
                }
            }
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

    <title>xcrime — Import Case</title>


    <!-- Tabler -->

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">


    <!-- xcrime CSS -->

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


                    <li class="nav-item active">

                        <a class="nav-link"
                           href="case-import.php">

                            <span class="nav-link-title">
                                Import Case
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
                           href="images.php">

                            <span class="nav-link-title">
                                Images
                            </span>

                        </a>

                    </li>


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
                            Import Case
                        </h2>

                        <div class="text-secondary">
                            Import case data from JSON
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- BODY -->

        <div class="page-body">

            <div class="container-xl">

                <div class="row">

                    <div class="col-lg-10">


                        <form
                            method="post"
                            action="case-import.php"
                        >


                            <!-- CSRF -->

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(
                                    $_SESSION['csrf_token'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >


                            <div class="card">


                                <div class="card-header">

                                    <h3 class="card-title">
                                        Paste JSON
                                    </h3>

                                </div>


                                <div class="card-body">


                                    <!-- ERROR -->

                                    <?php if ($error !== ''): ?>

                                        <div
                                            class="alert alert-danger"
                                            role="alert"
                                        >

                                            <?= htmlspecialchars(
                                                $error,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <!-- SUCCESS -->

                                    <?php if ($success !== ''): ?>

                                        <div
                                            class="alert alert-success"
                                            role="alert"
                                        >

                                            <?= htmlspecialchars(
                                                $success,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </div>

                                    <?php endif; ?>


                                    <div class="mb-3">

                                        <label class="form-label">

                                            JSON

                                        </label>


                                        <textarea
                                            name="json_data"
                                            class="form-control"
                                            rows="25"
                                            placeholder='Paste your JSON here...'
                                            spellcheck="false"
                                        ><?= htmlspecialchars(
                                            $_POST['json_data'] ?? '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?></textarea>

                                    </div>


                                    <div class="text-secondary">

                                        Expected format:

                                        <code>
                                            JSON array of cases
                                        </code>

                                    </div>

                                </div>


                                <div class="card-footer">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        Import Case

                                    </button>

                                </div>


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
