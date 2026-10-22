<?php
/**
 * Test Dynamic Content Translation on Public Pages
 * Verifies that products, news, banners, etc. dynamically change when lang parameter changes.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/translation_helpers.php';

$langs = [
    'id' => [
        'product1' => 'Steering Wheel Systems (Sistem Roda Kemudi)',
        'banner_about' => 'About Company'
    ],
    'en' => [
        'product1' => 'Steering Wheel Systems',
        'banner_about' => 'About Our Company'
    ],
    'ja' => [
        'product1' => 'ステアリングホイールシステム',
        'banner_about' => '会社案内・企業理念'
    ],
    'zh' => [
        'product1' => '方向盘安全集成系统',
        'banner_about' => '关于我们与企业传承'
    ],
    'ko' => [
        'product1' => '스티어링 휠 안전 시스템',
        'banner_about' => '회사 소개 및 기업 이념'
    ]
];

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - DYNAMIC TRANSLATION TEST\n";
echo "========================================================\n\n";

$allPassed = true;

foreach ($langs as $lCode => $expected) {
    echo "--- Testing Language: [{$lCode}] ---\n";
    
    // 1. Direct translation helper test
    $pRow = $conn->query("SELECT * FROM products WHERE id = 1")->fetch_assoc();
    $translatedP = translate_entity_row('product', $pRow, $lCode);
    $actualPName = $translatedP['name'];
    
    $bRow = $conn->query("SELECT * FROM page_banners WHERE page_key = 'about'")->fetch_assoc();
    $translatedB = translate_entity_row('page_banner', $bRow, $lCode);
    $actualBTitle = $translatedB['page_title'];

    echo " Product 1 Name: '{$actualPName}'\n";
    echo " About Banner Title: '{$actualBTitle}'\n";

    if ($actualPName === $expected['product1']) {
        echo " [PASS] Product name correctly matches expected for [{$lCode}]\n";
    } else {
        echo " [FAIL] Expected '{$expected['product1']}', got '{$actualPName}'\n";
        $allPassed = false;
    }

    if ($actualBTitle === $expected['banner_about']) {
        echo " [PASS] Banner title correctly matches expected for [{$lCode}]\n";
    } else {
        echo " [FAIL] Expected '{$expected['banner_about']}', got '{$actualBTitle}'\n";
        $allPassed = false;
    }
    echo "\n";
}

if ($allPassed) {
    echo ">>> ALL DYNAMIC TRANSLATION TESTS PASSED! CONTENT RESPONDS DYNAMICALLY TO ALL 5 LANGUAGES! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED <<<\n";
    exit(1);
}
