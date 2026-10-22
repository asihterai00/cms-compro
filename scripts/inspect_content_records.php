<?php
require_once __DIR__ . '/../config/database.php';

function dumpTable($conn, $table, $fields) {
    echo "=== $table ===\n";
    $fieldStr = implode(', ', $fields);
    $res = $conn->query("SELECT $fieldStr FROM `$table`");
    while ($row = $res->fetch_assoc()) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
    }
    echo "\n";
}

dumpTable($conn, 'products', ['id', 'name', 'description']);
dumpTable($conn, 'news', ['id', 'title', 'published_at']);
dumpTable($conn, 'company_profile', ['id', 'tagline', 'short_description']);
dumpTable($conn, 'page_banners', ['id', 'page_key', 'page_title', 'subtitle']);
dumpTable($conn, 'careers', ['id', 'position', 'department']);
dumpTable($conn, 'certifications', ['id', 'name', 'category']);
dumpTable($conn, 'facility_machinery', ['id', 'model_brand', 'tonnage', 'application']);
