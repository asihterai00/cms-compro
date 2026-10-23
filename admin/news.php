<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/translation_helpers.php";

require_permission('news', 'read');

/* Tambahkan kolom image jika database versi lama belum memilikinya. */
$columnCheck = $conn->query("SHOW COLUMNS FROM news LIKE 'image'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE news ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER content");
}

$editItem = null;
$newsTranslations = [];
if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editItem = $stmt->get_result()->fetch_assoc();
    if ($editItem && function_exists('get_entity_all_translations')) {
        $newsTranslations = get_entity_all_translations('news', $id);
    }
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
    $sort_order = (int)($_POST["sort_order"] ?? 10);

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
            $newsId = $id;
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image = ?, sort_order = ? WHERE id = ?");
                $stmt->bind_param("sssii", $title, $content, $image, $sort_order, $id);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO news (title, content, image, sort_order, published_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssi", $title, $content, $image, $sort_order);
                $stmt->execute();
                $newsId = $conn->insert_id;
            }

            // Save multi-language translations
            $transPost = $_POST['trans'] ?? [];
            if (!empty($transPost) && function_exists('save_entity_translations') && $newsId > 0) {
                save_entity_translations('news', $newsId, $transPost);
            }

            header("Location: news.php?saved=1");
            exit;
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

