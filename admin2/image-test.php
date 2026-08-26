<?php

require_once __DIR__ . '/includes/db.php';

echo "DB OK<br>";

$stmt = $pdo->query("SELECT * FROM images LIMIT 1");

echo "IMAGES OK<br>";

$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($row);
echo "</pre>";
