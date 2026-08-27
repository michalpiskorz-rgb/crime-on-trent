<?php

require_once __DIR__ . '/includes/db.php';

echo "Połączenie z bazą działa!<br>";

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM cases");
$row = $stmt->fetch();

echo "Liczba spraw w bazie: " . $row['total'];
