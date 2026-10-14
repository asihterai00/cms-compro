<?php
require_once __DIR__ . '/../config/database.php';

// 1. Seed Certifications
$conn->query("TRUNCATE TABLE certifications");

$certs = [
    [
        'name' => 'IATF 16949:2016',
        'category' => 'Automotive Quality Management',
        'certificate_number' => 'IATF-0482910 / TÜV-CERT',
        'issued_by' => 'TÜV Rheinland International',
        'valid_until' => '2027-12-31',
        'description' => 'Standar sistem manajemen mutu tertinggi dan wajib bagi rantai pasok otomotif global (Tier-1 Supplier) untuk desain, rekayasa, dan manufaktur roda kemudi, airbag, dan modul resin interior.',
        'sort_order' => 1
    ],
    [
        'name' => 'ISO 9001:2015',
        'category' => 'Quality Management System',
        'certificate_number' => 'ID-QMS-9001-88321',
        'issued_by' => 'SGS International Certification',
        'valid_until' => '2028-06-30',
        'description' => 'Sertifikasi sistem manajemen mutu internasional yang memastikan konsistensi proses operasional, ketertelusuran material (traceability), dan kepuasan pelanggan secara berkesinambungan.',
        'sort_order' => 2
    ],
    [
        'name' => 'ISO 14001:2015',
        'category' => 'Environmental Management System',
        'certificate_number' => 'EMS-14001-ID-54210',
        'issued_by' => 'Lloyd\'s Register / SGS',
        'valid_until' => '2027-10-15',
        'description' => 'Sistem manajemen lingkungan sesuai regulasi Kementerian Lingkungan Hidup dan Kehutanan (KLHK) RI, pengelolaan limbah B3 berizin, efisiensi energi, dan program reduksi jejak karbon pabrik.',
        'sort_order' => 3
    ],
    [
        'name' => 'ISO 45001:2018 & SMK3 PP 50/2012',
        'category' => 'Occupational Health & Safety (K3)',
        'certificate_number' => 'OHSMS-45001-SMK3-9921',
        'issued_by' => 'Kementerian Ketenagakerjaan RI & TÜV Rheinland',
        'valid_until' => '2027-08-20',
        'description' => 'Penerapan Sistem Manajemen Keselamatan dan Kesehatan Kerja (SMK3) sesuai Peraturan Pemerintah RI No. 50 Tahun 2012 dan ISO 45001 guna menjamin budaya keselamatan kerja tanpa kecelakaan (Zero Accident).',
        'sort_order' => 4
    ],
    [
        'name' => 'Kemenperin & SNI Compliance',
        'category' => 'National Regulatory Standards',
        'certificate_number' => 'REG-KEMENPERIN-TKDN-2026',
        'issued_by' => 'Kementerian Perindustrian Republik Indonesia',
        'valid_until' => '2029-01-01',
        'description' => 'Kepatuhan terhadap standar keselamatan komponen kendaraan bermotor nasional dan pemenuhan Tingkat Komponen Dalam Negeri (TKDN) sesuai peta jalan industri manufaktur Kemenperin RI.',
        'sort_order' => 5
    ]
];

$stmtCert = $conn->prepare("INSERT INTO certifications (name, category, certificate_number, issued_by, valid_until, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($certs as $c) {
    $stmtCert->bind_param('ssssssi', $c['name'], $c['category'], $c['certificate_number'], $c['issued_by'], $c['valid_until'], $c['description'], $c['sort_order']);
    $stmtCert->execute();
    echo "Added Certification: " . $c['name'] . "\n";
}

// 2. Seed Real Nihon Plast Products
$conn->query("TRUNCATE TABLE products");

