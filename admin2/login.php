<?php

require_once __DIR__ . '/includes/db.php';


// ==================================================
// SECURE SESSION
// ==================================================

if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/admin2/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}


// ==================================================
// ALREADY LOGGED IN
// ==================================================

if (!empty($_SESSION['admin_user_id'])) {

    header('Location: index.php');
    exit;
}


// ==================================================
// CSRF TOKEN
// ==================================================

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(random_bytes(32));
}


// ==================================================
// VARIABLES
// ==================================================

$error = '';


// ==================================================
// LOGIN
// ==================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --------------------------------------------------
    // Check CSRF
    // --------------------------------------------------

    $csrfToken =
        $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
        )
    ) {

        $error = 'Invalid request. Please try again.';

    } else {

        // --------------------------------------------------
        // Get form data
        // --------------------------------------------------

        $username =
            trim($_POST['username'] ?? '');

        $password =
            $_POST['password'] ?? '';


        // --------------------------------------------------
        // Validate
        // --------------------------------------------------

        if (
            $username === '' ||
            $password === ''
        ) {

            $error =
                'Please enter your username and password.';

        } else {

            // --------------------------------------------------
            // Find user
            // --------------------------------------------------

            $stmt = $pdo->prepare("
                SELECT
                    id,
                    username,
                    password_hash
                FROM admin_users
                WHERE username = ?
                LIMIT 1
            ");

            $stmt->execute([
                $username
            ]);

            $user =
                $stmt->fetch(PDO::FETCH_ASSOC);


            // --------------------------------------------------
            // Verify password
            // --------------------------------------------------

            if (
                $user &&
                password_verify(
                    $password,
                    $user['password_hash']
                )
            ) {

                // --------------------------------------------------
                // New session ID
                // --------------------------------------------------

                session_regenerate_id(true);


                // --------------------------------------------------
                // Store user in session
                // --------------------------------------------------

                $_SESSION['admin_user_id'] =
                    (int) $user['id'];

                $_SESSION['admin_username'] =
                    $user['username'];


                // --------------------------------------------------
                // New CSRF token
                // --------------------------------------------------

                $_SESSION['csrf_token'] =
                    bin2hex(random_bytes(32));


                // --------------------------------------------------
                // Dashboard
                // --------------------------------------------------

                header('Location: index.php');
                exit;

            } else {

                $error =
                    'Invalid username or password.';

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

    <title>xcrime — Login</title>


    <!-- Tabler -->

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>


<body class="d-flex flex-column">


<div class="page page-center">


    <div class="container container-tight py-4">


        <!-- LOGO -->

        <div class="text-center mb-4">

            <div class="navbar-brand navbar-brand-autodark">

                <span class="h1">
                    xcrime
                </span>

            </div>

        </div>


        <!-- LOGIN CARD -->

        <form
            class="card card-md"
            method="post"
            action="login.php"
            autocomplete="off"
        >

            <div class="card-body">


                <!-- TITLE -->

                <h2 class="h2 text-center mb-4">
                    Login to xcrime Admin
                </h2>


                <!-- ERROR -->

                <?php if ($error !== ''): ?>

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >

                        <div>

                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </div>

                    </div>

                <?php endif; ?>


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


                <!-- USERNAME -->

                <div class="mb-3">

                    <label class="form-label">

                        Username

                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Username"
                        autocomplete="username"
                        value="<?= htmlspecialchars(
                            $_POST['username'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                        autofocus
                    >

                </div>


                <!-- PASSWORD -->

                <div class="mb-3">

                    <label class="form-label">

                        Password

                    </label>


                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Password"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <!-- LOGIN BUTTON -->

                <div class="form-footer">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        Login

                    </button>

                </div>

            </div>

        </form>


        <!-- FOOTER -->

        <div class="text-center text-secondary mt-3">

            xcrime Admin

        </div>


    </div>

</div>


<!-- Tabler JS -->

<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
