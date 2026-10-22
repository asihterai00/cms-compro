<?php
// Render index.php buffer and extract OEM ticker section
$_GET['lang'] = 'en';
ob_start();
require __DIR__ . '/../index.php';
$html = ob_get_clean();

// Find OEM ticker section
if (preg_match('/<section class="oem-ticker-section[^>]*>(.*?)<\/section>/s', $html, $m)) {
    echo "OEM Ticker Section Found (" . strlen($m[1]) . " bytes):\n";
    // Check if SVG images are referenced
    preg_match_all('/<img[^>]+src="([^">]+)"[^>]*>/i', $m[1], $imgs);
    echo "Images found in ticker: " . count($imgs[1]) . "\n";
    foreach (array_unique($imgs[1]) as $src) {
        echo " - $src (" . (file_exists(__DIR__ . '/../' . $src) ? 'EXISTS' : 'NOT FOUND') . ")\n";
    }

    // Check brand names found
    preg_match_all('/<div class="oem-brand-name"[^>]*>(.*?)<\/div>/s', $m[1], $names);
    echo "Brand names found: " . count($names[1]) . "\n";
    foreach (array_unique(array_map('trim', $names[1])) as $nm) {
        echo " - $nm\n";
    }

    // Check stats bar inside
    if (strpos($m[1], 'stats-bar') !== false) {
        echo "Stats-bar present inside with clean separation: YES\n";
    }
} else {
    echo "OEM Ticker Section NOT found!\n";
}
