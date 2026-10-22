<?php
require_once __DIR__ . '/../config/database.php';
global $conn;

$orderMap = [
    'TOYOTA' => 1,
    'HONDA' => 2,
    'NISSAN' => 3,
    'DAIHATSU' => 4,
    'MITSUBISHI MOTORS' => 5,
    'SUZUKI' => 6,
    'SUBARU' => 7,
    'MAZDA' => 8,
    'ISUZU' => 9,
    'HYUNDAI' => 10,
];

foreach ($orderMap as $title => $ord) {
    $conn->query("UPDATE site_section_items SET sort_order = $ord WHERE section_key = 'oem_trust' AND title = '$title'");
}
echo "Sort order normalized.\n";
