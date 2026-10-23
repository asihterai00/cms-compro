<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/translation_helpers.php";

/*
 * HOME PAGE PT NIHON PLAST INDONESIA
 * Global Tier-1 Automotive Safety, Steering Wheel & Precision Resin Systems
 * Meets IATF 16949, ISO 9001, ISO 14001, ISO 45001 / SMK3 Standards
 * 100% Dynamic CMS Elements & Granular Active/Inactive Controls
 */

// 1. Company Profile
$companyResult = $conn->query("SELECT * FROM company_profile ORDER BY id ASC LIMIT 1");
$company = $companyResult ? $companyResult->fetch_assoc() : [];
if ($company && function_exists('translate_entity_row')) {
    $company = translate_entity_row('company_profile', $company);
}

// 2. Core Products (4 Main Pillars)
$products = $conn->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC LIMIT 4");

// 3. Certifications & ISO Standards
$certifications = $conn->query("SELECT * FROM certifications WHERE status = 'active' ORDER BY sort_order ASC, id ASC");

// 4. Latest News (3 Compact Articles)
$news = $conn->query("SELECT * FROM news ORDER BY sort_order ASC, published_at DESC LIMIT 3");

if (!function_exists('sec_active')) {
    function sec_active($key, $default = true) {
        return is_section_active('home', $key, $default);
    }
}

if (!function_exists('sec_val')) {
    function sec_val($key, $field, $default = '') {
        return get_section_val('home', $key, $field, $default);
    }
}

