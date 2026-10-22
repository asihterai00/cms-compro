<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";
require_once __DIR__ . "/includes/translation_helpers.php";

$categoryFilter = trim($_GET["category"] ?? "");

if ($categoryFilter !== "") {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY id ASC");
    $param = "%" . $categoryFilter . "%";
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query("SELECT * FROM products ORDER BY id ASC");
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('nav_products', 'Katalog Produk')) ?> & Sistem Komponen Otomotif</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
    <style>
        .product-filter-btn {
            border: 1px solid var(--np-border);
            background: #FFFFFF;
            color: var(--np-body);
            font-size: 0.86rem;
            font-weight: 700;
            padding: 0.5rem 1.15rem;
            border-radius: var(--np-radius-pill);
            transition: var(--np-transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .product-filter-btn:hover,
        .product-filter-btn.active {
            background: var(--np-green);
            color: #FFFFFF !important;
            border-color: var(--np-green);
            box-shadow: 0 4px 10px rgba(0, 90, 54, 0.2);
        }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "products"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <!-- 1. Catalog Intro Header -->
        <?php if (is_section_active('products', 'products_catalog_intro')): ?>
        <div class="max-w-700 mx-auto text-center mb-5">
            <span class="eyebrow-badge mx-auto">
                <i class="bi <?= htmlspecialchars(get_section_val('products', 'products_catalog_intro', 'icon', 'bi-grid-3x3-gap-fill')) ?>"></i>
                <?= htmlspecialchars(get_section_val('products', 'products_catalog_intro', 'badge_text', 'TIER-1 OEM PRODUCT PORTFOLIO')) ?>
            </span>
            <h1 class="display-6 fw-bold text-np-green mb-2">
                <?= htmlspecialchars(get_section_val('products', 'products_catalog_intro', 'title', 'Katalog Komponen Otomotif Berstandar Dunia')) ?>
            </h1>
            <p class="text-muted">
                <?= htmlspecialchars(get_section_val('products', 'products_catalog_intro', 'subtitle', 'Menyediakan sistem roda kemudi terintegrasi, modul keselamatan kantung udara, dan moulding resin interior eksterior berkualitas OEM.')) ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-5">
            <a href="products.php" class="product-filter-btn <?= $categoryFilter === '' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Semua Lini Produk
            </a>
            <a href="products.php?category=Steering" class="product-filter-btn <?= $categoryFilter === 'Steering' ? 'active' : '' ?>">
                <i class="bi bi-circle-square"></i> Steering Wheel Systems
            </a>
            <a href="products.php?category=Airbag" class="product-filter-btn <?= $categoryFilter === 'Airbag' ? 'active' : '' ?>">
                <i class="bi bi-shield-shaded"></i> Airbag Safety Modules
            </a>
            <a href="products.php?category=Cockpit" class="product-filter-btn <?= $categoryFilter === 'Cockpit' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Cockpit & Interior Resins
            </a>
            <a href="products.php?category=Exterior" class="product-filter-btn <?= $categoryFilter === 'Exterior' ? 'active' : '' ?>">
                <i class="bi bi-shield-check"></i> Exterior & Aero Trims
            </a>
        </div>

        <!-- Product Grid -->
        <div class="row g-4">
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): 
                    if (function_exists('translate_entity_row')) $product = translate_entity_row('product', $product);
                ?>
                    <?php $image = contentImageUrl($product["image"] ?? "", "uploads/products"); ?>
                    <div class="col-md-6 col-lg-3">
                        <article class="product-card-modern">
                            <div class="product-thumb-wrapper">
                                <span class="product-pill">Tier-1 OEM</span>
                                <?php if ($image !== ""): ?>
                                    <img src="<?= htmlspecialchars($image) ?>"
                                         class="product-card-thumb"
                                         alt="<?= htmlspecialchars($product["name"] ?? "Product") ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 220px;">
                                        <i class="bi bi-car-front text-muted fs-1"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <span class="badge bg-np-tint text-np-green border border-success-subtle align-self-start mb-2 small">
                                    IATF 16949 Certified
                                </span>
                                <h3 class="h5 fw-bold text-np-green mb-2">
                                    <a class="text-decoration-none text-np-green" href="product-detail.php?id=<?= (int) $product["id"] ?>">
                                        <?= htmlspecialchars($product["name"] ?? "") ?>
                                    </a>
                                </h3>
                                <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                    <?= htmlspecialchars($product["description"] ?? "") ?>
                                </p>
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                    <a class="small fw-bold text-np-green text-decoration-none" href="product-detail.php?id=<?= (int) $product["id"] ?>">
                                        Detail Spesifikasi →
                                    </a>
                                    <a href="contact.php?product=<?= urlencode($product['name']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" title="Minta Penawaran B2B">
                                        RFQ <i class="bi bi-send"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Tidak ada produk ditemukan untuk kategori ini.</p>
                    <a href="products.php" class="btn btn-np-primary">Kembali ke Semua Produk</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 2. Technical Capabilities & Resin Compatibility -->
<?php if (is_section_active('products', 'products_technical_capabilities')): ?>
<section class="section-padding bg-light border-top">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('products', 'products_technical_capabilities', 'icon', 'bi-layers-fill')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('products', 'products_technical_capabilities', 'badge_text', 'MATERIAL ENGINEERING EXCELLENCE')) ?>
                </span>
                <h2 class="display-6 fw-bold text-np-green mb-3">
                    <?= htmlspecialchars(get_section_val('products', 'products_technical_capabilities', 'title', 'Keunggulan Material & Toleransi Presisi Tinggi')) ?>
                </h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars(get_section_val('products', 'products_technical_capabilities', 'subtitle', 'Penggunaan resin rekayasa tinggi (PP-TD20, ABS, PC/ABS, PA66-GF) dengan ketahanan getaran, anti gores, dan kestabilan termal optimal.'))) ?>
                </p>
                <div class="row g-3">
                    <?php 
                    $matFeats = sec_items('products_material_features');
                    if (!empty($matFeats)):
                        foreach ($matFeats as $mf):
                    ?>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi <?= htmlspecialchars($mf['icon'] ?: 'bi-shield-check') ?> me-1"></i> <?= htmlspecialchars($mf['title']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($mf['subtitle'] ?? $mf['content'] ?? '') ?></small>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 bg-white rounded-4 border shadow-sm">
                    <h5 class="fw-bold text-np-green mb-3"><i class="bi bi-cpu me-2"></i> Matriks Resin Kompatibel</h5>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <?php 
                        $resins = sec_items('products_resins');
                        if (!empty($resins)):
                            foreach ($resins as $rs):
                        ?>
                            <li class="p-2 bg-light rounded d-flex justify-content-between">
                                <strong><?= htmlspecialchars($rs['title']) ?></strong>
                                <span class="text-muted"><?= htmlspecialchars($rs['subtitle'] ?? $rs['content'] ?? '') ?></span>
                            </li>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 3. Custom OEM Engineering -->
