<?php

require_once __DIR__ . '/db.php';

echo "DB OK";

$stmt = $pdo->query("SELECT id, slug FROM cases ORDER BY id DESC");

echo "<pre>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . " - " . $row['slug'] . "\n";
}

echo "</pre>";
