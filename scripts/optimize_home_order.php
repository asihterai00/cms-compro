<?php
/**
 * Optimize Homepage Sections Order & Active/Inactive Defaults
 * PT Nihon Plast Indonesia
 *
 * Requirements:
 * 1. News Section positioned immediately at #2 on scroll (right below Hero & Stats)
 * 2. Deactivate heavy/redundant sections (gallery_preview, quality_pillars, esg_sustainability) on Home by default
 *    to make initial page load instantaneous and lightweight
 * 3. Set standard corporate Tier-1 sequence
 */

require_once __DIR__ . "/../config/database.php";

echo "Optimizing site_sections for page = 'home'...\n";

$homeOrder = [
    'hero' => [
        'sort_order' => 10,
        'is_active' => 1,
        'section_name' => 'Hero Banner Utama'
    ],
    'stats' => [
        'sort_order' => 20,
        'is_active' => 1,
        'section_name' => 'Statistik Ringkas (Stats Bar)'
    ],
    'news_preview' => [
        'sort_order' => 30,
        'is_active' => 1,
        'section_name' => 'Berita & Informasi Perusahaan (No. 2 Saat Scroll)'
    ],
    'products_grid' => [
        'sort_order' => 40,
        'is_active' => 1,
        'section_name' => 'Katalog 4 Pilar Produk Utama'
    ],
    'vehicle_explorer' => [
        'sort_order' => 50,
        'is_active' => 1,
        'section_name' => 'Interactive Vehicle Zone Explorer'
    ],
    'facility_preview' => [
        'sort_order' => 60,
        'is_active' => 1,
        'section_name' => 'Highlight Fasilitas Pabrik Cikampek'
    ],
    'certifications_grid' => [
        'sort_order' => 70,
        'is_active' => 1,
        'section_name' => 'Sertifikasi ISO & Standar Regulasi RI'
    ],
    'about_intro' => [
        'sort_order' => 80,
        'is_active' => 1,
        'section_name' => 'Profil Singkat Perusahaan (About)'
    ],
    'oem_trust' => [
        'sort_order' => 90,
        'is_active' => 1,
        'section_name' => 'OEM Trust & Partner Bar'
    ],
    'contact_cta' => [
        'sort_order' => 100,
        'is_active' => 1,
        'section_name' => 'Call to Action (B2B RFQ Inquiry)'
    ],
    'quality_pillars' => [
        'sort_order' => 110,
        'is_active' => 0, // Nonaktif di Home agar tidak berat & tidak duplikasi (ada di quality.php)
        'section_name' => 'Filosofi Monozukuri & Quality Assurance (Opsional Home)'
    ],
    'esg_sustainability' => [
        'sort_order' => 120,
        'is_active' => 0, // Nonaktif di Home agar hemat DOM (ada di about.php & quality.php)
        'section_name' => 'Green Monozukuri & Keberlanjutan (Opsional Home)'
    ],
    'gallery_preview' => [
        'sort_order' => 130,
        'is_active' => 0, // Nonaktif di Home agar hemat 5-10MB gambar (tersedia lengkap di gallery.php)
        'section_name' => 'Galeri Foto Kegiatan & Fasilitas (Opsional Home)'
    ]
];

$stmt = $conn->prepare("UPDATE site_sections SET sort_order = ?, is_active = ?, section_name = ? WHERE page = 'home' AND section_key = ?");

foreach ($homeOrder as $key => $config) {
    $stmt->bind_param("iiss", $config['sort_order'], $config['is_active'], $config['section_name'], $key);
    $stmt->execute();
    echo "Updated [{$key}] -> Sort Order: {$config['sort_order']}, Active: {$config['is_active']}\n";
}

$stmt->close();
echo "\nHomepage sections optimized successfully!\n";
