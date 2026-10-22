<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('media', 'read');

$success = "";
$error = "";

// Flash messages
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
if (isset($_SESSION['flash_error'])) {
    $error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    require_csrf();
    if (!has_permission('media', 'create')) {
        $error = "Anda tidak memiliki izin untuk mengunggah media.";
    } else {
        $folder = trim($_POST['target_folder'] ?? 'general');
        $allowedFolders = ['products', 'news', 'banners', 'facility', 'certifications', 'gallery', 'sections', 'oem', 'general'];
        if (!in_array($folder, $allowedFolders, true)) {
            $folder = 'general';
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

            if (!in_array($ext, $allowedExts, true)) {
                $error = "Format file tidak diizinkan. Gunakan JPG, PNG, WEBP, atau SVG.";
            } elseif ($file['size'] > 10 * 1024 * 1024) {
                $error = "Ukuran file terlalu besar. Maksimal 10MB.";
            } else {
                $cleanName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
                $filename = $cleanName . '_' . time() . '.' . $ext;

                $targetDir = ($folder === 'oem') 
                    ? __DIR__ . '/../assets/img/oem/'
                    : __DIR__ . '/../uploads/' . $folder . '/';

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                if (move_uploaded_file($file['tmp_name'], $targetDir . $filename)) {
                    $relPath = ($folder === 'oem') 
                        ? 'assets/img/oem/' . $filename 
                        : 'uploads/' . $folder . '/' . $filename;
                    $_SESSION['flash_success'] = "File berhasil diunggah: " . htmlspecialchars($relPath);
                    header("Location: media.php?filter=" . urlencode($folder));
                    exit;
                } else {
                    $error = "Gagal memindahkan file ke direktori tujuan.";
                }
            }
        } else {
            $error = "Silakan pilih file gambar yang valid untuk diunggah.";
        }
    }
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['file'])) {
    require_csrf();
    if (!has_permission('media', 'delete')) {
        $_SESSION['flash_error'] = "Anda tidak memiliki izin untuk menghapus file media.";
    } else {
        $fileParam = trim($_GET['file']);
        // Sanitize path traversal
        if (strpos($fileParam, '..') === false && (strpos($fileParam, 'uploads/') === 0 || strpos($fileParam, 'assets/img/oem/') === 0)) {
            $fullPath = __DIR__ . '/../' . $fileParam;
            if (is_file($fullPath)) {
                @unlink($fullPath);
                $_SESSION['flash_success'] = "File media berhasil dihapus.";
            } else {
                $_SESSION['flash_error'] = "File tidak ditemukan di server.";
            }
        } else {
            $_SESSION['flash_error'] = "Path file tidak valid.";
        }
    }
    header("Location: media.php");
    exit;
}

// Scan directories for media files
$filter = trim($_GET['filter'] ?? 'all');
$search = trim($_GET['search'] ?? '');

$directories = [
    'products' => __DIR__ . '/../uploads/products',
    'news' => __DIR__ . '/../uploads/news',
    'banners' => __DIR__ . '/../uploads/banners',
    'facility' => __DIR__ . '/../uploads/facility',
    'certifications' => __DIR__ . '/../uploads/certifications',
    'gallery' => __DIR__ . '/../uploads/gallery',
    'sections' => __DIR__ . '/../uploads/sections',
    'general' => __DIR__ . '/../uploads/general',
    'oem' => __DIR__ . '/../assets/img/oem'
];

$mediaFiles = [];
$totalBytes = 0;

foreach ($directories as $cat => $dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $full = $dir . '/' . $f;
            if (!is_file($full)) continue;

            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'])) continue;

            $size = filesize($full);
            $totalBytes += $size;
            $rel = ($cat === 'oem') ? 'assets/img/oem/' . $f : 'uploads/' . $cat . '/' . $f;
            $mtime = filemtime($full);

            // Filter check
            if ($filter !== 'all' && $filter !== $cat) continue;
            if ($search !== '' && stripos($f, $search) === false) continue;

            $mediaFiles[] = [
                'name' => $f,
                'category' => $cat,
                'path' => $rel,
                'full_path' => $full,
                'size' => $size,
                'ext' => $ext,
                'time' => $mtime
            ];
        }
    }
}

// Sort by latest modified
usort($mediaFiles, function($a, $b) {
    return $b['time'] - $a['time'];
});

