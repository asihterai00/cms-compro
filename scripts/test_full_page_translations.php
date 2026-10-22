<?php
/**
 * PT Nihon Plast Indonesia - Comprehensive Verification of Full Page Translations
 * Tests that every section, card, header, and footer dynamically switches in all 5 languages.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/translation_helpers.php';
require_once __DIR__ . '/../includes/site_helpers.php';

$testLangs = [
    'id' => [
        'nav_home' => 'Beranda',
        'products_title' => 'Sistem Komponen Otomotif',
        'facility_title' => 'Fasilitas Pabrik Presisi di Cikampek, Karawang',
        'cert_title' => 'Standar Internasional & Regulasi Indonesia',
        'news_title' => 'Berita & Informasi Perusahaan',
        'stat_1' => 'IATF 16949 / ISO 14001',
        'facility_card_1' => 'Engineering & Mold R&D',
        'hero_slide_1_content' => 'Mitra manufaktur terpercaya bagi OEM otomotif dunia'
    ],
    'en' => [
        'nav_home' => 'Home',
        'products_title' => 'Automotive Component Systems',
        'facility_title' => 'Precision Manufacturing Facility in Cikampek, Karawang',
        'cert_title' => 'International Standards & Indonesian Regulatory Compliance',
        'news_title' => 'Company News & Corporate Announcements',
        'stat_1' => 'IATF 16949 / ISO 14001',
        'facility_card_1' => 'Engineering & Mold R&D',
        'hero_slide_1_content' => 'Trusted manufacturing partner to world OEMs'
    ],
    'ja' => [
        'nav_home' => 'ホーム',
        'products_title' => '自動車部品システム・製品ラインナップ',
        'facility_title' => 'チカンペック精密製造工場（西ジャワ州カラワン）',
        'cert_title' => '品質マネジメント規格認証・現地法規制への適合',
        'news_title' => '企業ニュース・インフォメーション',
        'stat_1' => 'IATF 16949 / ISO 14001',
        'facility_card_1' => '金型開発・エンジニアリングR&D',
        'hero_slide_1_content' => '150Tから2,500Tまで対応する22台のサーボ射出成形機'
    ],
    'zh' => [
        'nav_home' => '首页',
        'products_title' => '汽车关键零部件系统产品阵容',
        'facility_title' => '西爪哇卡拉旺芝坎佩精密制造基地',
        'cert_title' => '国际汽车质量标准与印尼国家法规合规体系',
        'news_title' => '企业新闻与官方资讯公告',
        'stat_1' => 'IATF 16949 / ISO 14001',
        'facility_card_1' => '工程模具研发与精密维护中心',
        'hero_slide_1_content' => '拥有22台150T至2500T伺服注塑机集群'
    ],
    'ko' => [
        'nav_home' => '홈',
        'products_title' => '자동차 부품 시스템 및 제품 라인업',
        'facility_title' => '치캄펙 카라왕 고정밀 제조 시설',
        'cert_title' => '국제 품질 인증 및 인도네시아 정부 규제 준수',
        'news_title' => '기업 뉴스 및 공식 공지사항',
        'stat_1' => 'IATF 16949 / ISO 14001',
        'facility_card_1' => '엔지니어링 및 금형 R&D 센터',
        'hero_slide_1_content' => '150T~2500T 서보 사출기 22대'
    ]
];

echo "========================================================" . PHP_EOL;
echo "PT NIHON PLAST INDONESIA - FULL PAGE TRANSLATION TEST" . PHP_EOL;
echo "========================================================" . PHP_EOL;

$allPassed = true;

foreach ($testLangs as $langCode => $expected) {
    echo PHP_EOL . "--- Testing Language: [$langCode] ---" . PHP_EOL;
    $_GET['lang'] = $langCode;
    set_lang($langCode);

    // Clear caches
    clear_page_sections_cache();
    get_section_items(null, true, true);

    ob_start();
    include __DIR__ . '/../index.php';
    $output = html_entity_decode(ob_get_clean(), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    foreach ($expected as $testKey => $expectedSubstring) {
        if (strpos($output, $expectedSubstring) !== false) {
            echo " [PASS] $testKey found: '$expectedSubstring'" . PHP_EOL;
        } else {
            echo " [FAIL] $testKey NOT found: '$expectedSubstring'" . PHP_EOL;
            $allPassed = false;
        }
    }
}

echo PHP_EOL . "========================================================" . PHP_EOL;
if ($allPassed) {
    echo ">>> ALL FULL-PAGE DYNAMIC TRANSLATION TESTS PASSED 100%! <<<" . PHP_EOL;
} else {
    echo ">>> SOME TRANSLATION TESTS FAILED! PLEASE REVIEW OUTPUT. <<<" . PHP_EOL;
}
echo "========================================================" . PHP_EOL;
