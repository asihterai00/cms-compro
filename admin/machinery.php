<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('facility', 'read');

$success = "";
$error = "";

// 1. Toggle Active
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    require_csrf();
    if (!has_permission('facility', 'update')) {
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk mengubah status mesin.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE facility_machinery SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['flash_success'] = "Status mesin berhasil diperbarui.";
    }
    header("Location: machinery.php");
    exit;
}

// 2. Delete Machine
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    require_csrf();
    require_permission('facility', 'delete');
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM facility_machinery WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['flash_success'] = "Data mesin berhasil dihapus.";
    }
    $stmt->close();
    header("Location: machinery.php");
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

// 3. Edit Machine
$editId = (int)($_GET['edit'] ?? 0);
$editRow = null;
if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM facility_machinery WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// 4. Handle POST Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $modelBrand = trim($_POST['model_brand'] ?? '');
    $tonnage = trim($_POST['tonnage'] ?? '');
    $application = trim($_POST['application'] ?? '');
    $automation = trim($_POST['automation'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 10);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0) {
        if (!has_permission('facility', 'update')) {
            $error = "Anda tidak memiliki izin untuk mengedit mesin.";
        }
    } else {
        if (!has_permission('facility', 'create')) {
            $error = "Anda tidak memiliki izin untuk menambah mesin.";
        }
    }

    if ($error === '' && ($category === '' || $modelBrand === '' || $tonnage === '')) {
        $error = "Kategori mesin, model/merk, dan kapasitas tonase wajib diisi.";
    }

    if ($error === '') {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE facility_machinery SET category = ?, model_brand = ?, tonnage = ?, application = ?, automation = ?, sort_order = ?, is_active = ? WHERE id = ?");
            $stmt->bind_param("sssssiii", $category, $modelBrand, $tonnage, $application, $automation, $sortOrder, $isActive, $id);
            if ($stmt->execute()) {
                $success = "Data mesin '{$category}' berhasil diperbarui.";
                $editRow = null;
            } else {
                $error = "Gagal memperbarui: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO facility_machinery (category, model_brand, tonnage, application, automation, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssii", $category, $modelBrand, $tonnage, $application, $automation, $sortOrder, $isActive);
            if ($stmt->execute()) {
                $success = "Data mesin baru '{$category}' berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$machineryList = $conn->query("SELECT * FROM facility_machinery ORDER BY sort_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Matriks Mesin & Presisi | PT Nihon Plast Indonesia</title>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-cpu text-success me-2"></i>Technical Machinery Matrix
            </h1>
            <p class="text-muted small mb-0">Kelola spesifikasi permesinan injeksi plastik, tonase, dan sistem otomasi fasilitas Cikampek.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="site_sections.php?page=facility" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-layout-text-window-reverse me-1"></i> Elemen Halaman Fasilitas
            </a>
            <?php if (!$editRow && has_permission('facility', 'create')): ?>
                <a href="#formMachinery" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mesin Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

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

    <!-- EDIT / ADD FORM -->
    <?php if ($editRow || isset($_GET['new'])): ?>
        <div class="card border-0 shadow-sm mb-4" id="formMachinery">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <?= $editRow ? 'Edit Mesin: ' . htmlspecialchars($editRow['category']) : 'Tambah Spesifikasi Mesin Baru' ?>
                </h5>
                <a href="machinery.php" class="btn btn-sm btn-outline-secondary">Batal</a>
            </div>
            <div class="card-body p-4">
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)($editRow['id'] ?? 0) ?>">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kategori Mesin</label>
                            <input type="text" name="category" class="form-control" required value="<?= htmlspecialchars($editRow['category'] ?? '') ?>" placeholder="contoh: Ultra-Large Injection Press">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Model / Merk Mesin</label>
                            <input type="text" name="model_brand" class="form-control" required value="<?= htmlspecialchars($editRow['model_brand'] ?? '') ?>" placeholder="contoh: Sumitomo / Toshiba Hydraulic">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kapasitas / Tonase</label>
                            <input type="text" name="tonnage" class="form-control" required value="<?= htmlspecialchars($editRow['tonnage'] ?? '') ?>" placeholder="contoh: 2.500 Ton">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aplikasi Utama Komponen</label>
                            <input type="text" name="application" class="form-control" value="<?= htmlspecialchars($editRow['application'] ?? '') ?>" placeholder="contoh: Instrument Panel & Cowl Top Exterior">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sistem Otomasi / Robotik</label>
                            <input type="text" name="automation" class="form-control" value="<?= htmlspecialchars($editRow['automation'] ?? '') ?>" placeholder="contoh: Robotic Part Unloader & Vision Sensor">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Urutan (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= (int)($editRow['sort_order'] ?? 10) ?>">
                        </div>
                        <div class="col-md-9 d-flex align-items-end">
                            <div class="form-check form-switch p-2 bg-light rounded-3 d-inline-flex align-items-center gap-2">
                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="statusSwitchM" name="is_active" value="1" <?= (!isset($editRow['is_active']) || $editRow['is_active'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold mb-0" for="statusSwitchM">Tampilkan di Tabel Mesin Publik (Active)</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Spesifikasi Mesin
                            </button>
                            <a href="machinery.php" class="btn btn-secondary px-4">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- MACHINERY TABLE -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Matriks Permesinan Fasilitas</h5>
            <span class="text-muted small">Total: <?= $machineryList->num_rows ?> Mesin</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 50px;">Urutan</th>
                            <th>Kategori Mesin</th>
                            <th>Model / Merk</th>
                            <th>Kapasitas / Tonase</th>
                            <th>Aplikasi Komponen</th>
                            <th>Sistem Otomasi</th>
                            <th style="width: 120px;">Status</th>
                            <th class="text-end pe-3" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($m = $machineryList->fetch_assoc()): ?>
                            <tr class="<?= $m['is_active'] ? '' : 'table-light opacity-75' ?>">
                                <td class="ps-3 fw-bold text-muted"><?= (int)$m['sort_order'] ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($m['category']) ?></td>
                                <td><?= htmlspecialchars($m['model_brand']) ?></td>
                                <td><span class="badge bg-light text-dark border fw-bold"><?= htmlspecialchars($m['tonnage']) ?></span></td>
                                <td class="small text-muted"><?= htmlspecialchars($m['application']) ?></td>
                                <td class="small text-muted"><?= htmlspecialchars($m['automation']) ?></td>
                                <td>
                                    <?php if (has_permission('facility', 'update')): ?>
                                        <a href="<?= csrf_url('machinery.php?action=toggle&id=' . (int)$m['id']) ?>" class="text-decoration-none" title="Klik untuk ubah status">
                                            <?php if ($m['is_active']): ?>
                                                <span class="badge bg-success rounded-pill px-2 py-1">ACTIVE</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-2 py-1">NOT ACTIVE</span>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge <?= $m['is_active'] ? 'bg-success' : 'bg-secondary' ?> rounded-pill px-2 py-1">
                                            <?= $m['is_active'] ? 'ACTIVE' : 'NOT ACTIVE' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <?php if (has_permission('facility', 'update')): ?>
                                            <a href="<?= csrf_url('machinery.php?action=toggle&id=' . (int)$m['id']) ?>" class="btn <?= $m['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" title="Toggle status">
                                                <i class="bi <?= $m['is_active'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="machinery.php?edit=<?= (int)$m['id'] ?>#formMachinery" class="btn btn-outline-primary" title="Edit mesin">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if (has_permission('facility', 'delete')): ?>
                                            <a href="<?= csrf_url('machinery.php?action=delete&id=' . (int)$m['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Hapus data mesin ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
