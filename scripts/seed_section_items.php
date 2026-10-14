<?php
require_once __DIR__ . '/../config/database.php';

// Wipe existing seeded items to prevent duplicates
$conn->query("TRUNCATE TABLE site_section_items");

$items = [
    // 1. Stats Bar (Home)
    [
        'section_key' => 'stats',
        'title' => 'IATF 16949 / ISO 14001',
        'subtitle' => 'Standar Mutu Otomotif Global',
        'icon' => 'bi-shield-check',
        'sort_order' => 10
    ],
    [
        'section_key' => 'stats',
        'title' => '4 Lini Produk Utama',
        'subtitle' => 'Steering, Airbag, Interior & Eksterior Resin',
        'icon' => 'bi-box-seam',
        'sort_order' => 20
    ],
    [
        'section_key' => 'stats',
        'title' => 'Pabrik Cikampek Karawang',
        'subtitle' => 'Cleanroom Class 8 & Injeksi 150T-2500T',
        'icon' => 'bi-building',
        'sort_order' => 30
    ],
    [
        'section_key' => 'stats',
        'title' => 'Zero Defect & Zero LTI',
        'subtitle' => 'Disiplin Budaya K3 Kemnaker RI & 5S',
        'icon' => 'bi-check2-circle',
        'sort_order' => 40
    ],

    // 2. OEM Trust Partners (Home)
    [
        'section_key' => 'oem_trust',
        'title' => 'TOYOTA',
        'badge' => 'OEM Partner',
        'sort_order' => 10
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'HONDA',
        'badge' => 'OEM Partner',
        'sort_order' => 20
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'NISSAN',
        'badge' => 'OEM Partner',
        'sort_order' => 30
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'DAIHATSU',
        'badge' => 'OEM Partner',
        'sort_order' => 40
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'MITSUBISHI',
        'badge' => 'OEM Partner',
        'sort_order' => 50
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'SUZUKI',
        'badge' => 'OEM Partner',
        'sort_order' => 60
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'SUBARU',
        'badge' => 'OEM Partner',
        'sort_order' => 70
    ],
    [
        'section_key' => 'oem_trust',
        'title' => 'MAZDA',
        'badge' => 'OEM Partner',
        'sort_order' => 80
    ],

    // 3. Vehicle Explorer (Home)
    [
        'section_key' => 'vehicle_explorer',
        'item_key' => 'steering',
        'title' => 'Steering Wheel Systems & Ergonomics',
        'subtitle' => 'Zone: Driver Cockpit Interface',
        'content' => 'Rangka magnesium/aluminium die-cast ringan dengan busa poliuretan integral, balutan kulit premium jahitan tangan, dan tombol multifungsi kontrol presisi tinggi.',
        'image' => 'uploads/products/product_steering_wheel.jpg',
        'icon' => 'bi-disc',
        'extra_data' => json_encode([
            'badges' => ['Magnesium/Al Die-Cast Core', 'Hand-Crafted Genuine Leather', 'Multi-Switch & Paddle Shift', 'FMVSS 208 / ECE R12 Compliant'],
            'tab_label' => '1. Roda Kemudi (Steering Wheels)',
            'link' => 'products.php'
        ]),
        'sort_order' => 10
    ],
    [
        'section_key' => 'vehicle_explorer',
        'item_key' => 'airbag',
        'title' => 'Airbag Safety Modules & Inflator Encasement',
        'subtitle' => 'Zone: Active & Passive Safety',
        'content' => 'Sistem proteksi benturan berkecepatan tinggi yang dirancang untuk mengembang sempurna dalam 20 hingga 30 milidetik demi keselamatan mutlak.',
        'image' => 'uploads/products/product_airbag_module.jpg',
        'icon' => 'bi-shield-shaded',
        'extra_data' => json_encode([
            'badges' => ['Driver, Passenger, Side Curtain', 'Cleanroom ISO Class 8 Assembly', 'Laser-Scored Seamless Tear Seam', '100% Traceability Barcode'],
            'tab_label' => '2. Modul Kantung Udara (Airbags)',
            'link' => 'products.php'
        ]),
        'sort_order' => 20
    ],
    [
        'section_key' => 'vehicle_explorer',
        'item_key' => 'cockpit',
        'title' => 'Instrument Panel & Interior Resins',
        'subtitle' => 'Zone: Cabin Luxury & Ergonomics',
        'content' => 'Komponen dashboard, garnish panel, kisi-kisi AC, dan konsol tengah dengan finishing tekstur lembut dan ketahanan deformasi suhu tinggi.',
        'image' => 'uploads/products/product_cockpit_resin.svg',
        'icon' => 'bi-speedometer2',
        'extra_data' => json_encode([
            'badges' => ['Dual-Shot Injection', 'Piano Black & Soft Touch', 'Zero Tolerance Fitment', 'Heat & UV Resistant JIS'],
            'tab_label' => '3. Kokpit & Konsol Tengah',
            'link' => 'products.php'
        ]),
        'sort_order' => 30
    ],
    [
        'section_key' => 'vehicle_explorer',
        'item_key' => 'cleanroom',
        'title' => 'Cleanroom Class 8 Assembly Area',
        'subtitle' => 'Zone: High Precision Cleanliness',
        'content' => 'Lingkungan perakitan bebas debu dan anti-statis (ESD) untuk penyatuan sensor pemicu dan modul kantung udara berkecepatan tinggi.',
        'image' => 'uploads/gallery/facility_cleanroom_lab.svg',
        'icon' => 'bi-shield-check',
        'extra_data' => json_encode([
            'badges' => ['HEPA Filtration 99.97%', 'ESD Antistatic Control', 'Positive Pressure Differential', 'High-Speed Sensor Deployment'],
            'tab_label' => '4. Area Cleanroom Class 8',
            'link' => 'facility.php'
        ]),
        'sort_order' => 40
    ],

    // 4. Quality Pillars (Home)
    [
        'section_key' => 'quality_pillars',
        'title' => 'Raw Material QA',
        'subtitle' => '01',
        'content' => 'Inspeksi spektrometri dan uji densitas bahan baku resin, kulit asli, serta busa polyurethane.',
        'sort_order' => 10
    ],
    [
        'section_key' => 'quality_pillars',
        'title' => 'Precision Injection',
        'subtitle' => '02',
        'content' => 'Mesin injeksi berteknologi servo Jepang dengan kontrol suhu dan tekanan mikro untuk toleransi ukuran mikron.',
        'sort_order' => 20
    ],
    [
        'section_key' => 'quality_pillars',
        'title' => 'Master Craftsmanship & CMM',
        'subtitle' => '03',
        'content' => 'Inspeksi dimensi geometri Coordinate Measuring Machine (CMM) berkalibrasi rutin laser.',
        'sort_order' => 30
    ],
    [
        'section_key' => 'quality_pillars',
        'title' => 'Safety Deployment Lab',
        'subtitle' => '04',
        'content' => 'Uji kecepatan tinggi dan ketahanan benturan sensor airbag drop-tower demi jaminan keselamatan mutlak.',
        'sort_order' => 40
    ],

    // 5. ESG Sustainability (Home)
    [
        'section_key' => 'esg_sustainability',
        'item_key' => 'stat_solar',
        'badge' => '450 Ton CO₂',
        'title' => 'Pengurangan Emisi Karbon/Tahun via Rooftop Solar PV',
        'icon' => 'bi-sun-fill',
        'sort_order' => 10
    ],
    [
        'section_key' => 'esg_sustainability',
        'item_key' => 'stat_recycle',
        'badge' => '28% Target',
        'title' => 'Rasio Penggunaan Recycled Polypropylene Resin',
        'icon' => 'bi-tree-fill',
        'sort_order' => 20
    ],
    [
        'section_key' => 'esg_sustainability',
        'item_key' => 'comp_iso14001',
        'title' => 'Sertifikasi ISO 14001:2015 Terakreditasi',
        'content' => 'Sistem manajemen lingkungan terintegrasi dengan pemilahan limbah resin 100% terkontrol.',
        'icon' => 'bi-patch-check-fill',
        'sort_order' => 30
    ],
    [
        'section_key' => 'esg_sustainability',
        'item_key' => 'comp_smk3',
        'title' => 'SMK3 PP 50/2012 & Zero Accident Award',
        'content' => 'Budaya K3 Jepang (5S & Anzen Daiichi) melindungi keselamatan seluruh tenaga kerja.',
        'icon' => 'bi-shield-check',
        'sort_order' => 40
    ],
    [
        'section_key' => 'esg_sustainability',
        'item_key' => 'comp_rohs',
        'title' => 'RoHS & ELV (End-of-Life Vehicles) Compliant',
        'content' => 'Bebas senyawa berbahaya timbal, merkuri, kadmium, dan kromium heksavalen.',
        'icon' => 'bi-droplet-half',
        'sort_order' => 50
    ],

    // 6. About Global Footprint (About)
    [
        'section_key' => 'about_global_footprint',
        'title' => 'Jepang (Headquarters & R&D Center)',
        'subtitle' => 'Fuji Plant, Aoshima Plant, Tomioka Plant',
        'icon' => 'bi-geo-alt-fill',
        'sort_order' => 10
    ],
    [
        'section_key' => 'about_global_footprint',
        'title' => 'Asia Pasifik (ASEAN & China)',
        'subtitle' => 'Indonesia (Cikampek Plant), Thailand, China (Wuhan & Zhongshan)',
        'icon' => 'bi-geo-alt-fill',
        'sort_order' => 20
    ],
    [
        'section_key' => 'about_global_footprint',
        'title' => 'Amerika Utara (North America)',
        'subtitle' => 'Amerika Serikat (Ohio) & Mexico (Matamoros & Silao)',
        'icon' => 'bi-geo-alt-fill',
        'sort_order' => 30
    ],

    // 7. Facility Capabilities (Facility)
    [
        'section_key' => 'facility_capabilities',
        'badge' => 'Injeksi Resin Presisi',
        'title' => 'High-Tonnage Injection Molding',
        'subtitle' => 'Armada 22 mesin injeksi plastik berkisar dari 150-ton hingga 2.500-ton servo-robotik.',
        'content' => 'Mencetak komponen presisi tinggi dari material PP, ABS, PC, dan PA66.',
        'image' => 'uploads/gallery/facility_injection_molding.jpg',
        'extra_data' => json_encode(['Multi-cavity mold compatibility', 'Gas-assist injection capability']),
        'sort_order' => 10
    ],
    [
        'section_key' => 'facility_capabilities',
        'badge' => 'Cleanroom Class 8',
        'title' => 'Modul Airbag Cleanroom Assembly',
        'subtitle' => 'Area perakitan steril berstandar ISO 14644-1 Class 8 terproteksi ESD.',
        'content' => 'Kontrol partikel mikro dan electrostatic discharge (ESD) terproteksi penuh.',
        'image' => 'uploads/gallery/facility_cleanroom_lab.svg',
        'extra_data' => json_encode(['Traceability barcode 100% individual', 'Automatic torque recording system']),
        'sort_order' => 20
    ],
    [
        'section_key' => 'facility_capabilities',
        'badge' => 'Takumi Craftsmanship',
        'title' => 'Leather & Urethane Steering Cell',
        'subtitle' => 'Keahlian pembungkusan kulit asli dan sintetis jahitan tangan presisi.',
        'content' => 'Dipadukan dengan busa polyurethane molding berdensitas tinggi untuk ergonomi optimal.',
        'image' => 'uploads/products/product_steering_wheel.jpg',
        'extra_data' => json_encode(['Euro-stitch, Cross-stitch, Baseball-stitch', 'Integrated steering heater option']),
        'sort_order' => 30
    ],

    // 8. Facility Laboratories (Facility)
    [
        'section_key' => 'facility_laboratories',
        'badge' => '3D Metrology',
        'title' => 'CMM Geometric Lab',
        'content' => 'Mesin pengukur koordinat 3 dimensi beresolusi 0,001 mm (mikron) untuk validasi cetakan mold presisi tinggi.',
        'icon' => 'bi-rulers',
        'sort_order' => 10
    ],
    [
        'section_key' => 'facility_laboratories',
        'badge' => 'Thermal Stress',
        'title' => 'Chamber Suhu Ekstrim',
        'content' => 'Ruang simulasi termal dengan rentang suhu dari -40°C hingga +120°C serta kelembaban 95% RH untuk uji penuaan material.',
        'icon' => 'bi-thermometer-half',
        'sort_order' => 20
    ],
    [
        'section_key' => 'facility_laboratories',
        'badge' => 'Safety Inflation',
        'title' => 'High-Speed Sensor Deployment Lab',
        'content' => 'Sensor optik presisi tinggi untuk menganalisis kurva tekanan inflasi kantung udara dalam 30 milidetik.',
        'icon' => 'bi-speedometer2',
        'sort_order' => 30
    ],
    [
        'section_key' => 'facility_laboratories',
        'badge' => 'Material Durability',
        'title' => 'Vibration & Endurance Rig',
        'content' => 'Simulator getaran frekuensi multi-aksial untuk menguji kelelahan material rangka kemudi sesuai standar JIS D1601.',
        'icon' => 'bi-activity',
        'sort_order' => 40
    ],

    // 9. Quality Regulatory Compliance (Quality)
    [
        'section_key' => 'quality_regulatory_compliance',
        'title' => 'SMK3 (Peraturan Pemerintah No. 50 Tahun 2012)',
        'content' => 'Penerapan wajib Sistem Manajemen K3 Kemnaker RI dengan audit berkala, pelatihan tanggap darurat, dan fasilitas APD berstandar penuh.',
        'icon' => 'bi-check-lg',
        'sort_order' => 10
    ],
    [
        'section_key' => 'quality_regulatory_compliance',
        'title' => 'Pengelolaan Lingkungan & Limbah B3 (KLHK RI)',
        'content' => 'Izin TPS Limbah B3 resmi, dokumen lingkungan AMDAL / UKL-UPL terverifikasi, dan kepatuhan baku mutu emisi pabrik.',
        'icon' => 'bi-check-lg',
        'sort_order' => 20
    ],
    [
        'section_key' => 'quality_regulatory_compliance',
        'title' => 'TKDN & SNI Otomotif (Kementerian Perindustrian)',
        'content' => 'Mendukung peningkatan penggunaan komponen produksi dalam negeri dan pemenuhan spesifikasi teknis kendaraan bermotor nasional.',
        'icon' => 'bi-check-lg',
        'sort_order' => 30
    ],

    // 10. Quality Flowchart Framework (Quality)
    [
        'section_key' => 'quality_flowchart',
        'title' => 'Quality Assurance (QA)',
        'subtitle' => 'APQP, FMEA, PPAP, MSA',
        'content' => 'Perencanaan kualitas matang mengadopsi metodologi APQP, FMEA, PPAP, dan MSA untuk mencegah potensi kecacatan sejak fase desain mold.',
        'icon' => 'bi-check2-circle',
        'sort_order' => 10
    ],
    [
        'section_key' => 'quality_flowchart',
        'title' => 'Quality Control (QC)',
        'subtitle' => '100% In-Line Inspection',
        'content' => 'Inspeksi otomatis berkamera resolusi tinggi dan pemeriksaan sensorik manual pada setiap tahap produksi kritis.',
        'icon' => 'bi-sliders',
        'sort_order' => 20
    ],
    [
        'section_key' => 'quality_flowchart',
        'title' => 'Quality Engineering (QE)',
        'subtitle' => 'Metrology & Lab Verification',
        'content' => 'Verifikasi dimensi mikron dengan 3D CMM Mitutoyo, pengujian ketahanan termal, dan simulasi benturan drop-tower.',
        'icon' => 'bi-cpu',
        'sort_order' => 30
    ],
    [
        'section_key' => 'quality_flowchart',
        'title' => 'Kaizen & Continuous Improvement',
        'subtitle' => 'PDCA & 5S Culture',
        'content' => 'Penerapan gugus kendali mutu (QCC) dan perbaikan berkesinambungan tanpa henti oleh seluruh jajaran karyawan.',
        'icon' => 'bi-arrow-repeat',
        'sort_order' => 40
    ],

    // 11. Career Culture & Benefits (Career)
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Remunerasi Kompetitif & BPJS Lengkap',
        'content' => 'Paket gaji standar industri Tier-1, BPJS Ketenagakerjaan & Kesehatan, serta asuransi kesehatan swasta.',
        'icon' => 'bi-shield-check',
        'sort_order' => 10
    ],
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Pelatihan Standar Jepang (Monozukuri)',
        'content' => 'Kesempatan transfer ilmu langsung dari tenaga ahli Jepang, program Kaizen, dan sertifikasi keahlian teknis.',
        'icon' => 'bi-mortarboard',
        'sort_order' => 20
    ],
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Lingkungan Kerja Bersih & Aman (5S & K3)',
        'content' => 'Budaya kerja ramah, fasilitas pabrik modern ber-AC, kantin sehat, dan komitmen Zero Accident.',
        'icon' => 'bi-heart-pulse',
        'sort_order' => 30
    ],
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Jenjang Karir Terbuka & Berkelanjutan',
        'content' => 'Apresiasi kinerja berbasis meritokrasi dan kesempatan promosi kepemimpinan yang transparan.',
        'icon' => 'bi-graph-up-arrow',
        'sort_order' => 40
    ],
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Transportasi & Fasilitas Kerja',
        'content' => 'Jemputan karyawan di berbagai rute strategis, seragam kerja, dan fasilitas ibadah yang nyaman.',
        'icon' => 'bi-bus-front',
        'sort_order' => 50
    ],
    [
        'section_key' => 'career_culture_benefits',
        'title' => 'Keseimbangan Kerja & Kehidupan',
        'content' => 'Jadwal kerja teratur sesuai regulasi ketenagakerjaan RI tanpa jam kerja berlebih yang merugikan.',
        'icon' => 'bi-calendar-check',
        'sort_order' => 60
    ],

    // 12. Career Hiring Process (Career)
    [
        'section_key' => 'career_hiring_process',
        'title' => 'Pendaftaran & Seleksi Berkas Online',
        'subtitle' => '01',
        'content' => 'Kirimkan CV terbaru dan portofolio Anda melalui portal karir resmi ini atau email HRD kami.',
        'sort_order' => 10
    ],
    [
        'section_key' => 'career_hiring_process',
        'title' => 'Psikotes & Uji Kompetensi Teknis',
        'subtitle' => '02',
        'content' => 'Penilaian kemampuan logika, kepribadian, serta uji keterampilan teknis sesuai posisi yang dilamar.',
        'sort_order' => 20
    ],
    [
        'section_key' => 'career_hiring_process',
        'title' => 'Wawancara User & Manajemen',
        'subtitle' => '03',
        'content' => 'Diskusi mendalam mengenai pengalaman kerja, kecocokan budaya kerja, dan rencana pengembangan diri.',
        'sort_order' => 30
    ],
    [
        'section_key' => 'career_hiring_process',
        'title' => 'Medical Check-Up & Penawaran Resmi',
        'subtitle' => '04',
        'content' => 'Pemeriksaan kesehatan kerja standar industri dan penandatanganan perjanjian kerja resmi.',
        'sort_order' => 40
    ]
];

$stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, icon, image, badge, extra_data, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

$count = 0;
foreach ($items as $item) {
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
        echo "Error inserting item: " . $stmt->error . "\n";
    }
}
$stmt->close();

echo "Successfully seeded {$count} dynamic sub-items into site_section_items table!\n";
