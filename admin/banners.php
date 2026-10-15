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

<div class="mb-4">
    <h1 class="h2 fw-bold">Page Banner Management</h1>
    <p class="text-muted">Ganti gambar, judul, dan subtitle setiap halaman secara terpisah.</p>
</div>

<?php if ($success !== ""): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error !== ""): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Pilih Halaman</h5>
                <div class="list-group">
                    <?php foreach ($pages as $pageKey => $data): ?>
                        <a
                            href="banners.php?page=<?= urlencode($pageKey) ?>"
                            class="list-group-item list-group-item-action <?= $selected === $pageKey ? "active" : "" ?>"
                        >
                            <?= htmlspecialchars($data[0]) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Banner: <?= htmlspecialchars($pages[$selected][0]) ?></h4>

                <form method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="page_key" value="<?= htmlspecialchars($selected) ?>">

                    <div class="mb-3">
                        <label class="form-label">Judul halaman</label>
                        <input type="text" name="page_title" class="form-control"
                            value="<?= htmlspecialchars($current["page_title"] ?? $pages[$selected][1]) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <textarea name="subtitle" class="form-control" rows="3"><?= htmlspecialchars($current["subtitle"] ?? $pages[$selected][2]) ?></textarea>
                    </div>

                    <?php if (!empty($current["image"])): ?>
                        <div class="mb-3">
                            <label class="form-label d-block">Gambar saat ini</label>
                            <img src="../<?= htmlspecialchars(ltrim($current["image"], "/")) ?>"
                                alt="Banner saat ini" class="img-fluid rounded"
                                style="max-height:230px;max-width:100%;object-fit:cover;">
                            <div class="form-text mt-1">
                                Path: <?= htmlspecialchars($current["image"]) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Pilih gambar banner</label>
                        <input type="file" name="banner_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                        <div class="form-text">JPG, PNG, WEBP; maksimal 5 MB. Jika tidak memilih file, gambar sebelumnya dipertahankan.</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Banner</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5>Ringkasan Banner</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>Halaman</th><th>Judul</th><th>Status Gambar</th><th></th></tr></thead>
                <tbody>
                <?php while ($row = $all->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($pages[$row["page_key"]][0] ?? $row["page_key"]) ?></td>
                        <td><?= htmlspecialchars($row["page_title"]) ?></td>
                        <td><?= !empty($row["image"]) ? "Sudah diatur" : "Belum ada gambar" ?></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="banners.php?page=<?= urlencode($row["page_key"]) ?>">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</div>
</body>
</html>
