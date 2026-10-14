<?php
require_once __DIR__ . '/../config/database.php';

echo "=== MIGRATING COMPLETE DYNAMIC CMS SECTIONS & MACHINERY FOR ALL PAGES ===\n\n";

// 1. Create table facility_machinery
$conn->query("CREATE TABLE IF NOT EXISTS facility_machinery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(150) NOT NULL,
    model_brand VARCHAR(150) NOT NULL,
    tonnage VARCHAR(100) NOT NULL,
    application VARCHAR(255) NOT NULL,
    automation VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 10,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(is_active),
    INDEX(sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
echo "Created or verified facility_machinery table.\n";

// Seed facility_machinery if empty
$chkMach = $conn->query("SELECT COUNT(*) AS total FROM facility_machinery");
if ($chkMach && $chkMach->fetch_assoc()['total'] == 0) {
    $defaultMach = [
        [
            'category' => 'Ultra-Large Injection Press',
            'model_brand' => 'Sumitomo / Toshiba Hydraulic',
            'tonnage' => '2.500 Ton',
            'application' => 'Instrument Panel & Cowl Top Exterior',
            'automation' => 'Robotic Part Unloader & Vision Sensor',
            'sort_order' => 10
        ],
        [
            'category' => 'Large Injection Press',
            'model_brand' => 'Nissei / JSW All-Electric',
            'tonnage' => '1.300 Ton - 1.800 Ton',
            'application' => 'Center Console & Airbag Cover Module',
            'automation' => 'Servo Take-out Robot & Degating Cell',
            'sort_order' => 20
        ],
        [
            'category' => 'Medium Injection Press',
            'model_brand' => 'Toyo / Fanuc High-Cycle',
            'tonnage' => '350 Ton - 850 Ton',
            'application' => 'Steering Wheel Bezel & Air Vent Registers',
            'automation' => 'Automated Conveyor & Runner Separator',
            'sort_order' => 30
        ],
        [
            'category' => 'Precision Injection Press',
            'model_brand' => 'Fanuc Roboshot Precision',
            'tonnage' => '150 Ton - 280 Ton',
            'application' => 'Small Switches, Clips & Internal Safety Locks',
            'automation' => 'Pick & Place Micro Gripper',
            'sort_order' => 40
        ],
        [
            'category' => 'Vibration Welding Rig',
            'model_brand' => 'Branson / Bielomatik Automated',
            'tonnage' => 'High Frequency 240 Hz',
            'application' => 'HVAC Ducts & Instrument Sub-assemblies',
            'automation' => 'Pneumatic Clamping & Acoustic Enclosure',
            'sort_order' => 50
        ]
    ];
    $stmtM = $conn->prepare("INSERT INTO facility_machinery (category, model_brand, tonnage, application, automation, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
    foreach ($defaultMach as $m) {
        $stmtM->bind_param("sssssi", $m['category'], $m['model_brand'], $m['tonnage'], $m['application'], $m['automation'], $m['sort_order']);
        $stmtM->execute();
    }
    echo "Seeded " . count($defaultMach) . " machinery records into facility_machinery.\n";
}

// 2. Comprehensive Sections for ALL Pages
$allPageSections = [
    // === ABOUT PAGE ===
    [
        'page' => 'about',
        'section_key' => 'about_dna',
        'section_name' => 'Profil Korporat & DNA Nihon Plast Group',
        'title' => 'Dedikasi Keselamatan & Presisi Monozukuri Otomotif Dunia',
        'subtitle' => 'Driving Safety & Precision Through Japanese Monozukuri',
        'content' => 'PT Nihon Plast Indonesia beroperasi di Kawasan Industri Cikampek, Karawang, Jawa Barat sebagai anak perusahaan dari Nihon Plast Co., Ltd. (Jepang). Sejak didirikan, kami mendedikasikan seluruh kapabilitas manufaktur untuk menghadirkan komponen keselamatan kemudi dan moulding resin interior tingkat presisi tinggi.',
        'badge_text' => 'NIHON PLAST GROUP DNA',
        'icon' => 'bi-shield-check',
        'image' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1200&q=80',
        'button_text' => 'Hubungi Kami',
        'button_link' => 'contact.php',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'about',
        'section_key' => 'about_vision_mission',
        'section_name' => 'Visi, Misi & Nilai Fundamental Perusahaan',
        'title' => 'Visi, Misi & Nilai Fundamental',
        'subtitle' => 'Prinsip kerja yang memandu setiap inovasi dan lini manufaktur di PT Nihon Plast Indonesia.',
        'content' => "Visi: Menjadi mitra manufaktur otomotif 'One-of-a-Kind' yang tak tergantikan bagi pelanggan.\nMisi: Menghasilkan produk berkualitas standar IATF 16949, menjalankan Kaizen berkelanjutan, serta mematuhi norma K3 dan lingkungan.",
        'badge_text' => 'CORPORATE FOUNDATION',
        'icon' => 'bi-compass',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'about',
        'section_key' => 'about_global_footprint',
        'section_name' => 'Jejaring Global Nihon Plast Co., Ltd.',
        'title' => 'Jejaring Global Nihon Plast Co., Ltd.',
        'subtitle' => 'Nihon Plast Co., Ltd. berkantor pusat di Fuji City, Shizuoka, Jepang sejak 1948 dengan jaringan pabrik di Asia Pasifik, Amerika Utara, dan Eropa.',
        'badge_text' => 'GLOBAL FOOTPRINT',
        'icon' => 'bi-globe-americas',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'about',
        'section_key' => 'about_datasheet',
        'section_name' => 'Ringkasan Fakta Perusahaan (Corporate Datasheet)',
        'title' => 'Ringkasan Fakta Perusahaan',
        'subtitle' => 'Informasi legal, kepemilikan saham, luas area pabrik Cikampek 28.500 m², dan akreditasi internasional.',
        'badge_text' => 'CORPORATE DATASHEET',
        'icon' => 'bi-info-circle-fill',
        'is_active' => 1,
        'sort_order' => 40
    ],
    [
        'page' => 'about',
        'section_key' => 'about_cta',
        'section_name' => 'CTA Fasilitas & Mutu ISO',
        'title' => 'Ingin Mengetahui Lebih Dalam Mengenai Fasilitas & Kualitas Kami?',
        'subtitle' => 'Jelajahi kapabilitas permesinan presisi kami atau tinjau sertifikasi sistem mutu internasional IATF 16949 yang kami miliki.',
        'button_text' => 'Tinjau Fasilitas & Mesin',
        'button_link' => 'facility.php',
        'badge_text' => 'EXPERIENCE OUR EXCELLENCE',
        'icon' => 'bi-cpu',
        'is_active' => 1,
        'sort_order' => 50
    ],

    // === FACILITY PAGE ===
    [
        'page' => 'facility',
        'section_key' => 'facility_plant_overview',
        'section_name' => 'Gambaran Pabrik Strategis Cikampek',
        'title' => 'Fasilitas Manufaktur Otomotif Berstandar Global Tier-1',
        'subtitle' => 'Pabrik PT Nihon Plast Indonesia didesain mengikuti standar ketat prinsipal Nihon Plast Co., Ltd. (Jepang) dengan luas lahan 28.500 m² dan luas bangunan 18.200 m².',
        'badge_text' => 'Kawasan Industri Indotaisei Cikampek',
        'icon' => 'bi-geo-alt-fill',
        'image' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1200&q=80',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'facility',
        'section_key' => 'facility_capabilities',
        'section_name' => 'Kapabilitas Sel Manufaktur (Manufacturing Cells)',
        'title' => 'Teknologi Manufaktur Unggulan',
        'subtitle' => 'Kombinasi permesinan injeksi otomatis bertekanan tinggi dengan keahlian tangan pengrajin (Takumi Craftsmanship).',
        'badge_text' => 'Kapabilitas Lini Produksi',
        'icon' => 'bi-cpu-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'facility',
        'section_key' => 'facility_machinery_intro',
        'section_name' => 'Matriks Spesifikasi Mesin (Technical Machinery Matrix)',
        'title' => 'Spesifikasi Mesin & Kapasitas Produksi',
        'subtitle' => 'Daftar permesinan produksi utama berkapasitas 150 Ton hingga 2.500 Ton yang beroperasi di fasilitas Cikampek Karawang.',
        'badge_text' => 'Technical Machinery Matrix',
        'icon' => 'bi-table',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'facility',
        'section_key' => 'facility_cleanroom',
        'section_name' => 'Area Perakitan Steril Cleanroom Class 8',
        'title' => 'Perakitan Modul Airbag di Cleanroom ISO Class 8',
        'subtitle' => 'Lingkungan bertekanan positif dengan kontrol partikel udara dan perlindungan electrostatic discharge (ESD) terproteksi penuh.',
        'badge_text' => 'Cleanroom ISO 14644-1',
        'icon' => 'bi-shield-check',
        'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80',
        'is_active' => 1,
        'sort_order' => 40
    ],
    [
        'page' => 'facility',
        'section_key' => 'facility_laboratories',
        'section_name' => 'Laboratorium Pengujian & Metrologi Presisi',
        'title' => 'Laboratorium Kontrol Mutu & Pengujian Dinamis',
        'subtitle' => 'Peralatan inspeksi Coordinate Measuring Machine (CMM), chamber uji suhu ekstrim -40°C hingga +120°C, dan sensor lab deploy airbag.',
        'badge_text' => 'Testing & Metrology Labs',
        'icon' => 'bi-moisture',
        'is_active' => 1,
        'sort_order' => 50
    ],
    [
        'page' => 'facility',
        'section_key' => 'facility_cta',
        'section_name' => 'CTA Kunjungan Pabrik & B2B RFQ',
        'title' => 'Rencanakan Audit Fasilitas atau Diskusikan Proyek OEM Anda',
        'subtitle' => 'Tim Commercial & Engineering kami siap menyambut kunjungan teknis atau meninjau kebutuhan penawaran spesifikasi komponen Anda.',
        'button_text' => 'Kirim Pengajuan B2B RFQ',
        'button_link' => 'contact.php',
        'badge_text' => 'COLLABORATE WITH US',
        'icon' => 'bi-briefcase-fill',
        'is_active' => 1,
        'sort_order' => 60
    ],

    // === QUALITY PAGE ===
    [
        'page' => 'quality',
        'section_key' => 'quality_zero_defect',
        'section_name' => 'Filosofi Mutu Zero Defect & Kebijakan Kualitas',
        'title' => 'Dedikasi Mutu Tanpa Kompromi untuk Keselamatan Pengemudi',
        'subtitle' => 'Sebagai pemasok otomotif Tier-1 global, prinsip Quality First dan Zero Defect ditanamkan ke dalam setiap tahap rekayasa hingga perakitan akhir.',
        'content' => '誇れる製品をつくり、顧客に奉仕し、社会に貢献する (Selalu menciptakan produk yang membanggakan, melayani pelanggan, dan berkontribusi kepada masyarakat).',
        'badge_text' => 'ZERO DEFECT PHILOSOPHY',
        'icon' => 'bi-shield-check',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'quality',
        'section_key' => 'quality_certifications_intro',
        'section_name' => 'Daftar Sertifikasi Resmi Terakreditasi',
        'title' => 'Sertifikasi & Akreditasi Resmi',
        'subtitle' => 'Seluruh fasilitas dan proses manufaktur kami diaudit secara berkala oleh badan sertifikasi independen internasional terkemuka.',
        'badge_text' => 'ACCREDITED CERTIFICATIONS',
        'icon' => 'bi-award-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'quality',
        'section_key' => 'quality_regulatory_compliance',
        'section_name' => 'Kepatuhan Regulasi Pemerintah RI (Kemenperin, KLHK, Kemnaker)',
        'title' => 'Kepatuhan Terhadap Hukum & Regulasi Republik Indonesia',
        'subtitle' => 'Mematuhi standar industri Kemenperin RI, regulasi lingkungan KLHK (ISO 14001:2015 / AMDAL), serta norma K3 Kemnaker (SMK3 PP 50/2012).',
        'badge_text' => 'INDONESIAN REGULATORY COMPLIANCE',
        'icon' => 'bi-shield-check',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'quality',
        'section_key' => 'quality_flowchart',
        'section_name' => '4 Tahapan Pengendalian Mutu & Kaizen Workflow',
        'title' => 'Sistem Manajemen Mutu Terintegrasi',
        'subtitle' => 'Tahap 1: Raw Material Incoming Inspection, Tahap 2: Real-time In-Process Quality Control, Tahap 3: 3D CMM Geometric Verification, Tahap 4: Pre-Delivery Final Audit.',
        'badge_text' => 'QUALITY ASSURANCE WORKFLOW',
        'icon' => 'bi-diagram-3-fill',
        'is_active' => 1,
        'sort_order' => 40
    ],
    [
        'page' => 'quality',
        'section_key' => 'quality_cta',
        'section_name' => 'CTA Konsultasi Standar Mutu',
        'title' => 'Butuh Lembar Kepatuhan Mutu atau Salinan Sertifikat ISO?',
        'subtitle' => 'Hubungi divisi Quality Assurance kami untuk mendapatkan salinan dokumen sertifikat resmi IATF 16949, ISO 14001, atau kepatuhan RoHS/ELV.',
        'button_text' => 'Hubungi Tim QA & Compliance',
        'button_link' => 'contact.php',
        'badge_text' => 'GET CERTIFICATION COPIES',
        'icon' => 'bi-patch-check-fill',
        'is_active' => 1,
        'sort_order' => 50
    ],

    // === PRODUCTS PAGE ===
    [
        'page' => 'products',
        'section_key' => 'products_catalog_intro',
        'section_name' => 'Pengantar Katalog Produk Otomotif',
        'title' => 'Katalog Komponen Otomotif Berstandar Dunia',
        'subtitle' => 'Menyediakan sistem roda kemudi terintegrasi, modul keselamatan kantung udara, dan moulding resin interior eksterior berkualitas OEM.',
        'badge_text' => 'TIER-1 OEM PRODUCT PORTFOLIO',
        'icon' => 'bi-grid-3x3-gap-fill',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'products',
        'section_key' => 'products_technical_capabilities',
        'section_name' => 'Kapabilitas Teknis & Kompatibilitas Resin',
        'title' => 'Keunggulan Material & Toleransi Presisi Tinggi',
        'subtitle' => 'Penggunaan resin rekayasa tinggi (PP-TD20, ABS, PC/ABS, PA66-GF) dengan ketahanan getaran, anti gores, dan kestabilan termal optimal.',
        'badge_text' => 'MATERIAL ENGINEERING EXCELLENCE',
        'icon' => 'bi-layers-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'products',
        'section_key' => 'products_custom_oem',
        'section_name' => 'Layanan Kustom OEM APM & Prototyping',
        'title' => 'Solusi Kustomisasi Komponen Sesuai Kebutuhan OEM',
        'subtitle' => 'Dukungan rekayasa teknis mulai dari perancangan cetakan (moulding), pemilihan tekstur laser butir, hingga uji coba prototipe cepat.',
        'badge_text' => 'TAILORED OEM SOURCING',
        'icon' => 'bi-gear-wide-connected',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'products',
        'section_key' => 'products_cta',
        'section_name' => 'CTA Permintaan Penawaran B2B Produk',
        'title' => 'Tertarik Mengembangkan Komponen Baru Bersama Kami?',
        'subtitle' => 'Ajukan permintaan penawaran harga (RFQ) dan jadwalkan sesi tinjauan teknis bersama tim rekayasa kami.',
        'button_text' => 'Ajukan Permintaan RFQ',
        'button_link' => 'contact.php',
        'badge_text' => 'B2B SOURCING INQUIRY',
        'icon' => 'bi-file-earmark-text',
        'is_active' => 1,
        'sort_order' => 40
    ],

    // === CAREER PAGE ===
    [
        'page' => 'career',
        'section_key' => 'career_intro',
        'section_name' => 'Filosofi SDM & Peluang Karir',
        'title' => 'Membangun Karir Berkelanjutan di Industri Manufaktur Presisi',
        'subtitle' => 'Kami percaya bahwa keunggulan produk berawal dari sumber daya manusia yang kompeten, berintegritas, dan menjunjung tinggi budaya keselamatan kerja.',
        'badge_text' => 'CAREER & TALENT DEVELOPMENT',
        'icon' => 'bi-people-fill',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'career',
        'section_key' => 'career_culture_benefits',
        'section_name' => 'Budaya Kerja 5S & Fasilitas Kesejahteraan',
        'title' => 'Kesejahteraan Karyawan & Pengembangan Profesional',
        'subtitle' => 'Pelatihan teknik Monozukuri langsung, fasilitas BPJS Kesehatan & Ketenagakerjaan lengkap, kantin higienis, dan bus jemputan karyawan.',
        'badge_text' => 'WORKPLACE CULTURE & PERKS',
        'icon' => 'bi-heart-pulse-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'career',
        'section_key' => 'career_hiring_process',
        'section_name' => 'Tahapan Proses Rekrutmen & Seleksi',
        'title' => 'Tahapan Rekrutmen Transparan & Bebas Biaya',
        'subtitle' => '1. Seleksi Berkas Administrasi, 2. Tes Kemampuan Teknis & Psikotes, 3. Wawancara HR & User, 4. Pemeriksaan Kesehatan (MCU) & Onboarding.',
        'badge_text' => 'RECRUITMENT STAGES',
        'icon' => 'bi-check2-circle',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'career',
        'section_key' => 'career_vacancies_intro',
        'section_name' => 'Daftar Posisi Lowongan Kerja Terbuka',
        'title' => 'Posisi Lowongan yang Sedang Dibuka',
        'subtitle' => 'Lihat kesempatan karir aktif di fasilitas pabrik Cikampek, Karawang dan kirimkan lamaran Anda.',
        'badge_text' => 'OPEN VACANCIES',
        'icon' => 'bi-briefcase-fill',
        'is_active' => 1,
        'sort_order' => 40
    ],

    // === CONTACT PAGE ===
    [
        'page' => 'contact',
        'section_key' => 'contact_liaison_intro',
        'section_name' => 'Liaison Komersial & B2B RFQ Header',
        'title' => 'Bermitra dengan PT Nihon Plast Indonesia',
        'subtitle' => 'Kami siap melayani kebutuhan OEM APM, perancangan prototipe teknis, serta audit fasilitas untuk komponen roda kemudi, modul airbag, dan komponen resin interior presisi.',
        'badge_text' => 'COMMERCIAL & ENGINEERING LIAISON',
        'icon' => 'bi-briefcase-fill',
        'is_active' => 1,
        'sort_order' => 10
    ],
    [
        'page' => 'contact',
        'section_key' => 'contact_info_card',
        'section_name' => 'Kartu Informasi Alamat Pabrik, Telepon & Email',
        'title' => 'Lokasi Pabrik & Kontak Resmi',
        'subtitle' => 'Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang 41373, Jawa Barat | Telepon: +62 (264) 351-888 | Email: corporate@n-plast.co.id',
        'badge_text' => 'HEADQUARTERS & MANUFACTURING PLANT',
        'icon' => 'bi-geo-alt-fill',
        'is_active' => 1,
        'sort_order' => 20
    ],
    [
        'page' => 'contact',
        'section_key' => 'contact_download_deck',
        'section_name' => 'Unduh Company Profile & Hotline WhatsApp',
        'title' => 'Company Profile & Capability Deck',
        'subtitle' => 'Unduh rangkuman profil korporat, data mesin injeksi plastik, kapasitas produksi, dan sertifikasi IATF 16949 / ISO 14001.',
        'button_text' => 'Unduh Company Profile (PDF)',
        'button_link' => 'about.php',
        'badge_text' => 'CORPORATE BROCHURE',
        'icon' => 'bi-file-earmark-pdf-fill',
        'is_active' => 1,
        'sort_order' => 30
    ],
    [
        'page' => 'contact',
        'section_key' => 'contact_rfq_form_intro',
        'section_name' => 'Formulir Pengajuan B2B RFQ & Konsultasi',
        'title' => 'Formulir Permintaan Penawaran & Konsultasi Teknis',
        'subtitle' => 'Silakan lengkapi formulir di bawah ini. Tim Commercial & Engineering kami akan merespons dalam waktu 1x24 jam kerja.',
        'badge_text' => 'FORMULIR PENGAJUAN B2B RFQ',
        'icon' => 'bi-send-check-fill',
        'is_active' => 1,
        'sort_order' => 40
    ],
    [
        'page' => 'contact',
        'section_key' => 'contact_map_section',
        'section_name' => 'Peta Navigasi Lokasi Pabrik Cikampek',
        'title' => 'Peta Lokasi Kawasan Industri Indotaisei Cikampek',
        'subtitle' => 'Akses strategis jalan tol Jakarta-Cikampek KM 68 Gerbang Tol Kalihurip Utama.',
        'badge_text' => 'PLANT NAVIGATION MAP',
        'icon' => 'bi-map-fill',
        'is_active' => 1,
        'sort_order' => 50
    ],

    // === GALLERY PAGE ===
    [
        'page' => 'gallery',
        'section_key' => 'gallery_header',
        'section_name' => 'Header Galeri Foto Dokumentasi Manufaktur',
        'title' => 'Galeri Aktivitas & Fasilitas Pabrik',
        'subtitle' => 'Dokumentasi fotografi beresolusi tinggi mengenai kegiatan produksi manufaktur, budaya keselamatan 5S, dan fasilitas pabrik PT Nihon Plast Indonesia.',
        'badge_text' => 'DOKUMENTASI FOTO MANUFAKTUR',
        'icon' => 'bi-camera-fill',
        'is_active' => 1,
        'sort_order' => 10
    ],

    // === NEWS PAGE ===
    [
        'page' => 'news',
        'section_key' => 'news_header',
        'section_name' => 'Header Berita & Pengumuman Korporat',
        'title' => 'Berita & Informasi Perusahaan',
        'subtitle' => 'Ikuti perkembangan terbaru mengenai ekspansi pabrik, pencapaian mutu, dan agenda korporat PT Nihon Plast Indonesia.',
        'badge_text' => 'LATEST UPDATES',
        'icon' => 'bi-newspaper',
        'is_active' => 1,
        'sort_order' => 10
    ]
];

// 3. Upsert into site_sections
$stmtSec = $conn->prepare("INSERT INTO site_sections 
    (page, section_key, section_name, title, subtitle, content, badge_text, icon, image, button_text, button_link, is_active, sort_order)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
    section_name = VALUES(section_name),
    title = COALESCE(site_sections.title, VALUES(title)),
    subtitle = COALESCE(site_sections.subtitle, VALUES(subtitle)),
    content = COALESCE(site_sections.content, VALUES(content)),
    badge_text = COALESCE(site_sections.badge_text, VALUES(badge_text)),
    icon = COALESCE(site_sections.icon, VALUES(icon)),
    image = COALESCE(site_sections.image, VALUES(image)),
    button_text = COALESCE(site_sections.button_text, VALUES(button_text)),
    button_link = COALESCE(site_sections.button_link, VALUES(button_link)),
    sort_order = VALUES(sort_order)");

$seededCount = 0;
foreach ($allPageSections as $s) {
    $content = $s['content'] ?? null;
    $image = $s['image'] ?? null;
    $buttonText = $s['button_text'] ?? null;
    $buttonLink = $s['button_link'] ?? null;

    $stmtSec->bind_param("sssssssssssii",
        $s['page'],
        $s['section_key'],
        $s['section_name'],
        $s['title'],
        $s['subtitle'],
        $content,
        $s['badge_text'],
        $s['icon'],
        $image,
        $buttonText,
        $buttonLink,
        $s['is_active'],
        $s['sort_order']
    );
    if ($stmtSec->execute()) {
        $seededCount++;
    }
}

echo "Successfully upserted " . $seededCount . " dynamic sections across all pages into site_sections table.\n";
echo "=== ALL PAGES MIGRATION COMPLETED SUCCESSFULLY ===\n";
