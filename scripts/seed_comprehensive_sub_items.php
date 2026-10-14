<?php
require_once __DIR__ . '/../config/database.php';

$newItems = [
    // 1. About Core Values
    [
        'section_key' => 'about_values',
        'item_key' => 'monozukuri',
        'title' => 'Monozukuri (Craftsmanship)',
        'subtitle' => 'Filosofi keunggulan manufaktur Jepang',
        'icon' => 'bi-hammer',
        'sort_order' => 10
    ],
    [
        'section_key' => 'about_values',
        'item_key' => 'safety_first',
        'title' => 'Safety First (Anzen Daiichi)',
        'subtitle' => 'Keselamatan tanpa kompromi pada setiap proses',
        'icon' => 'bi-shield-fill-check',
        'sort_order' => 20
    ],
    [
        'section_key' => 'about_values',
        'item_key' => 'kaizen',
        'title' => 'Continuous Kaizen',
        'subtitle' => 'Perbaikan tiada henti secara konsisten',
        'icon' => 'bi-arrow-repeat',
        'sort_order' => 30
    ],
    [
        'section_key' => 'about_values',
        'item_key' => 'integrity',
        'title' => 'Integrity & Harmony',
        'subtitle' => 'Integritas tinggi dan keharmonisan tim kerja',
        'icon' => 'bi-people-fill',
        'sort_order' => 40
    ],

    // 2. About Datasheet Table Rows
    [
        'section_key' => 'about_datasheet',
        'title' => 'Nama Perusahaan',
        'content' => 'PT Nihon Plast Indonesia',
        'sort_order' => 10
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Kepemilikan Saham',
        'content' => 'Nihon Plast Co., Ltd. (Jepang) - Tier-1 Automotive Group',
        'sort_order' => 20
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Lokasi Pabrik',
        'content' => 'Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang, Jawa Barat',
        'sort_order' => 30
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Luas Area Pabrik',
        'content' => 'Total Lahan: 28.500 m² | Bangunan Produksi: 18.200 m²',
        'sort_order' => 40
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Lini Produk Utama',
        'content' => 'Steering Wheel Systems, Airbag Modules, Cockpit & Exterior Trims',
        'sort_order' => 50
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Sertifikasi Mutu',
        'content' => 'IATF 16949:2016, ISO 14001:2015, SMK3 / ISO 45001',
        'extra_data' => json_encode(['badges' => ['IATF 16949:2016', 'ISO 14001:2015', 'SMK3 / ISO 45001']]),
        'sort_order' => 60
    ],
    [
        'section_key' => 'about_datasheet',
        'title' => 'Mitra Pelanggan OEM',
        'content' => 'Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda',
        'sort_order' => 70
    ],

    // 3. Facility Plant Overview Stats
    [
        'section_key' => 'facility_plant_stats',
        'title' => '28.500 m²',
        'subtitle' => 'Total Luas Lahan Pabrik',
        'icon' => 'bi-aspect-ratio',
        'sort_order' => 10
    ],
    [
        'section_key' => 'facility_plant_stats',
        'title' => '18.200 m²',
        'subtitle' => 'Luas Bangunan Produksi',
        'icon' => 'bi-building-gear',
        'sort_order' => 20
    ],
    [
        'section_key' => 'facility_plant_stats',
        'title' => '120.000 / Bln',
        'subtitle' => 'Kapasitas Steering Wheel',
        'icon' => 'bi-disc',
        'sort_order' => 30
    ],
    [
        'section_key' => 'facility_plant_stats',
        'title' => '95.000 / Bln',
        'subtitle' => 'Kapasitas Modul Airbag',
        'icon' => 'bi-shield-shaded',
        'sort_order' => 40
    ],

    // 4. Facility Capabilities (Cells 4 & 5 to complete all 5 cells)
    [
        'section_key' => 'facility_capabilities',
        'badge' => 'Otomasi Pengelasan',
        'title' => 'Automated Vibration & Ultrasonic Welding',
        'subtitle' => 'Pengelasan Frekuensi Tinggi Komponen Interior',
        'content' => 'Stasiun pengelasan otomatis bergetar dan ultrasonik untuk merekatkan modul saluran udara (air ducts), penutup airbag, dan konsol tengah dengan kekuatan rekat setara material cetak utuh tanpa penggunaan lem kimiawi beracun.',
        'icon' => 'bi-lightning-charge-fill',
        'extra_data' => json_encode(['Zero chemical adhesive technology', 'High-tensile joint weld verification']),
        'sort_order' => 40
    ],
    [
        'section_key' => 'facility_capabilities',
        'badge' => 'Foaming Presisi',
        'title' => 'High-Pressure Polyurethane (PU) Foaming',
        'subtitle' => 'Inti Rangka Kemudi & Komponen Peredam',
        'content' => 'Sistem injeksi busa poliuretan bertekanan tinggi pada rangka inti logam roda kemudi, menjamin daya redam getaran berkendara yang optimal dan keselamatan deselerasi tabrakan pengemudi.',
        'icon' => 'bi-shield-shaded',
        'extra_data' => json_encode(['High-density shock absorption', 'Ergonomic tactile feeling standard']),
        'sort_order' => 50
    ],

    // 5. Career Intro Feature Cards
    [
        'section_key' => 'career_intro_features',
        'title' => 'Pelatihan Terstruktur',
        'subtitle' => 'Program transfer teknologi dan pelatihan standar manufaktur Jepang.',
        'icon' => 'bi-mortarboard-fill',
        'sort_order' => 10
    ],
    [
        'section_key' => 'career_intro_features',
        'title' => 'Budaya K3 / SMK3',
        'subtitle' => 'Lingkungan kerja higienis dengan penghargaan Zero Accident.',
        'icon' => 'bi-shield-check',
        'sort_order' => 20
    ],

    // 6. Products Material Features
    [
        'section_key' => 'products_material_features',
        'title' => 'Flame Retardant',
        'subtitle' => 'Standar ketahanan api otomotif FMVSS 302',
        'icon' => 'bi-shield-check',
        'sort_order' => 10
    ],
    [
        'section_key' => 'products_material_features',
        'title' => 'UV & Weathering',
        'subtitle' => 'Anti-degradasi paparan sinar matahari tropis',
        'icon' => 'bi-sun',
        'sort_order' => 20
    ],

    // 7. Products Resin Compatibility Matrix
    [
        'section_key' => 'products_resins',
        'title' => 'Polypropylene (PP-TD / PP-GF)',
        'subtitle' => 'Door trims, cowl tops, consoles',
        'icon' => 'bi-layers',
        'sort_order' => 10
    ],
    [
        'section_key' => 'products_resins',
        'title' => 'ABS / PC-ABS Alloy',
        'subtitle' => 'Instrument panels, bezel clusters',
        'icon' => 'bi-layers',
        'sort_order' => 20
    ],
    [
        'section_key' => 'products_resins',
        'title' => 'Polyamide (PA6 / PA66-GF30)',
        'subtitle' => 'High structural & underhood parts',
        'icon' => 'bi-layers',
        'sort_order' => 30
    ],
    [
        'section_key' => 'products_resins',
        'title' => 'Polyurethane (PUR Leather/Foam)',
        'subtitle' => 'Steering wheel grips & covers',
        'icon' => 'bi-layers',
        'sort_order' => 40
    ]
];

$stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, icon, image, badge, extra_data, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

$count = 0;
foreach ($newItems as $item) {
    // Check if duplicate exists for section_key + title
    $chk = $conn->prepare("SELECT id FROM site_section_items WHERE section_key = ? AND title = ? LIMIT 1");
    $chk->bind_param("ss", $item['section_key'], $item['title']);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        $chk->close();
        continue;
    }
    $chk->close();

    $secKey = $item['section_key'];
    $itemKey = $item['item_key'] ?? null;
    $title = $item['title'];
    $subtitle = $item['subtitle'] ?? null;
    $content = $item['content'] ?? null;
    $icon = $item['icon'] ?? null;
    $image = $item['image'] ?? null;
    $badge = $item['badge'] ?? null;
    $extraData = $item['extra_data'] ?? null;
    $sortOrder = $item['sort_order'] ?? 10;

    $stmt->bind_param("sssssssssi", $secKey, $itemKey, $title, $subtitle, $content, $icon, $image, $badge, $extraData, $sortOrder);
    if ($stmt->execute()) {
        $count++;
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
}
$stmt->close();

echo "Added {$count} additional sub-items successfully!\n";
