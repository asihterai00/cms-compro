<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT id, item_key, title, subtitle, image, extra_data FROM site_section_items WHERE section_key = 'oem_trust' ORDER BY sort_order ASC, id ASC");
while ($r = $res->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | Key: " . $r['item_key'] . " | Title: " . $r['title'] . " | Image: " . $r['image'] . " | Extra: " . $r['extra_data'] . PHP_EOL;
}
