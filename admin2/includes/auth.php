<?php

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
// AUTHENTICATION
// --------------------------------------------------

if (empty($_SESSION['admin_user_id'])) {

    header('Location: ../login.php');
    exit;
}
