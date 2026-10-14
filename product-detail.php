<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

$id = (int) ($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    http_response_code(404);
}
$image = contentImageUrl($product["image"] ?? "", "uploads/products");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars($product["name"] ?? "Produk tidak ditemukan") ?></title>
    <link rel="stylesheet" href="assets/css/product-detail-layout.css">
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<main class="section-padding">
    <div class="container">
        <?php if (!$product): ?>
            <div class="alert alert-warning">Produk tidak ditemukan.</div>
            <a href="products.php" class="btn btn-outline-dark">Kembali ke Products</a>
        <?php else: ?>
            <a href="products.php" class="content-back-link">← Kembali ke Products</a>
            <div class="product-detail-layout">
                <div class="product-detail-media">
                    <?php if ($image !== ""): ?>
                        <button type="button" class="product-detail-image-button" data-bs-toggle="modal" data-bs-target="#productImageModal" aria-label="Perbesar foto produk">
                            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product["name"]) ?>" class="product-detail-image">
                        </button>
                    <?php endif; ?>
                </div>
                <div class="product-detail-info">
                    <p class="eyebrow">OUR PRODUCTS</p>
                    <h1 class="content-detail-title"><?= htmlspecialchars($product["name"]) ?></h1>
                    <div class="content-detail-copy"><?= nl2br(htmlspecialchars($product["description"] ?? "")) ?></div>
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
