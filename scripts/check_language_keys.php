<?php
$dir = __DIR__ . '/..';
$idDict = require $dir . '/languages/id.php';

$files = glob($dir . '/*.php');
$usedKeys = [];

foreach ($files as $f) {
    $content = file_get_contents($f);
    if (preg_match_all("/__\(\s*['\"]([^'\"]+)['\"]/i", $content, $m)) {
        foreach ($m[1] as $k) {
            $usedKeys[$k][] = basename($f);
        }
    }
}

$includesFiles = glob($dir . '/includes/*.php');
foreach ($includesFiles as $f) {
    $content = file_get_contents($f);
    if (preg_match_all("/__\(\s*['\"]([^'\"]+)['\"]/i", $content, $m)) {
        foreach ($m[1] as $k) {
            $usedKeys[$k][] = 'includes/' . basename($f);
        }
    }
}

echo "Total unique translation keys used in codebase: " . count($usedKeys) . "\n";
$missingFromDict = [];
foreach ($usedKeys as $k => $locations) {
    if (!isset($idDict[$k])) {
        $missingFromDict[$k] = $locations;
    }
}

echo "Keys missing from dictionary: " . count($missingFromDict) . "\n";
foreach ($missingFromDict as $k => $locs) {
    echo " - '$k' used in: " . implode(', ', array_unique($locs)) . "\n";
}
