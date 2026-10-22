<?php
require_once __DIR__ . '/../config/database.php';

echo "=== ALL SITE_SECTIONS ===" . PHP_EOL;
$res = $conn->query("SELECT id, page, section_key, title, subtitle, badge_text, button_text FROM site_sections ORDER BY id ASC");
while ($r = $res->fetch_assoc()) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . PHP_EOL;
}

echo PHP_EOL . "=== ALL SITE_SECTION_ITEMS ===" . PHP_EOL;
$res2 = $conn->query("SELECT id, section_key, item_key, title, subtitle, content FROM site_section_items ORDER BY section_key ASC, sort_order ASC, id ASC");
while ($r = $res2->fetch_assoc()) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
