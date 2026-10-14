<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('site_section_items', 'read');

$success = "";
$error = "";

// 1. Quick 1-Click Toggle Active / Inactive
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    if (!has_permission('site_section_items', 'update')) {
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk mengubah status sub-item ini.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE site_section_items SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = "Status sub-item berhasil diperbarui.";
        }
        $stmt->close();
    }
    $secKey = isset($_GET['section_key']) ? '?section_key=' . urlencode($_GET['section_key']) : '';
    header("Location: site_section_items.php" . $secKey);
    exit;
}

// 2. Handle Delete Sub-Item
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    require_permission('site_section_items', 'delete');
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT image FROM site_section_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        if (!empty($row['image']) && is_file(__DIR__ . '/../' . $row['image'])) {
            @unlink(__DIR__ . '/../' . $row['image']);
        }
        $stmt = $conn->prepare("DELETE FROM site_section_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['flash_success'] = "Sub-item berhasil dihapus.";
    }
    $secKey = isset($_GET['section_key']) ? '?section_key=' . urlencode($_GET['section_key']) : '';
    header("Location: site_section_items.php" . $secKey);
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

// Filter section_key
$filterSection = trim($_GET['section_key'] ?? 'all');

// Fetch edit record
$editId = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM site_section_items WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle Form Submission (Add or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $sectionKey = trim($_POST['section_key'] ?? '');
    $itemKey = trim($_POST['item_key'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $badge = trim($_POST['badge'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $extraDataRaw = trim($_POST['extra_data'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 10);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        if (!has_permission('site_section_items', 'update')) {
            $error = "Anda tidak memiliki izin untuk memperbarui sub-item.";
        }
    } else {
        if (!has_permission('site_section_items', 'create')) {
            $error = "Anda tidak memiliki izin untuk menambah sub-item baru.";
        }
    }

    if ($error === '' && ($sectionKey === '' || $title === '')) {
        $error = "Section Key dan Judul Sub-Item wajib diisi.";
    }

    // Validate JSON extra_data if provided
    $extraData = null;
    if ($extraDataRaw !== '') {
        $decoded = json_decode($extraDataRaw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $extraData = json_encode($decoded);
        } else {
            $error = "Format Extra Data harus berupa JSON yang valid. Contoh: {\"badges\": [\"A\", \"B\"]}";
        }
    }

    if ($error === '') {
        $uploadDir = __DIR__ . '/../uploads/sections/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imagePath = $_POST['existing_image'] ?? '';

        // Library image selection
        if (!empty($_POST['selected_library_image'])) {
            $imagePath = trim($_POST['selected_library_image']);
        }

        // File upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                $filename = 'item_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $sectionKey) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'uploads/sections/' . $filename;
                }
            } else {
                $error = "Format foto tidak valid. Gunakan JPG, PNG, WEBP, atau SVG.";
            }
        }

        if ($error === '') {
            $itemKeyVal = ($itemKey !== '') ? $itemKey : null;
            $subVal = ($subtitle !== '') ? $subtitle : null;
            $contVal = ($content !== '') ? $content : null;
            $iconVal = ($icon !== '') ? $icon : null;
            $badgeVal = ($badge !== '') ? $badge : null;
            $imgVal = ($imagePath !== '') ? $imagePath : null;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE site_section_items SET section_key = ?, item_key = ?, title = ?, subtitle = ?, content = ?, icon = ?, image = ?, badge = ?, extra_data = ?, sort_order = ?, is_active = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("sssssssssiis", $sectionKey, $itemKeyVal, $title, $subVal, $contVal, $iconVal, $imgVal, $badgeVal, $extraData, $sortOrder, $isActive, $id);
                if ($stmt->execute()) {
                    $success = "Sub-item '{$title}' berhasil diperbarui.";
                    $editRow = null;
                } else {
                    $error = "Gagal memperbarui: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, icon, image, badge, extra_data, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssii", $sectionKey, $itemKeyVal, $title, $subVal, $contVal, $iconVal, $imgVal, $badgeVal, $extraData, $sortOrder, $isActive);
                if ($stmt->execute()) {
                    $success = "Sub-item baru '{$title}' berhasil ditambahkan ke '{$sectionKey}'.";
                } else {
                    $error = "Gagal menyimpan: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Fetch all distinct section_keys and their counts
$secStatsRes = $conn->query("SELECT section_key, COUNT(*) as cnt, SUM(is_active) as active_cnt FROM site_section_items GROUP BY section_key ORDER BY section_key ASC");
$sectionStats = [];
$totalItems = 0;
$totalActive = 0;
while ($s = $secStatsRes->fetch_assoc()) {
    $sectionStats[$s['section_key']] = $s;
    $totalItems += (int)$s['cnt'];
    $totalActive += (int)$s['active_cnt'];
}

// Fetch items according to filter
if ($filterSection === 'all') {
    $itemsQuery = $conn->query("SELECT * FROM site_section_items ORDER BY section_key ASC, sort_order ASC, id ASC");
} else {
    $stmt = $conn->prepare("SELECT * FROM site_section_items WHERE section_key = ? ORDER BY sort_order ASC, id ASC");
    $stmt->bind_param("s", $filterSection);
    $stmt->execute();
    $itemsQuery = $stmt->get_result();
}

$allItems = [];
if ($itemsQuery) {
    while ($it = $itemsQuery->fetch_assoc()) {
        $allItems[] = $it;
    }
}

// Section friendly labels
$secKeyLabels = [
    'stats' => 'Home - Stats Counter Bar',
    'oem_trust' => 'Home - OEM Automotive Badges',
    'vehicle_explorer' => 'Home - Vehicle Interactive Explorer Zones',
    'quality_pillars' => 'Home - Quality Manufacturing Pillars',
    'esg_sustainability' => 'Home - ESG & Sustainability Metrics',
    'about_values' => 'About - Corporate Core Values',
    'about_global_footprint' => 'About - Global Network & Footprint',
    'about_datasheet' => 'About - Corporate Datasheet Table',
    'facility_plant_stats' => 'Facility - Plant Land & Capacity Stats',
    'facility_capabilities' => 'Facility - Core Manufacturing Cells',
    'facility_laboratories' => 'Facility - Testing & Metrology Labs',
    'quality_regulatory_compliance' => 'Quality - Indonesian Regulatory Compliance',
    'quality_flowchart' => 'Quality - Quality Framework 4 Pillars',
    'career_intro_features' => 'Career - Intro Highlights & K3',
    'career_culture_benefits' => 'Career - Culture & Employee Benefits',
    'career_hiring_process' => 'Career - 4 Hiring Process Stages',
    'products_material_features' => 'Products - Engineering Material Features',
    'products_resins' => 'Products - Resin Compatibility Matrix'
];

$corporateAssets = [
    ['path' => 'uploads/products/product_steering_wheel.jpg', 'title' => 'Steering Wheel Systems', 'cat' => 'Products'],
    ['path' => 'uploads/products/product_airbag_module.jpg', 'title' => 'Airbag Safety Modules', 'cat' => 'Products'],
    ['path' => 'uploads/products/product_cockpit_resin.svg', 'title' => 'Cockpit & Resin Interior', 'cat' => 'Products'],
    ['path' => 'uploads/gallery/facility_injection_molding.jpg', 'title' => 'Injection Molding Plant', 'cat' => 'Facility'],
    ['path' => 'uploads/gallery/facility_cleanroom_lab.svg', 'title' => 'Cleanroom Class 8 Lab', 'cat' => 'Facility'],
    ['path' => 'uploads/certifications/cert_iatf_16949.svg', 'title' => 'IATF 16949 Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/certifications/cert_iso_14001.svg', 'title' => 'ISO 14001 Seal', 'cat' => 'Certifications'],
    ['path' => 'uploads/company/logo_nihon_plast_green.png', 'title' => 'Nihon Plast Logo', 'cat' => 'Company']
];

$iconCategories = [
    'Mutu & Sertifikasi' => ['bi-shield-check', 'bi-patch-check-fill', 'bi-award', 'bi-award-fill', 'bi-check2-circle', 'bi-shield-shaded', 'bi-star-fill'],
    'Manufaktur & Mesin' => ['bi-gear-fill', 'bi-gear-wide-connected', 'bi-tools', 'bi-cpu', 'bi-cpu-fill', 'bi-box-seam', 'bi-boxes', 'bi-layers', 'bi-hammer'],
    'Otomotif & Presisi' => ['bi-disc', 'bi-disc-fill', 'bi-speedometer2', 'bi-car-front-fill', 'bi-fuel-pump', 'bi-compass'],
    'Fasilitas & Lab' => ['bi-building', 'bi-building-gear', 'bi-rulers', 'bi-thermometer-half', 'bi-activity', 'bi-geo-alt-fill', 'bi-aspect-ratio'],
    'Lingkungan & HR' => ['bi-tree-fill', 'bi-sun', 'bi-sun-fill', 'bi-droplet-half', 'bi-people-fill', 'bi-mortarboard-fill', 'bi-heart-pulse-fill', 'bi-bus-front']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>Dynamic Sub-Items & Cards - Admin</title>
    <style>
        .badge-active { background-color: #005A36; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .badge-inactive { background-color: #64748b; color: #FFFFFF; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; }
        .icon-preview-box { width: 36px; height: 36px; border-radius: 8px; background: #e6f4ee; color: #005A36; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .img-thumb-preview { width: 55px; height: 38px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; }
        .icon-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 8px; max-height: 380px; overflow-y: auto; padding: 6px; }
        .icon-picker-item { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px 4px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #ffffff; transition: all 0.15s; font-size: 0.65rem; text-align: center; color: #334155; }
        .icon-picker-item i { font-size: 1.4rem; margin-bottom: 4px; color: #005A36; }
        .icon-picker-item:hover { border-color: #005A36; background: #e6f4ee; transform: translateY(-2px); }
        .media-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; max-height: 420px; overflow-y: auto; padding: 6px; }
        .media-picker-card { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; cursor: pointer; background: #ffffff; transition: all 0.15s; }
        .media-picker-card:hover { border-color: #005A36; box-shadow: 0 4px 12px rgba(0, 90, 54, 0.18); transform: translateY(-2px); }
        .media-picker-img { width: 100%; height: 95px; object-fit: contain; background: #0f172a; padding: 4px; }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-collection text-success me-2"></i>Dynamic Sub-Items, Cards & Poin Elemen
            </h1>
            <p class="text-muted small mb-0">
                Kelola setiap kartu, mitra OEM, zona mobil interaktif, pilar mutu, fasilitas lab, dan spesifikasi secara dinamis.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="site_sections.php" class="btn btn-outline-secondary">
                <i class="bi bi-layout-text-window-reverse me-1"></i> Ke Halaman Elemen Induk
            </a>
            <a href="#formItem" class="btn btn-np-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Sub-Item Baru
            </a>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= htmlspecialchars($success) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= htmlspecialchars($error) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- STATS OVERVIEW CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Sub-Item Terdaftar</div>
                        <div class="fs-3 fw-bold text-dark"><?= count($allItems) ?> <small class="fs-6 text-muted font-monospace">/ <?= $totalItems ?> Global</small></div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-primary fs-4"><i class="bi bi-stack"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Sub-Item Aktif di Publik</div>
                        <div class="fs-3 fw-bold text-success"><?= $totalActive ?> Sub-Item</div>
                    </div>
                    <div class="p-3 bg-success-subtle rounded-circle text-success fs-4"><i class="bi bi-check-circle-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Jumlah Kategori / Section</div>
                        <div class="fs-3 fw-bold text-dark"><?= count($sectionStats) ?> Section Key</div>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-secondary fs-4"><i class="bi bi-tags"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION DROPDOWN -->
    <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
        <form method="get" action="site_section_items.php" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="fw-bold small text-muted"><i class="bi bi-filter me-1"></i>Filter Section Induk:</label>
            </div>
            <div class="col-md-6 col-lg-5">
                <select name="section_key" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" <?= $filterSection === 'all' ? 'selected' : '' ?>>-- Semua Section Key (<?= $totalItems ?> items) --</option>
                    <?php foreach ($sectionStats as $sKey => $sData): ?>
                        <option value="<?= htmlspecialchars($sKey) ?>" <?= $filterSection === $sKey ? 'selected' : '' ?>>
                            <?= htmlspecialchars($secKeyLabels[$sKey] ?? $sKey) ?> (<?= (int)$sData['cnt'] ?> items)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($filterSection !== 'all'): ?>
                <div class="col-auto">
                    <a href="site_section_items.php" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- FORM ADD / EDIT SUB-ITEM -->
    <div class="card border-0 shadow-sm mb-4" id="formItem">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="bi <?= $editRow ? 'bi-pencil-square text-primary' : 'bi-plus-circle text-success' ?> me-2"></i>
                <?= $editRow ? 'Edit Sub-Item: ' . htmlspecialchars($editRow['title']) : 'Tambah Sub-Item Baru' ?>
            </h5>
            <?php if ($editRow): ?>
                <a href="site_section_items.php?section_key=<?= urlencode($filterSection) ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal Edit
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body p-4">
            <form method="post" action="site_section_items.php?section_key=<?= urlencode($filterSection) ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int)($editRow['id'] ?? 0) ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editRow['image'] ?? '') ?>">
                <input type="hidden" name="selected_library_image" id="selected_library_image" value="">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Section Induk (Section Key) <span class="text-danger">*</span></label>
                        <select name="section_key" class="form-select" required>
                            <option value="">-- Pilih Section Key --</option>
                            <?php foreach ($secKeyLabels as $k => $label): ?>
                                <option value="<?= htmlspecialchars($k) ?>" <?= (($editRow['section_key'] ?? $filterSection) === $k) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k) ?> - <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Item Key (Opsional, untuk identifikasi teknis/tab)</label>
                        <input type="text" name="item_key" class="form-control" value="<?= htmlspecialchars($editRow['item_key'] ?? '') ?>" placeholder="contoh: steering, airbag, stat_solar">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold small">Judul Sub-Item / Teks Utama <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" placeholder="contoh: Steering Wheel Systems, CMM Geometric Lab, dll" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Sub-judul / Poin Nomor</label>
                        <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($editRow['subtitle'] ?? '') ?>" placeholder="contoh: 01, Driver Cockpit, Standar Mutu">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Deskripsi / Konten Teks Lengkap</label>
                        <textarea name="content" class="form-control" rows="3" placeholder="Isi deskripsi atau detail spesifikasi sub-item..."><?= htmlspecialchars($editRow['content'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Pilih Icon (Bootstrap Icons)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i id="iconPreview" class="bi <?= htmlspecialchars($editRow['icon'] ?? 'bi-grid') ?>"></i></span>
                            <input type="text" name="icon" id="iconInput" class="form-control" value="<?= htmlspecialchars($editRow['icon'] ?? '') ?>" placeholder="bi-shield-check">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                                <i class="bi bi-palette"></i> Pilih
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Badge Text / Label Tag</label>
                        <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($editRow['badge'] ?? '') ?>" placeholder="contoh: OEM Partner, 3D Metrology, Injeksi Presisi">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Urutan Tampil (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= (int)($editRow['sort_order'] ?? 10) ?>" step="5">
                    </div>

                    <!-- Upload Foto & Media Asset Library -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Foto / Gambar Sub-Item</label>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                            <button type="button" class="btn btn-outline-success text-nowrap" data-bs-toggle="modal" data-bs-target="#mediaAssetModal">
                                <i class="bi bi-images"></i> Pilih Library
                            </button>
                        </div>
                        <?php if (!empty($editRow['image'])): ?>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <img src="../<?= htmlspecialchars($editRow['image']) ?>" class="img-thumb-preview" alt="Preview">
                                <span class="small text-muted font-monospace"><?= htmlspecialchars($editRow['image']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Extra Data JSON -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Extra Data (JSON format untuk daftar badge/checklist)</label>
                        <textarea name="extra_data" class="form-control font-monospace small" rows="2" placeholder='{"badges": ["Item 1", "Item 2"]}'><?= htmlspecialchars($editRow['extra_data'] ?? '') ?></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" <?= (!isset($editRow) || $editRow['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold text-dark" for="isActiveCheck">
                                Aktifkan sub-item ini untuk tampil di halaman publik (Active)
                            </label>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-np-primary px-4 py-2 fw-bold">
                            <i class="bi bi-save me-1"></i> <?= $editRow ? 'Simpan Perubahan Sub-Item' : 'Tambahkan Sub-Item' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SUB-ITEMS TABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                Daftar Sub-Item (<?= count($allItems) ?> items)
            </h5>
            <div class="text-muted small">Klik status untuk switch <strong>Active / Not Active</strong> seketika</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Urutan</th>
                            <th style="width: 50px;">Icon / Foto</th>
                            <th style="width: 200px;">Section Induk</th>
                            <th>Judul & Teks</th>
                            <th>Badge / Extra Data</th>
                            <th style="width: 140px;">Status</th>
                            <th class="text-end pe-3" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allItems)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    Belum ada sub-item untuk filter ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allItems as $it): ?>
                                <tr class="<?= $it['is_active'] ? '' : 'table-light opacity-75' ?>">
                                    <td class="ps-3 fw-bold text-muted"><?= (int)$it['sort_order'] ?></td>
                                    <td>
                                        <?php if (!empty($it['image'])): ?>
                                            <img src="../<?= htmlspecialchars($it['image']) ?>" class="img-thumb-preview" alt="Thumb">
                                        <?php elseif (!empty($it['icon'])): ?>
                                            <div class="icon-preview-box">
                                                <i class="bi <?= htmlspecialchars($it['icon']) ?>"></i>
                                            </div>
                                        <?php else: ?>
                                            <div class="icon-preview-box text-muted">
                                                <i class="bi bi-dot"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border small mb-1">
                                            <?= htmlspecialchars($it['section_key']) ?>
                                        </span>
                                        <?php if (!empty($it['item_key'])): ?>
                                            <div class="small text-muted font-monospace">key: <?= htmlspecialchars($it['item_key']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($it['title']) ?></div>
                                        <?php if (!empty($it['subtitle'])): ?>
                                            <div class="small text-success fw-semibold"><?= htmlspecialchars($it['subtitle']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($it['content'])): ?>
                                            <div class="small text-muted text-truncate" style="max-width: 320px;" title="<?= htmlspecialchars($it['content']) ?>">
                                                <?= htmlspecialchars($it['content']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($it['badge'])): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">
                                                <?= htmlspecialchars($it['badge']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($it['extra_data'])): ?>
                                            <div class="small text-muted font-monospace text-truncate" style="max-width: 180px;" title="<?= htmlspecialchars($it['extra_data']) ?>">
                                                JSON Data
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="site_section_items.php?action=toggle&id=<?= (int)$it['id'] ?>&section_key=<?= urlencode($filterSection) ?>" 
                                           class="text-decoration-none d-inline-block" 
                                           title="Klik untuk toggle status aktif">
                                            <?php if ($it['is_active']): ?>
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
                                            <a href="site_section_items.php?edit=<?= (int)$it['id'] ?>&section_key=<?= urlencode($filterSection) ?>#formItem" class="btn btn-outline-primary" title="Edit sub-item ini">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if (has_permission('site_section_items', 'delete')): ?>
                                                <a href="site_section_items.php?action=delete&id=<?= (int)$it['id'] ?>&section_key=<?= urlencode($filterSection) ?>" class="btn btn-outline-danger" onclick="return confirm('Hapus sub-item <?= htmlspecialchars(addslashes($it['title'])) ?>?')">
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

<!-- MODAL: ICON PICKER -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-np-green text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Palet Icon Presisi Otomotif</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="iconSearchInput" class="form-control mb-3" placeholder="Ketik nama icon (misal: shield, gear, car, award)...">
                <?php foreach ($iconCategories as $catName => $icons): ?>
                    <h6 class="fw-bold text-dark mt-3 mb-2 small text-uppercase"><?= htmlspecialchars($catName) ?></h6>
                    <div class="icon-picker-grid mb-3">
                        <?php foreach ($icons as $ic): ?>
                            <div class="icon-picker-item" onclick="selectIcon('<?= $ic ?>')">
                                <i class="bi <?= $ic ?>"></i>
                                <span><?= str_replace('bi-', '', $ic) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: MEDIA ASSET LIBRARY -->
<div class="modal fade" id="mediaAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-np-green text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-images me-2"></i>Media Asset Library</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="media-picker-grid">
                    <?php foreach ($corporateAssets as $asset): ?>
                        <div class="media-picker-card" onclick="selectLibraryImage('<?= $asset['path'] ?>')">
                            <img src="../<?= $asset['path'] ?>" class="media-picker-img" alt="<?= htmlspecialchars($asset['title']) ?>">
                            <div class="p-2 small">
                                <span class="badge bg-secondary mb-1"><?= htmlspecialchars($asset['cat']) ?></span>
                                <div class="fw-semibold text-truncate"><?= htmlspecialchars($asset['title']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectIcon(iconClass) {
    document.getElementById('iconInput').value = iconClass;
    document.getElementById('iconPreview').className = 'bi ' + iconClass;
    const modalEl = document.getElementById('iconPickerModal');
    bootstrap.Modal.getInstance(modalEl).hide();
}

function selectLibraryImage(imagePath) {
    document.getElementById('selected_library_image').value = imagePath;
    alert('Gambar perpustakaan dipilih: ' + imagePath);
    const modalEl = document.getElementById('mediaAssetModal');
    bootstrap.Modal.getInstance(modalEl).hide();
}

document.getElementById('iconSearchInput')?.addEventListener('input', function() {
    const term = this.value.toLowerCase().trim();
    document.querySelectorAll('.icon-picker-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(term) ? 'flex' : 'none';
    });
});
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