$news = $conn->query("SELECT * FROM news ORDER BY sort_order ASC, published_at DESC, id DESC");
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
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-newspaper text-emerald me-2"></i>Berita &amp; Publikasi Korporat
        </h1>
        <p class="text-muted small mb-0">
            Kelola publikasi berita resmi, siaran pers, agenda CSR, serta pengumuman internal perusahaan.
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <?php if (!$editItem): ?>
            <a href="#formNews" class="btn btn-emerald btn-sm d-inline-flex align-items-center gap-2 shadow-sm px-3 py-2 fw-bold">
                <i class="bi bi-plus-circle-fill"></i> Tambah Berita Baru
            </a>
        <?php else: ?>
            <a href="news.php" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-x-circle"></i> Batal Edit
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($notice !== ""): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($notice) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error !== ""): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="card border-0 shadow-sm mb-4" id="formNews">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-slate-900">
            <i class="bi <?= $editItem ? 'bi-pencil-square text-primary' : 'bi-plus-circle text-emerald' ?> me-2"></i>
            <?= $editItem ? "Edit Berita: " . htmlspecialchars($editItem['title']) : "Tambah Berita Baru" ?>
        </h5>
        <?php if ($editItem): ?>
            <a href="news.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Batal
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-4">
        <?php if (!empty($editItem["image"])): ?>
            <div class="mb-3">
                <label class="form-label d-block small fw-bold">Foto Berita Saat Ini</label>
                <img src="../<?= htmlspecialchars(ltrim($editItem["image"], "/")) ?>" alt="Foto berita" class="rounded-3 border" style="max-width:320px;max-height:180px;object-fit:cover;">
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="mt-2">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($editItem["id"] ?? 0) ?>">
            <input type="hidden" name="old_image" value="<?= htmlspecialchars($editItem["image"] ?? "") ?>">
            <!-- Multi-Language Translation Tabs -->
            <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-dark text-uppercase"><i class="bi bi-translate me-1"></i> Konten Berita Multilingual:</span>
                    <small class="text-muted">Tersimpan ke database & berubah otomatis saat pengunjung mengganti bahasa</small>
                </div>
                <ul class="nav nav-tabs mb-3" id="langTabsNews" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-success" id="tab-news-id-tab" data-bs-toggle="tab" data-bs-target="#tab-news-id" type="button" role="tab">
                            <img src="../assets/img/flags/id.svg" style="width:16px;height:12px;margin-right:4px;"> Indonesia (Utama)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-news-en-tab" data-bs-toggle="tab" data-bs-target="#tab-news-en" type="button" role="tab">
                            <img src="../assets/img/flags/en.svg" style="width:16px;height:12px;margin-right:4px;"> English
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-news-ja-tab" data-bs-toggle="tab" data-bs-target="#tab-news-ja" type="button" role="tab">
                            <img src="../assets/img/flags/ja.svg" style="width:16px;height:12px;margin-right:4px;"> 日本語
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-news-zh-tab" data-bs-toggle="tab" data-bs-target="#tab-news-zh" type="button" role="tab">
                            <img src="../assets/img/flags/zh.svg" style="width:16px;height:12px;margin-right:4px;"> 简体中文
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-news-ko-tab" data-bs-toggle="tab" data-bs-target="#tab-news-ko" type="button" role="tab">
                            <img src="../assets/img/flags/ko.svg" style="width:16px;height:12px;margin-right:4px;"> 한국어
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="langTabsContentNews">
                    <!-- Tab ID -->
                    <div class="tab-pane fade show active" id="tab-news-id" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Berita (🇮🇩 Indonesia)</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editItem["title"] ?? "") ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Isi Berita (🇮🇩 Indonesia)</label>
                            <textarea name="content" class="form-control" rows="5"><?= htmlspecialchars($editItem["content"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <!-- Tab EN -->
                    <div class="tab-pane fade" id="tab-news-en" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">News Title (🇬🇧 English)</label>
                            <input type="text" name="trans[en][title]" class="form-control" value="<?= htmlspecialchars($newsTranslations['en']['title'] ?? '') ?>" placeholder="English title...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">News Content (🇬🇧 English)</label>
                            <textarea name="trans[en][content]" class="form-control" rows="5" placeholder="English content..."><?= htmlspecialchars($newsTranslations['en']['content'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab JA -->
                    <div class="tab-pane fade" id="tab-news-ja" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ニュースタイトル (🇯🇵 日本語)</label>
                            <input type="text" name="trans[ja][title]" class="form-control" value="<?= htmlspecialchars($newsTranslations['ja']['title'] ?? '') ?>" placeholder="日本語のタイトル...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">ニュース本文 (🇯🇵 日本語)</label>
                            <textarea name="trans[ja][content]" class="form-control" rows="5" placeholder="日本語の記事本文..."><?= htmlspecialchars($newsTranslations['ja']['content'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab ZH -->
                    <div class="tab-pane fade" id="tab-news-zh" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">新闻标题 (🇨🇳 简体中文)</label>
                            <input type="text" name="trans[zh][title]" class="form-control" value="<?= htmlspecialchars($newsTranslations['zh']['title'] ?? '') ?>" placeholder="中文新闻标题...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">新闻内容 (🇨🇳 简体中文)</label>
                            <textarea name="trans[zh][content]" class="form-control" rows="5" placeholder="中文新闻详细正文..."><?= htmlspecialchars($newsTranslations['zh']['content'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab KO -->
                    <div class="tab-pane fade" id="tab-news-ko" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">뉴스 제목 (🇰🇷 한국어)</label>
                            <input type="text" name="trans[ko][title]" class="form-control" value="<?= htmlspecialchars($newsTranslations['ko']['title'] ?? '') ?>" placeholder="한국어 뉴스 제목...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">뉴스 본문 (🇰🇷 한국어)</label>
                            <textarea name="trans[ko][content]" class="form-control" rows="5" placeholder="한국어 뉴스 본문 내용..."><?= htmlspecialchars($newsTranslations['ko']['content'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Foto berita (JPG/PNG/WEBP, maksimal 5 MB)</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">Opsional. Jika tidak memilih foto baru, foto lama tetap digunakan.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nomor Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($editItem['sort_order'] ?? 10) ?>" step="5">
                    <div class="form-text">Angka kecil tampil lebih dulu.</div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-emerald px-4 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> <?= $editItem ? "Perbarui Berita" : "Simpan Berita Baru" ?>
                </button>
                <?php if ($editItem): ?>
                    <a href="news.php" class="btn btn-outline-secondary px-3">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-slate-900">Daftar Berita &amp; Artikel Publikasi</h5>
        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle fw-bold">Live Portal</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 110px;">Urutan</th>
                        <th style="width: 90px;">Foto</th>
                        <th style="width: 140px;">Tanggal</th>
                        <th style="width: 260px;">Judul Berita</th>
                        <th>Ringkasan Isi</th>
                        <th style="width: 140px;" class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($news): while ($item = $news->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="ajax_reorder.php?table=news&action=move_up&id=<?= (int)$item['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Atas">
                                    <i class="bi bi-arrow-up"></i>
                                </a>
                                <a href="ajax_reorder.php?table=news&action=move_down&id=<?= (int)$item['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Bawah">
                                    <i class="bi bi-arrow-down"></i>
                                </a>
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace"><?= (int)($item['sort_order'] ?? 10) ?></span>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($item["image"])): ?>
                                <img src="../<?= htmlspecialchars(ltrim($item["image"], "/")) ?>" alt="" class="rounded-3 border" style="width:75px;height:50px;object-fit:cover;">
                            <?php else: ?>
                                <div class="rounded-3 border d-flex align-items-center justify-content-center bg-light text-muted" style="width:75px;height:50px;font-size:0.7rem;">
                                    No Image
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small fw-semibold text-slate-700"><?= htmlspecialchars(date('d M Y', strtotime($item["published_at"] ?? 'now'))) ?></div>
                            <div class="small text-muted" style="font-size: 0.68rem;"><?= htmlspecialchars(date('H:i', strtotime($item["published_at"] ?? 'now'))) ?> WIB</div>
                        </td>
                        <td>
                            <div class="fw-bold text-slate-900"><?= htmlspecialchars($item["title"] ?? "") ?></div>
                            <span class="badge bg-light text-slate-600 border small mt-1 font-monospace" style="font-size: 0.65rem;">ID: #<?= (int)$item["id"] ?></span>
                        </td>
                        <td>
                            <div class="small text-muted" style="line-height: 1.5; max-width: 450px;">
                                <?= htmlspecialchars(mb_strimwidth(strip_tags($item["content"] ?? ""), 0, 110, "...")) ?>
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="news.php?edit=<?= (int) $item["id"] ?>#formNews" class="btn btn-outline-primary" title="Edit berita ini">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <?php if (has_permission('news', 'delete')): ?>
                                    <a href="<?= csrf_url('news.php?delete=' . (int) $item["id"]) ?>" class="btn btn-outline-danger" title="Hapus berita ini">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
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
