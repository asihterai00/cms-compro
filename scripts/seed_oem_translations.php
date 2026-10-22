<?php
require_once __DIR__ . '/../config/database.php';
global $conn;

$langs = ['id', 'en', 'ja', 'zh', 'ko'];
$items = [
    5 => [ // TOYOTA
        'title' => 'TOYOTA',
        'subtitle' => [
            'id' => 'Mitra Tier-1 Global',
            'en' => 'Global Tier-1 Partner',
            'ja' => 'グローバルTier-1パートナー',
            'zh' => '全球一级合作伙伴',
            'ko' => '글로벌 Tier-1 파트너'
        ]
    ],
    6 => [ // HONDA
        'title' => 'HONDA',
        'subtitle' => [
            'id' => 'Aliansi Resmi OEM',
            'en' => 'Official OEM Alliance',
            'ja' => '公式OEM提携パートナー',
            'zh' => '官方OEM联盟',
            'ko' => '공식 OEM 동맹'
        ]
    ],
    7 => [ // NISSAN
        'title' => 'NISSAN',
        'subtitle' => [
            'id' => 'Pemasok Platform Strategis',
            'en' => 'Strategic Platform Supplier',
            'ja' => '戦略的プラットフォームサプライヤー',
            'zh' => '战略平台供应商',
            'ko' => '전략적 플랫폼 공급업체'
        ]
    ],
    8 => [ // DAIHATSU
        'title' => 'DAIHATSU',
        'subtitle' => [
            'id' => 'Mitra Perakitan Langsung',
            'en' => 'Direct Assembly Partner',
            'ja' => 'ダイレクトアセンブリパートナー',
            'zh' => '直接总装合作伙伴',
            'ko' => '직접 조립 파트너'
        ]
    ],
    9 => [ // MITSUBISHI MOTORS
        'title' => 'MITSUBISHI MOTORS',
        'subtitle' => [
            'id' => 'Pemasok Komponen Presisi',
            'en' => 'Precision Component Supplier',
            'ja' => '精密コンポーネントサプライヤー',
            'zh' => '精密零部件供应商',
            'ko' => '정밀 부품 공급업체'
        ]
    ],
    10 => [ // SUZUKI
        'title' => 'SUZUKI',
        'subtitle' => [
            'id' => 'Pemasok Jalur Produksi Volume Tinggi',
            'en' => 'High-Volume Line Supplier',
            'ja' => '量産ラインサプライヤー',
            'zh' => '大批量产线供应商',
            'ko' => '대량 생산 라인 공급업체'
        ]
    ],
    11 => [ // SUBARU
        'title' => 'SUBARU',
        'subtitle' => [
            'id' => 'Mitra Sistem Keselamatan & Kemudi',
            'en' => 'Safety & Steering Systems Partner',
            'ja' => '安全＆ステアリングシステムパートナー',
            'zh' => '安全与转向系统合作伙伴',
            'ko' => '안전 및 스티어링 시스템 파트너'
        ]
    ],
    12 => [ // MAZDA
        'title' => 'MAZDA',
        'subtitle' => [
            'id' => 'Pemasok Komponen Interior Premium',
            'en' => 'Premium Interior Component Supplier',
            'ja' => 'プレミアム内装部品サプライヤー',
            'zh' => '高级内饰零部件供应商',
            'ko' => '프리미엄 인테리어 부품 공급업체'
        ]
    ],
    85 => [ // ISUZU
        'title' => 'ISUZU',
        'subtitle' => [
            'id' => 'Pemasok Kendaraan Niaga & Truk',
            'en' => 'Commercial Vehicle Supplier',
            'ja' => '商用車サプライヤー',
            'zh' => '商用车辆供应商',
            'ko' => '상용차 공급업체'
        ]
    ],
    86 => [ // HYUNDAI
        'title' => 'HYUNDAI',
        'subtitle' => [
            'id' => 'Mitra Platform Global',
            'en' => 'Global Platform Partner',
            'ja' => 'グローバルプラットフォームパートナー',
            'zh' => '全球平台合作伙伴',
            'ko' => '글로벌 플랫폼 파트너'
        ]
    ]
];

$stmt = $conn->prepare("INSERT INTO content_translations (entity_type, entity_id, lang_code, field_name, translation_value) 
                        VALUES ('site_section_items', ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

foreach ($items as $itemId => $data) {
    foreach ($langs as $l) {
        $titleVal = $data['title'];
        $fieldTitle = 'title';
        $stmt->bind_param('isss', $itemId, $l, $fieldTitle, $titleVal);
        $stmt->execute();

        $subVal = $data['subtitle'][$l] ?? $data['subtitle']['en'];
        $fieldSub = 'subtitle';
        $stmt->bind_param('isss', $itemId, $l, $fieldSub, $subVal);
        $stmt->execute();
    }
    echo "Seeded translations for item #$itemId ({$data['title']})\n";
}

echo "All OEM translations seeded successfully.\n";
