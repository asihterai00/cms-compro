<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Products</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
    <style>
        .product-photo-trigger{display:block;width:100%;padding:0;border:0;background:transparent;cursor:zoom-in;position:relative}
        .product-photo-trigger .product-image{display:block;width:100%;height:240px;object-fit:contain;background:#f7f7f7}
        .product-photo-trigger:focus-visible{outline:3px solid #198754;outline-offset:3px}
        .product-photo-modal{display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.86);align-items:center;justify-content:center;padding:28px}
        .product-photo-modal.is-open{display:flex}
        .product-photo-modal img{display:block;max-width: min(92vw,1100px);max-height:85vh;width:auto;height:auto;object-fit:contain;background:#fff;border-radius:4px}
        .product-photo-close{position:absolute;top:14px;right:22px;border:0;border-radius:50%;width:44px;height:44px;font-size:30px;line-height:1;background:#fff;color:#222;cursor:pointer}
        .product-photo-caption{position:absolute;bottom:12px;left:20px;right:20px;text-align:center;color:#fff}
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "products"; include __DIR__ . "/includes/page_banner.php"; ?>
<section class="section-padding"><div class="container">
    <div class="row g-4">
        <?php if ($products): while ($product = $products->fetch_assoc()): ?>
            <?php $image = contentImageUrl($product["image"] ?? "", "uploads/products"); ?>
            <div class="col-md-6 col-lg-4">
                <article class="card product-card h-100 content-click-card">
                    <?php if ($image !== ""): ?>
                        <button type="button" class="product-photo-trigger js-product-photo" data-image="<?= htmlspecialchars($image, ENT_QUOTES, "UTF-8") ?>" data-caption="<?= htmlspecialchars($product["name"] ?? "Produk", ENT_QUOTES, "UTF-8") ?>" aria-label="Perbesar foto <?= htmlspecialchars($product["name"] ?? "produk", ENT_QUOTES, "UTF-8") ?>">
                            <img src="<?= htmlspecialchars($image) ?>" class="product-image" alt="<?= htmlspecialchars($product["name"] ?? "Product") ?>" loading="lazy">
                            <span class="image-open-hint">Lihat foto ⌕</span>
                        </button>
                    <?php endif; ?>
                    <div class="card-body p-4">
                        <h2 class="h4"><a class="content-title-link" href="product-detail.php?id=<?= (int) $product["id"] ?>"><?= htmlspecialchars($product["name"] ?? "") ?></a></h2>
                        <p><?= htmlspecialchars($product["description"] ?? "") ?></p>
                        <a class="content-more-link" href="product-detail.php?id=<?= (int) $product["id"] ?>">Lihat detail produk →</a>
                    </div>
                </article>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div></section>

<div class="product-photo-modal" id="productPhotoModal" role="dialog" aria-modal="true" aria-label="Pratinjau foto produk" aria-hidden="true">
    <button type="button" class="product-photo-close" id="productPhotoClose" aria-label="Tutup">&times;</button>
    <img id="productPhotoModalImage" src="" alt="">
    <div class="product-photo-caption" id="productPhotoCaption"></div>
</div>
<script>
(function () {
    const modal = document.getElementById('productPhotoModal');
    const modalImage = document.getElementById('productPhotoModalImage');
    const caption = document.getElementById('productPhotoCaption');
    const closeButton = document.getElementById('productPhotoClose');
    let previousFocus = null;

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        modalImage.src = '';
        document.body.style.overflow = '';
        if (previousFocus) previousFocus.focus();
    }

    document.querySelectorAll('.js-product-photo').forEach(function (button) {
        button.addEventListener('click', function () {
            previousFocus = button;
            modalImage.src = button.getAttribute('data-image') || '';
            modalImage.alt = button.getAttribute('data-caption') || 'Foto produk';
            caption.textContent = button.getAttribute('data-caption') || '';
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            closeButton.focus();
        });
    });
    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', function (event) {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });
})();
</script>
<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
