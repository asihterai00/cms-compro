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

// 5. Gallery (Only queried if active to save DB load)
$galleryItems = sec_active('gallery_preview', false) ? $conn->query("SELECT id, title, image FROM gallery ORDER BY id DESC LIMIT 6") : false;

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
                        $slideBg = resolve_image_path($slide['image'], 'uploads/gallery');
                        $extra = $slide['extra'] ?? [];
                        $badges = $extra['badges'] ?? [];
                        $btnPrimaryText = $extra['btn_primary_text'] ?? ($slide['link_label'] ?? __('hero_btn_products', 'Jelajahi Produk'));
                        $btnPrimaryLink = $extra['btn_primary_link'] ?? ($slide['link_url'] ?? '#products-home');
                        $btnSecText = $extra['btn_secondary_text'] ?? __('hero_btn_rfq', 'Konsultasi Teknis & B2B RFQ');
                        $btnSecLink = $extra['btn_secondary_link'] ?? 'contact.php';
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
                                            <div class="small text-secondary pt-1">
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
<?php endif; ?>

<!-- 2. QUICK STATS BAR -->
<?php if (sec_active('stats')): ?>
    <?php $statItems = sec_items('stats'); ?>
    <?php if (!empty($statItems)): ?>
        <div class="container">
            <div class="stats-bar">
                <div class="row g-4 align-items-center">
                    <?php 
                    $colClass = count($statItems) <= 3 ? 'col-md-4' : 'col-6 col-md-3'; 
                    foreach ($statItems as $st): 
                    ?>
                        <div class="<?= $colClass ?> stat-item">
                            <div class="stat-value text-np-green"><?= htmlspecialchars($st['title']) ?></div>
                            <div class="stat-label"><?= htmlspecialchars($st['subtitle'] ?? '') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- 3. NEWS SECTION: Latest Updates & Corporate Topics (No. 2 saat scroll) -->
<?php if (sec_active('news_preview')): ?>
    <section id="news-home" class="home-section py-5 bg-white border-bottom">
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
                <a href="<?= htmlspecialchars(sec_val('news_preview', 'button_link', 'news.php')) ?>" class="btn btn-sm btn-np-outline">
                    <?= htmlspecialchars(sec_val('news_preview', 'button_text', __('news_btn', 'Lihat Semua Berita'))) ?> <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                <?php if ($news && $news->num_rows > 0): ?>
                    <?php while ($article = $news->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <article class="card border rounded-4 h-100 p-4 bg-light shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-np-tint text-np-green border border-success-subtle small">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($article['published_at'])) ?>
                                    </span>
                                    <span class="badge bg-white text-muted border small">Corporate</span>
                                </div>
                                <h3 class="h5 fw-bold text-np-green mb-2">
                                    <a href="news-detail.php?id=<?= (int)$article['id'] ?>" class="text-decoration-none text-np-green">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h3>
                                <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($article['content'] ?? ''), 0, 120, '...')) ?>
                                </p>
                                <div class="pt-2 border-top">
                                    <a href="news-detail.php?id=<?= (int)$article['id'] ?>" class="small fw-bold text-np-green text-decoration-none">
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

<!-- TOMBOL NAVIGASI HALAMAN HOME -->
<nav class="home-section-nav" aria-label="Navigasi bagian Home">
    <div class="container">
        <?php if (sec_active('news_preview')): ?><a href="#news-home"><i class="bi bi-newspaper me-1"></i><?= htmlspecialchars(__('nav_news', 'Berita')) ?></a><?php endif; ?>
        <?php if (sec_active('products_grid')): ?><a href="#products-home"><i class="bi bi-grid-fill me-1"></i><?= htmlspecialchars(__('nav_products', 'Produk')) ?></a><?php endif; ?>
        <?php if (sec_active('vehicle_explorer')): ?><a href="#vehicle-explorer-home"><i class="bi bi-car-front-fill me-1"></i><?= htmlspecialchars(__('vehicle_explorer_title', 'Vehicle Explorer')) ?></a><?php endif; ?>
        <?php if (sec_active('facility_preview')): ?><a href="#facility-home"><i class="bi bi-buildings me-1"></i><?= htmlspecialchars(__('nav_facility', 'Fasilitas')) ?></a><?php endif; ?>
        <?php if (sec_active('certifications_grid')): ?><a href="#certifications-home"><i class="bi bi-patch-check-fill me-1"></i><?= htmlspecialchars(__('nav_quality', 'Sertifikasi & Regulasi')) ?></a><?php endif; ?>
        <?php if (sec_active('about_intro')): ?><a href="#about-home"><i class="bi bi-building me-1"></i><?= htmlspecialchars(__('nav_about', 'Tentang Kami')) ?></a><?php endif; ?>
        <?php if (sec_active('contact_cta')): ?><a href="#contact-home"><i class="bi bi-envelope-fill me-1"></i><?= htmlspecialchars(__('nav_contact', 'Kontak RFQ')) ?></a><?php endif; ?>
    </div>
</nav>

