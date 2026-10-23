<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('machinery', 'read');

$success = "";
$error = "";

// 1. Toggle Active (supports AJAX & Synchronous)
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    require_csrf();
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (isset($_GET['ajax']) && $_GET['ajax'] == 1);
    
    if (!has_permission('machinery', 'update') && !has_permission('facility', 'update')) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengubah status mesin.']);
            exit;
        }
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk mengubah status mesin.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("UPDATE facility_machinery SET is_active = IF(is_active = 1, 0, 1) WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Fetch updated status
        $fStmt = $conn->prepare("SELECT is_active, model_brand FROM facility_machinery WHERE id = ?");
        $fStmt->bind_param("i", $id);
        $fStmt->execute();
        $res = $fStmt->get_result()->fetch_assoc();
        $fStmt->close();
        $newStatus = (int)($res['is_active'] ?? 0);
        $mb = $res['model_brand'] ?? 'Mesin';

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'id' => $id,
                'is_active' => $newStatus,
                'message' => 'Status "' . $mb . '" diubah menjadi ' . ($newStatus ? 'AKTIF' : 'NONAKTIF')
            ]);
            exit;
        }
        $_SESSION['flash_success'] = "Status mesin berhasil diperbarui.";
    }
    header("Location: machinery.php");
    exit;
}

