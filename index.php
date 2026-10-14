<?php
require_once __DIR__ . "/config/database.php";

/*
 * HOME PAGE PT NIHON PLAST INDONESIA
 * Tier-1 Automotive Safety, Steering Wheel & Precision Resin Systems
 * Meets IATF 16949, ISO 9001, ISO 14001, ISO 45001 / SMK3 Standards
 * 100% Dynamic CMS Elements & Granular Active/Inactive Controls
 */

// 1. Company Profile
$companyResult = $conn->query("SELECT * FROM company_profile ORDER BY id ASC LIMIT 1");
$company = $companyResult ? $companyResult->fetch_assoc() : [];

// 2. Core Products
$products = $conn->query("SELECT * FROM products ORDER BY id ASC LIMIT 4");

// 3. Certifications & ISO Standards
$certifications = $conn->query("SELECT * FROM certifications WHERE status = 'active' ORDER BY sort_order ASC, id ASC");

// 4. Latest News
$news = $conn->query("SELECT * FROM news ORDER BY published_at DESC LIMIT 3");

// 5. Gallery (100% Static High-Resolution Photos)
$galleryItems = $conn->query("SELECT id, title, image FROM gallery ORDER BY id DESC LIMIT 6");

// 6. Dynamic Site Sections Engine (CMS Elements with Active / Inactive State)
$sectionsResult = $conn->query("SELECT * FROM site_sections WHERE page = 'home'");
$homeSections = [];
if ($sectionsResult) {
    while ($sec = $sectionsResult->fetch_assoc()) {
        $homeSections[$sec['section_key']] = $sec;
    }
}

if (!function_exists('sec_active')) {
    function sec_active($key, $default = true) {
        global $homeSections;
        if (isset($homeSections[$key])) {
            return (bool)$homeSections[$key]['is_active'];
        }
        return $default;
    }
}

if (!function_exists('sec_val')) {
    function sec_val($key, $field, $default = '') {
        global $homeSections;
        if (isset($homeSections[$key]) && !empty($homeSections[$key][$field])) {
            return $homeSections[$key][$field];
        }
        return $default;
    }
}

