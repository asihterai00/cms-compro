<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('news', 'read');

/* Tambahkan kolom image jika database versi lama belum memilikinya. */
$columnCheck = $conn->query("SHOW COLUMNS FROM news LIKE 'image'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE news ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER content");
}

$editItem = null;
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editItem = $stmt->get_result()->fetch_assoc();
}

$notice = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();

    $id = (int) ($_POST["id"] ?? 0);
    if ($id > 0) {
        require_permission('news', 'update');
    } else {
        require_permission('news', 'create');
    }

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $image = trim($_POST["old_image"] ?? "");

    if ($title === "") {
        $error = "Judul berita wajib diisi.";
    } else {
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $upResult = cms_upload_image($_FILES["image"], "news", "news_");
            if ($upResult["success"]) {
                $image = $upResult["path"];
            } else {
                $error = $upResult["error"];
            }
        }

        if ($error === "") {
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $content, $image, $id);
            } else {
                $stmt = $conn->prepare("INSERT INTO news (title, content, image, published_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("sss", $title, $content, $image);
            }
            if ($stmt->execute()) {
                header("Location: news.php?saved=1");
                exit;
            } else {
                $error = "Berita gagal disimpan: " . $conn->error;
            }
        }
    }
}

if (isset($_GET["delete"])) {
    require_csrf();
    require_permission('news', 'delete');

    $id = (int) $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: news.php?deleted=1");
    exit;
}

if (isset($_GET["saved"])) $notice = "Berita berhasil disimpan.";
if (isset($_GET["deleted"])) $notice = "Berita berhasil dihapus.";

$news = $conn->query("SELECT * FROM news ORDER BY published_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Berita & Publikasi | PT Nihon Plast Indonesia</title>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>
<h1 class="mb-4">News</h1>

<?php if ($notice !== ""): ?><div class="alert alert-success"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
<?php if ($error !== ""): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card table-card mb-4">
    <div class="card-body p-4">
        <h5><?= $editItem ? "Edit News" : "Add News" ?></h5>
        <?php if (!empty($editItem["image"])): ?>
            <div class="mb-3">
                <label class="form-label d-block">Foto saat ini</label>
                <img src="../<?= htmlspecialchars(ltrim($editItem["image"], "/")) ?>" alt="Foto berita" style="max-width:320px;max-height:180px;object-fit:cover;border-radius:10px">
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="mt-3">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($editItem["id"] ?? 0) ?>">
            <input type="hidden" name="old_image" value="<?= htmlspecialchars($editItem["image"] ?? "") ?>">
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editItem["title"] ?? "") ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi berita</label>
                <textarea name="content" class="form-control" rows="6"><?= htmlspecialchars($editItem["content"] ?? "") ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto berita (JPG/PNG/WEBP, maksimal 5 MB)</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Opsional. Jika tidak memilih foto baru, foto lama tetap digunakan.</div>
            </div>
            <button class="btn btn-primary"><?= $editItem ? "Update News" : "Save News" ?></button>
            <?php if ($editItem): ?><a href="news.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Foto</th><th>Tanggal</th><th>Judul</th><th>Isi</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php if ($news): while ($item = $news->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item["image"])): ?>
                                <img src="../<?= htmlspecialchars(ltrim($item["image"], "/")) ?>" alt="" style="width:90px;height:60px;object-fit:cover;border-radius:6px">
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item["published_at"] ?? "") ?></td>
                        <td><?= htmlspecialchars($item["title"] ?? "") ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth(strip_tags($item["content"] ?? ""), 0, 100, "...")) ?></td>
                        <td class="text-nowrap">
                            <a href="news.php?edit=<?= (int) $item["id"] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <?php if (has_permission('news', 'delete')): ?>
                                <a href="<?= csrf_url('news.php?delete=' . (int) $item["id"]) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus berita ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