<?php if (is_section_active('products', 'products_custom_oem')): ?>
<section class="section-padding">
    <div class="container text-center max-w-700 mx-auto">
        <span class="eyebrow-badge mx-auto">
            <i class="bi <?= htmlspecialchars(get_section_val('products', 'products_custom_oem', 'icon', 'bi-gear-wide-connected')) ?>"></i>
            <?= htmlspecialchars(get_section_val('products', 'products_custom_oem', 'badge_text', 'TAILORED OEM SOURCING')) ?>
        </span>
        <h2 class="display-6 fw-bold text-np-green mb-3">
            <?= htmlspecialchars(get_section_val('products', 'products_custom_oem', 'title', 'Solusi Kustomisasi Komponen Sesuai Kebutuhan OEM')) ?>
        </h2>
        <p class="text-muted mb-4">
            <?= nl2br(htmlspecialchars(get_section_val('products', 'products_custom_oem', 'subtitle', 'Dukungan rekayasa teknis mulai dari perancangan cetakan (moulding), pemilihan tekstur laser butir, hingga uji coba prototipe cepat.'))) ?>
        </p>
    </div>
</section>
<?php endif; ?>

<!-- 4. CTA B2B Sourcing -->
<?php if (is_section_active('products', 'products_cta')): ?>
<section class="section-padding bg-np-green text-white">
    <div class="container text-center max-w-700 mx-auto">
        <div class="badge bg-white text-np-green fw-bold px-3 py-2 rounded-pill mb-3">
            <i class="bi <?= htmlspecialchars(get_section_val('products', 'products_cta', 'icon', 'bi-file-earmark-text')) ?> me-1"></i>
            <?= htmlspecialchars(get_section_val('products', 'products_cta', 'badge_text', 'B2B SOURCING INQUIRY')) ?>
        </div>
        <h2 class="display-6 fw-bold mb-3 text-white">
            <?= htmlspecialchars(get_section_val('products', 'products_cta', 'title', 'Tertarik Mengembangkan Komponen Baru Bersama Kami?')) ?>
        </h2>
        <p class="lead text-white-50 mb-4">
            <?= htmlspecialchars(get_section_val('products', 'products_cta', 'subtitle', 'Ajukan permintaan penawaran harga (RFQ) dan jadwalkan sesi tinjauan teknis bersama tim rekayasa kami.')) ?>
        </p>
        <a href="<?= htmlspecialchars(get_section_val('products', 'products_cta', 'button_link', 'contact.php')) ?>" class="btn btn-light text-np-green fw-bold px-4 py-3 rounded-pill">
            <?= htmlspecialchars(get_section_val('products', 'products_cta', 'button_text', 'Ajukan Permintaan RFQ')) ?>
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