if (!function_exists('homeImagePath')) {
    function homeImagePath($path, $folder = "") {
        $path = trim((string) $path);
        if ($path === "") return "";
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        if (strpos($path, "uploads/") === 0) return $path;
        if ($folder !== "") return rtrim($folder, "/") . "/" . basename($path);
        return $path;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars($company["company_name"] ?? "PT Nihon Plast Indonesia") ?> - <?= htmlspecialchars(__('hero_badge', 'Tier-1 Automotive Safety & Precision Systems')) ?></title>
    <meta name="description" content="<?= htmlspecialchars(__('site_meta_desc', 'PT Nihon Plast Indonesia - Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Resin Interior dengan standar IATF 16949 & ISO.')) ?>">
    <link rel="stylesheet" href="assets/css/home-scroll.css">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<!-- 1. HERO SECTION: High-Tech Automotive Tier-1 -->
<?php if (sec_active('hero')): ?>
    <?php
    $heroBg = sec_val('hero', 'bg_image', '');
    $heroStyle = !empty($heroBg) ? 'style="background: linear-gradient(rgba(15,23,42,0.7), rgba(15,23,42,0.85)), url(' . htmlspecialchars($heroBg) . ') center/cover no-repeat;"' : '';
    ?>
    <header class="hero-corporate" <?= $heroStyle ?>>
        <div class="container py-5 my-auto">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(sec_val('hero', 'icon', 'bi-shield-check')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('hero', 'badge_text', __('hero_badge', 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS'))) ?>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">
                        <?= htmlspecialchars(sec_val('hero', 'title', __('hero_title', 'Producing Reliable Technology for Complete Peace of Mind'))) ?>
                    </h1>
                    <p class="lead-text">
                        <?= htmlspecialchars(sec_val('hero', 'subtitle', $company["short_description"] ?? __('hero_desc', 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.'))) ?>
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= htmlspecialchars(sec_val('hero', 'button_link', '#products-home')) ?>" class="btn btn-np-primary">
                            <?= htmlspecialchars(sec_val('hero', 'button_text', __('hero_btn_products', 'Jelajahi Produk'))) ?> <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="contact.php" class="btn btn-np-outline">
                            <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars(__('hero_btn_rfq', 'Konsultasi Teknis & B2B RFQ')) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>

<!-- 2. QUICK STATS BAR -->
<?php if (sec_active('stats')): ?>
    <div class="container">
        <div class="stats-bar">
            <div class="row g-4 align-items-center">
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-value text-np-green"><?= htmlspecialchars(__('stat_iso_title', 'IATF 16949 / ISO 14001')) ?></div>
                    <div class="stat-label"><?= htmlspecialchars(__('stat_iso_sub', 'Standar Mutu Otomotif Global')) ?></div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-value text-np-green"><?= htmlspecialchars(__('stat_products_title', '4 Lini Produk Utama')) ?></div>
                    <div class="stat-label"><?= htmlspecialchars(__('stat_products_sub', 'Steering, Airbag, Interior & Eksterior Resin')) ?></div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-value text-np-green"><?= htmlspecialchars(__('stat_facility_title', 'Pabrik Cikampek Karawang')) ?></div>
                    <div class="stat-label"><?= htmlspecialchars(__('stat_facility_sub', 'Cleanroom Class 8 & Injeksi 150T-2500T')) ?></div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-value text-np-green"><?= htmlspecialchars(__('stat_safety_title', 'Zero Defect & Zero LTI')) ?></div>
                    <div class="stat-label"><?= htmlspecialchars(__('stat_safety_sub', 'Disiplin Budaya K3 Kemnaker RI & 5S')) ?></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- 3. OEM TRUST BAR: AUTOMOTIVE PARTNERS & CLIENT ELEVATION -->
<?php if (sec_active('oem_trust')): ?>
    <section class="oem-trust-section mt-4">
        <div class="container text-center">
            <span class="eyebrow-badge mb-3">
                <i class="bi <?= htmlspecialchars(sec_val('oem_trust', 'icon', 'bi-award-fill')) ?>"></i> 
                <?= htmlspecialchars(sec_val('oem_trust', 'badge_text', __('oem_trust_badge', 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS'))) ?>
            </span>
            <div class="oem-grid mt-2">
                <div class="oem-badge-item">TOYOTA</div>
                <div class="oem-badge-item">HONDA</div>
                <div class="oem-badge-item">NISSAN</div>
                <div class="oem-badge-item">DAIHATSU</div>
                <div class="oem-badge-item">MITSUBISHI</div>
                <div class="oem-badge-item">SUZUKI</div>
                <div class="oem-badge-item">SUBARU</div>
                <div class="oem-badge-item">MAZDA</div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- TOMBOL NAVIGASI HALAMAN HOME -->
<nav class="home-section-nav mt-4" aria-label="Navigasi bagian Home">
    <div class="container">
        <?php if (sec_active('about_intro')): ?><a href="#about-home"><?= htmlspecialchars(__('nav_about', 'About')) ?></a><?php endif; ?>
        <?php if (sec_active('vehicle_explorer')): ?><a href="#vehicle-explorer-home"><?= htmlspecialchars(__('vehicle_explorer_title', 'Vehicle Explorer')) ?></a><?php endif; ?>
        <?php if (sec_active('products_grid')): ?><a href="#products-home"><?= htmlspecialchars(__('nav_products', 'Products')) ?></a><?php endif; ?>
        <?php if (sec_active('certifications_grid')): ?><a href="#certifications-home"><?= htmlspecialchars(__('nav_quality', 'Certifications & ISO')) ?></a><?php endif; ?>
        <?php if (sec_active('quality_pillars')): ?><a href="#quality-home"><?= htmlspecialchars(__('quality_title', 'Quality & Monozukuri')) ?></a><?php endif; ?>
        <?php if (sec_active('facility_preview')): ?><a href="#facility-home"><?= htmlspecialchars(__('nav_facility', 'Facility')) ?></a><?php endif; ?>
        <?php if (sec_active('esg_sustainability')): ?><a href="#esg-home"><?= htmlspecialchars(__('esg_title', 'Green Monozukuri')) ?></a><?php endif; ?>
        <?php if (sec_active('gallery_preview')): ?><a href="#gallery-home"><?= htmlspecialchars(__('nav_gallery', 'Gallery')) ?></a><?php endif; ?>
        <?php if (sec_active('news_preview')): ?><a href="#news-home"><?= htmlspecialchars(__('nav_news', 'News')) ?></a><?php endif; ?>
        <?php if (sec_active('contact_cta')): ?><a href="#contact-home"><?= htmlspecialchars(__('nav_contact', 'Contact')) ?></a><?php endif; ?>
    </div>
</nav>

<!-- 4. ABOUT COMPANY -->
<?php if (sec_active('about_intro')): ?>
    <section id="about-home" class="home-section section-padding">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-7">
                    <div class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(sec_val('about_intro', 'icon', 'bi-building')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('about_intro', 'badge_text', __('about_intro_badge', 'PROFIL SINGKAT PERUSAHAAN'))) ?>
                    </div>
                    <h2 class="h1 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(sec_val('about_intro', 'title', $company["company_name"] ?? __('brand_name', 'PT Nihon Plast Indonesia'))) ?>
                    </h2>
                    <p class="fs-5 text-secondary fw-semibold mb-3">
                        <?= htmlspecialchars(sec_val('about_intro', 'subtitle', $company["tagline"] ?? __('about_intro_title', 'Driving Safety & Precision Through Japanese Monozukuri'))) ?>
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        <?= nl2br(htmlspecialchars(sec_val('about_intro', 'content', $company["about_text"] ?? __('about_intro_p1', 'PT Nihon Plast Indonesia beroperasi di Kawasan Industri Cikampek, Karawang, Jawa Barat sebagai anak perusahaan dari Nihon Plast Co., Ltd. (Jepang).')))) ?>
                    </p>

                    <!-- Visi & Nilai Inti Ringkas -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-compass me-1"></i> Visi Korporat</h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($company["vision"] ?? "Menjadi mitra manufaktur otomotif One-of-a-Kind yang tak tergantikan.") ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-award me-1"></i> Nilai Inti</h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($company["values_text"] ?? "Monozukuri (Craftsmanship), Safety First, Kaizen, and Harmony.") ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= htmlspecialchars(sec_val('about_intro', 'button_link', 'about.php')) ?>" class="btn btn-np-primary">
                            <?= htmlspecialchars(sec_val('about_intro', 'button_text', __('about_btn_more', 'Profil Selengkapnya'))) ?>
                        </a>
                        <a href="contact.php" class="btn btn-np-outline"><?= htmlspecialchars(__('contact_us', 'Hubungi Kami')) ?></a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-np-green p-3 rounded-3 text-white"><i class="bi bi-geo-alt-fill fs-4"></i></span>
                            <div>
                                <h5 class="fw-bold mb-0">Kantor & Fasilitas Pabrik</h5>
                                <small class="text-muted">Kawasan Industri Indotaisei, Cikampek, Karawang</small>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2"><strong>Alamat Pabrik:</strong><br><span class="text-muted"><?= nl2br(htmlspecialchars($company["address"] ?? "Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang, Jawa Barat 41373")) ?></span></p>
                        <p class="mb-2"><strong>Telepon:</strong><br><span class="text-muted"><?= htmlspecialchars($company["phone"] ?? "+62 (264) 351-888") ?></span></p>
                        <p class="mb-0"><strong>Email Resmi:</strong><br><span class="text-muted"><?= htmlspecialchars($company["email"] ?? "corporate@n-plast.co.id") ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 5. INTERACTIVE VEHICLE ZONE EXPLORER -->
<?php if (sec_active('vehicle_explorer')): ?>
    <section id="vehicle-explorer-home" class="home-section section-padding pb-0">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-4">
                <span class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(sec_val('vehicle_explorer', 'icon', 'bi-car-front-fill')) ?>"></i> 
                    <?= htmlspecialchars(sec_val('vehicle_explorer', 'badge_text', __('vehicle_explorer_badge', 'VEHICLE APPLICATION EXPLORER'))) ?>
                </span>
                <h2 class="display-6 fw-bold text-np-green mb-2">
                    <?= htmlspecialchars(sec_val('vehicle_explorer', 'title', __('vehicle_explorer_title', 'Peta Penerapan Komponen pada Kendaraan'))) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(sec_val('vehicle_explorer', 'subtitle', __('vehicle_explorer_desc', 'Jelajahi integrasi teknologi keselamatan dan komponen interior Nihon Plast pada arsitektur mobil modern.'))) ?>
                </p>
            </div>

            <div class="vehicle-explorer-card">
                <!-- Nav Tabs -->
                <div class="zone-nav-tabs" role="tablist">
                    <button type="button" class="zone-tab-btn active" data-zone="steering">
                        <i class="bi bi-circle-square"></i> 1. <?= htmlspecialchars(__('zone_steering_title', 'Roda Kemudi (Steering)')) ?>
                    </button>
                    <button type="button" class="zone-tab-btn" data-zone="airbag">
                        <i class="bi bi-shield-shaded"></i> 2. <?= htmlspecialchars(__('zone_airbag_title', 'Modul Airbag Keselamatan')) ?>
                    </button>
                    <button type="button" class="zone-tab-btn" data-zone="cockpit">
                        <i class="bi bi-speedometer2"></i> 3. <?= htmlspecialchars(__('zone_interior_title', 'Kokpit & Konsol Tengah')) ?>
                    </button>
                    <button type="button" class="zone-tab-btn" data-zone="cleanroom">
                        <i class="bi bi-shield-check"></i> 4. <?= htmlspecialchars(__('zone_cleanroom_title', 'Area Cleanroom Class 8')) ?>
                    </button>
                </div>

                <!-- Pane 1: Steering Wheel -->
                <div class="zone-content-pane active" id="zone-steering">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <div class="zone-visual-display">
                                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=900&q=80" alt="Steering Wheel System Application">
                                <div class="zone-hotspot" style="top: 45%; left: 50%;" title="Driver Airbag & Switch Module">1</div>
                                <div class="zone-hotspot" style="top: 25%; left: 25%;" title="Genuine Leather Stitching">2</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Zone: Driver Cockpit Interface</span>
                            <h3 class="h4 fw-bold text-np-green mb-3"><?= htmlspecialchars(__('zone_steering_title', 'Steering Wheel Systems & Ergonomics')) ?></h3>
                            <p class="text-muted mb-4">
                                <?= htmlspecialchars(__('zone_steering_desc', 'Rangka magnesium/aluminium die-cast ringan dengan busa poliuretan integral, balutan kulit premium jahitan tangan, dan tombol multifungsi kontrol presisi tinggi.')) ?>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Magnesium/Al Die-Cast Core</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Hand-Crafted Genuine Leather</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Multi-Switch & Paddle Shift</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> FMVSS 208 / ECE R12 Compliant</span>
                            </div>
                            <a href="products.php" class="btn btn-np-primary btn-sm">
                                <?= htmlspecialchars(__('btn_view_more', 'Lihat Katalog Steering')) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pane 2: Airbags -->
                <div class="zone-content-pane" id="zone-airbag">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <div class="zone-visual-display">
                                <img src="https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=900&q=80" alt="Airbag Safety Module Application">
                                <div class="zone-hotspot" style="top: 50%; left: 48%;" title="Driver Airbag Inflator Module">1</div>
                                <div class="zone-hotspot" style="top: 30%; left: 75%;" title="Passenger Cushion Deployment">2</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Zone: Active & Passive Safety</span>
                            <h3 class="h4 fw-bold text-np-green mb-3"><?= htmlspecialchars(__('zone_airbag_title', 'Airbag Safety Modules & Inflator Encasement')) ?></h3>
                            <p class="text-muted mb-4">
                                <?= htmlspecialchars(__('zone_airbag_desc', 'Sistem proteksi benturan berkecepatan tinggi yang dirancang untuk mengembang sempurna dalam 20 hingga 30 milidetik.')) ?>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Driver, Passenger, Side Curtain</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Cleanroom ISO Class 8 Assembly</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Laser-Scored Seamless Tear Seam</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> 100% Traceability Barcode</span>
                            </div>
                            <a href="products.php" class="btn btn-np-primary btn-sm">
                                <?= htmlspecialchars(__('btn_view_more', 'Lihat Katalog Airbag')) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pane 3: Cockpit Resins -->
                <div class="zone-content-pane" id="zone-cockpit">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <div class="zone-visual-display">
                                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80" alt="Cockpit & Instrument Panel Resins">
                                <div class="zone-hotspot" style="top: 40%; left: 55%;" title="Center Console & AC Vents">1</div>
                                <div class="zone-hotspot" style="top: 60%; left: 35%;" title="Glovebox & Lower Trims">2</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Zone: Vehicle Interior & Cabin</span>
                            <h3 class="h4 fw-bold text-np-green mb-3"><?= htmlspecialchars(__('zone_interior_title', 'Cockpit, Instrument Panel & Center Console')) ?></h3>
                            <p class="text-muted mb-4">
                                <?= htmlspecialchars(__('zone_interior_desc', 'Komponen resin interior berskala besar dicetak menggunakan mesin injeksi presisi tinggi hingga 2.500 Ton.')) ?>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> High-Tonnage Resin Molding</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Low-VOC & Recycled PP Formulations</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Integrated Air Vent Registers</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> High Acoustic Damping</span>
                            </div>
                            <a href="products.php" class="btn btn-np-primary btn-sm">
                                <?= htmlspecialchars(__('btn_view_more', 'Lihat Katalog Interior')) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pane 4: Cleanroom Class 8 -->
                <div class="zone-content-pane" id="zone-cleanroom">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <div class="zone-visual-display">
                                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=900&q=80" alt="Cleanroom Class 8 Assembly">
                                <div class="zone-hotspot" style="top: 35%; left: 60%;" title="Positive Pressure Air Filtration">1</div>
                                <div class="zone-hotspot" style="top: 70%; left: 45%;" title="ESD Controlled Workstation">2</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Zone: High-Precision Cleanroom</span>
                            <h3 class="h4 fw-bold text-np-green mb-3"><?= htmlspecialchars(__('zone_cleanroom_title', 'Area Perakitan Bersih (Cleanroom Class 8)')) ?></h3>
                            <p class="text-muted mb-4">
                                <?= htmlspecialchars(__('zone_cleanroom_desc', 'Lingkungan bertekanan positif bebas debu dengan kontrol elektrostatik (ESD) untuk perakitan sensor krusial dan modul airbag.')) ?>
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> ISO 14644-1 Class 8 Certified</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> ESD Safe Anti-Static Flooring</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Temperature & Humidity Lock</span>
                                <span class="spec-badge-pill"><i class="bi bi-check2"></i> Strict Air Shower Protocol</span>
                            </div>
                            <a href="facility.php" class="btn btn-np-primary btn-sm">
                                <?= htmlspecialchars(__('btn_view_more', 'Jelajahi Cleanroom')) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 6. PRODUCTS: 4 Core Pillars of Nihon Plast -->
<?php if (sec_active('products_grid')): ?>
    <section id="products-home" class="home-section section-padding section-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <div class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(sec_val('products_grid', 'icon', 'bi-grid-3x3-gap-fill')) ?>"></i> 
                    <?= htmlspecialchars(sec_val('products_grid', 'badge_text', __('products_section_badge', 'PORTFOLIO KEUNGGULAN PRODUK'))) ?>
                </div>
                <h2 class="h1 fw-bold text-np-green">
                    <?= htmlspecialchars(sec_val('products_grid', 'title', __('products_section_title', 'Sistem Komponen Otomotif'))) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(sec_val('products_grid', 'subtitle', __('products_section_desc', 'Komponen interior resin presisi tinggi, modul keselamatan airbag, dan roda kemudi berkualitas OEM dengan standar kepatuhan ketat.'))) ?>
                </p>
            </div>

            <div class="row g-4">
                <?php if ($products && $products->num_rows > 0): ?>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <?php $productImage = homeImagePath($product["image"] ?? "", "uploads/products"); ?>
                        <div class="col-md-6 col-lg-3">
                            <article class="product-card-modern">
                                <div class="product-thumb-wrapper">
                                    <span class="product-pill">Tier-1 OEM</span>
                                    <?php if ($productImage !== ""): ?>
                                        <img src="<?= htmlspecialchars($productImage) ?>"
                                             class="product-card-thumb"
                                             alt="<?= htmlspecialchars($product["name"] ?? "Product") ?>"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="home-image-placeholder"><span>Nihon Plast Component</span></div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <h3 class="h6 fw-bold text-np-green mb-2"><?= htmlspecialchars($product["name"] ?? "") ?></h3>
                                    <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                        <?= htmlspecialchars($product["description"] ?? "") ?>
                                    </p>
                                    <a href="product-detail.php?id=<?= (int)$product['id'] ?>" class="small fw-bold text-np-green text-decoration-none">
                                        <?= htmlspecialchars(__('view_details', 'Detail Spesifikasi')) ?> <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?= htmlspecialchars(sec_val('products_grid', 'button_link', 'products.php')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('products_grid', 'button_text', __('products_btn_all', 'Lihat Semua Lini Produk'))) ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 7. CERTIFICATIONS & INDONESIAN REGULATORY COMPLIANCE -->
<?php if (sec_active('certifications_grid')): ?>
    <section id="certifications-home" class="home-section section-padding">
        <div class="container">
            <div class="row align-items-end justify-content-between mb-5">
                <div class="col-lg-8">
                    <div class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(sec_val('certifications_grid', 'icon', 'bi-patch-check-fill')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('certifications_grid', 'badge_text', __('certifications_badge', 'STANDAR INTERNASIONAL & REGULASI INDONESIA'))) ?>
                    </div>
                    <h2 class="h1 fw-bold text-np-green mb-2">
                        <?= htmlspecialchars(sec_val('certifications_grid', 'title', __('certifications_title', 'Standar Internasional & Regulasi Indonesia'))) ?>
                    </h2>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars(sec_val('certifications_grid', 'subtitle', __('certifications_desc', 'PT Nihon Plast Indonesia beroperasi penuh dengan standar mutu otomotif internasional serta kepatuhan mutlak terhadap regulasi Kemenperin, KLHK, dan Kemnaker RI.'))) ?>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="<?= htmlspecialchars(sec_val('certifications_grid', 'button_link', 'quality.php')) ?>" class="btn btn-np-primary">
                        <?= htmlspecialchars(sec_val('certifications_grid', 'button_text', __('certifications_btn', 'Pelajari Kebijakan Mutu'))) ?> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <?php if ($certifications && $certifications->num_rows > 0): ?>
                    <?php while ($cert = $certifications->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="cert-card <?= str_contains($cert['name'], 'IATF') ? 'featured-iatf' : '' ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="cert-card-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <span class="badge bg-np-tint text-np-green border border-success-subtle small">
                                        <?= htmlspecialchars($cert['category']) ?>
                                    </span>
                                </div>

                                <h4 class="h5 fw-bold text-np-green mb-2">
                                    <?= htmlspecialchars($cert['name']) ?>
                                </h4>

                                <?php if (!empty($cert['certificate_number'])): ?>
                                    <div>
                                        <span class="cert-number-badge">
                                            No: <?= htmlspecialchars($cert['certificate_number']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                    <?= htmlspecialchars($cert['description']) ?>
                                </p>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
                                    <span><i class="bi bi-bank me-1"></i><?= htmlspecialchars($cert['issued_by'] ?? 'Accredited Body') ?></span>
                                    <?php if (!empty($cert['valid_until'])): ?>
                                        <span class="badge bg-light text-dark border">
                                            Valid: <?= date('Y', strtotime($cert['valid_until'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 8. QUALITY & MONOZUKURI ASSURANCE -->
<?php if (sec_active('quality_pillars')): ?>
    <section id="quality-home" class="home-section section-padding section-dark">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <div class="eyebrow-badge badge-white mx-auto">
                    <i class="bi <?= htmlspecialchars(sec_val('quality_pillars', 'icon', 'bi-gear-wide-connected')) ?>"></i> 
                    <?= htmlspecialchars(sec_val('quality_pillars', 'badge_text', __('quality_badge', 'JAPANESE MONOZUKURI PHILOSOPHY'))) ?>
                </div>
                <h2 class="h1 fw-bold text-white">
                    <?= htmlspecialchars(sec_val('quality_pillars', 'title', __('quality_title', 'Komitmen Mutu & Zero Defect'))) ?>
                </h2>
                <p class="text-white-50">
                    <?= htmlspecialchars(sec_val('quality_pillars', 'subtitle', __('quality_desc', 'Setiap produk yang keluar dari pabrik melewati serangkaian inspeksi berstandar tinggi demi memastikan keselamatan pengemudi dan penumpang.'))) ?>
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-num">01</div>
                        <h5 class="fw-bold text-np-green mb-2"><?= htmlspecialchars(__('quality_pillar1_title', 'Raw Material QA')) ?></h5>
                        <p class="small text-muted mb-0"><?= htmlspecialchars(__('quality_pillar1_desc', 'Inspeksi spektrometri dan uji densitas bahan baku resin, kulit asli, serta busa polyurethane.')) ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-num">02</div>
                        <h5 class="fw-bold text-np-green mb-2">Precision Injection</h5>
                        <p class="small text-muted mb-0">Mesin injeksi berteknologi servo Jepang dengan kontrol suhu dan tekanan mikro untuk toleransi ukuran mikron.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-num">03</div>
                        <h5 class="fw-bold text-np-green mb-2"><?= htmlspecialchars(__('quality_pillar2_title', 'Master Craftsmanship & CMM')) ?></h5>
                        <p class="small text-muted mb-0"><?= htmlspecialchars(__('quality_pillar2_desc', 'Inspeksi dimensi geometri Coordinate Measuring Machine (CMM) berkalibrasi rutin laser.')) ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-num">04</div>
                        <h5 class="fw-bold text-np-green mb-2"><?= htmlspecialchars(__('quality_pillar3_title', 'Safety Deployment Lab')) ?></h5>
                        <p class="small text-muted mb-0"><?= htmlspecialchars(__('quality_pillar3_desc', 'Uji kecepatan tinggi dan ketahanan benturan sensor airbag drop-tower demi jaminan keselamatan mutlak.')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 9. FACILITY: Factory Overview -->
<?php if (sec_active('facility_preview')): ?>
    <section id="facility-home" class="home-section section-padding section-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <div class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(sec_val('facility_preview', 'icon', 'bi-buildings')) ?>"></i> 
                    <?= htmlspecialchars(sec_val('facility_preview', 'badge_text', __('facility_badge', 'MANUFACTURING FACILITY'))) ?>
                </div>
                <h2 class="h1 fw-bold text-np-green">
                    <?= htmlspecialchars(sec_val('facility_preview', 'title', __('facility_title', 'Fasilitas Pabrik Presisi di Cikampek, Karawang'))) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(sec_val('facility_preview', 'subtitle', __('facility_desc', 'Mengintegrasikan mesin injeksi plastik presisi tinggi 150T–2500T, Cleanroom Assembly Class 8, dan lab kontrol kualitas berstandar 5S.'))) ?>
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=800&q=80" class="home-card-image" alt="Engineering Lab" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">Engineering & Mold R&D</h5>
                            <p class="small text-muted mb-0">Dukungan tim rekayasa teknis untuk pembuatan dan perawatan mold presisi cetak resin plastik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <img src="https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=800&q=80" class="home-card-image" alt="Production Facility" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">High-Tonnage Injection Line</h5>
                            <p class="small text-muted mb-0">Area 22 mesin injeksi multi-tonase yang mampu memproduksi komponen interior besar dan cover eksterior.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80" class="home-card-image" alt="Cleanroom Assembly" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">Safety Module Cleanroom</h5>
                            <p class="small text-muted mb-0">Area perakitan steril berstandar ISO Class 8 untuk modul airbag dan switch kendali setir kemudi mobil.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= htmlspecialchars(sec_val('facility_preview', 'button_link', 'facility.php')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('facility_preview', 'button_text', __('facility_btn', 'Jelajahi Seluruh Fasilitas Pabrik'))) ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 10. GREEN MONOZUKURI & SUSTAINABILITY (ESG) -->
<?php if (sec_active('esg_sustainability')): ?>
    <section id="esg-home" class="home-section section-padding bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(sec_val('esg_sustainability', 'icon', 'bi-recycle')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('esg_sustainability', 'badge_text', __('esg_badge', 'SUSTAINABILITY & CARBON NEUTRALITY'))) ?>
                    </span>
                    <h2 class="display-6 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(sec_val('esg_sustainability', 'title', __('esg_title', 'Green Monozukuri: Komitmen Keberlanjutan Otomotif'))) ?>
                    </h2>
                    <p class="text-muted mb-4">
                        <?= htmlspecialchars(sec_val('esg_sustainability', 'subtitle', __('esg_desc', 'Pabrik Karawang aktif menerapkan integrasi material resin daur ulang ramah lingkungan, efisiensi energi solar fotovoltaik, dan standar ISO 14001.'))) ?>
                    </p>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="esg-stat-box h-100">
                                <div class="esg-icon-circle"><i class="bi bi-sun-fill"></i></div>
                                <div class="fs-4 fw-bold text-np-green">450 Ton CO₂</div>
                                <div class="small text-muted fw-semibold">Pengurangan Emisi Karbon/Tahun via Rooftop Solar PV</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="esg-stat-box h-100">
                                <div class="esg-icon-circle"><i class="bi bi-tree-fill"></i></div>
                                <div class="fs-4 fw-bold text-np-green">28% Target</div>
                                <div class="small text-muted fw-semibold">Rasio Penggunaan Recycled Polypropylene Resin</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 bg-white rounded-4 border shadow-sm">
                        <h5 class="fw-bold text-np-green mb-3"><i class="bi bi-check-all text-success me-2"></i>Pilar Kepatuhan Lingkungan & K3</h5>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                                <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                <div>
                                    <strong>Sertifikasi ISO 14001:2015 Terakreditasi</strong>
                                    <p class="text-muted mb-0">Sistem manajemen lingkungan terintegrasi dengan pemilahan limbah resin 100% terkontrol.</p>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <div>
                                    <strong>SMK3 PP 50/2012 & Zero Accident Award</strong>
                                    <p class="text-muted mb-0">Budaya K3 Jepang (5S & Anzen Daiichi) melindungi keselamatan seluruh tenaga kerja.</p>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                                <i class="bi bi-droplet-half text-success fs-5"></i>
                                <div>
                                    <strong>RoHS & ELV (End-of-Life Vehicles) Compliant</strong>
                                    <p class="text-muted mb-0">Bebas senyawa berbahaya timbal, merkuri, kadmium, dan kromium heksavalen.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 11. GALLERY (100% Static High-Resolution Photos) -->
<?php if (sec_active('gallery_preview')): ?>
    <section id="gallery-home" class="home-section section-padding">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <div class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(sec_val('gallery_preview', 'icon', 'bi-camera-fill')) ?>"></i> 
                    <?= htmlspecialchars(sec_val('gallery_preview', 'badge_text', __('gallery_badge', 'DOKUMENTASI FOTO MANUFAKTUR'))) ?>
                </div>
                <h2 class="h1 fw-bold text-np-green">
                    <?= htmlspecialchars(sec_val('gallery_preview', 'title', __('gallery_title', 'Galeri Aktivitas & Fasilitas'))) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(sec_val('gallery_preview', 'subtitle', __('gallery_desc', 'Dokumentasi fotografi beresolusi tinggi mengenai kegiatan produksi manufaktur, budaya keselamatan 5S, dan fasilitas pabrik.'))) ?>
                </p>
            </div>

            <div class="row g-4">
                <?php if ($galleryItems && $galleryItems->num_rows > 0): ?>
                    <?php while ($item = $galleryItems->fetch_assoc()): ?>
                        <?php $galleryImage = homeImagePath($item["image"] ?? "", "uploads/gallery"); ?>
                        <div class="col-6 col-lg-4">
                            <article class="home-gallery-card border-0 shadow-sm rounded-3 overflow-hidden">
                                <?php if ($galleryImage !== ""): ?>
                                    <img src="<?= htmlspecialchars($galleryImage) ?>"
                                         alt="<?= htmlspecialchars($item["title"] ?? "Company gallery") ?>"
                                         style="height: 220px; object-fit: cover; width: 100%; display: block;"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="home-image-placeholder p-5 text-center text-muted bg-light">
                                        <i class="bi bi-image fs-1 d-block mb-1"></i> Foto Presisi
                                    </div>
                                <?php endif; ?>
                                <div class="home-gallery-caption p-3 bg-white">
                                    <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($item["title"] ?? "") ?></h6>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
                <a href="<?= htmlspecialchars(sec_val('gallery_preview', 'button_link', 'gallery.php')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('gallery_preview', 'button_text', __('gallery_btn', 'Buka Galeri Foto Lengkap'))) ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 12. NEWS SECTION -->
<?php if (sec_active('news_preview')): ?>
    <section id="news-home" class="home-section section-padding section-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                <div>
                    <div class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(sec_val('news_preview', 'icon', 'bi-newspaper')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('news_preview', 'badge_text', __('news_badge', 'LATEST UPDATES'))) ?>
                    </div>
                    <h2 class="h1 fw-bold text-np-green mb-0">
                        <?= htmlspecialchars(sec_val('news_preview', 'title', __('news_title', 'Berita & Informasi Perusahaan'))) ?>
                    </h2>
                </div>
                <a href="<?= htmlspecialchars(sec_val('news_preview', 'button_link', 'news.php')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('news_preview', 'button_text', __('news_btn', 'Lihat Semua Berita'))) ?>
                </a>
            </div>

            <div class="row g-4">
                <?php if ($news && $news->num_rows > 0): ?>
                    <?php while ($article = $news->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <article class="card border-0 shadow-sm rounded-4 h-100 p-4 bg-white">
                                <span class="small text-muted mb-2"><i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($article['published_at'])) ?></span>
                                <h3 class="h5 fw-bold text-np-green mb-3"><?= htmlspecialchars($article['title']) ?></h3>
                                <p class="small text-muted flex-grow-1" style="line-height: 1.6;">
                                    <?= htmlspecialchars(substr($article['content'], 0, 140)) ?>...
                                </p>
                                <a href="news-detail.php?id=<?= (int)$article['id'] ?>" class="small fw-bold text-np-green text-decoration-none">
                                    <?= htmlspecialchars(__('read_more', 'Baca Selengkapnya')) ?> <i class="bi bi-arrow-right"></i>
                                </a>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 13. CONTACT CTA (B2B SOURCING & PROCUREMENT) -->
<?php if (sec_active('contact_cta')): ?>
    <section id="contact-home" class="home-section section-padding">
        <div class="container">
            <div class="card border-0 shadow-lg rounded-4 p-5 bg-np-green text-white text-center">
                <div class="max-w-700 mx-auto">
                    <span class="badge bg-white text-np-green px-3 py-2 rounded-pill fw-bold text-uppercase mb-3">
                        <i class="bi <?= htmlspecialchars(sec_val('contact_cta', 'icon', 'bi-briefcase-fill')) ?> me-1"></i> 
                        <?= htmlspecialchars(sec_val('contact_cta', 'badge_text', __('contact_cta_badge', 'OEM B2B SOURCING & TECHNICAL RFQ'))) ?>
                    </span>
                    <h2 class="display-6 fw-bold text-white mb-3">
                        <?= htmlspecialchars(sec_val('contact_cta', 'title', __('contact_cta_title', 'Siap Bermitra dengan Manufaktur Berstandar Dunia?'))) ?>
                    </h2>
                    <p class="text-white-50 fs-5 mb-4">
                        <?= htmlspecialchars(sec_val('contact_cta', 'subtitle', __('contact_cta_desc', 'Hubungi tim perwakilan PT Nihon Plast Indonesia untuk kebutuhan pasokan komponen roda kemudi, sistem kantung udara (airbags), dan pencetakan resin interior presisi tinggi bersertifikasi IATF 16949.'))) ?>
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="<?= htmlspecialchars(sec_val('contact_cta', 'button_link', 'contact.php')) ?>" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-np-green shadow">
                            <i class="bi bi-file-earmark-text me-1"></i> <?= htmlspecialchars(sec_val('contact_cta', 'button_text', __('contact_cta_btn', 'Ajukan B2B RFQ Inquiry'))) ?>
                        </a>
                        <a href="contact.php#download-profile" class="btn btn-outline-light rounded-pill px-4 py-3 fw-semibold">
                            <i class="bi bi-download me-1"></i> <?= htmlspecialchars(__('download_spec', 'Unduh Company Profile')) ?>
                        </a>
                        <a href="career.php" class="btn btn-outline-light rounded-pill px-4 py-3 fw-semibold">
                            <?= htmlspecialchars(__('nav_career', 'Karir')) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>

<!-- Lightbox Modal untuk Preview Foto -->
<div class="home-photo-modal" id="homePhotoModal" aria-hidden="true" role="dialog" aria-label="Pratinjau Foto">
    <div class="home-photo-modal-backdrop" data-home-photo-close></div>
    <div class="home-photo-modal-dialog">
        <button type="button" class="home-photo-modal-close" data-home-photo-close aria-label="Tutup pratinjau foto">&times;</button>
        <img class="home-photo-modal-image" id="homePhotoModalImage" alt="">
    </div>
</div>

<script>
(function () {
    // 1. Vehicle Zone Explorer Tabs Switcher
    const zoneButtons = document.querySelectorAll('.zone-tab-btn');
    const zonePanes = document.querySelectorAll('.zone-content-pane');

    zoneButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetZone = this.getAttribute('data-zone');
            
            // Toggle tab active state
            zoneButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Toggle pane active state
            zonePanes.forEach(pane => {
                pane.classList.remove('active');
                if (pane.id === 'zone-' + targetZone) {
                    pane.classList.add('active');
                }
            });
        });
    });

    // 2. Lightbox Modal
    const modal = document.getElementById('homePhotoModal');
    const modalImage = document.getElementById('homePhotoModalImage');
    if (!modal || !modalImage) return;

    function openHomePhotoModal(photoUrl, altText) {
        modalImage.src = photoUrl;
        modalImage.alt = altText || 'Foto kegiatan';
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('home-photo-modal-open');
    }

    function closeHomePhotoModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('home-photo-modal-open');
        modalImage.removeAttribute('src');
        modalImage.alt = '';
    }

    document.addEventListener('click', function (event) {
        const clickedImage = event.target.closest('#products-home img, #gallery-home img, #facility-home img');
        if (!clickedImage) return;
        const photoUrl = clickedImage.currentSrc || clickedImage.src;
        if (!photoUrl) return;
        event.preventDefault();
        event.stopPropagation();
        openHomePhotoModal(photoUrl, clickedImage.alt);
    }, true);

    modal.querySelectorAll('[data-home-photo-close]').forEach(function (button) {
        button.addEventListener('click', closeHomePhotoModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeHomePhotoModal();
        }
    });
})();
</script>

</body>
</html>
