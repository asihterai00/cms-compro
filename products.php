<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

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
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Lini Produk & Sistem Komponen Otomotif - PT Nihon Plast Indonesia</title>
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
                <?php while ($product = $products->fetch_assoc()): ?>
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

<!-- B2B Consultation Section -->
<section class="section-padding bg-light border-top">
    <div class="container text-center max-w-700 mx-auto">
        <span class="eyebrow-badge mx-auto">
            <i class="bi bi-tools"></i> CUSTOM OEM ENGINEERING
        </span>
        <h2 class="display-6 fw-bold text-np-green mb-3">Membutuhkan Komponen dengan Spesifikasi Khusus?</h2>
        <p class="text-muted mb-4">
            Tim R&D dan manufaktur PT Nihon Plast Indonesia siap mendiskusikan mold tooling, material resin terbarukan, dan perancangan prototipe keselamatan bersama tim rekayasa otomotif Anda.
        </p>
        <a href="contact.php" class="btn btn-np-primary px-4 py-3 fw-bold">
            <i class="bi bi-chat-square-text me-2"></i> Konsultasikan Proyek OEM Anda
        </a>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
