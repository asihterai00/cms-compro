<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";
require_once __DIR__ . "/includes/translation_helpers.php";

$id = (int) ($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    http_response_code(404);
}
if ($product && function_exists('translate_entity_row')) {
    $product = translate_entity_row('product', $product);
}
$image = contentImageUrl($product["image"] ?? "", "uploads/products");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars($product["name"] ?? "Detail Komponen Otomotif") ?></title>
    <link rel="stylesheet" href="assets/css/product-detail-layout.css">
    <style>
        .spec-box-item {
            background: var(--np-light);
            border: 1px solid var(--np-border);
            border-radius: var(--np-radius-md);
            padding: 1.25rem;
        }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<main class="section-padding">
    <div class="container">
        <?php if (!$product): ?>
            <div class="alert alert-warning">Produk tidak ditemukan.</div>
            <a href="products.php" class="btn btn-np-outline">Kembali ke Products</a>
        <?php else: ?>
            <a href="products.php" class="content-back-link mb-4 d-inline-block text-np-green fw-bold">← Kembali ke Lini Produk</a>
            <div class="product-detail-layout">
                <div class="product-detail-media">
                    <?php if ($image !== ""): ?>
                        <button type="button" class="product-detail-image-button" data-bs-toggle="modal" data-bs-target="#productImageModal" aria-label="Perbesar foto produk">
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product["name"]) ?>" class="product-detail-image">
                        </button>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-4" style="height: 340px;">
                            <i class="bi bi-car-front text-muted fs-1"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="product-detail-info">
                    <span class="eyebrow-badge">
                        <i class="bi bi-patch-check-fill"></i> TIER-1 OEM AUTOMOTIVE COMPONENT
                    </span>
                    <h1 class="display-6 fw-bold text-np-green mb-3"><?= htmlspecialchars($product["name"]) ?></h1>
                    
                    <div class="content-detail-copy text-muted mb-4" style="line-height: 1.8;">
                        <?= nl2br(htmlspecialchars($product["description"] ?? "")) ?>
                    </div>

                    <!-- Technical Specs Box -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="spec-box-item">
                                <div class="small text-muted fw-bold text-uppercase">Standar Sertifikasi Mutu</div>
                                <div class="fw-bold text-np-green"><i class="bi bi-shield-check me-1"></i> IATF 16949:2016</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="spec-box-item">
                                <div class="small text-muted fw-bold text-uppercase">Fasilitas Manufaktur</div>
                                <div class="fw-bold text-np-green"><i class="bi bi-building me-1"></i> Cikampek, Karawang Plant</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="spec-box-item">
                                <div class="small text-muted fw-bold text-uppercase">Kepatuhan Lingkungan</div>
                                <div class="fw-bold text-np-green"><i class="bi bi-recycle me-1"></i> ISO 14001 & RoHS / ELV</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="spec-box-item">
                                <div class="small text-muted fw-bold text-uppercase">Toleransi Presisi</div>
                                <div class="fw-bold text-np-green"><i class="bi bi-crosshair me-1"></i> CMM Verified (±0.001 mm)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="contact.php?product=<?= urlencode($product['name']) ?>" class="btn btn-np-primary">
                            <i class="bi bi-file-earmark-text me-1"></i> Ajukan B2B RFQ untuk Komponen Ini
                        </a>
                        <a href="quality.php" class="btn btn-np-outline">
                            <i class="bi bi-patch-check me-1"></i> Verifikasi Standar Mutu
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($image !== ""): ?>
            <div class="modal fade" id="productImageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-dark border-0">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body text-center pt-0">
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product["name"]) ?>" class="product-detail-modal-image">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
