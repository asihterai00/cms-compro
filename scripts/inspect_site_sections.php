<?php
require_once __DIR__ . '/../config/database.php';

echo "=== COUNT OF SITE_SECTIONS ===" . PHP_EOL;
$r1 = $conn->query("SELECT COUNT(*) FROM site_sections")->fetch_row()[0];
echo "site_sections count: $r1" . PHP_EOL;

echo "=== COUNT OF SITE_SECTION_ITEMS ===" . PHP_EOL;
$r2 = $conn->query("SELECT COUNT(*) FROM site_section_items")->fetch_row()[0];
echo "site_section_items count: $r2" . PHP_EOL;

echo "=== SECTION ITEMS BY SECTION_KEY ===" . PHP_EOL;
$res = $conn->query("SELECT section_key, COUNT(*) as cnt FROM site_section_items GROUP BY section_key");
while ($r = $res->fetch_assoc()) {
    echo "Key: " . str_pad($r['section_key'], 25) . " | Count: " . $r['cnt'] . PHP_EOL;
}
