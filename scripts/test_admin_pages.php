<?php
// Simulate logged-in admin session
session_start();
$_SESSION['admin_id'] = 1;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_role'] = 'superadmin';
$_SESSION['admin_full_name'] = 'Super Administrator';
$_SERVER['REQUEST_METHOD'] = 'GET';

$adminPages = [
    'dashboard.php',
    'site_sections.php',
    'site_section_items.php',
    'banners.php',
    'products.php',
    'news.php',
    'career.php',
    'gallery.php',
    'machinery.php',
    'certifications.php',
    'media.php',
    'messages.php',
    'translations.php',
    'company.php',
    'users.php'
];

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - ADMIN DASHBOARD SUITE TEST\n";
echo "Testing all " . count($adminPages) . " Admin Pages\n";
echo "========================================================\n";

$passCount = 0;
foreach ($adminPages as $page) {
    $path = __DIR__ . '/../admin/' . $page;
    if (!file_exists($path)) {
        echo " [FAIL] $page: File not found!\n";
        continue;
    }

    ob_start();
    try {
        include $path;
        $output = ob_get_clean();
        $len = strlen($output);

        // Check for PHP warnings or fatal errors
        if (stripos($output, 'Fatal error') !== false || stripos($output, 'Parse error') !== false || stripos($output, 'Warning:') !== false) {
            echo " [FAIL] $page: Contains PHP Errors or Warnings!\n";
            // Print error snippet
            preg_match('/(Fatal error|Parse error|Warning:[^\n]+)/i', $output, $m);
            echo "        " . ($m[1] ?? 'Unknown error') . "\n";
        } else {
            echo " [PASS] $page ($len bytes) - Clean render\n";
            $passCount++;
        }
    } catch (\Throwable $e) {
        ob_end_clean();
        echo " [FAIL] $page: Exception: " . $e->getMessage() . "\n";
    }
}

echo "========================================================\n";
echo "SUMMARY: $passCount / " . count($adminPages) . " PASSED\n";
echo "========================================================\n";
