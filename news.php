<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";
$news = $conn->query("SELECT * FROM news ORDER BY published_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>News</title>
    <link rel="stylesheet" href="assets/css/content-clickable.css">
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "news"; include __DIR__ . "/includes/page_banner.php"; ?>
<section class="section-padding"><div class="container"><div class="row g-4">
<?php if ($news && $news->num_rows > 0): while ($item = $news->fetch_assoc()): ?>
    <?php $image = contentImageUrl($item["image"] ?? "", "uploads/news"); ?>
    <div class="col-md-6 col-lg-4">
        <article class="card h-100 content-click-card">
            <?php if ($image !== ""): ?>
                <a class="content-image-link" href="<?= htmlspecialchars($image) ?>" target="_blank" rel="noopener">
                    <img src="<?= htmlspecialchars($image) ?>" class="content-news-card-image" alt="<?= htmlspecialchars($item["title"] ?? "News") ?>" loading="lazy">
                    <span class="image-open-hint">Lihat foto ↗</span>
                </a>
            <?php endif; ?>
            <div class="card-body p-4">
                <small class="text-muted"><?= htmlspecialchars($item["published_at"] ?? "") ?></small>
                <h2 class="h4 mt-3"><a class="content-title-link" href="news-detail.php?id=<?= (int)$item["id"] ?>"><?= htmlspecialchars($item["title"] ?? "") ?></a></h2>
                <p><?= htmlspecialchars(mb_strimwidth(strip_tags($item["content"] ?? ""), 0, 180, "...")) ?></p>
                <a href="news-detail.php?id=<?= (int)$item["id"] ?>" class="content-more-link">Baca berita lengkap →</a>
            </div>
        </article>
    </div>
<?php endwhile; else: ?><div class="col-12"><div class="alert alert-info">Belum ada berita.</div></div><?php endif; ?>
</div></div></section>
<?php include __DIR__ . "/includes/footer.php"; ?>
</body></html>
