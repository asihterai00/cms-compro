<?php
require_once __DIR__ . '/../config/database.php';

$sections = [];
$res = $conn->query("SELECT id, page, section_key, title, subtitle, badge_text, button_text FROM site_sections ORDER BY id ASC");
while ($r = $res->fetch_assoc()) {
    $sections[] = $r;
}

$items = [];
$res2 = $conn->query("SELECT id, section_key, item_key, title, subtitle, content FROM site_section_items ORDER BY id ASC");
while ($r2 = $res2->fetch_assoc()) {
    $items[] = $r2;
}

file_put_contents(__DIR__ . '/sections_raw.json', json_encode($sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents(__DIR__ . '/items_raw.json', json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported " . count($sections) . " sections and " . count($items) . " items." . PHP_EOL;
