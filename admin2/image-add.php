<?php

require_once __DIR__ . '/includes/db.php';

$case_id = isset($_GET['case_id']) ? (int) $_GET['case_id'] : 0;

if ($case_id <= 0) {
    die('Invalid case ID.');
}

/*
 * Pobierz sprawę
 */
$stmt = $pdo->prepare("
    SELECT id, slug
    FROM cases
    WHERE id = ?
");

$stmt->execute([$case_id]);

$case = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$case) {
    die('Case not found.');
}


/*
 * Dodawanie obrazka
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $image_url = trim($_POST['image_url'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    if ($image_url === '') {
        $error = 'Image URL is required.';
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO images
                (case_id, image_url, title, sort_order)
            VALUES
                (?, ?, ?, ?)
        ");

        $stmt->execute([
            $case_id,
            $image_url,
            $title !== '' ? $title : null,
            $sort_order
        ]);

        header('Location: case-edit.php?id=' . $case_id);
        exit;
    }
}

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Add Image — xcrime</title>

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>

<body>

<div class="page">

    <div class="page-wrapper">

        <div class="page-header">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Add Image
                        </h2>

                        <div class="text-secondary mt-1">

                            Case:
                            <?= htmlspecialchars(
                                $case['slug'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="page-body">

            <div class="container-xl">

                <div class="row">

                    <div class="col-lg-8">

                        <form method="post">

                            <div class="card">

                                <div class="card-body">

                                    <?php if (!empty($error)): ?>

                                        <div class="alert alert-danger">
                                            <?= htmlspecialchars(
                                                $error,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>

                                    <?php endif; ?>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Image URL
                                        </label>

                                        <input
                                            type="url"
                                            name="image_url"
                                            id="image_url"
                                            class="form-control"
                                            placeholder="https://example.com/image.jpg"
                                            required
                                        >

                                        <div class="form-hint">
                                            The image stays on the external
                                            server. Only the URL is stored.
                                        </div>

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Title
                                        </label>

                                        <input
                                            type="text"
                                            name="title"
                                            class="form-control"
                                            maxlength="255"
                                        >

                                    </div>


                                    <div class="mb-3">

                                        <label class="form-label">
                                            Sort order
                                        </label>

                                        <input
                                            type="number"
                                            name="sort_order"
                                            class="form-control"
                                            value="0"
                                            min="0"
                                        >

                                    </div>


                                    <div
                                        id="preview"
                                        class="mt-4"
                                        style="display:none;"
                                    >

                                        <label class="form-label">
                                            Preview
                                        </label>

                                        <div>

                                            <img
                                                id="preview-image"
                                                src=""
                                                alt=""
                                                style="
                                                    max-width:100%;
                                                    max-height:400px;
                                                    object-fit:contain;
                                                "
                                            >

                                        </div>

                                    </div>

                                </div>


                                <div class="card-footer">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Add Image
                                    </button>

                                    <a
                                        href="case-edit.php?id=<?= $case_id ?>"
                                        class="btn btn-link"
                                    >
                                        Cancel
                                    </a>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

const imageUrl = document.getElementById('image_url');
const preview = document.getElementById('preview');
const previewImage = document.getElementById('preview-image');

imageUrl.addEventListener('input', function () {

    const url = this.value.trim();

    if (url === '') {

        preview.style.display = 'none';
        previewImage.src = '';

        return;
    }

    previewImage.src = url;
    preview.style.display = 'block';

});

previewImage.addEventListener('error', function () {

    preview.style.display = 'none';

});

</script>

</body>

</html>
