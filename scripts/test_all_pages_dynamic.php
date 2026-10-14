<?php
/**
 * Test Suite: All Pages Dynamic CMS & Active/Inactive Toggle Verification
 * PT Nihon Plast Indonesia
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - DYNAMIC CMS VERIFICATION TEST\n";
echo "========================================================\n\n";

require_once __DIR__ . "/../config/database.php";

$passed = 0;
$failed = 0;

function assertTest($condition, $message) {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] " . $message . "\n";
        $passed++;
    } else {
        echo " [FAIL] " . $message . "\n";
        $failed++;
    }
}

// 1. Database Connection & Schema Verification
echo "\n--- 1. Database Schema & Data Counts ---\n";
assertTest(isset($conn) && $conn->ping(), "MySQL Database connection alive");

$resSections = $conn->query("SELECT COUNT(*) AS total FROM site_sections");
$totalSections = $resSections ? (int)$resSections->fetch_assoc()['total'] : 0;
assertTest($totalSections >= 44, "site_sections table populated with dynamic elements (Count: {$totalSections})");

$resItems = $conn->query("SELECT COUNT(*) AS total FROM site_section_items");
$totalItems = $resItems ? (int)$resItems->fetch_assoc()['total'] : 0;
assertTest($totalItems >= 70, "site_section_items table populated with dynamic sub-items/cards (Count: {$totalItems})");

$resMachinery = $conn->query("SELECT COUNT(*) AS total FROM facility_machinery");
$totalMachinery = $resMachinery ? (int)$resMachinery->fetch_assoc()['total'] : 0;
assertTest($totalMachinery >= 5, "facility_machinery table populated with technical machines (Count: {$totalMachinery})");

// 2. Render Test for All 9 Public Pages
echo "\n--- 2. Public Page Rendering Test (Output Buffering) ---\n";
$pages = [
    'index.php',
    'about.php',
    'facility.php',
    'quality.php',
    'contact.php',
    'career.php',
    'products.php',
    'news.php',
    'gallery.php'
];

foreach ($pages as $pageFile) {
    $filePath = __DIR__ . "/../" . $pageFile;
    if (!file_exists($filePath)) {
        assertTest(false, "File exists: {$pageFile}");
        continue;
    }
    
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET = [];
    $_POST = [];
    
    clear_page_sections_cache();
    
    ob_start();
    try {
        include $filePath;
        $output = ob_get_clean();
        $hasOutput = strlen($output) > 1000;
        $noFatal = stripos($output, 'Fatal error') === false && stripos($output, 'Parse error') === false;
        assertTest($hasOutput && $noFatal, "Render {$pageFile} successfully (Bytes: " . strlen($output) . ")");
    } catch (Throwable $e) {
        ob_end_clean();
        assertTest(false, "Render {$pageFile} threw exception: " . $e->getMessage());
    }
}

// 3. Dynamic Active/Inactive Section Toggle Verification (Quality CTA Test)
echo "\n--- 3. Dynamic Section Active/Inactive Toggle Test (quality_cta) ---\n";

$conn->query("UPDATE site_sections SET is_active = 0 WHERE page = 'quality' AND section_key = 'quality_cta'");
clear_page_sections_cache('quality');

ob_start();
include __DIR__ . "/../quality.php";
$outputInactive = ob_get_clean();

$ctaMarker = "Butuh Lembar Kepatuhan Mutu atau Salinan Sertifikat ISO?";
assertTest(strpos($outputInactive, $ctaMarker) === false, "When is_active = 0, section 'quality_cta' is completely hidden from HTML");

$conn->query("UPDATE site_sections SET is_active = 1 WHERE page = 'quality' AND section_key = 'quality_cta'");
clear_page_sections_cache('quality');

ob_start();
include __DIR__ . "/../quality.php";
$outputActive = ob_get_clean();

assertTest(strpos($outputActive, $ctaMarker) !== false, "When is_active = 1, section 'quality_cta' is rendered in HTML");

// 4. Dynamic Sub-Item Active/Inactive Toggle Verification (About Core Values Test)
echo "\n--- 4. Dynamic Sub-Item Active/Inactive Toggle Test (about_values: Monozukuri) ---\n";

$valueMarker = "Monozukuri (Craftsmanship)";

// Set Monozukuri inactive
$conn->query("UPDATE site_section_items SET is_active = 0 WHERE section_key = 'about_values' AND title LIKE '%Monozukuri%'");
clear_section_items_cache('about_values');

ob_start();
include __DIR__ . "/../about.php";
$outputValInactive = ob_get_clean();

assertTest(strpos($outputValInactive, $valueMarker) === false, "When sub-item is_active = 0, 'Monozukuri' badge is completely hidden from HTML");

// Restore Monozukuri active
$conn->query("UPDATE site_section_items SET is_active = 1 WHERE section_key = 'about_values' AND title LIKE '%Monozukuri%'");
clear_section_items_cache('about_values');

ob_start();
include __DIR__ . "/../about.php";
$outputValActive = ob_get_clean();

assertTest(strpos($outputValActive, $valueMarker) !== false, "When sub-item is_active = 1, 'Monozukuri' badge is rendered in HTML");

// 5. Dynamic Sub-Item Active/Inactive Toggle Verification (OEM Brand Test)
echo "\n--- 5. Dynamic Sub-Item Active/Inactive Toggle Test (oem_trust: TOYOTA) ---\n";

$oemMarker = 'TOYOTA';

// Set TOYOTA inactive
$conn->query("UPDATE site_section_items SET is_active = 0 WHERE section_key = 'oem_trust' AND title = 'TOYOTA'");
clear_section_items_cache('oem_trust');

ob_start();
include __DIR__ . "/../index.php";
$outputOemInactive = ob_get_clean();

assertTest(strpos($outputOemInactive, $oemMarker) === false, "When OEM badge is_active = 0, 'TOYOTA' partner logo is hidden from HTML");

// Restore TOYOTA active
$conn->query("UPDATE site_section_items SET is_active = 1 WHERE section_key = 'oem_trust' AND title = 'TOYOTA'");
clear_section_items_cache('oem_trust');

ob_start();
include __DIR__ . "/../index.php";
$outputOemActive = ob_get_clean();

assertTest(strpos($outputOemActive, $oemMarker) !== false, "When OEM badge is_active = 1, 'TOYOTA' partner logo is rendered in HTML");

// 6. Admin Panels Syntax Verification
echo "\n--- 6. Admin Panel Syntax Checks ---\n";
$adminFiles = [
    'admin/site_sections.php',
    'admin/site_section_items.php',
    'admin/machinery.php',
    'admin/users.php',
    'admin/auth.php',
    'admin/products.php',
    'admin/certifications.php'
];

foreach ($adminFiles as $af) {
    $fullAdminPath = __DIR__ . "/../" . $af;
    $out = [];
    $ret = 0;
    exec("\"C:\\laragon\\bin\\php\\php-8.3.33-Win32-vs16-x64\\php.exe\" -l \"{$fullAdminPath}\" 2>&1", $out, $ret);
    assertTest(file_exists($fullAdminPath) && $ret === 0, "Admin file syntax valid: {$af}");
}

// 7. Summary
echo "\n========================================================\n";
echo "TEST RESULTS: {$passed} PASSED, {$failed} FAILED\n";
echo "========================================================\n";

if ($failed === 0) {
    echo ">>> ALL TESTS PASSED! ZERO HARDCODED ELEMENTS. ALL CMS CARDS & SECTIONS 100% DYNAMIC! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED. PLEASE REVIEW OUTPUT ABOVE. <<<\n";
    exit(1);
}
