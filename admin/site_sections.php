<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('site_sections', 'read');

$success = "";
$error = "";

// 1. Handle Quick 1-Click Toggle Active / Inactive
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    if (!has_permission('site_sections', 'update')) {
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk mengubah status elemen.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE site_sections SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = "Status elemen berhasil diperbarui.";
        }
        $stmt->close();
    }
    $retPage = isset($_GET['page']) ? '?page=' . urlencode($_GET['page']) : '';
    header("Location: site_sections.php" . $retPage);
    exit;
}

// 2. Handle Delete Section
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    require_permission('site_sections', 'delete');
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT image, bg_image FROM site_sections WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        if (!empty($row['image']) && is_file(__DIR__ . '/../' . $row['image'])) {
            @unlink(__DIR__ . '/../' . $row['image']);
        }
        if (!empty($row['bg_image']) && is_file(__DIR__ . '/../' . $row['bg_image'])) {
            @unlink(__DIR__ . '/../' . $row['bg_image']);
        }
        $stmt = $conn->prepare("DELETE FROM site_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['flash_success'] = "Elemen berhasil dihapus.";
    }
    $retPage = isset($_GET['page']) ? '?page=' . urlencode($_GET['page']) : '';
    header("Location: site_sections.php" . $retPage);
    exit;
}

// Flash messages
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
if (isset($_SESSION['flash_error'])) {
    $error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}

// Current Filter Page
$filterPage = trim($_GET['page'] ?? 'all');
$validPages = ['all', 'home', 'about', 'facility', 'quality', 'products', 'contact', 'career', 'gallery', 'news'];
if (!in_array($filterPage, $validPages)) {
    $filterPage = 'all';
}

