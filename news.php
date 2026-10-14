<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

$news = $conn->query("SELECT * FROM news ORDER BY published_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Berita & Informasi Perusahaan - PT Nihon Plast Indonesia</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "news"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <div class="max-w-700 mb-5">
            <span class="eyebrow-badge">
                <i class="bi bi-newspaper"></i> CORPORATE ANNOUNCEMENTS
            </span>
            <h1 class="display-6 fw-bold text-np-green mb-2">Berita & Informasi Perusahaan Terkini</h1>
            <p class="text-muted">Ikuti perkembangan terbaru mengenai ekspansi pabrik, pencapaian sertifikasi mutu, inisiatif keberlanjutan, dan kegiatan perusahaan kami.</p>
        </div>

        <div class="row g-4">
            <?php if ($news && $news->num_rows > 0): ?>
                <?php while ($item = $news->fetch_assoc()): ?>
                    <?php $image = contentImageUrl($item["image"] ?? "", "uploads/news"); ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 content-click-card">
                            <?php if ($image !== ""): ?>
                                <a class="content-image-link" href="news-detail.php?id=<?= (int)$item["id"] ?>">
                                    <img src="<?= htmlspecialchars($image) ?>" class="content-news-card-image" alt="<?= htmlspecialchars($item["title"] ?? "News") ?>" loading="lazy">
                                    <span class="image-open-hint">Baca berita →</span>
                                </a>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column">
                                <small class="text-muted mb-2"><i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($item["published_at"] ?? "now")) ?></small>
                                <h2 class="h5 fw-bold mb-2">
                                    <a class="content-title-link" href="news-detail.php?id=<?= (int)$item["id"] ?>">
                                        <?= htmlspecialchars($item["title"] ?? "") ?>
                                    </a>
                                </h2>
                                <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.6;">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($item["content"] ?? ""), 0, 160, "...")) ?>
                                </p>
                                <div class="pt-2 border-top">
                                    <a href="news-detail.php?id=<?= (int)$item["id"] ?>" class="content-more-link">
                                        Baca selengkapnya →
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border text-center py-4">Belum ada berita yang dipublikasikan.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
