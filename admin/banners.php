<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('banners', 'read');

$conn->query("
    CREATE TABLE IF NOT EXISTS page_banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_key VARCHAR(40) NOT NULL UNIQUE,
        page_title VARCHAR(200) NOT NULL,
        subtitle TEXT,
        image VARCHAR(255) DEFAULT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ON UPDATE CURRENT_TIMESTAMP
    )
");

$pages = [
    "home" => ["Home", "PT Nihon Plast Indonesia", "Driving Quality Through Automotive Manufacturing"],
    "about" => ["About", "About Company", "Learn more about our company, vision, and mission."],
    "products" => ["Products", "Our Products", "Automotive components built with quality and precision."],
    "gallery" => ["Gallery", "Our Gallery", "Photos of our facilities and company activities."],
    "quality" => ["Quality", "Quality & Continuous Improvement", "Commitment to quality, safety, and improvement."],
    "facility" => ["Facility", "Factory & Facility", "Explore our manufacturing facilities."],
    "career" => ["Career", "Join Our Team", "Explore career opportunities with us."],
    "news" => ["News", "Company News", "Latest updates and company activities."],
    "contact" => ["Contact", "Contact Us", "Get in touch with our team."]
];

foreach ($pages as $key => $data) {
    $stmt = $conn->prepare(
        "INSERT IGNORE INTO page_banners (page_key, page_title, subtitle)
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $key, $data[1], $data[2]);
    $stmt->execute();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    require_permission('banners', 'update');

    $key = $_POST["page_key"] ?? "";
    $title = trim($_POST["page_title"] ?? "");
    $subtitle = trim($_POST["subtitle"] ?? "");

    if (!isset($pages[$key])) {
        $error = "Halaman tidak valid.";
    } elseif ($title === "") {
        $error = "Judul halaman wajib diisi.";
    } else {
        $stmt = $conn->prepare(
            "SELECT image FROM page_banners WHERE page_key = ? LIMIT 1"
        );
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $old = $stmt->get_result()->fetch_assoc();
        $image = $old["image"] ?? "";

        if (isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $upResult = cms_upload_image($_FILES["banner_image"], "pages", "banner_" . $key . "_");
            if ($upResult["success"]) {
                $image = $upResult["path"];
            } else {
                $error = $upResult["error"];
            }
        }

        if ($error === "") {
            $stmt = $conn->prepare(
                "UPDATE page_banners
                 SET page_title = ?, subtitle = ?, image = ?
                 WHERE page_key = ?"
            );
            $stmt->bind_param("ssss", $title, $subtitle, $image, $key);
            if ($stmt->execute()) {
                $success = "Banner halaman berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui banner: " . $conn->error;
            }
        }
    }
}

$selected = $_GET["page"] ?? ($_POST["page_key"] ?? "home");
if (!isset($pages[$selected])) {
    $selected = "home";
}

$stmt = $conn->prepare("SELECT * FROM page_banners WHERE page_key = ? LIMIT 1");
$stmt->bind_param("s", $selected);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();

$all = $conn->query("SELECT * FROM page_banners ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Banners | PT Nihon Plast Indonesia</title>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-image text-emerald me-2"></i>Banner Header Halaman Publik
        </h1>
        <p class="text-muted small mb-0">
            Ganti banner jumbotron, judul utama, dan sub-judul setiap halaman publik secara dinamis.
        </p>
    </div>
</div>

<?php if ($success !== ""): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($error !== ""): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-slate-900"><i class="bi bi-list-ul me-2 text-emerald"></i>Pilih Halaman Publik</h6>
            </div>
            <div class="card-body p-2">
                <div class="list-group list-group-flush rounded-3">
                    <?php foreach ($pages as $pageKey => $data): ?>
                        <?php $isAct = ($selected === $pageKey); ?>
                        <a
                            href="banners.php?page=<?= urlencode($pageKey) ?>"
                            class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 px-3 rounded-2 mb-1 border-0 <?= $isAct ? "bg-emerald text-white fw-bold shadow-sm" : "text-slate-700" ?>"
                        >
                            <span><?= htmlspecialchars($data[0]) ?></span>
                            <i class="bi bi-chevron-right small opacity-75"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-slate-900">
                    <i class="bi bi-pencil-square text-emerald me-2"></i>Edit Banner: <?= htmlspecialchars($pages[$selected][0]) ?>
                </h5>
                <span class="badge bg-light text-slate-700 border font-monospace">key: <?= htmlspecialchars($selected) ?></span>
            </div>
            <div class="card-body p-4">

                <form method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="page_key" value="<?= htmlspecialchars($selected) ?>">

                    <div class="mb-3">
                        <label class="form-label">Judul Utama Halaman (Page Title)</label>
                        <input type="text" name="page_title" class="form-control"
                            value="<?= htmlspecialchars($current["page_title"] ?? $pages[$selected][1]) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub-judul / Deskripsi Banner (Subtitle)</label>
                        <textarea name="subtitle" class="form-control" rows="3"><?= htmlspecialchars($current["subtitle"] ?? $pages[$selected][2]) ?></textarea>
                    </div>

                    <?php if (!empty($current["image"])): ?>
                        <div class="mb-3">
                            <label class="form-label d-block">Foto Banner Saat Ini</label>
                            <img src="../<?= htmlspecialchars(ltrim($current["image"], "/")) ?>"
                                alt="Banner saat ini" class="img-fluid rounded-3 border"
                                style="max-height:220px;width:100%;object-fit:cover;">
                            <div class="form-text mt-1 font-monospace small">
                                Path: <?= htmlspecialchars($current["image"]) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label">Unggah Foto Banner Baru</label>
                        <input type="file" name="banner_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text">JPG, PNG, WEBP (maks. 5MB). Biarkan kosong bila tidak ingin mengganti gambar.</div>
                    </div>

                    <button type="submit" class="btn btn-emerald px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-slate-900">Ringkasan Seluruh Banner Halaman</h5>
        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle fw-bold">9 Halaman Publik</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Halaman</th><th>Judul Halaman</th><th>Status Banner</th><th class="text-end pe-3">Aksi</th></tr></thead>
                <tbody>
                <?php while ($row = $all->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold text-slate-900"><?= htmlspecialchars($pages[$row["page_key"]][0] ?? $row["page_key"]) ?></td>
                        <td><?= htmlspecialchars($row["page_title"]) ?></td>
                        <td>
                            <?php if (!empty($row["image"])): ?>
                                <span class="badge-active d-inline-flex align-items-center gap-1"><i class="bi bi-check-circle-fill"></i> Sudah Diatur</span>
                            <?php else: ?>
                                <span class="badge-inactive d-inline-flex align-items-center gap-1"><i class="bi bi-dash-circle"></i> Default Sistem</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-3">
                            <a class="btn btn-sm btn-outline-primary" href="banners.php?page=<?= urlencode($row["page_key"]) ?>">
                                <i class="bi bi-pencil"></i> Edit Banner
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
