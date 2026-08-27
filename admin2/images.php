<?php

require_once __DIR__ . '/includes/db.php';

$sql = "
    SELECT
        i.id,
        i.case_id,
        i.image_url,
        i.title,
        i.sort_order,
        c.slug
    FROM images i
    LEFT JOIN cases c
        ON i.case_id = c.id
    ORDER BY i.case_id DESC, i.sort_order ASC, i.id ASC
";

$stmt = $pdo->query($sql);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>xcrime — Images</title>

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>

<body>

<div class="page">

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

                    <li class="nav-item active">
                        <a class="nav-link" href="images.php">
                            <span class="nav-link-title">
                                Images
                            </span>
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </aside>


    <div class="page-wrapper">

        <div class="page-header d-print-none">

            <div class="container-xl">

                <div class="row align-items-center">

                    <div class="col">

                        <h2 class="page-title">
                            Images
                        </h2>

                        <div class="text-secondary mt-1">
                            All case images
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="page-body">

            <div class="container-xl">

                <div class="card">

                    <div class="table-responsive">

                        <table class="table table-vcenter card-table">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Case</th>
                                    <th>Title</th>
                                    <th>Order</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach ($images as $image): ?>

                                <tr>

                                    <td>
                                        <?= (int) $image['id'] ?>
                                    </td>

                                    <td>

                                        <img
                                            src="<?= htmlspecialchars(
                                                $image['image_url'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            alt=""
                                            style="
                                                width:100px;
                                                height:70px;
                                                object-fit:cover;
                                            "
                                        >

                                    </td>

                                    <td>

                                        <?= htmlspecialchars(
                                            $image['slug'] ?? '—',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars(
                                            $image['title'] ?? '—',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= (int) $image['sort_order'] ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <?php if (count($images) === 0): ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center text-secondary">

                                        No images found.

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
