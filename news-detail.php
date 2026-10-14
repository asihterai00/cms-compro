<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/content_helpers.php";

$id = (int) ($_GET["id"] ?? 0);
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
if (!$article) {
    http_response_code(404);
}
$image = contentImageUrl($article["image"] ?? "", "uploads/news");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars($article["title"] ?? "Berita tidak ditemukan") ?></title>
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>
<main class="section-padding">
    <div class="container content-detail-container">
        <?php if (!$article): ?>
            <div class="alert alert-warning">Berita tidak ditemukan.</div>
            <a href="news.php" class="btn btn-outline-dark">Kembali ke News</a>
        <?php else: ?>
            <a href="news.php" class="content-back-link">← Kembali ke News</a>
            <article class="content-article mt-3">
                <?php if ($image !== ""): ?>
                    <a href="<?= htmlspecialchars($image) ?>" target="_blank" rel="noopener">
                        <img src="<?= htmlspecialchars($image) ?>" class="content-news-image" alt="<?= htmlspecialchars($article["title"]) ?>">
                    </a>
                <?php endif; ?>
                <small class="text-muted"><?= htmlspecialchars($article["published_at"] ?? "") ?></small>
                <h1 class="content-detail-title mt-3"><?= htmlspecialchars($article["title"]) ?></h1>
                <div class="content-detail-copy"><?= nl2br(htmlspecialchars($article["content"] ?? "")) ?></div>
            </article>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
