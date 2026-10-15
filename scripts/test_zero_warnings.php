<?php
/**
 * Test zero PHP warnings, deprecations, or notices across all public pages
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$pages = [
    'index.php',
    'about.php',
    'products.php',
    'product-detail.php?id=1',
    'facility.php',
    'quality.php',
    'career.php',
    'news.php',
    'news-detail.php?id=1',
    'contact.php',
    'gallery.php',
    'gallery-detail.php?id=1'
];

$allClean = true;

foreach ($pages as $pageEntry) {
    $parts = explode('?', $pageEntry);
    $pageFile = $parts[0];
    $queryString = $parts[1] ?? '';
    
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['PHP_SELF'] = '/cms-compro/' . $pageFile;
    $_SERVER['SCRIPT_NAME'] = '/cms-compro/' . $pageFile;
    $_GET = [];
    if ($queryString !== '') {
        parse_str($queryString, $_GET);
    }
    
    ob_start();
    try {
        include __DIR__ . '/../' . $pageFile;
        $output = ob_get_clean();
        
        $hasNotice = preg_match('/(<b>)?(Notice|Warning|Deprecated|Fatal error)(<\/b>)?:/i', $output, $matches);
        if ($hasNotice) {
            echo "[FAIL] $pageEntry has PHP errors/warnings:\n";
            // Find lines with Warning or Deprecated
            $lines = explode("\n", $output);
            foreach ($lines as $line) {
                if (stripos($line, 'Warning') !== false || stripos($line, 'Deprecated') !== false || stripos($line, 'Notice') !== false || stripos($line, 'Fatal') !== false) {
                    echo "   >> " . trim(strip_tags($line)) . "\n";
                }
            }
            $allClean = false;
        } else {
            echo "[PASS] $pageEntry (Bytes: " . strlen($output) . ", 0 Warnings/Notices)\n";
        }
    } catch (Throwable $e) {
        ob_end_clean();
        echo "[ERROR] $pageEntry threw exception: " . $e->getMessage() . "\n";
        $allClean = false;
    }
}

if ($allClean) {
    echo "\n>>> 100% CLEAN: ZERO WARNINGS, NOTICES, OR DEPRECATIONS ACROSS ALL PUBLIC PAGES! <<<\n";
} else {
    echo "\n>>> WARNINGS DETECTED! <<<\n";
}
