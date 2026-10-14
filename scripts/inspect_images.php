<?php
require_once __DIR__ . '/../config/database.php';

$tables = ['news', 'certifications', 'page_banners', 'company_profile', 'site_sections'];

foreach ($tables as $t) {
    echo "=== Table Schema: $t ===" . PHP_EOL;
    $res = $conn->query("DESC $t");
    $imgCols = [];
    while ($r = $res->fetch_assoc()) {
        echo "  " . $r['Field'] . " (" . $r['Type'] . ")" . PHP_EOL;
        if (stripos($r['Field'], 'image') !== false || stripos($r['Field'], 'photo') !== false || stripos($r['Field'], 'logo') !== false || stripos($r['Field'], 'banner') !== false) {
            $imgCols[] = $r['Field'];
        }
    }
    if (!empty($imgCols)) {
        echo "  --- DATA for " . implode(', ', $imgCols) . " ---" . PHP_EOL;
        $selectCols = array_merge(['id'], $imgCols);
        $dRes = $conn->query("SELECT " . implode(', ', $selectCols) . " FROM $t");
        if ($dRes) {
            while ($row = $dRes->fetch_assoc()) {
                echo "  " . json_encode($row, JSON_UNESCAPED_SLASHES) . PHP_EOL;
            }
        }
    }
}
