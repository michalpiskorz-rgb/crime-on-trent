<?php

require_once __DIR__ . '/../admin2/includes/db.php';

header('Content-Type: application/json; charset=utf-8');

try {

    $sql = "
        SELECT
            c.id,
            c.slug,
            c.latitude,
            c.longitude,
            c.event_date,

            c.title_pl,
            c.title_en,

            c.description_pl,
            c.description_en,

            c.location,

            cat.name_pl AS category_pl,
            cat.name_en AS category_en,
            CONCAT('assets/icons/', cat.icon_url) AS icon_url,

            s.name AS status

        FROM cases c

        LEFT JOIN categories cat
            ON c.category_id = cat.id

        LEFT JOIN statuses s
            ON c.status_id = s.id

        WHERE s.name = 'published'

        ORDER BY c.id DESC
    ";

    $stmt = $pdo->query($sql);

    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(
        $cases,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Database error'
    ]);
}
