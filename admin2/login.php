<?php

require_once __DIR__ . '/includes/db.php';


// --------------------------------------------------
// SECURE SESSION
// --------------------------------------------------

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


// --------------------------------------------------
// ALREADY LOGGED IN
// --------------------------------------------------

if (!empty($_SESSION['admin_user_id'])) {

    header('Location: index.php');
    exit;
}


// --------------------------------------------------
// CSRF TOKEN
// --------------------------------------------------

if (empty($_SESSION['login_csrf_token'])) {

    $_SESSION['login_csrf_token'] =
        bin2hex(random_bytes(32));
}


// --------------------------------------------------
// LOGIN ATTEMPT LIMIT
// --------------------------------------------------

// Maximum failed attempts during one session
$maxAttempts = 5;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}


$error = false;


// --------------------------------------------------
// LOGIN
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // --------------------------------------------------
    // CHECK CSRF
    // --------------------------------------------------

    $csrfToken =
        $_POST['csrf_token'] ?? '';

    if (
        !hash_equals(
            $_SESSION['login_csrf_token'],
            $csrfToken
        )
    ) {

        $error = true;

    }


    // --------------------------------------------------
    // CHECK ATTEMPT LIMIT
    // --------------------------------------------------

    elseif (
        $_SESSION['login_attempts'] >= $maxAttempts
    ) {

        $error = true;

    }


    else {

        $username =
            trim($_POST['username'] ?? '');

        $password =
            $_POST['password'] ?? '';


        if (
            $username === '' ||
            $password === ''
        ) {

            $error = true;

        }

        else {

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
            // VERIFY PASSWORD
            // --------------------------------------------------

            if (
                $user &&
                password_verify(
                    $password,
                    $user['password_hash']
                )
            ) {

                // New session ID after successful login
                session_regenerate_id(true);


                $_SESSION['admin_user_id'] =
                    (int) $user['id'];

                $_SESSION['admin_username'] =
                    $user['username'];


                // Reset failed attempts
                $_SESSION['login_attempts'] = 0;


                // Generate a new CSRF token
                $_SESSION['login_csrf_token'] =
                    bin2hex(random_bytes(32));


                header('Location: index.php');
                exit;

            }

            else {

                // Failed login
                $_SESSION['login_attempts']++;

                $error = true;

            }

        }

    }

}

?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Login — xcrime</title>


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

            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>

        </div>


        <!-- LOGIN CARD -->

        <div class="card card-md">

            <div class="card-body">


                <h2 class="h2 text-center mb-4">

                    Login to your account

                </h2>


                <?php if ($error): ?>

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >

                        Invalid username or password.

                    </div>

                <?php endif; ?>


                <?php if (
                    $_SESSION['login_attempts']
                    >= $maxAttempts
                ): ?>

                    <div
                        class="alert alert-warning"
                        role="alert"
                    >

                        Too many failed login attempts.
                        Please start a new session.

                    </div>

                <?php endif; ?>


                <form
                    method="post"
                    action="login.php"
                    autocomplete="off"
                >


                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars(
                            $_SESSION['login_csrf_token'],
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
                            placeholder="Your username"
                            autocomplete="username"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-2">

                        <label class="form-label">

                            Password

                        </label>


                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Your password"
                            autocomplete="current-password"
                            required
                        >

                    </div>


                    <!-- BUTTON -->

                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            <?php
                            if (
                                $_SESSION['login_attempts']
                                >= $maxAttempts
                            ) {
                                echo 'disabled';
                            }
                            ?>
                        >

                            Login

                        </button>

                    </div>


                </form>

            </div>

        </div>


        <div
            class="text-center text-secondary mt-3"
        >

            xcrime administration panel

        </div>


    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