$prods = [
    [
        'name' => 'Steering Wheel Systems (Sistem Roda Kemudi)',
        'description' => 'Roda kemudi berstandar global dengan pilihan material genuine leather presisi tinggi, polyurethane bermutu tinggi, switch kontrol multifungsi, pemanas (heated wheel), serta sensor sentuh keselamatan (Hands-on Detection).',
        'image' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1000&q=80'
    ],
    [
        'name' => 'Airbag Safety Modules (Modul Kantung Udara)',
        'description' => 'Modul airbag berkecepatan tinggi terintegrasi (Driver, Passenger, Side Curtain, and Knee Airbag) dengan penutup cover resin presisi yang dapat membuka sempurna dalam hitungan milidetik saat terjadi benturan.',
        'image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1000&q=80'
    ],
    [
        'name' => 'Cockpit & Interior Resin Parts (Komponen Interior Resin)',
        'description' => 'Komponen kabin interior berestetika tinggi dan presisi millimeter: Instrument Panel Cluster, Air Conditioner Registers & Ducts, Center Console Box, Glove Box, Cup Holders, dan Pillar Garnish.',
        'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1000&q=80'
    ],
    [
        'name' => 'Exterior & Aerodynamic Trim (Komponen Eksterior & Aerodinamis)',
        'description' => 'Komponen eksterior berbahan resin berkekuatan tinggi terhadap cuaca ekstrem dan benturan kerikil: Cover Cowl Top, Fender Protector, dan Wheel Resonator peredam kebisingan resonansi roda mobil.',
        'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1000&q=80'
    ]
];

$stmtProd = $conn->prepare("INSERT INTO products (name, description, image) VALUES (?, ?, ?)");
foreach ($prods as $p) {
    $stmtProd->bind_param('sss', $p['name'], $p['description'], $p['image']);
    $stmtProd->execute();
    echo "Added Product: " . $p['name'] . "\n";
}

// 3. Update Company Profile to Authentic Japanese Tier-1 Profile
$compSql = "UPDATE company_profile SET
    company_name = 'PT Nihon Plast Indonesia',
    tagline = 'Driving Safety & Precision Through Japanese Monozukuri',
    short_description = 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.',
    about_text = 'PT Nihon Plast Indonesia merupakan bagian dari Nihon Plast Co., Ltd. (Jepang) yang didirikan untuk menghadirkan keunggulan manufaktur komponen otomotif kelas dunia di Indonesia. Berlokasi strategis di Tambun, Bekasi, fasilitas kami mengintegrasikan teknologi injeksi plastik presisi (Precision Resin Molding), proses pembungkusan kulit presisi (Leather Wrapping Craftsmanship), perakitan modul keselamatan berkecepatan tinggi, serta laboratorium pengujian kualitas ketat demi memenuhi standar OEM global dan regulasi ketenagakerjaan serta lingkungan Republik Indonesia.',
    vision = 'Menjadi mitra manufaktur otomotif \'One-of-a-Kind\' yang tak tergantikan bagi pelanggan melalui penguasaan teknologi unik, dedikasi keselamatan tanpa kompromi, dan produk berkualitas tinggi.',
    mission = '1. Menghasilkan produk otomotif yang membanggakan serta memenuhi standar mutu internasional tertinggi (IATF 16949:2016).\n2. Menjalankan filosofi Monozukuri dan Kaizen secara konsisten di seluruh lini operasional.\n3. Mematuhi regulasi ketenagakerjaan, keselamatan kerja (SMK3/ISO 45001), dan kepedulian lingkungan (ISO 14001) demi pembangunan industri nasional yang berkelanjutan.',
    values_text = 'Monozukuri (Craftsmanship), Safety First, Integrity, Continuous Improvement (Kaizen), and Sustainable Harmony.',
    address = 'Jalan Raya Tambun KM.38.2, Tambun Selatan, Kabupaten Bekasi, Jawa Barat 17510, Indonesia',
    phone = '(021) 8983-0000',
    email = 'info@nihon-plast.co.id'
    WHERE id = 1";

$conn->query($compSql);
echo "Updated Company Profile successfully!\n";
