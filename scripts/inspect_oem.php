<?php
require_once __DIR__ . '/../config/database.php';
global $conn;

$res = $conn->query("SELECT * FROM site_section_items WHERE section_key = 'oem_trust' ORDER BY sort_order ASC, id ASC");
$items = $res->fetch_all(MYSQLI_ASSOC);

echo "Found " . count($items) . " OEM items:\n";
foreach ($items as $it) {
    echo "ID: {$it['id']} | Title: {$it['title']} | Subtitle: {$it['subtitle']} | Image: {$it['image']} | Extra: {$it['extra_data']}\n";
}