<!-- 4. PRODUCTS: 4 Core Pillars of Nihon Plast -->
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
                <?php $zones = sec_items('vehicle_explorer'); ?>
                <div class="zone-nav-tabs" role="tablist">
                    <?php foreach ($zones as $idx => $z): ?>
                        <button type="button" class="zone-tab-btn <?= $idx === 0 ? 'active' : '' ?>" data-zone="<?= htmlspecialchars($z['item_key'] ?: 'zone-'.$z['id']) ?>">
                            <i class="bi <?= htmlspecialchars($z['icon'] ?? 'bi-gear') ?>"></i> <?= htmlspecialchars($z['extra']['tab_label'] ?? $z['title']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($zones as $idx => $z): ?>
                    <div class="zone-content-pane <?= $idx === 0 ? 'active' : '' ?>" id="zone-<?= htmlspecialchars($z['item_key'] ?: 'zone-'.$z['id']) ?>">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-6">
                                <div class="zone-visual-display">
                                    <img src="<?= htmlspecialchars($z['image']) ?>" alt="<?= htmlspecialchars($z['title']) ?>" loading="lazy">
                                    <div class="zone-hotspot" style="top: 45%; left: 50%;" title="Precision Component">1</div>
                                    <div class="zone-hotspot" style="top: 30%; left: 35%;" title="Safety Traceability">2</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php if (!empty($z['subtitle'])): ?>
                                    <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2"><?= htmlspecialchars($z['subtitle']) ?></span>
                                <?php endif; ?>
                                <h3 class="h4 fw-bold text-np-green mb-3"><?= htmlspecialchars($z['title']) ?></h3>
                                <p class="text-muted mb-4"><?= htmlspecialchars($z['content'] ?? '') ?></p>
                                <?php if (!empty($z['extra']['badges']) && is_array($z['extra']['badges'])): ?>
                                    <div class="d-flex flex-wrap gap-2 mb-4">
                                        <?php foreach ($z['extra']['badges'] as $b): ?>
                                            <span class="spec-badge-pill"><i class="bi bi-check2"></i> <?= htmlspecialchars($b) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="<?= htmlspecialchars($z['extra']['link'] ?? 'products.php') ?>" class="btn btn-np-primary btn-sm">
                                    <?= htmlspecialchars(__('btn_view_more', 'Pelajari Spesifikasi')) ?> <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 6. FACILITY: Factory Overview -->
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
                        <img src="uploads/gallery/facility_cleanroom_lab.svg" class="home-card-image" alt="Engineering Lab" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">Engineering & Mold R&D</h5>
                            <p class="small text-muted mb-0">Dukungan tim rekayasa teknis untuk pembuatan dan perawatan mold presisi cetak resin plastik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <img src="uploads/gallery/facility_injection_molding.jpg" class="home-card-image" alt="Production Facility" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">High-Tonnage Injection Line</h5>
                            <p class="small text-muted mb-0">Area 22 mesin injeksi multi-tonase yang mampu memproduksi komponen interior besar dan cover eksterior.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <img src="uploads/gallery/facility_cleanroom_lab.svg" class="home-card-image" alt="Cleanroom Assembly" loading="lazy">
                        <div class="p-4">
                            <h5 class="fw-bold text-np-green">Safety Module Cleanroom</h5>
                            <p class="small text-muted mb-0">Area perakitan steril berstandar ISO Class 8 untuk modul airbag dan switch kendali setir kemudi mobil.</p>
                        </div>
                    </div>
                </div>

                <!-- Virtual Plant Tour & Manufacturing Motion Video Showcase -->
                <div class="col-12 mt-4" data-aos="fade-up" data-aos-duration="750">
                    <div class="video-tour-banner position-relative rounded-4 overflow-hidden border shadow-sm">
                        <video class="w-100 d-block" style="height: 380px; object-fit: cover;" poster="uploads/gallery/facility_injection_molding.jpg" muted loop playsinline autoplay>
                            <source src="uploads/videos/plant_tour.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="video-tour-overlay d-flex flex-column justify-content-end p-4 p-md-5">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-play-circle-fill me-1"></i> MONOZUKURI PRODUCTION MOTION
                                </span>
                                <span class="badge bg-dark bg-opacity-75 text-white border border-secondary px-3 py-2 rounded-pill">
                                    <i class="bi bi-badge-hd-fill me-1"></i> Full HD 60FPS
                                </span>
                            </div>
                            <h3 class="text-white fw-bold mb-2">Virtual Plant Tour & High-Precision Production Cells</h3>
                            <p class="text-white-50 mb-0 max-w-700">
                                Saksikan secara visual presisi 22 mesin injeksi servo multi-tonase (150T - 2.500T), cleanroom Class 8 perakitan airbag safety module, serta pengujian mutu berstandar IATF 16949 di Cikampek, Karawang.
                            </p>
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
                                    <div class="cert-card-icon d-flex align-items-center justify-content-center p-1">
                                        <?php if (!empty($cert['image'])): ?>
                                            <img src="<?= htmlspecialchars($cert['image']) ?>" alt="<?= htmlspecialchars($cert['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                        <?php else: ?>
                                            <i class="bi bi-shield-check"></i>
                                        <?php endif; ?>
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

<!-- 8. ABOUT COMPANY -->
<?php if (sec_active('about_intro')): ?>
    <section id="about-home" class="home-section section-padding section-light">
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
                            <div class="p-3 bg-white rounded-3 border h-100">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-compass me-1"></i> Visi Korporat</h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($company["vision"] ?? "Menjadi mitra manufaktur otomotif One-of-a-Kind yang tak tergantikan.") ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 border h-100">
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
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
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

<!-- 9. OEM TRUST BAR: AUTOMOTIVE PARTNERS & CLIENT ELEVATION -->
<?php if (sec_active('oem_trust')): ?>
    <?php $oemList = sec_items('oem_trust'); ?>
    <section class="oem-ticker-section oem-trust-section">
        <div class="container text-center mb-3">
            <span class="eyebrow-badge">
                <i class="bi <?= htmlspecialchars(sec_val('oem_trust', 'icon', 'bi-award-fill')) ?>"></i> 
                <?= htmlspecialchars(sec_val('oem_trust', 'badge_text', __('oem_trust_badge', 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS'))) ?>
            </span>
        </div>
        <div class="swiper oem-ticker-swiper">
            <div class="swiper-wrapper">
                <?php 
                // Loop OEM partners 3x to ensure uninterrupted, seamless infinite continuous ticker
                $tickerItems = array_merge($oemList, $oemList, $oemList);
                foreach ($tickerItems as $oem): 
                ?>
                    <div class="swiper-slide" style="width: auto;">
                        <div class="oem-ticker-card">
                            <i class="bi bi-patch-check-fill text-success me-2"></i>
                            <?= htmlspecialchars($oem['title']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 10. QUALITY & MONOZUKURI ASSURANCE (Opsional Home / Dimatikan Default agar Cepat) -->
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
                <?php foreach (sec_items('quality_pillars') as $idx => $step): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="step-card">
                            <div class="step-num"><?= htmlspecialchars($step['subtitle'] ?: sprintf('%02d', $idx + 1)) ?></div>
                            <h5 class="fw-bold text-np-green mb-2"><?= htmlspecialchars($step['title']) ?></h5>
                            <p class="small text-muted mb-0"><?= htmlspecialchars($step['content'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 11. GREEN MONOZUKURI & SUSTAINABILITY (Opsional Home / Dimatikan Default agar Cepat) -->
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

                    <?php 
                    $esgItems = sec_items('esg_sustainability'); 
                    $esgStats = array_filter($esgItems, fn($it) => str_starts_with($it['item_key'] ?? '', 'stat_'));
                    $esgCompliances = array_filter($esgItems, fn($it) => str_starts_with($it['item_key'] ?? '', 'comp_'));
                    ?>
                    <div class="row g-3">
                        <?php foreach ($esgStats as $es): ?>
                            <div class="col-sm-6">
                                <div class="esg-stat-box h-100">
                                    <div class="esg-icon-circle"><i class="bi <?= htmlspecialchars($es['icon'] ?? 'bi-sun-fill') ?>"></i></div>
                                    <div class="fs-4 fw-bold text-np-green"><?= htmlspecialchars($es['badge'] ?? '') ?></div>
                                    <div class="small text-muted fw-semibold"><?= htmlspecialchars($es['title']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 bg-white rounded-4 border shadow-sm">
                        <h5 class="fw-bold text-np-green mb-3"><i class="bi bi-check-all text-success me-2"></i>Pilar Kepatuhan Lingkungan & K3</h5>
                        <ul class="list-group list-group-flush small">
                            <?php foreach ($esgCompliances as $ec): ?>
                                <li class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                                    <i class="bi <?= htmlspecialchars($ec['icon'] ?? 'bi-patch-check-fill') ?> text-success fs-5"></i>
                                    <div>
                                        <strong><?= htmlspecialchars($ec['title']) ?></strong>
                                        <p class="text-muted mb-0"><?= htmlspecialchars($ec['content'] ?? '') ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 12. GALLERY (Opsional Home / Dimatikan Default agar Hemat Bandwidth) -->
<?php if (sec_active('gallery_preview') && $galleryItems && $galleryItems->num_rows > 0): ?>
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
            </div>

            <div class="text-center mt-4">
                <a href="<?= htmlspecialchars(sec_val('gallery_preview', 'button_link', 'gallery.php')) ?>" class="btn btn-np-outline">
                    <?= htmlspecialchars(sec_val('gallery_preview', 'button_text', __('gallery_btn', 'Buka Galeri Foto Lengkap'))) ?>
                </a>
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

    // 2. Continuous Silky Linear OEM Marquee
    const oemSwiperEl = document.querySelector('.oem-ticker-swiper');
    if (oemSwiperEl && typeof Swiper !== 'undefined') {
        new Swiper('.oem-ticker-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 24,
            loop: true,
            speed: 5000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false
            },
            allowTouchMove: false
        });
    }

    // 3. Vehicle Zone Explorer Tabs Switcher
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

    // 4. Lightbox Modal
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