// 2. Delete Machine
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    require_csrf();
    if (!has_permission('machinery', 'delete') && !has_permission('facility', 'delete')) {
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk menghapus data mesin.";
    } else {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("DELETE FROM facility_machinery WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['flash_success'] = "Data mesin berhasil dihapus.";
        }
        $stmt->close();
    }
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
        if (!has_permission('machinery', 'update') && !has_permission('facility', 'update')) {
            $error = "Anda tidak memiliki izin untuk mengedit mesin.";
        }
    } else {
        if (!has_permission('machinery', 'create') && !has_permission('facility', 'create')) {
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

<div class="mb-4">
    <!-- Executive Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-cpu me-1"></i> FACILITY & TECHNICAL SPEC
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    Total <?= $machineryList->num_rows ?> Unit
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">Technical Machinery Matrix</h1>
            <p class="text-muted small mb-0">Kelola spesifikasi permesinan injeksi plastik, tonase 150T - 2500T, dan sistem otomasi fasilitas Cikampek.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="site_sections.php?page=facility" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-layout-text-window-reverse me-1"></i> Elemen Halaman Fasilitas
            </a>
            <?php if (has_permission('machinery', 'create') || has_permission('facility', 'create')): ?>
                <button class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formMachineryCollapse" aria-expanded="<?= ($editRow || isset($_GET['new'])) ? 'true' : 'false' ?>">
                    <i class="bi bi-plus-circle me-1"></i> <?= $editRow ? 'Tutup Form' : 'Tambah Mesin Baru' ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- EDIT / ADD FORM COLLAPSIBLE -->
    <div class="collapse <?= ($editRow || isset($_GET['new'])) ? 'show' : '' ?> mb-4" id="formMachineryCollapse">
        <div class="card border-0 shadow-sm rounded-4" id="formMachinery">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                        <i class="bi <?= $editRow ? 'bi-pencil' : 'bi-plus-lg' ?>"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">
                        <?= $editRow ? 'Edit Mesin: ' . htmlspecialchars($editRow['category']) : 'Tambah Spesifikasi Mesin Baru' ?>
                    </h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#formMachineryCollapse">
                    Tutup
                </button>
            </div>
            <div class="card-body p-4">
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)($editRow['id'] ?? 0) ?>">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kategori Mesin *</label>
                            <input type="text" name="category" class="form-control" required value="<?= htmlspecialchars($editRow['category'] ?? '') ?>" placeholder="contoh: Ultra-Large Injection Press">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Model / Merk Mesin *</label>
                            <input type="text" name="model_brand" class="form-control" required value="<?= htmlspecialchars($editRow['model_brand'] ?? '') ?>" placeholder="contoh: Sumitomo / Toshiba Hydraulic">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kapasitas / Tonase *</label>
                            <input type="text" name="tonnage" class="form-control" required value="<?= htmlspecialchars($editRow['tonnage'] ?? '') ?>" placeholder="contoh: 2.500 Ton">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Aplikasi Utama Komponen</label>
                            <input type="text" name="application" class="form-control" value="<?= htmlspecialchars($editRow['application'] ?? '') ?>" placeholder="contoh: Instrument Panel & Cowl Top Exterior">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Sistem Otomasi / Robotik</label>
                            <input type="text" name="automation" class="form-control" value="<?= htmlspecialchars($editRow['automation'] ?? '') ?>" placeholder="contoh: Robotic Part Unloader & Vision Sensor">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Urutan (Sort Order)</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= (int)($editRow['sort_order'] ?? 10) ?>">
                        </div>
                        <div class="col-md-9 d-flex align-items-end">
                            <div class="form-check form-switch p-2 bg-light rounded-3 d-inline-flex align-items-center gap-2 border">
                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="statusSwitchM" name="is_active" value="1" <?= (!isset($editRow['is_active']) || $editRow['is_active'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold mb-0 small" for="statusSwitchM">Tampilkan di Tabel Mesin Publik (Active)</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                            <button type="submit" class="btn btn-emerald px-4 rounded-pill shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Spesifikasi Mesin
                            </button>
                            <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-toggle="collapse" data-bs-target="#formMachineryCollapse">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MACHINERY TABLE -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Daftar Matriks Permesinan Fasilitas</h5>
            <span class="text-muted small">Total: <strong><?= $machineryList->num_rows ?></strong> Mesin</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="machineryTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 70px;">Urutan</th>
                            <th>Kategori Mesin</th>
                            <th>Model / Merk</th>
                            <th>Kapasitas / Tonase</th>
                            <th>Aplikasi Komponen</th>
                            <th>Sistem Otomasi</th>
                            <th style="width: 130px;">Status</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($machineryList->num_rows === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-cpu fs-1 d-block mb-2 text-muted"></i>
                                    Belum ada data mesin. Silakan klik tombol <strong>Tambah Mesin Baru</strong> di atas.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while ($m = $machineryList->fetch_assoc()): ?>
                                <tr class="<?= $m['is_active'] ? '' : 'table-light opacity-75' ?>" id="row-machinery-<?= $m['id'] ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="ajax_reorder.php?table=facility_machinery&action=move_up&id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Atas">
                                                <i class="bi bi-arrow-up"></i>
                                            </a>
                                            <a href="ajax_reorder.php?table=facility_machinery&action=move_down&id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Bawah">
                                                <i class="bi bi-arrow-down"></i>
                                            </a>
                                            <span class="badge bg-light text-dark border px-2 py-1 font-monospace"><?= (int)$m['sort_order'] ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($m['category']) ?></td>
                                    <td><span class="fw-semibold text-dark"><?= htmlspecialchars($m['model_brand']) ?></span></td>
                                    <td><span class="badge bg-light text-dark border fw-bold"><?= htmlspecialchars($m['tonnage']) ?></span></td>
                                    <td class="small text-muted"><?= htmlspecialchars($m['application'] ?: '-') ?></td>
                                    <td class="small text-muted"><?= htmlspecialchars($m['automation'] ?: '-') ?></td>
                                    <td>
                                        <?php if (has_permission('machinery', 'update') || has_permission('facility', 'update')): ?>
                                            <a href="<?= csrf_url('machinery.php?action=toggle&id=' . (int)$m['id']) ?>" 
                                               class="btn-toggle-machinery text-decoration-none" 
                                               data-id="<?= (int)$m['id'] ?>"
                                               title="Klik untuk ubah status aktif/nonaktif">
                                                <span class="badge <?= $m['is_active'] ? 'bg-emerald-subtle text-emerald' : 'bg-secondary-subtle text-secondary' ?> rounded-pill px-3 py-1 fs-xs fw-bold border" id="badge-machinery-<?= $m['id'] ?>">
                                                    <i class="bi <?= $m['is_active'] ? 'bi-check-circle-fill' : 'bi-dash-circle' ?> me-1"></i>
                                                    <?= $m['is_active'] ? 'ACTIVE' : 'INACTIVE' ?>
                                                </span>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge <?= $m['is_active'] ? 'bg-emerald-subtle text-emerald' : 'bg-secondary-subtle text-secondary' ?> rounded-pill px-3 py-1 fs-xs fw-bold border">
                                                <?= $m['is_active'] ? 'ACTIVE' : 'INACTIVE' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <a href="machinery.php?edit=<?= (int)$m['id'] ?>#formMachineryCollapse" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Edit mesin">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if (has_permission('machinery', 'delete') || has_permission('facility', 'delete')): ?>
                                                <a href="<?= csrf_url('machinery.php?action=delete&id=' . (int)$m['id']) ?>" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Hapus mesin" onclick="return confirm('Apakah Anda yakin ingin menghapus data mesin ini?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1-Click AJAX Toggle for Machinery
    document.querySelectorAll('.btn-toggle-machinery').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const btn = this;
            const machId = btn.getAttribute('data-id');
            const targetUrl = btn.getAttribute('href') + '&ajax=1';
            const badge = document.getElementById('badge-machinery-' + machId);
            const row = document.getElementById('row-machinery-' + machId);

            fetch(targetUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_active === 1) {
                        badge.className = 'badge bg-emerald-subtle text-emerald rounded-pill px-3 py-1 fs-xs fw-bold border';
                        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> ACTIVE';
                        if (row) row.classList.remove('table-light', 'opacity-75');
                    } else {
                        badge.className = 'badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-xs fw-bold border';
                        badge.innerHTML = '<i class="bi bi-dash-circle me-1"></i> INACTIVE';
                        if (row) row.classList.add('table-light', 'opacity-75');
                    }
                    if (window.showAdminToast) {
                        window.showAdminToast(data.message, 'success');
                    }
                } else {
                    alert(data.message || 'Gagal mengubah status.');
                }
            })
            .catch(err => {
                console.error(err);
                window.location.href = btn.getAttribute('href');
            });
        });
    });
});
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
