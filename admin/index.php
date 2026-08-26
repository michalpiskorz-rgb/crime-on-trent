<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Crime on Trent - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
</head>

<body>

<div class="page">

    <!-- TOP BAR -->
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <h1 class="navbar-brand navbar-brand-autodark">
                Crime on Trent
            </h1>

            <div class="navbar-nav flex-row order-md-last">

                <div class="nav-item">
                    <span class="nav-link">
                        Admin
                    </span>
                </div>

            </div>

        </div>
    </header>


    <!-- PAGE -->
    <div class="page-wrapper">

        <div class="container-xl">

            <div class="row g-4">

                <!-- SIDEBAR -->
                <div class="col-md-3 col-lg-2">

                    <div class="list-group list-group-transparent">

                        <a href="index.php"
                           class="list-group-item list-group-item-action active">
                            <span class="me-2">🏠</span>
                            Dashboard
                        </a>

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">🕵️</span>
                            Cases
                        </a>

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">📍</span>
                            Locations
                        </a>

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">🏷️</span>
                            Categories
                        </a>

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">🔗</span>
                            Sources
                        </a>

                    </div>

                    <div class="hr-text mt-4">
                        System
                    </div>

                    <div class="list-group list-group-transparent">

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">⚙️</span>
                            Settings
                        </a>

                        <a href="#"
                           class="list-group-item list-group-item-action">
                            <span class="me-2">👤</span>
                            Users
                        </a>

                    </div>

                </div>


                <!-- MAIN CONTENT -->
                <div class="col-md-9 col-lg-10">

                    <!-- PAGE HEADER -->
                    <div class="page-header d-print-none mb-4">

                        <div class="row align-items-center">

                            <div class="col">

                                <h2 class="page-title">
                                    Dashboard
                                </h2>

                                <div class="text-secondary">
                                    Crime on Trent administration panel
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- STATISTICS -->
                    <div class="row row-deck row-cards">


                        <!-- CASES -->
                        <div class="col-sm-6 col-lg-3">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex align-items-center">

                                        <div>
                                            <div class="h1 mb-0">
                                                0
                                            </div>

                                            <div class="text-secondary">
                                                Cases
                                            </div>
                                        </div>

                                        <div class="ms-auto">
                                            <span class="avatar bg-blue-lt">
                                                🕵️
                                            </span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- LOCATIONS -->
                        <div class="col-sm-6 col-lg-3">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex align-items-center">

                                        <div>

                                            <div class="h1 mb-0">
                                                0
                                            </div>

                                            <div class="text-secondary">
                                                Locations
                                            </div>

                                        </div>

                                        <div class="ms-auto">

                                            <span class="avatar bg-green-lt">
                                                📍
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- CATEGORIES -->
                        <div class="col-sm-6 col-lg-3">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex align-items-center">

                                        <div>

                                            <div class="h1 mb-0">
                                                0
                                            </div>

                                            <div class="text-secondary">
                                                Categories
                                            </div>

                                        </div>

                                        <div class="ms-auto">

                                            <span class="avatar bg-orange-lt">
                                                🏷️
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- SOURCES -->
                        <div class="col-sm-6 col-lg-3">

                            <div class="card">

                                <div class="card-body">

                                    <div class="d-flex align-items-center">

                                        <div>

                                            <div class="h1 mb-0">
                                                0
                                            </div>

                                            <div class="text-secondary">
                                                Sources
                                            </div>

                                        </div>

                                        <div class="ms-auto">

                                            <span class="avatar bg-purple-lt">
                                                🔗
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- RECENT CASES -->
                        <div class="col-12">

                            <div class="card">

                                <div class="card-header">

                                    <h3 class="card-title">
                                        Recent cases
                                    </h3>

                                    <div class="card-actions">

                                        <a href="#" class="btn btn-primary">
                                            + Add case
                                        </a>

                                    </div>

                                </div>


                                <div class="table-responsive">

                                    <table class="table table-vcenter card-table">

                                        <thead>

                                            <tr>
                                                <th>Case</th>
                                                <th>Category</th>
                                                <th>Year</th>
                                                <th>Location</th>
                                                <th class="w-1"></th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td colspan="5"
                                                    class="text-center text-secondary py-5">

                                                    No cases have been added yet.

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

</body>
</html>