// 3. Edit Record Fetch
$editId = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM site_sections WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// 4. Handle POST Form Submission (Create or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $page = trim($_POST['page'] ?? 'home');
    $sectionKey = trim($_POST['section_key'] ?? '');
    $sectionName = trim($_POST['section_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $badgeText = trim($_POST['badge_text'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $buttonText = trim($_POST['button_text'] ?? '');
    $buttonLink = trim($_POST['button_link'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $sortOrder = (int)($_POST['sort_order'] ?? 10);

    if ($id > 0) {
        if (!has_permission('site_sections', 'update')) {
            $error = "Anda tidak memiliki izin untuk memperbarui elemen ini.";
        }
    } else {
        if (!has_permission('site_sections', 'create')) {
            $error = "Anda tidak memiliki izin untuk menambah elemen baru.";
        }
    }

    if ($error === '' && ($sectionKey === '' || $sectionName === '')) {
        $error = "Section Key dan Nama Elemen wajib diisi.";
    }

    if ($error === '') {
        $uploadDir = __DIR__ . '/../uploads/sections/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imagePath = $_POST['existing_image'] ?? '';
        $bgImagePath = $_POST['existing_bg_image'] ?? '';

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'sec_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionKey) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'uploads/sections/' . $filename;
                }
            } else {
                $error = "Format foto utama tidak valid. Hanya JPG, PNG, WEBP yang diperbolehkan.";
            }
        }

        // Handle Background Image Upload
        if ($error === '' && isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['bg_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $bgFilename = 'bg_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionKey) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['bg_image']['tmp_name'], $uploadDir . $bgFilename)) {
                    $bgImagePath = 'uploads/sections/' . $bgFilename;
                }
            } else {
                $error = "Format foto latar tidak valid. Hanya JPG, PNG, WEBP yang diperbolehkan.";
            }
        }

        if ($error === '') {
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE site_sections SET 
                    page = ?, section_key = ?, section_name = ?, title = ?, subtitle = ?, content = ?,
                    badge_text = ?, icon = ?, image = ?, bg_image = ?, button_text = ?, button_link = ?,
                    is_active = ?, sort_order = ?
                    WHERE id = ?");
                $stmt->bind_param("sssssssssssiiii", 
                    $page, $sectionKey, $sectionName, $title, $subtitle, $content,
                    $badgeText, $icon, $imagePath, $bgImagePath, $buttonText, $buttonLink,
                    $isActive, $sortOrder, $id);
                if ($stmt->execute()) {
                    $success = "Elemen '{$sectionName}' berhasil diperbarui.";
                    $editRow = null;
                } else {
                    $error = "Gagal memperbarui elemen: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO site_sections 
                    (page, section_key, section_name, title, subtitle, content, badge_text, icon, image, bg_image, button_text, button_link, is_active, sort_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssssiii", 
                    $page, $sectionKey, $sectionName, $title, $subtitle, $content,
                    $badgeText, $icon, $imagePath, $bgImagePath, $buttonText, $buttonLink,
                    $isActive, $sortOrder);
                if ($stmt->execute()) {
                    $success = "Elemen baru '{$sectionName}' berhasil ditambahkan.";
                } else {
                    $error = "Gagal menambahkan elemen: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Counts per page for tabs
$countsQuery = $conn->query("SELECT page, COUNT(*) as c FROM site_sections GROUP BY page");
$pageCounts = [];
$totalAllCount = 0;
while ($cq = $countsQuery->fetch_assoc()) {
    $pageCounts[$cq['page']] = (int)$cq['c'];
    $totalAllCount += (int)$cq['c'];
}

// Fetch sections according to filter
if ($filterPage === 'all') {
    $sectionsQuery = $conn->query("SELECT * FROM site_sections ORDER BY page ASC, sort_order ASC, id ASC");
} else {
    $stmt = $conn->prepare("SELECT * FROM site_sections WHERE page = ? ORDER BY sort_order ASC, id ASC");
    $stmt->bind_param("s", $filterPage);
    $stmt->execute();
    $sectionsQuery = $stmt->get_result();
}

$allSections = [];
$activeCount = 0;
$inactiveCount = 0;
if ($sectionsQuery) {
    while ($sec = $sectionsQuery->fetch_assoc()) {
        if ($sec['is_active']) {
            $activeCount++;
        } else {
            $inactiveCount++;
        }
        $allSections[] = $sec;
    }
}

$pageLabels = [
    'all' => 'Semua Halaman',
    'home' => 'Beranda (Home)',
    'about' => 'Tentang Kami (About)',
    'facility' => 'Fasilitas & Mesin (Facility)',
    'quality' => 'Mutu & ISO (Quality)',
    'products' => 'Produk (Products)',
    'contact' => 'Kontak & RFQ (Contact)',
    'career' => 'Karir (Career)',
    'gallery' => 'Galeri Foto (Gallery)',
    'news' => 'Berita (News)'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>Dynamic CMS Elements - All Pages - Admin</title>
    <style>
        .badge-active { background-color: #005A36; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .badge-inactive { background-color: #64748b; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .table-sections td { vertical-align: middle; }
        .icon-preview-box { width: 38px; height: 38px; border-radius: 8px; background: #e6f4ee; color: #005A36; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .img-thumb-preview { width: 60px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; }
        .page-filter-nav { overflow-x: auto; white-space: nowrap; padding-bottom: 4px; }
        .page-filter-nav .nav-link { border-radius: 50px; padding: 0.45rem 1rem; font-weight: 600; font-size: 0.85rem; color: #475569; }
        .page-filter-nav .nav-link.active { background-color: #005A36; color: #ffffff !important; }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-layout-text-window-reverse text-success me-2"></i>100% Dynamic CMS Elements & Sections
            </h1>
            <p class="text-muted small mb-0">
                Semua halaman dan tiap elemen bisa diubah secara dinamis dari database dengan switch <strong>Active / Not Active</strong> seketika.
            </p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-light border text-dark p-2 px-3 rounded-3 d-flex align-items-center gap-2">
                <span class="badge bg-success rounded-pill"><?= $activeCount ?></span> Aktif
                <span class="badge bg-secondary rounded-pill"><?= $inactiveCount ?></span> Nonaktif
            </span>
            <?php if (!$editRow && has_permission('site_sections', 'create')): ?>
                <a href="#formSection" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-plus-circle"></i> Tambah Elemen Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- PAGE TABS FILTER -->
    <div class="page-filter-nav mb-4">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link <?= $filterPage === 'all' ? 'active' : 'bg-light border' ?>" href="site_sections.php?page=all">
                    Semua Halaman (<?= $totalAllCount ?>)
                </a>
            </li>
            <?php foreach (['home', 'about', 'facility', 'quality', 'products', 'contact', 'career', 'gallery', 'news'] as $pKey): ?>
                <?php $cnt = $pageCounts[$pKey] ?? 0; ?>
                <li class="nav-item">
                    <a class="nav-link <?= $filterPage === $pKey ? 'active' : 'bg-light border' ?>" href="site_sections.php?page=<?= $pKey ?>">
                        <?= htmlspecialchars($pageLabels[$pKey]) ?> (<?= $cnt ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- SUB-MODULE SHORTCUT IF APPLICABLE -->
    <?php if ($filterPage === 'facility'): ?>
        <div class="alert alert-success d-flex justify-content-between align-items-center py-2 px-3 mb-4 rounded-3">
            <div>
                <i class="bi bi-cpu-fill me-1"></i> <strong>Permesinan Fasilitas:</strong> Ingin mengedit daftar permesinan 150T-2500T baris demi baris?
            </div>
            <a href="machinery.php" class="btn btn-sm btn-success">
                <i class="bi bi-table me-1"></i> Buka Matriks Mesin (Machinery)
            </a>
        </div>
    <?php elseif ($filterPage === 'quality'): ?>
        <div class="alert alert-success d-flex justify-content-between align-items-center py-2 px-3 mb-4 rounded-3">
            <div>
                <i class="bi bi-patch-check-fill me-1"></i> <strong>Akreditasi ISO:</strong> Ingin menambah atau mengubah sertifikat ISO dan nomor sertifikasi?
            </div>
            <a href="certifications.php" class="btn btn-sm btn-success">
                <i class="bi bi-award me-1"></i> Kelola Sertifikat ISO
            </a>
        </div>
    <?php elseif ($filterPage === 'products'): ?>
        <div class="alert alert-success d-flex justify-content-between align-items-center py-2 px-3 mb-4 rounded-3">
            <div>
                <i class="bi bi-box-seam me-1"></i> <strong>Katalog Produk:</strong> Ingin mengelola produk spesifik (Steering, Airbag, Resin)?
            </div>
            <a href="products.php" class="btn btn-sm btn-success">
                <i class="bi bi-box-seam me-1"></i> Kelola Katalog Produk
            </a>
        </div>
    <?php endif; ?>

    <!-- EDIT / CREATE FORM -->
    <?php if ($editRow || isset($_GET['new'])): ?>
        <div class="card border-0 shadow-sm mb-4" id="formSection">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    <?= $editRow ? 'Edit Elemen: ' . htmlspecialchars($editRow['section_name']) : 'Tambah Elemen Dynamic Baru' ?>
                </h5>
                <a href="site_sections.php<?= $filterPage !== 'all' ? '?page=' . urlencode($filterPage) : '' ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
            </div>
            <div class="card-body p-4">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= (int)($editRow['id'] ?? 0) ?>">
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editRow['image'] ?? '') ?>">
                    <input type="hidden" name="existing_bg_image" value="<?= htmlspecialchars($editRow['bg_image'] ?? '') ?>">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Halaman (Page)</label>
                            <select name="page" class="form-select">
                                <option value="home" <?= ($editRow['page'] ?? $filterPage) === 'home' ? 'selected' : '' ?>>Beranda (home)</option>
                                <option value="about" <?= ($editRow['page'] ?? $filterPage) === 'about' ? 'selected' : '' ?>>Tentang Kami (about)</option>
                                <option value="facility" <?= ($editRow['page'] ?? $filterPage) === 'facility' ? 'selected' : '' ?>>Fasilitas & Mesin (facility)</option>
                                <option value="quality" <?= ($editRow['page'] ?? $filterPage) === 'quality' ? 'selected' : '' ?>>Mutu & ISO (quality)</option>
                                <option value="products" <?= ($editRow['page'] ?? $filterPage) === 'products' ? 'selected' : '' ?>>Produk (products)</option>
                                <option value="contact" <?= ($editRow['page'] ?? $filterPage) === 'contact' ? 'selected' : '' ?>>Kontak & RFQ (contact)</option>
                                <option value="career" <?= ($editRow['page'] ?? $filterPage) === 'career' ? 'selected' : '' ?>>Karir (career)</option>
                                <option value="gallery" <?= ($editRow['page'] ?? $filterPage) === 'gallery' ? 'selected' : '' ?>>Galeri Foto (gallery)</option>
                                <option value="news" <?= ($editRow['page'] ?? $filterPage) === 'news' ? 'selected' : '' ?>>Berita (news)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Section Key (Kode Teknis)</label>
                            <input type="text" name="section_key" class="form-control" required value="<?= htmlspecialchars($editRow['section_key'] ?? '') ?>" placeholder="contoh: hero, about_dna, facility_overview">
                            <div class="form-text">Kode unik tanpa spasi.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nama Bagian / Elemen</label>
                            <input type="text" name="section_name" class="form-control" required value="<?= htmlspecialchars($editRow['section_name'] ?? '') ?>" placeholder="contoh: Profil DNA Nihon Plast">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Urutan (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= (int)($editRow['sort_order'] ?? 10) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Utama (Title)</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" placeholder="Judul besar bagian">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Eyebrow / Badge Text</label>
                            <input type="text" name="badge_text" class="form-control" value="<?= htmlspecialchars($editRow['badge_text'] ?? '') ?>" placeholder="contoh: NIHON PLAST GROUP DNA">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Subjudul / Deskripsi Pendek (Subtitle)</label>
                            <textarea name="subtitle" class="form-control" rows="2" placeholder="Penjelasan ringkas paragraf"><?= htmlspecialchars($editRow['subtitle'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Konten Detail Paragraf (Content)</label>
                            <textarea name="content" class="form-control" rows="4" placeholder="Teks konten lengkap elemen"><?= htmlspecialchars($editRow['content'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Icon Bootstrap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi <?= htmlspecialchars($editRow['icon'] ?? 'bi-shield-check') ?>"></i></span>
                                <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($editRow['icon'] ?? 'bi-shield-check') ?>" placeholder="bi-shield-check, bi-gear-fill">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Teks Tombol (Button Text)</label>
                            <input type="text" name="button_text" class="form-control" value="<?= htmlspecialchars($editRow['button_text'] ?? '') ?>" placeholder="contoh: Jelajahi Fasilitas">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tautan Tombol (Button Link)</label>
                            <input type="text" name="button_link" class="form-control" value="<?= htmlspecialchars($editRow['button_link'] ?? '') ?>" placeholder="contoh: facility.php">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto / Gambar Elemen</label>
                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <?php if (!empty($editRow['image'])): ?>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="../<?= htmlspecialchars($editRow['image']) ?>" class="img-thumb-preview" alt="Preview">
                                    <span class="small text-muted"><?= htmlspecialchars($editRow['image']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Latar Belakang (Background Image)</label>
                            <input type="file" name="bg_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <?php if (!empty($editRow['bg_image'])): ?>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="../<?= htmlspecialchars($editRow['bg_image']) ?>" class="img-thumb-preview" alt="BG Preview">
                                    <span class="small text-muted"><?= htmlspecialchars($editRow['bg_image']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-check form-switch p-2 bg-light rounded-3 d-inline-flex align-items-center gap-3">
                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="statusSwitch" name="is_active" value="1" <?= (!isset($editRow['is_active']) || $editRow['is_active'] == 1) ? 'checked' : '' ?> style="width: 2.5em; height: 1.3em;">
                                <label class="form-check-label fw-bold mb-0 cursor-pointer" for="statusSwitch">
                                    Tampilkan di Website (Active Status)
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Elemen
                            </button>
                            <a href="site_sections.php<?= $filterPage !== 'all' ? '?page=' . urlencode($filterPage) : '' ?>" class="btn btn-secondary px-4">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- SECTIONS TABLE & STATUS MANAGEMENT -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                Elemen Halaman: <?= htmlspecialchars($pageLabels[$filterPage]) ?> (<?= count($allSections) ?> Elemen)
            </h5>
            <div class="text-muted small">Klik badge <strong>Active / Not Active</strong> untuk switch instan</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sections mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Urutan</th>
                            <th style="width: 60px;">Icon</th>
                            <th>Halaman & Kode Elemen</th>
                            <th>Judul & Teks</th>
                            <th style="width: 140px;">Status</th>
                            <th class="text-end pe-3" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allSections)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-layout-text-window-reverse fs-1 d-block mb-2 text-secondary"></i>
                                    Belum ada elemen dynamic untuk halaman ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allSections as $s): ?>
                                <tr class="<?= $s['is_active'] ? '' : 'table-light opacity-75' ?>">
                                    <td class="ps-3 fw-bold text-muted"><?= (int)$s['sort_order'] ?></td>
                                    <td>
                                        <div class="icon-preview-box">
                                            <i class="bi <?= htmlspecialchars($s['icon'] ?? 'bi-grid') ?>"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border small text-uppercase mb-1">
                                            <?= htmlspecialchars($s['page']) ?>
                                        </span>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($s['section_name']) ?></div>
                                        <code class="small text-secondary">key: <?= htmlspecialchars($s['section_key']) ?></code>
                                        <?php if (!empty($s['badge_text'])): ?>
                                            <div class="badge bg-light text-success border mt-1 small"><?= htmlspecialchars($s['badge_text']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 320px;" title="<?= htmlspecialchars($s['title'] ?? '') ?>">
                                            <?= htmlspecialchars($s['title'] ?? '-') ?>
                                        </div>
                                        <div class="small text-muted text-truncate" style="max-width: 320px;" title="<?= htmlspecialchars($s['subtitle'] ?? '') ?>">
                                            <?= htmlspecialchars($s['subtitle'] ?? '') ?>
                                        </div>
                                        <?php if (!empty($s['button_text'])): ?>
                                            <span class="badge bg-light text-secondary border small mt-1">
                                                Tombol: <?= htmlspecialchars($s['button_text']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- 1-Click Quick Toggle -->
                                        <a href="site_sections.php?action=toggle&id=<?= (int)$s['id'] ?>&page=<?= urlencode($filterPage) ?>" 
                                           class="text-decoration-none d-inline-block" 
                                           title="Klik untuk mengubah status aktif/nonaktif">
                                            <?php if ($s['is_active']): ?>
                                                <span class="badge-active d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-check-circle-fill"></i> ACTIVE
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-inactive d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-eye-slash-fill"></i> NOT ACTIVE
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Quick Toggle Button -->
                                            <a href="site_sections.php?action=toggle&id=<?= (int)$s['id'] ?>&page=<?= urlencode($filterPage) ?>" 
                                               class="btn <?= $s['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" 
                                               title="<?= $s['is_active'] ? 'Nonaktifkan elemen ini' : 'Aktifkan elemen ini' ?>">
                                                <i class="bi <?= $s['is_active'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            </a>
                                            <!-- Edit Button -->
                                            <a href="site_sections.php?edit=<?= (int)$s['id'] ?>&page=<?= urlencode($filterPage) ?>#formSection" class="btn btn-outline-primary" title="Edit konten elemen ini">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <!-- Delete Button -->
                                            <?php if (has_permission('site_sections', 'delete')): ?>
                                                <a href="site_sections.php?action=delete&id=<?= (int)$s['id'] ?>&page=<?= urlencode($filterPage) ?>" class="btn btn-outline-danger" onclick="return confirm('Hapus elemen <?= htmlspecialchars($s['section_name']) ?>?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
