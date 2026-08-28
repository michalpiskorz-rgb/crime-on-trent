<?php

/*
 * Bezpieczna konfiguracja sesji
 */

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
 * Sprawdzenie logowania
 */

if (empty($_SESSION['admin_user_id'])) {

    header('Location: /admin2/login.php');

    exit;
}
