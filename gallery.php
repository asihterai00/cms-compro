<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

/* Menyiapkan tabel media tambahan bila belum tersedia. */
$conn->query("CREATE TABLE IF NOT EXISTS gallery_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    media_type ENUM('image','video','youtube') NOT NULL,
    media_path VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(gallery_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* Mengambil kategori galeri dan gambar cover untuk kartu. */
$galleryItems = $conn->query("
    SELECT g.id, g.title,
    COALESCE(
        (SELECT gm.media_path FROM gallery_media gm
         WHERE gm.gallery_id=g.id AND gm.media_type='image'
         ORDER BY gm.id LIMIT 1),
        g.image
    ) AS cover
    FROM gallery g ORDER BY g.id DESC
");

/* Mengubah media menjadi URL yang aman untuk ditampilkan. */
function galleryModalMediaUrl($path, $type) {
    if ($type === 'youtube') {
        return $path;
    }
    if ($type === 'video') {
        return 'uploads/gallery/' . basename($path);
    }
    return contentImageUrl($path, 'uploads/gallery');
}

/* Mengumpulkan seluruh media untuk setiap kategori yang akan dibuka dalam modal. */
$galleryModalItems = [];
if ($galleryItems) {
    while ($item = $galleryItems->fetch_assoc()) {
        $id = (int)$item['id'];
        $mediaRows = [];
        $stmt = $conn->prepare("SELECT media_type, media_path FROM gallery_media WHERE gallery_id=? ORDER BY id DESC");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($media = $result->fetch_assoc()) {
            $mediaRows[] = [
                'type' => $media['media_type'],
                'url' => galleryModalMediaUrl($media['media_path'], $media['media_type']),
                'title' => $item['title']
            ];
        }
        $stmt->close();

        /* Menggunakan foto lama dari tabel gallery bila kategori belum punya media tambahan. */
        if (!$mediaRows && !empty($item['cover'])) {
            $mediaRows[] = [
                'type' => 'image',
                'url' => contentImageUrl($item['cover'], 'uploads/gallery'),
                'title' => $item['title']
            ];
        }

        $galleryModalItems[] = [
            'id' => $id,
            'title' => $item['title'],
            'cover' => contentImageUrl($item['cover'] ?? '', 'uploads/gallery'),
            'count' => count($mediaRows),
            'media' => $mediaRows
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Gallery</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
    <style>
        /* Membuat seluruh kartu galeri tetap mengikuti gaya website yang ada. */
        .gallery-modal-card-trigger { cursor: pointer; }
        .gallery-modal-card-trigger img { cursor: zoom-in; }
        /* Lapisan belakang modal. */
        .gallery-view-modal { display:none; position:fixed; inset:0; z-index:100000; align-items:center; justify-content:center; padding:20px; }
        /* Menampilkan modal saat dibuka. */
        .gallery-view-modal.is-open { display:flex; }
        /* Latar gelap modal. */
        .gallery-view-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.82); }
        /* Panel modal. */
        .gallery-view-modal__panel { position:relative; z-index:1; width:min(1000px,96vw); max-height:92vh; overflow:auto; background:#fff; border-radius:12px; padding:22px; }
        /* Tombol tutup modal. */
        .gallery-view-modal__close { position:sticky; top:0; float:right; z-index:2; width:38px; height:38px; border:0; border-radius:50%; background:#f1f1f1; color:#222; font-size:27px; line-height:1; cursor:pointer; }
        /* Tata letak media di dalam modal konten galeri. */
        .gallery-view-modal__media-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:16px; clear:both; }
        .gallery-view-modal__media-item { min-width:0; background:#f5f6f7; border-radius:10px; padding:8px; }
        .gallery-view-modal__media-item img { display:block; width:100%; max-height:65vh; object-fit:contain; cursor:zoom-in; }
        .gallery-view-modal__media-item video { display:block; width:100%; max-height:65vh; }
        .gallery-view-modal__media-item iframe { display:block; width:100%; aspect-ratio:16/9; border:0; }
        /* Modal foto tunggal: hanya menampilkan foto yang dipilih. */
        .gallery-photo-only .gallery-view-modal__panel { width:auto; max-width:96vw; padding:12px; overflow:visible; background:transparent; box-shadow:none; }
        .gallery-photo-only .gallery-view-modal__media-grid,
        .gallery-photo-only .gallery-view-modal__heading { display:none; }
        .gallery-photo-only .gallery-photo-large { display:block; max-width:94vw; max-height:86vh; width:auto; height:auto; object-fit:contain; border-radius:5px; }
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
<?php $bannerKey="gallery"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($galleryModalItems)): ?>
                <?php foreach ($galleryModalItems as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 content-click-card">
                            <button type="button" class="content-image-link gallery-modal-card-trigger"
                                data-gallery-open-content="<?= (int)$item['id'] ?>"
                                aria-label="Lihat galeri <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php if (!empty($item['cover'])): ?>
                                    <img src="<?= htmlspecialchars($item['cover'], ENT_QUOTES, 'UTF-8') ?>"
                                         class="product-image" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                                <?php else: ?>
                                    <span class="d-block p-5 text-center">Foto / Video</span>
                                <?php endif; ?>
                                <span class="image-open-hint">Lihat media →</span>
                            </button>
                            <div class="card-body p-4">
                                <h2 class="h5">
                                    <button type="button" class="content-title-link gallery-modal-card-trigger"
                                        data-gallery-open-content="<?= (int)$item['id'] ?>">
                                        <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </button>
                                </h2>
                                <span class="text-muted small"><?= (int)$item['count'] ?> media</span>
                                <br>
                                <button type="button" class="content-more-link gallery-modal-card-trigger"
                                    data-gallery-open-content="<?= $item['id'] ?>">Buka galeri →</button>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><div class="alert alert-info">Belum ada galeri.</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Satu modal digunakan untuk foto tunggal maupun isi kategori galeri. -->
<div id="galleryViewModal" class="gallery-view-modal" aria-hidden="true">
    <div class="gallery-view-modal__backdrop" data-gallery-close></div>
    <div class="gallery-view-modal__panel" role="dialog" aria-modal="true" aria-label="Konten galeri">
        <button type="button" class="gallery-view-modal__close" aria-label="Tutup" data-gallery-close>&times;</button>
        <h2 class="gallery-view-modal__heading h4" id="galleryViewModalTitle"></h2>
        <img id="galleryPhotoLarge" class="gallery-photo-large" src="" alt="">
        <div id="galleryViewModalMedia" class="gallery-view-modal__media-grid"></div>
    </div>
</div>

<script>
(function () {
    'use strict';

    /* Menyediakan data galeri dari PHP agar modal tidak perlu membuka halaman lain. */
    const galleryItems = <?= json_encode($galleryModalItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const modal = document.getElementById('galleryViewModal');
    const modalTitle = document.getElementById('galleryViewModalTitle');
    const modalMedia = document.getElementById('galleryViewModalMedia');
    const largePhoto = document.getElementById('galleryPhotoLarge');

    /* Membuka kerangka modal dan mengunci scroll halaman belakang. */
    function openGalleryModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('gallery-modal-open');
    }

    /* Menutup modal dan menghentikan media yang sedang diputar. */
    function closeGalleryModal() {
        modal.classList.remove('is-open', 'gallery-photo-only');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('gallery-modal-open');
        modalMedia.innerHTML = '';
        largePhoto.src = '';
        largePhoto.alt = '';
        modalTitle.textContent = '';
    }

    /* Menampilkan foto saja di dalam modal, tanpa judul atau konten lain. */
    function openPhotoOnly(photoUrl, altText) {
        modal.classList.add('gallery-photo-only');
        modalTitle.textContent = '';
        modalMedia.innerHTML = '';
        largePhoto.src = photoUrl;
        largePhoto.alt = altText || 'Foto galeri';
        openGalleryModal();
    }

    /* Membuat elemen media berdasarkan jenis foto, video lokal, atau YouTube. */
    function createGalleryMedia(media, title) {
        const wrapper = document.createElement('div');
        wrapper.className = 'gallery-view-modal__media-item';

        if (media.type === 'youtube') {
            const frame = document.createElement('iframe');
            frame.src = media.url;
            frame.title = 'Video YouTube - ' + title;
            frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            frame.allowFullscreen = true;
            wrapper.appendChild(frame);
        } else if (media.type === 'video') {
            const video = document.createElement('video');
            video.controls = true;
            video.preload = 'metadata';
            const source = document.createElement('source');
            source.src = media.url;
            video.appendChild(source);
            wrapper.appendChild(video);
        } else {
            const image = document.createElement('img');
            image.src = media.url;
            image.alt = title;
            image.loading = 'lazy';
            image.addEventListener('click', function () {
                openPhotoOnly(media.url, title);
            });
            wrapper.appendChild(image);
        }
        return wrapper;
    }

    /* Membuka semua media milik kategori galeri di dalam modal yang sama. */
    function openGalleryContent(galleryId) {
        const item = galleryItems.find(function (entry) {
            return Number(entry.id) === Number(galleryId);
        });
        if (!item) return;

        modal.classList.remove('gallery-photo-only');
        largePhoto.src = '';
        modalTitle.textContent = item.title;
        modalMedia.innerHTML = '';

        item.media.forEach(function (media) {
            modalMedia.appendChild(createGalleryMedia(media, item.title));
        });
        openGalleryModal();
    }

    /* Menangani klik foto cover, judul, atau tombol Buka galeri. */
    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-gallery-open-content]');
        if (!trigger) return;
        event.preventDefault();
        openGalleryContent(trigger.getAttribute('data-gallery-open-content'));
    });

    /* Menutup modal ketika tombol X atau latar gelap diklik. */
    modal.querySelectorAll('[data-gallery-close]').forEach(function (element) {
        element.addEventListener('click', closeGalleryModal);
    });

    /* Menutup modal menggunakan tombol Escape pada keyboard. */
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeGalleryModal();
        }
    });
})();
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
