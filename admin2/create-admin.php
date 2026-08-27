<?php

require_once __DIR__ . '/includes/db.php';

$username = 'admin2';
$password = 'roparopa';

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO admin_users (username, password_hash)
    VALUES (?, ?)
");

$stmt->execute([
    $username,
    $password_hash
]);

echo "Administrator created successfully.";
