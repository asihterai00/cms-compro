<?php
require_once __DIR__ . '/../config/database.php';

$heroSlides = [
    [
        'section_key' => 'hero_slides',
        'item_key' => 'slide_injection',
        'title' => 'Japanese Monozukuri Precision & High-Tonnage Injection Molding',
        'subtitle' => 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS',
        'content' => 'Mitra manufaktur terpercaya bagi OEM otomotif dunia dengan armada 22 mesin injeksi servo berkapasitas 150T hingga 2.500T, cleanroom Class 8, dan sertifikasi IATF 16949.',
        'badge' => 'IATF 16949 / ISO 14001',
        'image' => 'uploads/gallery/facility_injection_molding.jpg',
        'icon' => 'bi-gear-wide-connected',
        'extra_data' => json_encode([
            'badges' => ['150T - 2500T Tonnage', 'Cleanroom Class 8', '0.001mm Tolerance', 'Tier-1 OEM Partner'],
            'btn_primary_text' => 'Jelajahi Produk',
            'btn_primary_link' => 'products.php',
            'btn_secondary_text' => 'Konsultasi B2B RFQ',
            'btn_secondary_link' => 'contact.php'
        ]),
        'sort_order' => 10
    ],
    [
        'section_key' => 'hero_slides',
        'item_key' => 'slide_airbag',
        'title' => 'Critical Airbag Safety Modules with 30ms High-Speed Deployment',
        'subtitle' => 'UNCOMPROMISING LIFE-SAVING OCCUPANT PROTECTION',
        'content' => 'Dirancang dan dirakit dalam cleanroom berstandar ISO 14644-1 Class 8 dengan kontrol elektrostatis ESD penuh dan 100% penelusuran barcode komponen inflator.',
        'badge' => 'Safety Critical 20-30ms',
        'image' => 'uploads/products/product_airbag_module.jpg',
        'icon' => 'bi-shield-shaded',
        'extra_data' => json_encode([
            'badges' => ['Driver & Passenger Modules', 'Laser Tear Seam', '100% Barcode Traceability', 'FMVSS 208 Compliant'],
            'btn_primary_text' => 'Modul Kantung Udara',
            'btn_primary_link' => 'products.php?category=Airbag',
            'btn_secondary_text' => 'Fasilitas Perakitan',
            'btn_secondary_link' => 'facility.php'
        ]),
        'sort_order' => 20
    ],
    [
        'section_key' => 'hero_slides',
        'item_key' => 'slide_steering',
        'title' => 'Takumi Master Craftsmanship: Genuine Leather Steering Wheels',
        'subtitle' => 'PREMIUM CABIN ERGONOMICS & DIE-CAST RIGIDITY',
        'content' => 'Kombinasi rangka logam magnesium/aluminium die-cast ringan dengan busa poliuretan berdensitas tinggi serta balutan kulit asli jahitan tangan presisi.',
        'badge' => 'Takumi Craftsmanship',
        'image' => 'uploads/products/product_steering_wheel.jpg',
        'icon' => 'bi-disc',
        'extra_data' => json_encode([
            'badges' => ['Magnesium Die-Cast Core', 'Genuine Hand-Stitched Leather', 'Paddle Shift & Multi-Switch', 'Integrated Heating'],
            'btn_primary_text' => 'Roda Kemudi',
            'btn_primary_link' => 'products.php?category=Steering',
            'btn_secondary_text' => 'Spesifikasi Teknik',
            'btn_secondary_link' => 'facility.php'
        ]),
        'sort_order' => 30
    ],
    [
        'section_key' => 'hero_slides',
        'item_key' => 'slide_esg',
        'title' => 'Green Monozukuri & Carbon Neutrality ESG Initiatives',
        'subtitle' => 'SUSTAINABLE ECO-RESIN & SOLAR PV INTEGRATION',
        'content' => 'Mengurangi 450 ton emisi CO₂ per tahun melalui instalasi solar rooftop fotovoltaik dan pemanfaatan resin daur ulang polipropilena ramah lingkungan.',
        'badge' => '450 Ton CO₂ Reduced / Year',
        'image' => 'uploads/pages/banner_home.jpg',
        'icon' => 'bi-sun-fill',
        'extra_data' => json_encode([
            'badges' => ['Solar PV Rooftop', '28% Recycled Resin Target', 'RoHS & ELV Compliant', 'ISO 14001:2015'],
            'btn_primary_text' => 'Inisiatif ESG',
            'btn_primary_link' => 'about.php',
            'btn_secondary_text' => 'Kirim Pengajuan RFQ',
            'btn_secondary_link' => 'contact.php'
        ]),
        'sort_order' => 40
    ]
];

$stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, icon, image, badge, extra_data, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

$count = 0;
foreach ($heroSlides as $s) {
    // Delete existing to update cleanly
    $del = $conn->prepare("DELETE FROM site_section_items WHERE section_key = 'hero_slides' AND item_key = ?");
    $del->bind_param("s", $s['item_key']);
    $del->execute();
    $del->close();

    $stmt->bind_param("sssssssssi", 
        $s['section_key'], 
        $s['item_key'], 
        $s['title'], 
        $s['subtitle'], 
        $s['content'], 
        $s['icon'], 
        $s['image'], 
        $s['badge'], 
        $s['extra_data'], 
        $s['sort_order']
    );
    if ($stmt->execute()) {
        $count++;
    }
}
$stmt->close();

echo "Seeded {$count} dynamic hero slides into site_section_items!\n";
