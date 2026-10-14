<?php
require_once __DIR__ . '/../config/database.php';

echo "=== MIGRATING DATABASE FOR MULTILINGUAL DYNAMIC CMS & RBAC ===\n\n";

// 1. ALTER TABLE users
$cols = $conn->query("SHOW COLUMNS FROM users");
$existingCols = [];
while ($c = $cols->fetch_assoc()) {
    $existingCols[] = $c['Field'];
}

if (!in_array('full_name', $existingCols)) {
    $conn->query("ALTER TABLE users ADD COLUMN full_name VARCHAR(150) AFTER username");
    echo "Added 'full_name' column to users.\n";
}
if (!in_array('email', $existingCols)) {
    $conn->query("ALTER TABLE users ADD COLUMN email VARCHAR(150) AFTER full_name");
    echo "Added 'email' column to users.\n";
}
if (!in_array('role', $existingCols)) {
    $conn->query("ALTER TABLE users ADD COLUMN role ENUM('superadmin', 'admin', 'editor', 'staff') DEFAULT 'admin' AFTER email");
    echo "Added 'role' column to users.\n";
}
if (!in_array('status', $existingCols)) {
    $conn->query("ALTER TABLE users ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER role");
    echo "Added 'status' column to users.\n";
}

// Ensure default user 'admin' is superadmin
$conn->query("UPDATE users SET role = 'superadmin', full_name = 'Super Administrator', status = 'active' WHERE username = 'admin'");
echo "Updated default admin account to superadmin.\n";

