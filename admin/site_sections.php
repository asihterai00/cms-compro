<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('site_sections', 'read');

$success = "";
$error = "";

// 1. Handle Quick 1-Click Toggle Active / Inactive (supports AJAX & Synchronous)
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    require_csrf();
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (isset($_GET['ajax']) && $_GET['ajax'] == 1);
    
    if (!has_permission('site_sections', 'update')) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengubah status elemen.']);
            exit;
        }
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk mengubah status elemen.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE site_sections SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Fetch updated status
        $res = $conn->query("SELECT is_active, section_name FROM site_sections WHERE id = " . $id)->fetch_assoc();
        $newStatus = (int)($res['is_active'] ?? 0);
        $secName = $res['section_name'] ?? 'Elemen';

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'id' => $id,
                'is_active' => $newStatus,
                'message' => 'Status "' . $secName . '" diubah menjadi ' . ($newStatus ? 'AKTIF' : 'NONAKTIF')
            ]);
            exit;
        }
        $_SESSION['flash_success'] = "Status elemen berhasil diperbarui.";
    }
    $retPage = isset($_GET['page']) ? '?page=' . urlencode($_GET['page']) : '';
    header("Location: site_sections.php" . $retPage);
    exit;
}

// 2. Handle Delete Section
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    require_csrf();
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
    require_csrf();
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

        // If selected from Media Asset Library
        if (!empty($_POST['selected_library_image'])) {
            $imagePath = trim($_POST['selected_library_image']);
        }
        if (!empty($_POST['selected_library_bg_image'])) {
            $bgImagePath = trim($_POST['selected_library_bg_image']);
        }

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                $filename = 'sec_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionKey) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'uploads/sections/' . $filename;
                }
            } else {
                $error = "Format foto utama tidak valid. Hanya JPG, PNG, WEBP, SVG yang diperbolehkan.";
            }
        }

        // Handle Background Image Upload
        if ($error === '' && isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['bg_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                $bgFilename = 'bg_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionKey) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['bg_image']['tmp_name'], $uploadDir . $bgFilename)) {
                    $bgImagePath = 'uploads/sections/' . $bgFilename;
                }
            } else {
                $error = "Format foto latar tidak valid. Hanya JPG, PNG, WEBP, SVG yang diperbolehkan.";
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

// Fetch sub-item counts per section_key
$subItemCounts = [];
$cRes = $conn->query("SELECT section_key, COUNT(*) as cnt FROM site_section_items GROUP BY section_key");
if ($cRes) {
    while ($cr = $cRes->fetch_assoc()) {
        $subItemCounts[$cr['section_key']] = (int)$cr['cnt'];
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
    <title>NPI CMS - Elemen Halaman Dinamis | PT Nihon Plast Indonesia</title>
    <style>
        .badge-active { background-color: #005A36; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .badge-inactive { background-color: #64748b; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .table-sections td { vertical-align: middle; }
        .icon-preview-box { width: 38px; height: 38px; border-radius: 8px; background: #e6f4ee; color: #005A36; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .img-thumb-preview { width: 60px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; }
        .page-filter-nav { overflow-x: auto; white-space: nowrap; padding-bottom: 4px; }
        .page-filter-nav .nav-link { border-radius: 50px; padding: 0.45rem 1rem; font-weight: 600; font-size: 0.85rem; color: #475569; }
        .page-filter-nav .nav-link.active { background-color: #005A36; color: #ffffff !important; }
        /* Icon Picker Styles */
        .icon-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 8px; max-height: 380px; overflow-y: auto; padding: 6px; }
        .icon-picker-item { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px 4px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #ffffff; transition: all 0.15s; font-size: 0.65rem; text-align: center; color: #334155; }
        .icon-picker-item i { font-size: 1.4rem; margin-bottom: 4px; color: #005A36; }
        .icon-picker-item:hover { border-color: #005A36; background: #e6f4ee; transform: translateY(-2px); }
        .icon-cat-btn { font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 50px; }
        /* Media Asset Picker Styles */
        .media-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; max-height: 420px; overflow-y: auto; padding: 6px; }
        .media-picker-card { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; cursor: pointer; background: #ffffff; transition: all 0.15s; position: relative; }
        .media-picker-card:hover { border-color: #005A36; box-shadow: 0 4px 12px rgba(0, 90, 54, 0.18); transform: translateY(-2px); }
        .media-picker-img { width: 100%; height: 95px; object-fit: contain; background: #0f172a; padding: 4px; }
        .media-picker-info { padding: 6px 8px; font-size: 0.72rem; }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="mb-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-layout-text-window-reverse text-emerald me-2"></i>100% Dynamic CMS Elements & Sections
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
            <?php if (has_permission('site_sections', 'create')): ?>
                <button class="btn btn-emerald btn-sm d-flex align-items-center gap-2 shadow-sm px-3 py-2 fw-bold" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#formSectionCollapse" 
                        aria-expanded="<?= ($editRow || isset($_GET['new'])) ? 'true' : 'false' ?>"
                        aria-controls="formSectionCollapse">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Elemen Baru
                </button>
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

    <!-- EDIT / CREATE FORM COLLAPSIBLE CONTAINER -->
    <div class="collapse <?= ($editRow || isset($_GET['new'])) ? 'show' : '' ?> mb-4" id="formSectionCollapse">
        <div class="card border-0 shadow-sm" id="formSection">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-slate-900">
                    <i class="bi <?= $editRow ? 'bi-pencil-square text-primary' : 'bi-plus-circle text-emerald' ?> me-2"></i>
                    <?= $editRow ? 'Edit Elemen: ' . htmlspecialchars($editRow['section_name']) : 'Tambah Elemen Dynamic Baru' ?>
                </h5>
                <?php if ($editRow): ?>
                    <a href="site_sections.php<?= $filterPage !== 'all' ? '?page=' . urlencode($filterPage) : '' ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Batal Edit
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#formSectionCollapse">
                        <i class="bi bi-x-circle me-1"></i> Tutup Form
                    </button>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <form method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
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
                                <span class="input-group-text" id="iconPreviewSpan"><i class="bi <?= htmlspecialchars($editRow['icon'] ?? 'bi-shield-check') ?>"></i></span>
                                <input type="text" name="icon" id="iconInput" class="form-control" value="<?= htmlspecialchars($editRow['icon'] ?? 'bi-shield-check') ?>" placeholder="bi-shield-check, bi-gear-fill">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#iconPickerModal" title="Pilih Icon dari Palet">
                                    <i class="bi bi-grid-3x3-gap-fill me-1"></i> Pilih
                                </button>
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
                            <div class="input-group mb-1">
                                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                                <button type="button" class="btn btn-outline-success" onclick="openMediaPicker('image')" title="Pilih dari Galeri Asset yang Sudah Tersedia">
                                    <i class="bi bi-images me-1"></i> Pilih Asset
                                </button>
                            </div>
                            <input type="hidden" name="existing_image" id="input_existing_image" value="<?= htmlspecialchars($editRow['image'] ?? '') ?>">
                            <input type="hidden" name="selected_library_image" id="input_selected_library_image" value="">
                            <div id="preview_box_image" class="mt-2 <?= empty($editRow['image']) ? 'd-none' : '' ?>">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light">
                                    <img src="../<?= htmlspecialchars($editRow['image'] ?? '') ?>" id="img_preview_image" class="img-thumb-preview" alt="Preview">
                                    <span class="small text-muted flex-grow-1 text-truncate" id="label_preview_image"><?= htmlspecialchars($editRow['image'] ?? '') ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="clearSelectedMedia('image')" title="Hapus Gambar"><i class="bi bi-x"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Latar Belakang (Background Image)</label>
                            <div class="input-group mb-1">
                                <input type="file" name="bg_image" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                                <button type="button" class="btn btn-outline-success" onclick="openMediaPicker('bg_image')" title="Pilih dari Galeri Asset yang Sudah Tersedia">
                                    <i class="bi bi-images me-1"></i> Pilih Asset
                                </button>
                            </div>
                            <input type="hidden" name="existing_bg_image" id="input_existing_bg_image" value="<?= htmlspecialchars($editRow['bg_image'] ?? '') ?>">
                            <input type="hidden" name="selected_library_bg_image" id="input_selected_library_bg_image" value="">
                            <div id="preview_box_bg_image" class="mt-2 <?= empty($editRow['bg_image']) ? 'd-none' : '' ?>">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light">
                                    <img src="../<?= htmlspecialchars($editRow['bg_image'] ?? '') ?>" id="img_preview_bg_image" class="img-thumb-preview" alt="BG Preview">
                                    <span class="small text-muted flex-grow-1 text-truncate" id="label_preview_bg_image"><?= htmlspecialchars($editRow['bg_image'] ?? '') ?></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="clearSelectedMedia('bg_image')" title="Hapus Gambar"><i class="bi bi-x"></i></button>
                                </div>
                            </div>
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
                            <button type="submit" class="btn btn-emerald px-4 fw-bold">
                                <i class="bi bi-save me-1"></i> Simpan Elemen
                            </button>
                            <a href="site_sections.php<?= $filterPage !== 'all' ? '?page=' . urlencode($filterPage) : '' ?>" class="btn btn-secondary px-4">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                                        <?php 
                                        $cntItems = $subItemCounts[$s['section_key']] ?? 0;
                                        if ($cntItems > 0):
                                        ?>
                                            <div class="mt-1">
                                                <a href="site_section_items.php?section_key=<?= urlencode($s['section_key']) ?>" class="btn btn-sm btn-outline-success py-0 px-2 fw-semibold" style="font-size: 0.72rem;">
                                                    <i class="bi bi-collection me-1"></i> Kelola <?= $cntItems ?> Sub-Item
                                                </a>
                                            </div>
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
                                        <?php if (has_permission('site_sections', 'update')): ?>
                                            <a href="<?= csrf_url('site_sections.php?action=toggle&id=' . (int)$s['id'] . '&page=' . urlencode($filterPage)) ?>" 
                                               class="btn-toggle-status text-decoration-none d-inline-block" 
                                               data-id="<?= (int)$s['id'] ?>" 
                                               data-csrf="<?= csrf_token() ?>"
                                               title="Klik untuk mengubah status aktif/nonaktif">
                                                <span id="status-badge-<?= (int)$s['id'] ?>" class="<?= $s['is_active'] ? 'badge-active' : 'badge-inactive' ?> d-inline-flex align-items-center gap-1">
                                                    <i class="bi <?= $s['is_active'] ? 'bi-check-circle-fill' : 'bi-eye-slash-fill' ?>"></i> 
                                                    <?= $s['is_active'] ? 'ACTIVE' : 'NOT ACTIVE' ?>
                                                </span>
                                            </a>
                                        <?php else: ?>
                                            <span class="<?= $s['is_active'] ? 'badge-active' : 'badge-inactive' ?> d-inline-flex align-items-center gap-1">
                                                <i class="bi <?= $s['is_active'] ? 'bi-check-circle-fill' : 'bi-eye-slash-fill' ?>"></i>
                                                <?= $s['is_active'] ? 'ACTIVE' : 'NOT ACTIVE' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Quick Toggle Button -->
                                            <?php if (has_permission('site_sections', 'update')): ?>
                                                <a href="<?= csrf_url('site_sections.php?action=toggle&id=' . (int)$s['id'] . '&page=' . urlencode($filterPage)) ?>" 
                                                   id="toggle-btn-<?= (int)$s['id'] ?>"
                                                   class="btn btn-toggle-status <?= $s['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" 
                                                   data-id="<?= (int)$s['id'] ?>" 
                                                   data-csrf="<?= csrf_token() ?>"
                                                   title="<?= $s['is_active'] ? 'Nonaktifkan elemen ini' : 'Aktifkan elemen ini' ?>">
                                                    <i class="bi <?= $s['is_active'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                                </a>
                                            <?php endif; ?>
                                            <!-- Edit Button -->
                                            <a href="site_sections.php?edit=<?= (int)$s['id'] ?>&page=<?= urlencode($filterPage) ?>#formSectionCollapse" class="btn btn-outline-primary" title="Edit konten elemen ini">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <!-- Delete Button -->
                                            <?php if (has_permission('site_sections', 'delete')): ?>
                                                <a href="<?= csrf_url('site_sections.php?action=delete&id=' . (int)$s['id'] . '&page=' . urlencode($filterPage)) ?>" class="btn btn-outline-danger" title="Hapus elemen ini">
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

<?php
$corporateAssets = [
    ['path' => 'uploads/products/product_steering_wheel.jpg', 'title' => 'Steering Wheel Systems (OEM Catalog)', 'cat' => 'Products'],
    ['path' => 'uploads/products/product_airbag_module.jpg', 'title' => 'Airbag Safety Modules (Precision Inflator)', 'cat' => 'Products'],
    ['path' => 'uploads/products/product_cockpit_resin.svg', 'title' => 'Cockpit & Resin Interior Trims (Blueprint)', 'cat' => 'Products'],
    ['path' => 'uploads/products/product_exterior_trim.svg', 'title' => 'Exterior Aerodynamic & Grille (Blueprint)', 'cat' => 'Products'],
    ['path' => 'uploads/gallery/facility_injection_molding.jpg', 'title' => 'Automated Injection Molding Plant', 'cat' => 'Facility'],
    ['path' => 'uploads/gallery/facility_cleanroom_lab.svg', 'title' => 'Cleanroom Class 100k & CMM Laboratory', 'cat' => 'Facility'],
    ['path' => 'uploads/certifications/cert_iatf_16949.svg', 'title' => 'IATF 16949:2016 Official Automotive Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/certifications/cert_iso_9001.svg', 'title' => 'ISO 9001:2015 Quality Management Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/certifications/cert_iso_14001.svg', 'title' => 'ISO 14001:2015 Environmental Green Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/certifications/cert_iso_45001.svg', 'title' => 'ISO 45001:2018 Safety K3 Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/certifications/cert_tkdn_kemenperin.svg', 'title' => 'TKDN 78.4% Kemenperin RI Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/company/logo_nihon_plast_green.png', 'title' => 'PT Nihon Plast Indonesia Corporate Logo', 'cat' => 'Company'],
    ['path' => 'uploads/pages/banner_home.jpg', 'title' => 'Panoramic Automotive Plant Banner', 'cat' => 'Banners']
];

$iconCategories = [
    'Mutu & ISO' => ['bi-shield-check', 'bi-patch-check-fill', 'bi-award', 'bi-award-fill', 'bi-check2-circle', 'bi-clipboard-check', 'bi-shield-shaded', 'bi-star-fill', 'bi-trophy', 'bi-bookmark-star-fill'],
    'Manufaktur & Mesin' => ['bi-gear-fill', 'bi-gear-wide-connected', 'bi-tools', 'bi-wrench-adjustable', 'bi-cpu', 'bi-cpu-fill', 'bi-robot', 'bi-boxes', 'bi-box-seam', 'bi-layers-fill', 'bi-hammer', 'bi-screwdriver'],
    'Otomotif & Transport' => ['bi-car-front', 'bi-car-front-fill', 'bi-speedometer', 'bi-speedometer2', 'bi-disc-fill', 'bi-fuel-pump', 'bi-ev-front', 'bi-compass'],
    'Fasilitas & Gedung' => ['bi-building', 'bi-building-fill-gear', 'bi-building-fill-check', 'bi-geo-alt-fill', 'bi-pin-map-fill', 'bi-hospital', 'bi-door-open'],
    'Lingkungan & ESG' => ['bi-tree-fill', 'bi-flower1', 'bi-sun-fill', 'bi-lightning-charge-fill', 'bi-recycle', 'bi-water', 'bi-cloud-sun'],
    'Bisnis & Data' => ['bi-graph-up-arrow', 'bi-bar-chart-line-fill', 'bi-pie-chart-fill', 'bi-briefcase-fill', 'bi-globe2', 'bi-people-fill', 'bi-person-badge-fill'],
    'Kontak & Layanan' => ['bi-envelope-fill', 'bi-telephone-fill', 'bi-chat-dots-fill', 'bi-send-fill', 'bi-headset', 'bi-info-circle-fill', 'bi-bell-fill']
];
?>

<!-- MODAL: ICON PICKER -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-np-green text-white">
                <h5 class="modal-title fw-bold" id="iconPickerModalLabel">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>Palet Icon Presisi Otomotif &amp; Korporat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <input type="text" id="iconSearchInput" class="form-control" placeholder="Cari icon (contoh: shield, gear, award, car, check, box, tools)..." autocomplete="off">
                </div>
                
                <?php foreach ($iconCategories as $catName => $icons): ?>
                    <div class="mb-3">
                        <div class="fw-bold text-muted small text-uppercase mb-2"><?= htmlspecialchars($catName) ?></div>
                        <div class="icon-picker-grid">
                            <?php foreach ($icons as $ic): ?>
                                <div class="icon-picker-item" data-icon="<?= $ic ?>" onclick="selectIcon('<?= $ic ?>')" title="<?= $ic ?>">
                                    <i class="bi <?= $ic ?>"></i>
                                    <span class="text-truncate" style="max-width: 65px;"><?= str_replace('bi-', '', $ic) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer bg-light py-2">
                <small class="text-muted me-auto"><i class="bi bi-info-circle me-1"></i>Klik pada salah satu icon untuk memilih secara instan.</small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MEDIA ASSET PICKER -->
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-np-green text-white">
                <h5 class="modal-title fw-bold" id="mediaPickerModalLabel">
                    <i class="bi bi-images me-2"></i>Pilih Gambar dari Galeri Asset Perusahaan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p class="text-muted small mb-3">
                    Semua gambar berkualitas tinggi (foto studio kemudi, airbag inflator, cetak biru resin injeksi, interior cleanroom, dan sertifikat resmi) siap digunakan dengan 1-klik tanpa perlu mengunggah ulang.
                </p>
                <div class="media-picker-grid">
                    <?php foreach ($corporateAssets as $asset): ?>
                        <div class="media-picker-card" onclick="selectMediaAsset('<?= htmlspecialchars($asset['path']) ?>')" title="Gunakan gambar ini">
                            <img src="../<?= htmlspecialchars($asset['path']) ?>" class="media-picker-img" alt="<?= htmlspecialchars($asset['title']) ?>">
                            <div class="media-picker-info">
                                <span class="badge bg-secondary-subtle text-secondary small text-uppercase mb-1"><?= htmlspecialchars($asset['cat']) ?></span>
                                <div class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($asset['title']) ?>"><?= htmlspecialchars($asset['title']) ?></div>
                                <code class="small text-muted text-truncate d-block"><?= htmlspecialchars($asset['path']) ?></code>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <small class="text-muted me-auto"><i class="bi bi-check-circle me-1"></i>Klik kartu gambar untuk menetapkannya ke form.</small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTargetField = 'image';

function openMediaPicker(target) {
    currentTargetField = target;
    const modal = new bootstrap.Modal(document.getElementById('mediaPickerModal'));
    modal.show();
}

function selectMediaAsset(path) {
    if (currentTargetField === 'image') {
        document.getElementById('input_selected_library_image').value = path;
        document.getElementById('input_existing_image').value = path;
        document.getElementById('img_preview_image').src = '../' + path;
        document.getElementById('label_preview_image').innerText = path;
        document.getElementById('preview_box_image').classList.remove('d-none');
    } else if (currentTargetField === 'bg_image') {
        document.getElementById('input_selected_library_bg_image').value = path;
        document.getElementById('input_existing_bg_image').value = path;
        document.getElementById('img_preview_bg_image').src = '../' + path;
        document.getElementById('label_preview_bg_image').innerText = path;
        document.getElementById('preview_box_bg_image').classList.remove('d-none');
    }
    bootstrap.Modal.getInstance(document.getElementById('mediaPickerModal')).hide();
}

function clearSelectedMedia(target) {
    if (target === 'image') {
        document.getElementById('input_selected_library_image').value = '';
        document.getElementById('input_existing_image').value = '';
        document.getElementById('preview_box_image').classList.add('d-none');
    } else if (target === 'bg_image') {
        document.getElementById('input_selected_library_bg_image').value = '';
        document.getElementById('input_existing_bg_image').value = '';
        document.getElementById('preview_box_bg_image').classList.add('d-none');
    }
}

function selectIcon(iconName) {
    const iconInput = document.getElementById('iconInput');
    const previewSpan = document.getElementById('iconPreviewSpan');
    if (iconInput) {
        iconInput.value = iconName;
    }
    if (previewSpan) {
        previewSpan.innerHTML = '<i class="bi ' + iconName + '"></i>';
    }
    bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
}

document.addEventListener('DOMContentLoaded', function() {
    const iconSearch = document.getElementById('iconSearchInput');
    if (iconSearch) {
        iconSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.icon-picker-item');
            items.forEach(function(item) {
                const name = item.getAttribute('data-icon') || '';
                if (name.includes(query) || query === '') {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    const iconInput = document.getElementById('iconInput');
    if (iconInput) {
        iconInput.addEventListener('input', function() {
            const val = this.value.trim();
            const previewSpan = document.getElementById('iconPreviewSpan');
            if (previewSpan) {
                previewSpan.innerHTML = '<i class="bi ' + (val ? val : 'bi-shield-check') + '"></i>';
            }
        });
    }

    // 1-Click Seamless AJAX Toggle for Active / Not Active
    document.querySelectorAll('.btn-toggle-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const csrf = this.getAttribute('data-csrf');
            if (!id || !csrf) return;

            const url = 'site_sections.php?action=toggle&id=' + id + '&ajax=1&csrf_token=' + encodeURIComponent(csrf);
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (typeof showAdminToast === 'function') {
                            showAdminToast(data.message, 'success');
                        }
                        // Update badge instantly
                        const badgeEl = document.getElementById('status-badge-' + id);
                        if (badgeEl) {
                            if (data.is_active == 1) {
                                badgeEl.className = 'badge-active d-inline-flex align-items-center gap-1';
                                badgeEl.innerHTML = '<i class="bi bi-check-circle-fill"></i> ACTIVE';
                            } else {
                                badgeEl.className = 'badge-inactive d-inline-flex align-items-center gap-1';
                                badgeEl.innerHTML = '<i class="bi bi-eye-slash-fill"></i> NOT ACTIVE';
                            }
                        }
                        // Update button icon instantly
                        const toggleBtn = document.getElementById('toggle-btn-' + id);
                        if (toggleBtn) {
                            if (data.is_active == 1) {
                                toggleBtn.className = 'btn btn-toggle-status btn-outline-warning';
                                toggleBtn.title = 'Nonaktifkan elemen ini';
                                toggleBtn.innerHTML = '<i class="bi bi-toggle-on"></i>';
                            } else {
                                toggleBtn.className = 'btn btn-toggle-status btn-outline-success';
                                toggleBtn.title = 'Aktifkan elemen ini';
                                toggleBtn.innerHTML = '<i class="bi bi-toggle-off"></i>';
                            }
                        }
                    } else {
                        if (typeof showAdminToast === 'function') {
                            showAdminToast(data.message || 'Gagal mengubah status', 'error');
                        } else {
                            alert(data.message || 'Gagal mengubah status');
                        }
                    }
                })
                .catch(err => {
                    // Fallback to regular full page reload
                    window.location.href = this.getAttribute('href');
                });
        });
    });
});
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
