<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT id, item_key, title, subtitle, content, extra_data FROM site_section_items WHERE section_key = 'hero_slides'");
while ($r = $res->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | Key: " . $r['item_key'] . PHP_EOL;
    echo "Title: " . $r['title'] . PHP_EOL;
    echo "Subtitle: " . $r['subtitle'] . PHP_EOL;
    echo "Content: " . $r['content'] . PHP_EOL;
    echo "Extra: " . $r['extra_data'] . PHP_EOL;
    echo "----------------------------------------" . PHP_EOL;
}
