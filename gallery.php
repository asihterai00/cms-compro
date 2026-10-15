<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

/* Menyiapkan tabel media foto bila belum tersedia. */
$conn->query("CREATE TABLE IF NOT EXISTS gallery_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    media_type ENUM('image') NOT NULL DEFAULT 'image',
    media_path VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(gallery_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* Mengambil kategori galeri dan foto cover untuk kartu. */
$galleryItems = $conn->query("
    SELECT g.id, g.title,
    COALESCE(
        (SELECT gm.media_path FROM gallery_media gm
         WHERE gm.gallery_id = g.id
         ORDER BY gm.id LIMIT 1),
        g.image
    ) AS cover
    FROM gallery g ORDER BY g.id DESC
");

/* Mengumpulkan seluruh foto untuk setiap kategori yang akan dibuka dalam modal. */
$galleryModalItems = [];
if ($galleryItems) {
    while ($item = $galleryItems->fetch_assoc()) {
        $id = (int)$item['id'];
        $photoRows = [];
        $stmt = $conn->prepare("SELECT media_path FROM gallery_media WHERE gallery_id = ? ORDER BY id DESC");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($photo = $result->fetch_assoc()) {
            $photoRows[] = [
                'type' => 'image',
                'url' => contentImageUrl($photo['media_path'], 'uploads/gallery'),
                'title' => $item['title']
            ];
        }
        $stmt->close();

        /* Menggunakan foto lama dari tabel gallery bila belum ada rekaman di gallery_media. */
        if (!$photoRows && !empty($item['cover'])) {
            $photoRows[] = [
                'type' => 'image',
                'url' => contentImageUrl($item['cover'], 'uploads/gallery'),
                'title' => $item['title']
            ];
        }

        $galleryModalItems[] = [
            'id' => $id,
            'title' => $item['title'],
            'cover' => contentImageUrl($item['cover'] ?? '', 'uploads/gallery'),
            'count' => count($photoRows),
            'media' => $photoRows
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('nav_gallery', 'Galeri Foto')) ?> & Dokumentasi Pabrik</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
    <style>
        .gallery-modal-card-trigger { cursor: pointer; }
        .gallery-modal-card-trigger img { cursor: zoom-in; }
        .gallery-view-modal { display:none; position:fixed; inset:0; z-index:100000; align-items:center; justify-content:center; padding:20px; }
        .gallery-view-modal.is-open { display:flex; }
        .gallery-view-modal__backdrop { position:absolute; inset:0; background:rgba(15, 23, 42, 0.88); backdrop-filter: blur(4px); }
        .gallery-view-modal__panel { position:relative; z-index:1; width:min(1060px, 96vw); max-height:92vh; overflow:auto; background:#ffffff; border-radius:12px; padding:24px; box-shadow:0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .gallery-view-modal__close { position:sticky; top:0; float:right; z-index:2; width:38px; height:38px; border:0; border-radius:50%; background:#f1f5f9; color:#0f172a; font-size:24px; line-height:1; cursor:pointer; transition:all 0.2s; }
        .gallery-view-modal__close:hover { background:#e2e8f0; color:#005A36; }
        .gallery-view-modal__media-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px; clear:both; margin-top:16px; }
        .gallery-view-modal__media-item { min-width:0; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px; overflow:hidden; }
        .gallery-view-modal__media-item img { display:block; width:100%; height:200px; object-fit:cover; border-radius:6px; cursor:zoom-in; transition:transform 0.3s; }
        .gallery-view-modal__media-item img:hover { transform:scale(1.02); }
        .gallery-photo-only .gallery-view-modal__panel { width:auto; max-width:96vw; padding:12px; overflow:visible; background:transparent; box-shadow:none; }
        .gallery-photo-only .gallery-view-modal__media-grid,
        .gallery-photo-only .gallery-view-modal__heading { display:none; }
        .gallery-photo-only .gallery-photo-large { display:block; max-width:94vw; max-height:88vh; width:auto; height:auto; object-fit:contain; border-radius:8px; box-shadow:0 20px 40px rgba(0,0,0,0.5); }
        .gallery-photo-large { display:none; }
        body.gallery-modal-open { overflow:hidden; }
        @media(max-width:600px) {
            .gallery-view-modal { padding:8px; }
            .gallery-view-modal__panel { max-height:94vh; padding:14px; }
            .gallery-view-modal__media-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "gallery"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <?php if (is_section_active('gallery', 'gallery_header')): ?>
        <div class="max-w-700 mb-5">
            <span class="eyebrow-badge">
                <i class="bi <?= htmlspecialchars(get_section_val('gallery', 'gallery_header', 'icon', 'bi-camera-fill')) ?>"></i>
                <?= htmlspecialchars(get_section_val('gallery', 'gallery_header', 'badge_text', __('gallery_badge', 'DOKUMENTASI FOTO MANUFAKTUR'))) ?>
            </span>
            <h1 class="display-6 fw-bold text-np-green mb-2">
                <?= htmlspecialchars(get_section_val('gallery', 'gallery_header', 'title', __('gallery_title', 'Galeri Aktivitas & Fasilitas'))) ?>
            </h1>
            <p class="text-muted">
                <?= htmlspecialchars(get_section_val('gallery', 'gallery_header', 'subtitle', __('gallery_desc', 'Dokumentasi fotografi beresolusi tinggi mengenai kegiatan produksi manufaktur, budaya keselamatan 5S, dan fasilitas pabrik PT Nihon Plast Indonesia.'))) ?>
            </p>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (!empty($galleryModalItems)): ?>
                <?php foreach ($galleryModalItems as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 content-click-card border-0 shadow-sm">
                            <button type="button" class="content-image-link gallery-modal-card-trigger border-0 bg-transparent p-0"
                                data-gallery-open-content="<?= (int)$item['id'] ?>"
                                aria-label="Lihat foto <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php if (!empty($item['cover'])): ?>
                                    <img src="<?= htmlspecialchars($item['cover'], ENT_QUOTES, 'UTF-8') ?>"
                                         class="product-image" style="height: 220px; object-fit: cover; width: 100%;" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="bg-light p-5 text-center text-muted">
                                        <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i> Foto Presisi
                                    </div>
                                <?php endif; ?>
                                <span class="image-open-hint"><?= htmlspecialchars(__('view_details', 'Lihat Rincian')) ?> →</span>
                            </button>
                            <div class="card-body p-4 d-flex flex-column">
                                <h2 class="h5 fw-bold mb-2">
                                    <button type="button" class="content-title-link gallery-modal-card-trigger border-0 bg-transparent p-0 text-start"
                                        data-gallery-open-content="<?= (int)$item['id'] ?>">
                                        <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </button>
                                </h2>
                                <span class="text-muted small mb-3"><?= (int)$item['count'] ?> <?= htmlspecialchars(__('specifications', 'Foto Presisi')) ?></span>
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill gallery-modal-card-trigger"
                                        data-gallery-open-content="<?= $item['id'] ?>">
                                        <?= htmlspecialchars(__('read_more', 'Buka Galeri')) ?> →
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border text-center p-5">
                        <i class="bi bi-images fs-1 text-muted d-block mb-2"></i>
                        <h5 class="fw-bold text-muted">Belum ada album foto</h5>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal Tinjau Foto -->
<div id="galleryViewModal" class="gallery-view-modal" aria-hidden="true" role="dialog">
    <div class="gallery-view-modal__backdrop" data-gallery-close></div>
    <div class="gallery-view-modal__panel" role="dialog" aria-modal="true" aria-label="Foto Dokumentasi">
        <button type="button" class="gallery-view-modal__close" aria-label="Tutup" data-gallery-close>&times;</button>
        <h2 class="gallery-view-modal__heading h4 fw-bold text-np-green" id="galleryViewModalTitle"></h2>
        <img id="galleryPhotoLarge" class="gallery-photo-large" src="" alt="">
        <div id="galleryViewModalMedia" class="gallery-view-modal__media-grid"></div>
    </div>
</div>

<script>
(function () {
    'use strict';
    const galleryItems = <?= json_encode($galleryModalItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const modal = document.getElementById('galleryViewModal');
    const modalTitle = document.getElementById('galleryViewModalTitle');
    const modalMedia = document.getElementById('galleryViewModalMedia');
    const largePhoto = document.getElementById('galleryPhotoLarge');

    function openGalleryModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('gallery-modal-open');
    }

    function closeGalleryModal() {
        modal.classList.remove('is-open', 'gallery-photo-only');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('gallery-modal-open');
        modalMedia.innerHTML = '';
        largePhoto.src = '';
        largePhoto.alt = '';
        modalTitle.textContent = '';
    }

    function openPhotoOnly(photoUrl, altText) {
        modal.classList.add('gallery-photo-only');
        modalTitle.textContent = '';
        modalMedia.innerHTML = '';
        largePhoto.src = photoUrl;
        largePhoto.alt = altText || 'Foto Dokumentasi Presisi';
        openGalleryModal();
    }

    function createGalleryMedia(media, title) {
        const wrapper = document.createElement('div');
        wrapper.className = 'gallery-view-modal__media-item';
        const image = document.createElement('img');
        image.src = media.url;
        image.alt = title;
        image.loading = 'lazy';
        image.addEventListener('click', function () {
            openPhotoOnly(media.url, title);
        });
        wrapper.appendChild(image);
        return wrapper;
    }

    function openGalleryContent(galleryId) {
        const item = galleryItems.find(function (entry) {
            return Number(entry.id) === Number(galleryId);
        });
        if (!item) return;

        modal.classList.remove('gallery-photo-only');
        largePhoto.src = '';
        modalTitle.textContent = item.title;
        modalMedia.innerHTML = '';

        if (Array.isArray(item.media) && item.media.length > 0) {
            item.media.forEach(function (media) {
                modalMedia.appendChild(createGalleryMedia(media, item.title));
            });
        }
        openGalleryModal();
    }

    document.querySelectorAll('[data-gallery-open-content]').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-gallery-open-content');
            if (id) {
                openGalleryContent(id);
            }
        });
    });

    document.querySelectorAll('[data-gallery-close]').forEach(function (element) {
        element.addEventListener('click', closeGalleryModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) {
            closeGalleryModal();
        }
    });
})();
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