function format_file_size($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 1) . ' MB';
    }
    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 1) . ' KB';
    }
    return $bytes . ' B';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Media & Asset Library | PT Nihon Plast Indonesia</title>
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle px-2 py-1 fw-bold">
                <i class="bi bi-images me-1"></i> ASSET REPOSITORY
            </span>
            <span class="text-muted small">Total: <?= count($mediaFiles) ?> File (<?= format_file_size($totalBytes) ?>)</span>
        </div>
        <h1 class="h3 fw-bold text-slate-800 mb-1">Media & Uploads Library</h1>
        <p class="text-muted small mb-0">Manajemen berkas aset visual, logo OEM, foto manufaktur, banner, dan gambar produk.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <?php if (has_permission('media', 'create')): ?>
            <button class="btn btn-emerald btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                <i class="bi bi-cloud-arrow-up fs-6"></i>
                <span>Unggah File Baru</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Category Filter Pills & Search Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-1">
                    <a href="media.php?filter=all" class="btn btn-sm <?= $filter === 'all' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        Semua Aset (<?= count($mediaFiles) ?>)
                    </a>
                    <a href="media.php?filter=oem" class="btn btn-sm <?= $filter === 'oem' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-award me-1"></i> Logo OEM
                    </a>
                    <a href="media.php?filter=products" class="btn btn-sm <?= $filter === 'products' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>
                    <a href="media.php?filter=news" class="btn btn-sm <?= $filter === 'news' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-newspaper me-1"></i> Berita
                    </a>
                    <a href="media.php?filter=banners" class="btn btn-sm <?= $filter === 'banners' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-image me-1"></i> Banner
                    </a>
                    <a href="media.php?filter=facility" class="btn btn-sm <?= $filter === 'facility' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-cpu me-1"></i> Fasilitas
                    </a>
                    <a href="media.php?filter=certifications" class="btn btn-sm <?= $filter === 'certifications' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-patch-check me-1"></i> Sertifikasi
                    </a>
                    <a href="media.php?filter=gallery" class="btn btn-sm <?= $filter === 'gallery' ? 'btn-emerald' : 'btn-light text-slate-600' ?> rounded-pill px-3">
                        <i class="bi bi-camera me-1"></i> Galeri
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <form method="GET" action="media.php" class="d-flex gap-2">
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama file..." value="<?= htmlspecialchars($search) ?>">
                        <?php if ($search !== ''): ?>
                            <a href="media.php?filter=<?= urlencode($filter) ?>" class="btn btn-light border"><i class="bi bi-x"></i></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Media Grid -->
<?php if (empty($mediaFiles)): ?>
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-images display-3 text-muted opacity-50 mb-3 d-block"></i>
            <h5 class="fw-bold text-slate-700">Tidak ada file media yang ditemukan</h5>
            <p class="text-muted small mb-3">Unggah file baru atau gunakan filter kategori lain di atas.</p>
            <?php if (has_permission('media', 'create')): ?>
                <button class="btn btn-emerald btn-sm px-4" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Unggah File Sekarang
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($mediaFiles as $m): ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100 media-card overflow-hidden">
                    <div class="media-thumb-container position-relative bg-light d-flex align-items-center justify-content-center p-2" style="height: 140px;">
                        <img src="../<?= htmlspecialchars($m['path']) ?>" alt="<?= htmlspecialchars($m['name']) ?>" class="img-fluid media-preview-thumb" loading="lazy">
                        <span class="badge bg-dark bg-opacity-75 text-white position-absolute top-0 end-0 m-2 font-monospace" style="font-size: 0.65rem;">
                            <?= strtoupper($m['ext']) ?>
                        </span>
                    </div>
                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                        <div>
                            <div class="small fw-bold text-slate-800 text-truncate" title="<?= htmlspecialchars($m['name']) ?>">
                                <?= htmlspecialchars($m['name']) ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.65rem;"><?= htmlspecialchars($m['category']) ?></span>
                                <span class="text-muted small" style="font-size: 0.68rem;"><?= format_file_size($m['size']) ?></span>
                            </div>
                        </div>
                        <div class="pt-2 mt-2 border-top d-flex gap-1 justify-content-between">
                            <button class="btn btn-sm btn-outline-secondary w-100 copy-path-btn" data-path="<?= htmlspecialchars($m['path']) ?>" title="Salin Path Relatif">
                                <i class="bi bi-clipboard me-1"></i> Salin Path
                            </button>
                            <?php if (has_permission('media', 'delete')): ?>
                                <a href="media.php?action=delete&file=<?= urlencode($m['path']) ?>&csrf_token=<?= urlencode(csrf_token()) ?>" 
                                   class="btn btn-sm btn-outline-danger px-2" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus file ini dari server? File yang dihapus tidak dapat dipulihkan.');"
                                   title="Hapus File">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="media.php" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="upload">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-slate-800">
                        <i class="bi bi-cloud-arrow-up text-emerald me-2"></i>Unggah File Media Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Kategori Folder Tujuan</label>
                        <select name="target_folder" class="form-select form-select-sm" required>
                            <option value="general">Umum / General (uploads/general/)</option>
                            <option value="oem">Logo OEM Partner (assets/img/oem/)</option>
                            <option value="products">Produk Otomotif (uploads/products/)</option>
                            <option value="news">Berita & Rilis (uploads/news/)</option>
                            <option value="banners">Banner Header Halaman (uploads/banners/)</option>
                            <option value="facility">Fasilitas & Mesin (uploads/facility/)</option>
                            <option value="certifications">Sertifikasi & Mutu (uploads/certifications/)</option>
                            <option value="gallery">Galeri Foto (uploads/gallery/)</option>
                        </select>
                        <div class="form-text small">File akan disimpan secara otomatis di direktori kategori yang dipilih.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Pilih Berkas Gambar</label>
                        <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg" required>
                        <div class="form-text small">Format didukung: <strong>JPG, PNG, WEBP, SVG</strong> (Maks. 10MB).</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald btn-sm px-4">
                        <i class="bi bi-upload me-1"></i> Unggah Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.copy-path-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const path = this.getAttribute('data-path');
            navigator.clipboard.writeText(path).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2 text-success"></i> Disalin!';
                this.classList.add('btn-success', 'text-white');
                this.classList.remove('btn-outline-secondary');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.remove('btn-success', 'text-white');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            });
        });
    });
});
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