// 2. CREATE TABLE user_permissions
$conn->query("CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    menu_key VARCHAR(50) NOT NULL,
    can_create TINYINT(1) DEFAULT 0,
    can_read TINYINT(1) DEFAULT 1,
    can_update TINYINT(1) DEFAULT 0,
    can_delete TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_menu (user_id, menu_key),
    INDEX(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
echo "Created or verified user_permissions table.\n";

// 3. CREATE TABLE site_sections
$conn->query("CREATE TABLE IF NOT EXISTS site_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(50) NOT NULL DEFAULT 'home',
    section_key VARCHAR(50) NOT NULL,
    section_name VARCHAR(150) NOT NULL,
    title VARCHAR(255) NULL,
    subtitle TEXT NULL,
    content TEXT NULL,
    badge_text VARCHAR(100) NULL,
    icon VARCHAR(100) NULL,
    image VARCHAR(255) NULL,
    bg_image VARCHAR(255) NULL,
    button_text VARCHAR(100) NULL,
    button_link VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_page_section (page, section_key),
    INDEX(page),
    INDEX(is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
echo "Created or verified site_sections table.\n";

// 4. SEED site_sections with existing homepage sections
$defaultSections = [
    [
        'page' => 'home',
        'section_key' => 'hero',
        'section_name' => 'Hero Banner Utama',
        'title' => 'Producing Reliable Technology for Complete Peace of Mind',
        'subtitle' => 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.',
        'badge_text' => 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS',
        'icon' => 'bi-shield-check',
        'button_text' => 'Jelajahi Produk',
        'button_link' => '#products-home',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'home',
        'section_key' => 'stats',
        'section_name' => 'Statistik Ringkas (Stats Bar)',
        'title' => 'Pencapaian & Kredensial Pabrik',
        'subtitle' => 'Standar Mutu IATF 16949, 4 Pilar Produk, ISO 14001/SMK3, Fasilitas Cikampek Karawang',
        'badge_text' => 'CREDENTIALS',
        'icon' => 'bi-bar-chart-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'home',
        'section_key' => 'oem_trust',
        'section_name' => 'OEM Trust & Partner Bar',
        'title' => 'Mitra Pabrikan Otomotif Terkemuka',
        'subtitle' => 'Menyuplai langsung produsen APM Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda',
        'badge_text' => 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS',
        'icon' => 'bi-award-fill',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'home',
        'section_key' => 'about_intro',
        'section_name' => 'Profil Singkat Perusahaan (About)',
        'title' => 'PT Nihon Plast Indonesia',
        'subtitle' => 'Driving Safety & Precision Through Japanese Monozukuri',
        'badge_text' => 'ABOUT OUR ENTERPRISE',
        'icon' => 'bi-building',
        'button_text' => 'Profil Selengkapnya',
        'button_link' => 'about.php',
        'is_active' => 1,
        'sort_order' => 40
    ],
    [
        'page' => 'home',
        'section_key' => 'vehicle_explorer',
        'section_name' => 'Interactive Vehicle Zone Explorer',
        'title' => 'Peta Penerapan Komponen pada Kendaraan',
        'subtitle' => 'Jelajahi integrasi teknologi keselamatan dan komponen interior Nihon Plast pada arsitektur mobil modern.',
        'badge_text' => 'VEHICLE APPLICATION EXPLORER',
        'icon' => 'bi-car-front-fill',
        'is_active' => 1,
        'sort_order' => 50
    ],
    [
        'page' => 'home',
        'section_key' => 'products_grid',
        'section_name' => 'Katalog 4 Pilar Produk Utama',
        'title' => 'Sistem Komponen Otomotif',
        'subtitle' => 'Komponen interior resin presisi tinggi, modul keselamatan airbag, dan roda kemudi berkualitas OEM dengan standar kepatuhan ketat.',
        'badge_text' => 'PORTFOLIO KEUNGGULAN PRODUK',
        'icon' => 'bi-grid-3x3-gap-fill',
        'button_text' => 'Lihat Semua Lini Produk',
        'button_link' => 'products.php',
        'is_active' => 1,
        'sort_order' => 60
    ],
    [
        'page' => 'home',
        'section_key' => 'certifications_grid',
        'section_name' => 'Sertifikasi ISO & Standar Regulasi RI',
        'title' => 'Standar Internasional & Regulasi Indonesia',
        'subtitle' => 'PT Nihon Plast Indonesia beroperasi penuh dengan standar mutu otomotif internasional serta kepatuhan mutlak terhadap regulasi Kemenperin, KLHK, dan Kemnaker RI.',
        'badge_text' => 'ACCREDITATION & COMPLIANCE',
        'icon' => 'bi-patch-check-fill',
        'button_text' => 'Pelajari Kebijakan Mutu',
        'button_link' => 'quality.php',
        'is_active' => 1,
        'sort_order' => 70
    ],
    [
        'page' => 'home',
        'section_key' => 'quality_pillars',
        'section_name' => 'Filosofi Monozukuri & Quality Assurance',
        'title' => 'Komitmen Mutu & Zero Defect',
        'subtitle' => 'Setiap produk yang keluar dari pabrik melewati serangkaian inspeksi berstandar tinggi demi memastikan keselamatan pengemudi dan penumpang.',
        'badge_text' => 'JAPANESE MONOZUKURI PHILOSOPHY',
        'icon' => 'bi-gear-wide-connected',
        'is_active' => 1,
        'sort_order' => 80
    ],
    [
        'page' => 'home',
        'section_key' => 'facility_preview',
        'section_name' => 'Highlight Fasilitas Pabrik Cikampek',
        'title' => 'Fasilitas Pabrik Presisi di Cikampek, Karawang',
        'subtitle' => 'Mengintegrasikan mesin injeksi plastik presisi tinggi 150T–2500T, Cleanroom Assembly Class 8, dan lab kontrol kualitas berstandar 5S.',
        'badge_text' => 'MANUFACTURING FACILITY',
        'icon' => 'bi-buildings',
        'button_text' => 'Jelajahi Seluruh Fasilitas Pabrik',
        'button_link' => 'facility.php',
        'is_active' => 1,
        'sort_order' => 90
    ],
    [
        'page' => 'home',
        'section_key' => 'esg_sustainability',
        'section_name' => 'Green Monozukuri & Keberlanjutan (ESG)',
        'title' => 'Green Monozukuri: Komitmen Keberlanjutan Otomotif',
        'subtitle' => 'Pabrik Karawang aktif menerapkan integrasi material resin daur ulang ramah lingkungan, efisiensi energi solar fotovoltaik, dan standar ISO 14001.',
        'badge_text' => 'SUSTAINABILITY & CARBON NEUTRALITY',
        'icon' => 'bi-recycle',
        'is_active' => 1,
        'sort_order' => 100
    ],
    [
        'page' => 'home',
        'section_key' => 'gallery_preview',
        'section_name' => 'Galeri Foto Kegiatan & Fasilitas',
        'title' => 'Galeri Aktivitas & Fasilitas',
        'subtitle' => 'Dokumentasi kegiatan manufaktur, budaya keselamatan 5S, dan kerja sama tim PT Nihon Plast Indonesia.',
        'badge_text' => 'COMPANY ACTIVITIES',
        'icon' => 'bi-camera-fill',
        'button_text' => 'Buka Halaman Gallery Lengkap',
        'button_link' => 'gallery.php',
        'is_active' => 1,
        'sort_order' => 110
    ],
    [
        'page' => 'home',
        'section_key' => 'news_preview',
        'section_name' => 'Berita & Informasi Perusahaan',
        'title' => 'Berita & Informasi Perusahaan',
        'subtitle' => 'Ikuti perkembangan terbaru mengenai ekspansi pabrik, pencapaian mutu, dan agenda korporat.',
        'badge_text' => 'LATEST UPDATES',
        'icon' => 'bi-newspaper',
        'button_text' => 'Lihat Semua Berita',
        'button_link' => 'news.php',
        'is_active' => 1,
        'sort_order' => 120
    ],
    [
        'page' => 'home',
        'section_key' => 'contact_cta',
        'section_name' => 'B2B RFQ & Kerjasama OEM (Call To Action)',
        'title' => 'Siap Bermitra dengan Manufaktur Berstandar Dunia?',
        'subtitle' => 'Hubungi tim perwakilan PT Nihon Plast Indonesia untuk kebutuhan pasokan komponen roda kemudi, sistem kantung udara (airbags), dan pencetakan resin interior presisi tinggi bersertifikasi IATF 16949.',
        'badge_text' => 'OEM B2B SOURCING & TECHNICAL RFQ',
        'icon' => 'bi-briefcase-fill',
        'button_text' => 'Ajukan B2B RFQ Inquiry',
        'button_link' => 'contact.php',
        'is_active' => 1,
        'sort_order' => 130
    ]
];

$stmt = $conn->prepare("INSERT INTO site_sections 
    (page, section_key, section_name, title, subtitle, badge_text, icon, button_text, button_link, is_active, sort_order)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
    section_name = VALUES(section_name),
    title = COALESCE(site_sections.title, VALUES(title)),
    subtitle = COALESCE(site_sections.subtitle, VALUES(subtitle)),
    badge_text = COALESCE(site_sections.badge_text, VALUES(badge_text)),
    icon = COALESCE(site_sections.icon, VALUES(icon)),
    button_text = COALESCE(site_sections.button_text, VALUES(button_text)),
    button_link = COALESCE(site_sections.button_link, VALUES(button_link))");

foreach ($defaultSections as $s) {
    $stmt->bind_param("sssssssssii", 
        $s['page'], 
        $s['section_key'], 
        $s['section_name'], 
        $s['title'], 
        $s['subtitle'], 
        $s['badge_text'], 
        $s['icon'], 
        $s['button_text'], 
        $s['button_link'], 
        $s['is_active'], 
        $s['sort_order']
    );
    $stmt->execute();
}
echo "Seeded " . count($defaultSections) . " dynamic sections into site_sections table.\n";
echo "=== MIGRATION COMPLETED SUCCESSFULLY ===\n";
