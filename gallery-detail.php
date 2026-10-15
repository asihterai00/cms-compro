<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

$id = (int)($_GET['id'] ?? 0);
$st = $conn->prepare('SELECT id, title, image FROM gallery WHERE id = ? LIMIT 1');
$st->bind_param('i', $id);
$st->execute();
$item = $st->get_result()->fetch_assoc();
if (!$item) {
    http_response_code(404);
}

$st = $conn->prepare('SELECT * FROM gallery_media WHERE gallery_id = ? ORDER BY id DESC');
$st->bind_param('i', $id);
$st->execute();
$media = $st->get_result();
$rows = $media ? $media->fetch_all(MYSQLI_ASSOC) : [];

if ($item && !$rows && !empty($item['image'])) {
    $rows[] = ['media_type' => 'image', 'media_path' => $item['image']];
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars($item['title'] ?? __('nav_gallery', 'Galeri Foto')) ?></title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
    <style>
        .gallery-photo-card {
            background: #ffffff;
            border: 1px solid var(--np-border);
            border-radius: var(--np-radius-md);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .gallery-photo-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .gallery-photo-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            cursor: zoom-in;
        }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>

<main class="section-padding">
    <div class="container">
        <a href="gallery.php" class="content-back-link mb-4 d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold">
            <i class="bi bi-arrow-left"></i> <?= htmlspecialchars(__('back_to_list', 'Kembali ke Galeri')) ?>
        </a>

        <?php if (!$item): ?>
            <div class="alert alert-warning mt-3">Album foto tidak ditemukan.</div>
        <?php else: ?>
            <div class="mb-4">
                <span class="eyebrow-badge">
                    <i class="bi bi-camera-fill"></i> <?= htmlspecialchars(__('gallery_badge', 'DOKUMENTASI FOTO MANUFAKTUR')) ?>
                </span>
                <h1 class="display-6 fw-bold text-np-green mb-2"><?= htmlspecialchars($item['title']) ?></h1>
                <p class="text-muted small"><?= count($rows) ?> <?= htmlspecialchars(__('specifications', 'Foto Presisi')) ?></p>
            </div>

            <div class="row g-4">
                <?php foreach ($rows as $m): ?>
                    <?php $src = contentImageUrl($m['media_path'], 'uploads/gallery'); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="gallery-photo-card">
                            <a href="<?= htmlspecialchars($src) ?>" target="_blank" rel="noopener">
                                <img src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
