<?php
require_once __DIR__ . '/../config/database.php';
global $conn;

$oemConfig = [
    'TOYOTA' => [
        'title' => 'TOYOTA',
        'subtitle' => 'Global Tier-1 Partner',
        'image' => 'assets/img/oem/toyota.svg',
        'extra' => [
            'brand_color' => '#EB0A1E',
            'brand_bg' => 'rgba(235, 10, 30, 0.05)',
            'font_weight' => '800',
            'letter_spacing' => '0.12em',
            'font_family' => "'Inter', 'Helvetica Neue', Arial, sans-serif",
            'badge_text' => 'Tier-1 Global'
        ]
    ],
    'HONDA' => [
        'title' => 'HONDA',
        'subtitle' => 'Official OEM Alliance',
        'image' => 'assets/img/oem/honda.svg',
        'extra' => [
            'brand_color' => '#E40521',
            'brand_bg' => 'rgba(228, 5, 33, 0.05)',
            'font_weight' => '900',
            'letter_spacing' => '0.08em',
            'font_family' => "'Inter', 'Arial Black', sans-serif",
            'badge_text' => 'OEM Alliance'
        ]
    ],
    'NISSAN' => [
        'title' => 'NISSAN',
        'subtitle' => 'Strategic Platform Supplier',
        'image' => 'assets/img/oem/nissan.svg',
        'extra' => [
            'brand_color' => '#C3002F',
            'brand_bg' => 'rgba(195, 0, 47, 0.05)',
            'font_weight' => '700',
            'letter_spacing' => '0.18em',
            'font_family' => "'Inter', 'Segoe UI', sans-serif",
            'badge_text' => 'Platform Partner'
        ]
    ],
    'DAIHATSU' => [
        'title' => 'DAIHATSU',
        'subtitle' => 'Direct Assembly Partner',
        'image' => 'assets/img/oem/daihatsu.svg',
        'extra' => [
            'brand_color' => '#D52B1E',
            'brand_bg' => 'rgba(213, 43, 30, 0.05)',
            'font_weight' => '800',
            'letter_spacing' => '0.10em',
            'font_family' => "'Inter', 'Helvetica Neue', sans-serif",
            'badge_text' => 'Direct Assembly'
        ]
    ],
    'MITSUBISHI' => [
        'title' => 'MITSUBISHI MOTORS',
        'subtitle' => 'Precision Component Supplier',
        'image' => 'assets/img/oem/mitsubishi.svg',
        'extra' => [
            'brand_color' => '#E60012',
            'brand_bg' => 'rgba(230, 0, 18, 0.05)',
            'font_weight' => '800',
            'letter_spacing' => '0.08em',
            'font_family' => "'Inter', Arial, sans-serif",
            'badge_text' => 'Tier-1 Supplier'
        ]
    ],
    'SUZUKI' => [
        'title' => 'SUZUKI',
        'subtitle' => 'High-Volume Line Supplier',
        'image' => 'assets/img/oem/suzuki.svg',
        'extra' => [
            'brand_color' => '#003882',
            'brand_accent' => '#E60012',
            'brand_bg' => 'rgba(0, 56, 130, 0.05)',
            'font_weight' => '900',
            'letter_spacing' => '0.06em',
            'font_family' => "'Inter', sans-serif",
            'badge_text' => 'Volume Supplier'
        ]
    ],
    'SUBARU' => [
        'title' => 'SUBARU',
        'subtitle' => 'Safety Systems Partner',
        'image' => 'assets/img/oem/subaru.svg',
        'extra' => [
            'brand_color' => '#013C74',
            'brand_bg' => 'rgba(1, 60, 116, 0.05)',
            'font_weight' => '800',
            'font_style' => 'italic',
            'letter_spacing' => '0.10em',
            'font_family' => "'Inter', 'Helvetica Neue', sans-serif",
            'badge_text' => 'Safety Partner'
        ]
    ],
    'MAZDA' => [
        'title' => 'MAZDA',
        'subtitle' => 'Premium Interior Component Supplier',
        'image' => 'assets/img/oem/mazda.svg',
        'extra' => [
            'brand_color' => '#910000',
            'brand_bg' => 'rgba(145, 0, 0, 0.05)',
            'font_weight' => '700',
            'letter_spacing' => '0.14em',
            'font_family' => "'Inter', sans-serif",
            'badge_text' => 'Premium Tier-1'
        ]
    ],
    'ISUZU' => [
        'title' => 'ISUZU',
        'subtitle' => 'Commercial Vehicle Supplier',
        'image' => 'assets/img/oem/isuzu.svg',
        'extra' => [
            'brand_color' => '#EC1C24',
            'brand_bg' => 'rgba(236, 28, 36, 0.05)',
            'font_weight' => '900',
            'letter_spacing' => '0.08em',
            'font_family' => "'Inter', sans-serif",
            'badge_text' => 'Commercial Tier-1'
        ]
    ],
    'HYUNDAI' => [
        'title' => 'HYUNDAI',
        'subtitle' => 'Global Platform Partner',
        'image' => 'assets/img/oem/hyundai.svg',
        'extra' => [
            'brand_color' => '#002C6C',
            'brand_bg' => 'rgba(0, 44, 108, 0.05)',
            'font_weight' => '800',
            'letter_spacing' => '0.10em',
            'font_family' => "'Inter', sans-serif",
            'badge_text' => 'Global Tier-1'
        ]
    ]
];

// Update existing items
$stmt = $conn->prepare("UPDATE site_section_items SET title = ?, subtitle = ?, image = ?, extra_data = ? WHERE id = ?");

$select = $conn->query("SELECT id, title FROM site_section_items WHERE section_key = 'oem_trust'");
$existing = [];
while ($row = $select->fetch_assoc()) {
    $existing[$row['id']] = $row['title'];
}

foreach ($existing as $id => $title) {
    // Match by key or title prefix
    $keyMatch = null;
    foreach ($oemConfig as $key => $data) {
        if (stripos($title, $key) !== false || stripos($key, $title) !== false) {
            $keyMatch = $key;
            break;
        }
    }
    if ($keyMatch) {
        $c = $oemConfig[$keyMatch];
        $extraJson = json_encode($c['extra'], JSON_UNESCAPED_SLASHES);
        $stmt->bind_param('ssssi', $c['title'], $c['subtitle'], $c['image'], $extraJson, $id);
        $stmt->execute();
        echo "Updated item #$id: {$c['title']}\n";
        unset($oemConfig[$keyMatch]);
    }
}

// Any remaining items in $oemConfig (like ISUZU and HYUNDAI), add them as new items
$insert = $conn->prepare("INSERT INTO site_section_items (section_key, title, subtitle, image, extra_data, is_active, sort_order) VALUES ('oem_trust', ?, ?, ?, ?, 1, ?)");
$order = 9;
foreach ($oemConfig as $k => $c) {
    $extraJson = json_encode($c['extra'], JSON_UNESCAPED_SLASHES);
    $insert->bind_param('ssssi', $c['title'], $c['subtitle'], $c['image'], $extraJson, $order);
    $insert->execute();
    echo "Inserted new OEM item: {$c['title']}\n";
    $order++;
}

echo "OEM data update complete.\n";
