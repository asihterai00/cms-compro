<?php
require_once __DIR__ . '/../config/database.php';

// Check if facility_preview items already exist
$check = $conn->query("SELECT COUNT(*) FROM site_section_items WHERE section_key = 'facility_preview'")->fetch_row()[0];
if ($check == 0) {
    $stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

    $items = [
        [
            'facility_preview',
            'facility_eng_mold',
            'Engineering & Mold R&D',
            'Presisi Cetak Resin Mikro',
            'Dukungan tim rekayasa teknis untuk pembuatan dan perawatan mold presisi cetak resin plastik toleransi mikro.',
            'uploads/gallery/facility_cleanroom_lab.svg',
            1
        ],
        [
            'facility_preview',
            'facility_high_tonnage',
            'High-Tonnage Injection Line',
            'Armada 150T - 2.500T',
            'Area 22 mesin injeksi multi-tonase (150T–2.500T) mampu memproduksi komponen interior besar dan cover eksterior.',
            'uploads/gallery/facility_injection_molding.jpg',
            2
        ],
        [
            'facility_preview',
            'facility_cleanroom_safety',
            'Safety Module Cleanroom',
            'ISO Class 8 Assembly',
            'Area perakitan steril berstandar ISO Class 8 untuk modul airbag dan switch kendali setir kemudi mobil.',
            'uploads/gallery/facility_cleanroom_lab.svg',
            3
        ]
    ];

    foreach ($items as $item) {
        $stmt->bind_param("ssssssi", $item[0], $item[1], $item[2], $item[3], $item[4], $item[5], $item[6]);
        $stmt->execute();
        echo "Inserted item: " . $item[2] . " (ID: " . $stmt->insert_id . ")" . PHP_EOL;
    }
    $stmt->close();
} else {
    echo "facility_preview items already exist ($check found)." . PHP_EOL;
}
