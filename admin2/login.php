<?php

require_once __DIR__ . '/includes/db.php';

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


/*
 * Jeżeli już zalogowany,
 * przejdź do Dashboardu.
 */
if (!empty($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}


$error = false;


/*
 * Obsługa formularza
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = true;

    } else {

        $stmt = $pdo->prepare("
            SELECT id, username, password_hash
            FROM admin_users
            WHERE username = ?
            LIMIT 1
        ");

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (
            $user &&
            password_verify($password, $user['password_hash'])
        ) {

            /*
             * Nowa sesja po poprawnym logowaniu.
             */
            session_regenerate_id(true);

            $_SESSION['admin_user_id'] = (int) $user['id'];
            $_SESSION['admin_username'] = $user['username'];

            header('Location: index.php');
            exit;

        } else {

            $error = true;

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

    <title>Login — xcrime</title>

    <link
        href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css"
        rel="stylesheet">

</head>

<body class="d-flex flex-column">

<div class="page page-center">

    <div class="container container-tight py-4">

        <div class="text-center mb-4">

            <h1 class="navbar-brand navbar-brand-autodark">
                xcrime
            </h1>

        </div>


        <div class="card card-md">

            <div class="card-body">

                <h2 class="h2 text-center mb-4">
                    Login to your account
                </h2>


                <?php if ($error): ?>

                    <div class="alert alert-danger" role="alert">

                        Invalid username or password.

                    </div>

                <?php endif; ?>


                <form method="post"
                      action="login.php"
                      autocomplete="off">


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


                    <div class="form-footer">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            Login

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <div class="text-center text-secondary mt-3">

            xcrime administration panel

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js">
</script>

</body>

</html>