if (!function_exists('homeImagePath')) {
    function homeImagePath($path, $folder = "") {
        $path = trim((string) $path);
        if ($path === "") return "assets/img/placeholder.svg";
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        if (strpos($path, "uploads/") === 0) return safe_image_url($path);
        if ($folder !== "") return safe_image_url(rtrim($folder, "/") . "/" . basename($path));
        return safe_image_url($path);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('hero_badge', 'Tier-1 Automotive Safety & Precision Systems')) ?></title>
    <meta name="description" content="<?= htmlspecialchars(__('site_meta_desc', 'PT Nihon Plast Indonesia - Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Resin Interior dengan standar IATF 16949 & ISO.')) ?>">
    <link rel="stylesheet" href="assets/css/home-scroll.css">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<!-- 1. HERO SECTION: High-Tech Automotive Tier-1 Carousel (Swiper 11) -->
<?php if (sec_active('hero')): ?>
    <?php 
    $heroSlides = sec_items('hero_slides'); 
    if (!empty($heroSlides)):
    ?>
        <header class="hero-swiper-container hero-corporate">
            <canvas id="ambientCanvas"></canvas>
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($heroSlides as $idx => $slide): 
                        $slideBg = safe_image_url(resolve_image_path($slide['image'], 'uploads/gallery'), 'uploads/gallery/facility_injection_molding.jpg');
                        $extra = $slide['extra'] ?? [];
                        $btnPrimaryText = $extra['btn_primary_text'] ?? ($slide['link_label'] ?? __('hero_btn_products', 'Jelajahi Produk Utama'));
                        $btnPrimaryLink = $extra['btn_primary_link'] ?? ($slide['link_url'] ?? '#products-home');
                        $btnSecText = $extra['btn_secondary_text'] ?? __('hero_btn_rfq', 'Permintaan B2B RFQ');
                        $btnSecLink = $extra['btn_secondary_link'] ?? 'contact';
                        if (function_exists('current_lang') && current_lang() !== 'id') {
                            $btnPrimaryText = __('hero_btn_products', 'Explore Products');
                            $btnSecText = __('hero_btn_rfq', 'Request RFQ');
                        }
                    ?>
                        <div class="swiper-slide hero-slide" style="background-image: url('<?= htmlspecialchars($slideBg, ENT_QUOTES, 'UTF-8') ?>');">
                            <div class="hero-slide-overlay"></div>
                            <div class="container hero-slide-content">
                                <div class="row align-items-center g-4">
                                    <div class="col-lg-8" data-aos="fade-right" data-aos-duration="750">
                                        <div class="hero-slide-eyebrow">
                                            <i class="bi <?= htmlspecialchars($slide['icon'] ?? 'bi-shield-check') ?>"></i>
                                            <span><?= htmlspecialchars($slide['subtitle'] ?: __('hero_badge', 'TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS')) ?></span>
                                        </div>
                                        <h1 class="hero-slide-title">
                                            <?= htmlspecialchars($slide['title']) ?>
                                        </h1>
                                        <p class="hero-slide-desc">
                                            <?= htmlspecialchars($slide['content']) ?>
                                        </p>
                                        <div class="d-flex flex-wrap gap-3 pt-2">
                                            <a href="<?= htmlspecialchars($btnPrimaryLink) ?>" class="btn btn-np-primary">
                                                <?= htmlspecialchars($btnPrimaryText) ?> <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                            <a href="<?= htmlspecialchars($btnSecLink) ?>" class="btn btn-np-outline">
                                                <i class="bi bi-file-earmark-text me-1"></i> <?= htmlspecialchars($btnSecText) ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 d-none d-lg-block" data-aos="fade-left" data-aos-duration="750" data-aos-delay="150">
                                        <div class="hero-hud-card">
                                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                                                <span class="small text-uppercase fw-bold text-success tracking-wider">
                                                    <i class="bi bi-cpu-fill me-1"></i> Spec & Certification
                                                </span>
                                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1">
                                                    <?= htmlspecialchars($slide['badge'] ?? 'Verified') ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($badges)): ?>
                                                <div class="d-flex flex-wrap gap-2 mb-3">
                                                    <?php foreach ($badges as $b): ?>
                                                        <span class="hero-hud-pill">
                                                            <i class="bi bi-check2-circle text-success"></i> <?= htmlspecialchars($b) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="small text-light opacity-75 pt-1">
                                                <i class="bi bi-geo-alt me-1 text-danger"></i> Cikampek Plant &bull; PT Nihon Plast Indonesia
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Dribbble Floating Controls: Progress Bar & Slide Counter -->
                <div class="hero-slider-progress-wrapper">
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="hero-counter">
                                <span class="hero-counter-active" id="heroCurrentSlide">01</span>
                                <span class="opacity-50">/</span>
                                <span id="heroTotalSlides"><?= sprintf('%02d', count($heroSlides)) ?></span>
                            </div>
                            <div class="hero-slider-controls">
                                <button class="hero-nav-btn hero-btn-prev" aria-label="Previous Slide">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="hero-nav-btn hero-btn-next" aria-label="Next Slide">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="hero-slider-progress-bar">
                            <div class="hero-slider-progress-fill" id="heroProgressFill"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php else: ?>
        <?php
        $heroBg = safe_image_url(sec_val('hero', 'bg_image', ''), 'uploads/gallery/facility_injection_molding.jpg');
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
                        <h1 class="display-4 fw-bold mb-3 text-white">
                            <?= htmlspecialchars(sec_val('hero', 'title', __('hero_title', 'Producing Reliable Technology for Complete Peace of Mind'))) ?>
                        </h1>
                        <p class="lead-text text-white-50">
                            <?= htmlspecialchars(sec_val('hero', 'subtitle', $company["short_description"] ?? __('hero_desc', 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.'))) ?>
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="<?= htmlspecialchars(sec_val('hero', 'button_link', '#products-home')) ?>" class="btn btn-np-primary">
                                <?= htmlspecialchars(sec_val('hero', 'button_text', __('hero_btn_products', 'Jelajahi Produk Utama'))) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="contact" class="btn btn-np-outline">
                                <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars(__('hero_btn_rfq', 'Permintaan B2B RFQ')) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php endif; ?>
<?php endif; ?>

<!-- 2. EXECUTIVE OEM AUTOMOTIVE SHOWCASE (DRIBBLE/APPLE GRADE) -->
<?php if (sec_active('oem_trust')): ?>
    <?php $oemList = sec_items('oem_trust'); ?>
    <section class="npi-oem-showcase" id="oem-network" style="position: relative !important; clear: both !important; margin: 0 !important;">
        <div class="container text-center mb-4">
            <div class="npi-showcase-badge">
                <span class="npi-pulse-dot"></span>
                <i class="bi <?= htmlspecialchars(sec_val('oem_trust', 'icon', 'bi-shield-check')) ?> ms-1 me-1"></i> 
                <?= htmlspecialchars(sec_val('oem_trust', 'badge_text', __('oem_trust_badge', 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS'))) ?>
            </div>
            <h2 class="npi-showcase-title">
                <?= htmlspecialchars(sec_val('oem_trust', 'title', __('oem_trust_title', 'Trusted Partner to Leading Vehicle Manufacturers'))) ?>
            </h2>
            <p class="npi-showcase-subtitle mx-auto">
                <?= htmlspecialchars(sec_val('oem_trust', 'subtitle', __('oem_trust_subtitle', 'Direct supplier to automotive OEMs including Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda, Isuzu, Hyundai'))) ?>
            </p>
        </div>

        <!-- Continuous Marquee with Dual Horizon Edge Fades -->
        <div class="npi-marquee-wrapper">
            <div class="npi-horizon-fade npi-fade-left"></div>
            <div class="npi-horizon-fade npi-fade-right"></div>
            <div class="swiper npi-oem-swiper oem-ticker-swiper">
                <div class="swiper-wrapper">
                    <?php 
                    $tickerItems = array_merge($oemList, $oemList, $oemList);
                    foreach ($tickerItems as $oem): 
                        $extra = $oem['extra'] ?? (!empty($oem['extra_data']) ? json_decode($oem['extra_data'], true) : []);
                        $brandColor = $extra['brand_color'] ?? '#005A36';
                        $brandBg = $extra['brand_bg'] ?? 'rgba(0, 90, 54, 0.05)';
                        $fontWeight = $extra['font_weight'] ?? '800';
                        $fontStyle = $extra['font_style'] ?? 'normal';
                        $letterSpacing = $extra['letter_spacing'] ?? '0.08em';
                        $fontFamily = $extra['font_family'] ?? 'inherit';
                        $logoImg = $oem['image'] ?? '';
                        $hasLogo = !empty($logoImg) && file_exists(__DIR__ . '/' . $logoImg);
                    ?>
                        <div class="swiper-slide" style="width: auto;">
                            <div class="npi-brand-card oem-brand-card" style="--brand-color: <?= htmlspecialchars($brandColor) ?>; --brand-bg: <?= htmlspecialchars($brandBg) ?>;">
                                <div class="npi-brand-emblem oem-logo-wrap">
                                    <?php if ($hasLogo): ?>
                                        <img src="<?= htmlspecialchars($logoImg) ?>" alt="<?= htmlspecialchars($oem['title']) ?> Crest" class="npi-brand-logo oem-brand-logo" width="38" height="38" loading="lazy">
                                    <?php else: ?>
                                        <i class="bi bi-shield-check text-success fs-4"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="npi-brand-info oem-meta">
                                    <div class="npi-brand-name oem-brand-name" style="color: <?= htmlspecialchars($brandColor) ?>; font-weight: <?= htmlspecialchars($fontWeight) ?>; font-style: <?= htmlspecialchars($fontStyle) ?>; letter-spacing: <?= htmlspecialchars($letterSpacing) ?>; font-family: <?= htmlspecialchars($fontFamily) ?>;">
                                        <?= htmlspecialchars($oem['title']) ?>
                                    </div>
                                    <div class="npi-brand-role oem-brand-tier">
                                        <span class="npi-role-dot oem-tier-dot" style="background-color: <?= htmlspecialchars($brandColor) ?>;"></span>
                                        <?= htmlspecialchars($oem['subtitle'] ?: 'OEM Tier-1 Partner') ?>
                                    </div>
                                </div>
                                <div class="npi-brand-accent-line oem-brand-indicator" style="background-color: <?= htmlspecialchars($brandColor) ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 3. STRATEGIC OPERATIONAL STATS & CAPACITY BAR (ZERO OVERLAP ISOLATED SECTION) -->
<?php if (sec_active('stats')): ?>
    <?php $statItems = sec_items('stats'); ?>
    <?php if (!empty($statItems)): ?>
        <section class="npi-stats-showcase stats-section" id="operational-capacity" style="position: relative !important; clear: both !important; margin: 0 !important; z-index: 1 !important;">
            <div class="container">
                <div class="npi-stats-box stats-bar border" style="margin: 0 auto !important; position: static !important; transform: none !important; clear: both !important;">
                    <div class="row g-4 text-center align-items-center">
                        <?php 
                        $colClass = count($statItems) <= 3 ? 'col-md-4' : 'col-6 col-md-3'; 
                        foreach ($statItems as $st): 
                        ?>
                            <div class="<?= $colClass ?> stat-item">
                                <div class="stat-value text-np-green fw-bold fs-3"><?= htmlspecialchars($st['title']) ?></div>
                                <div class="stat-label small text-muted mt-1 fw-bold"><?= htmlspecialchars($st['subtitle'] ?? '') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<!-- 3. PRODUCTS: 4 Core Pillars of Nihon Plast (THE MAIN ATTRACTION) -->
<?php if (sec_active('products_grid')): ?>
    <section id="products-home" class="home-section py-5 section-light">
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
                    <?php while ($product = $products->fetch_assoc()): 
                        if (function_exists('translate_entity_row')) $product = translate_entity_row('product', $product);
                    ?>
                        <?php $productImage = safe_image_url($product["image"] ?? "", 'assets/img/placeholder.svg'); ?>
                        <div class="col-md-6 col-lg-3">
                            <article class="product-card-modern bg-white rounded-4 border shadow-sm overflow-hidden h-100 d-flex flex-column">
                                <div class="product-thumb-wrapper" style="background: #0f172a; aspect-ratio: 16/10; position: relative; overflow: hidden;">
                                    <span class="product-pill position-absolute top-0 start-0 m-2 badge bg-success bg-opacity-90 text-white px-2 py-1 small">Tier-1 OEM</span>
                                    <img src="<?= htmlspecialchars($productImage) ?>"
                                         class="product-card-thumb w-100 h-100"
                                         style="object-fit: cover;"
                                         alt="<?= htmlspecialchars($product["name"] ?? "Product") ?>"
                                         loading="lazy">
                                </div>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <h3 class="h6 fw-bold text-np-green mb-2"><?= htmlspecialchars($product["name"] ?? "") ?></h3>
                                    <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                        <?= htmlspecialchars($product["description"] ?? "") ?>
                                    </p>
                                    <a href="product-detail?id=<?= (int)$product['id'] ?>" class="small fw-bold text-np-green text-decoration-none pt-2 border-top">
                                        <?= htmlspecialchars(__('view_details', 'Detail Spesifikasi')) ?> <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?= htmlspecialchars(sec_val('products_grid', 'button_link', 'products')) ?>" class="btn btn-np-primary px-4 py-3 fw-bold">
                    <?= htmlspecialchars(sec_val('products_grid', 'button_text', __('products_btn_all', 'Lihat Semua Lini Produk'))) ?> <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 4. FACILITY: Manufacturing Power & Precision Capability -->
<?php if (sec_active('facility_preview')): ?>
    <section id="facility-home" class="home-section py-5 bg-white border-top border-bottom">
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

                <?php 
                $facilityCards = sec_items('facility_preview');
                foreach ($facilityCards as $fCard): 
                    $cardImg = safe_image_url($fCard['image'] ?? '', 'uploads/gallery/facility_injection_molding.jpg');
                ?>
                    <div class="col-md-4">
                        <div class="card border rounded-4 overflow-hidden h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($cardImg) ?>" class="home-card-image" alt="<?= htmlspecialchars($fCard['title']) ?>" style="height: 220px; object-fit: cover;" loading="lazy">
                            <div class="p-4">
                                <h5 class="fw-bold text-np-green mb-2"><?= htmlspecialchars($fCard['title']) ?></h5>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($fCard['content']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- High-Impact Industrial Capability Banner (No Heavy Video Stalls) -->
                <div class="col-12 mt-4">
                    <div class="p-4 p-md-5 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #003B22 0%, #0F172A 100%);">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-cpu-fill me-1"></i> MONOZUKURI INDUSTRIAL CAPABILITY
                                    </span>
                                    <span class="badge bg-dark bg-opacity-50 text-white border border-light border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="bi bi-shield-check me-1"></i> IATF 16949 Certified
                                    </span>
                                </div>
                                <h3 class="text-white fw-bold mb-2"><?= htmlspecialchars(__('integrated_plant_title', 'Pabrik Otomotif Terintegrasi di Kawasan Industri Cikampek')) ?></h3>
                                <p class="text-white-50 mb-0" style="max-width: 700px; line-height: 1.7;">
                                    <?= htmlspecialchars(__('integrated_plant_desc', 'Didukung laboratorium inspeksi 3D CMM Mitutoyo, deployment chamber uji ledak airbag, Xenon Arc UV weatherometer, serta jalur painting robotic otomatis untuk menjamin standar kualitas Tier-1 OEM.')) ?>
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <a href="facility" class="btn btn-light rounded-pill px-4 py-3 fw-bold text-np-green shadow">
                                    <i class="bi bi-gear-wide-connected me-1"></i> <?= htmlspecialchars(__('matrix_machines_lab', 'Matriks Mesin & Lab')) ?> <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?= htmlspecialchars(sec_val('facility_preview', 'button_link', 'facility')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('facility_preview', 'button_text', __('facility_btn', 'Jelajahi Seluruh Fasilitas Pabrik'))) ?> <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 5. CERTIFICATIONS & INDONESIAN REGULATORY COMPLIANCE -->
<?php if (sec_active('certifications_grid')): ?>
    <section id="certifications-home" class="home-section py-5 section-light">
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
                    <a href="<?= htmlspecialchars(sec_val('certifications_grid', 'button_link', 'quality')) ?>" class="btn btn-np-primary">
                        <?= htmlspecialchars(sec_val('certifications_grid', 'button_text', __('certifications_btn', 'Pelajari Kebijakan Mutu'))) ?> <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <?php if ($certifications && $certifications->num_rows > 0): ?>
                    <?php while ($cert = $certifications->fetch_assoc()): 
                        if (function_exists('translate_entity_row')) $cert = translate_entity_row('certification', $cert);
                    ?>
                        <?php $certImg = safe_image_url($cert['image'] ?? '', 'assets/img/placeholder.svg'); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="cert-card bg-white rounded-4 border p-4 shadow-sm h-100 d-flex flex-column <?= str_contains($cert['name'], 'IATF') ? 'featured-iatf border-success' : '' ?>">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="cert-card-icon d-flex align-items-center justify-content-center p-1" style="width: 54px; height: 54px;">
                                        <img src="<?= htmlspecialchars($certImg) ?>" alt="<?= htmlspecialchars($cert['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                    <span class="badge bg-np-tint text-np-green border border-success-subtle small">
                                        <?= htmlspecialchars($cert['category']) ?>
                                    </span>
                                </div>

                                <h4 class="h5 fw-bold text-np-green mb-2">
                                    <?= htmlspecialchars($cert['name']) ?>
                                </h4>

                                <?php if (!empty($cert['certificate_number'])): ?>
                                    <div class="mb-2">
                                        <span class="badge bg-light text-muted border small">
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

<!-- 6. ABOUT COMPANY & JAPANESE MONOZUKURI HERITAGE -->
<?php if (sec_active('about_intro')): ?>
    <section id="about-home" class="home-section py-5 bg-white border-top">
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
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-compass me-1"></i> <?= htmlspecialchars(__('corporate_vision_title', 'Visi Korporat')) ?></h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($company["vision"] ?? "Menjadi mitra manufaktur otomotif One-of-a-Kind yang tak tergantikan.") ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-award me-1"></i> <?= htmlspecialchars(__('core_values_title', 'Nilai Inti')) ?></h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($company["values_text"] ?? "Monozukuri (Craftsmanship), Safety First, Kaizen, and Harmony.") ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= htmlspecialchars(sec_val('about_intro', 'button_link', 'about')) ?>" class="btn btn-np-primary">
                            <?= htmlspecialchars(sec_val('about_intro', 'button_text', __('about_btn_more', 'Profil Selengkapnya'))) ?>
                        </a>
                        <a href="contact" class="btn btn-np-outline"><?= htmlspecialchars(__('contact_us', 'Hubungi Kami')) ?></a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border rounded-4 shadow-sm p-4 bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-np-green p-3 rounded-3 text-white"><i class="bi bi-geo-alt-fill fs-4"></i></span>
                            <div>
                                <h5 class="fw-bold mb-0"><?= htmlspecialchars(__('office_plant_title', 'Kantor & Fasilitas Pabrik')) ?></h5>
                                <small class="text-muted"><?= htmlspecialchars(__('office_plant_sub', 'Kawasan Industri Indotaisei, Cikampek, Karawang')) ?></small>
                            </div>
                        </div>
                        <hr>
                        <p class="mb-2"><strong><?= htmlspecialchars(__('office_plant_address', 'Alamat Pabrik:')) ?></strong><br><span class="text-muted small"><?= nl2br(htmlspecialchars($company["address"] ?? "Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang, Jawa Barat 41373")) ?></span></p>
                        <p class="mb-2"><strong><?= htmlspecialchars(__('office_plant_phone', 'Telepon:')) ?></strong><br><span class="text-muted small"><?= htmlspecialchars($company["phone"] ?? "+62 (264) 351-888") ?></span></p>
                        <p class="mb-0"><strong><?= htmlspecialchars(__('office_plant_email', 'Email Resmi:')) ?></strong><br><span class="text-muted small"><?= htmlspecialchars($company["email"] ?? "corporate@n-plast.co.id") ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 7. NEWS SECTION: Latest Updates & Corporate Topics (Clean 3-Card Grid) -->
<?php if (sec_active('news_preview')): ?>
    <section id="news-home" class="home-section py-5 section-light border-top">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <div class="eyebrow-badge mb-1">
                        <i class="bi <?= htmlspecialchars(sec_val('news_preview', 'icon', 'bi-newspaper')) ?>"></i> 
                        <?= htmlspecialchars(sec_val('news_preview', 'badge_text', __('news_badge', 'LATEST UPDATES'))) ?>
                    </div>
                    <h2 class="h2 fw-bold text-np-green mb-0">
                        <?= htmlspecialchars(sec_val('news_preview', 'title', __('news_title', 'Berita & Informasi Perusahaan'))) ?>
                    </h2>
                </div>
                <a href="<?= htmlspecialchars(sec_val('news_preview', 'button_link', 'news')) ?>" class="btn btn-sm btn-np-outline">
                    <?= htmlspecialchars(sec_val('news_preview', 'button_text', __('news_btn', 'Lihat Semua Berita'))) ?> <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                <?php if ($news && $news->num_rows > 0): ?>
                    <?php while ($article = $news->fetch_assoc()): 
                        if (function_exists('translate_entity_row')) $article = translate_entity_row('news', $article);
                    ?>
                        <div class="col-md-4">
                            <article class="card border rounded-4 h-100 p-4 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-np-tint text-np-green border border-success-subtle small">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($article['published_at'])) ?>
                                    </span>
                                    <span class="badge bg-light text-muted border small">Corporate</span>
                                </div>
                                <h3 class="h5 fw-bold text-np-green mb-2">
                                    <a href="news-detail?id=<?= (int)$article['id'] ?>" class="text-decoration-none text-np-green">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h3>
                                <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($article['content'] ?? ''), 0, 120, '...')) ?>
                                </p>
                                <div class="pt-2 border-top">
                                    <a href="news-detail?id=<?= (int)$article['id'] ?>" class="small fw-bold text-np-green text-decoration-none">
                                        <?= htmlspecialchars(__('read_more', 'Baca Selengkapnya')) ?> →
                                    </a>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 8. HIGH-CONVERTING B2B SOURCING & PROCUREMENT RFQ CTA -->
<?php if (sec_active('contact_cta')): ?>
    <section id="contact-home" class="home-section py-5">
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
                        <a href="<?= htmlspecialchars(sec_val('contact_cta', 'button_link', 'contact')) ?>" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-np-green shadow">
                            <i class="bi bi-file-earmark-text me-1"></i> <?= htmlspecialchars(sec_val('contact_cta', 'button_text', __('contact_cta_btn', 'Ajukan B2B RFQ Inquiry'))) ?>
                        </a>
                        <a href="contact#download-profile" class="btn btn-outline-light rounded-pill px-4 py-3 fw-semibold">
                            <i class="bi bi-download me-1"></i> <?= htmlspecialchars(__('download_spec', 'Unduh Company Profile')) ?>
                        </a>
                        <a href="career" class="btn btn-outline-light rounded-pill px-4 py-3 fw-semibold">
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

<!-- Ambient Canvas Particle Script -->
<script src="assets/js/ambient-canvas.js"></script>

<script>
(function () {
    // 1. Swiper 11 Hero Carousel with Dynamic HUD & Horizontal Progress
    const heroSwiperEl = document.querySelector('.hero-swiper');
    if (heroSwiperEl && typeof Swiper !== 'undefined') {
        const progressFill = document.getElementById('heroProgressFill');
        const currentSlideEl = document.getElementById('heroCurrentSlide');
        const AUTOPLAY_TIME = 6500;

        function startProgress() {
            if (!progressFill) return;
            progressFill.style.transition = 'none';
            progressFill.style.width = '0%';
            requestAnimationFrame(() => {
                progressFill.style.transition = `width ${AUTOPLAY_TIME}ms linear`;
                progressFill.style.width = '100%';
            });
        }

        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            speed: 850,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            autoplay: {
                delay: AUTOPLAY_TIME,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: '.hero-btn-next',
                prevEl: '.hero-btn-prev'
            },
            on: {
                init: function () {
                    startProgress();
                },
                slideChange: function () {
                    if (currentSlideEl) {
                        const realIndex = this.realIndex + 1;
                        currentSlideEl.textContent = realIndex < 10 ? '0' + realIndex : realIndex;
                    }
                    startProgress();
                }
            }
        });
    }

    // 2. Executive Continuous Silky Horizon OEM Marquee with Pause on Hover
    const oemSwiperEl = document.querySelector('.npi-oem-swiper') || document.querySelector('.oem-ticker-swiper');
    if (oemSwiperEl && typeof Swiper !== 'undefined') {
        new Swiper(oemSwiperEl, {
            slidesPerView: 'auto',
            spaceBetween: 24,
            loop: true,
            speed: 6000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            allowTouchMove: true
        });
    }

    // 3. Lightbox Modal
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
        const clickedImage = event.target.closest('#products-home img, #facility-home img');
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
