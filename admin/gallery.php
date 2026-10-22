<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

// Check RBAC permission for gallery menu
if (function_exists('require_permission')) {
    require_permission('gallery', 'read');
}

$success = "";
$error = "";

// Ensure gallery_media table exists for static photos
$conn->query("CREATE TABLE IF NOT EXISTS gallery_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    media_type ENUM('image') NOT NULL DEFAULT 'image',
    media_path VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (gallery_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$edit_id = (int)($_GET['edit'] ?? 0);
$editGallery = ['id' => 0, 'title' => '', 'image' => ''];

if ($edit_id) {
    $st = $conn->prepare('SELECT * FROM gallery WHERE id = ?');
    $st->bind_param('i', $edit_id);
    $st->execute();
    $editGallery = $st->get_result()->fetch_assoc() ?: $editGallery;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $currentImage = $editGallery['image'] ?? '';
    $newFiles = [];
    $error = '';

    // RBAC check for action
    if ($id && function_exists('has_permission') && !has_permission('gallery', 'update')) {
        $error = "Anda tidak memiliki izin untuk memperbarui galeri.";
    } elseif (!$id && function_exists('has_permission') && !has_permission('gallery', 'create')) {
        $error = "Anda tidak memiliki izin untuk menambah galeri.";
    }

    if ($error === '' && $title === '') {
        $error = 'Nama kategori wajib diisi.';
    }

    if ($error === '' && isset($_FILES['media']['name']) && is_array($_FILES['media']['name'])) {
        $allowed = ['jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'webp' => 'image'];
        for ($i = 0; $i < count($_FILES['media']['name']); $i++) {
            if ($_FILES['media']['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
            if ($_FILES['media']['error'][$i] !== UPLOAD_ERR_OK) {
                $error = 'Ada file foto yang gagal diupload.';
                break;
            }
            $ext = strtolower(pathinfo($_FILES['media']['name'][$i], PATHINFO_EXTENSION));
            if (!isset($allowed[$ext])) {
                $error = 'Format file hanya diperbolehkan foto: JPG, JPEG, PNG, WEBP (video dan gif dilarang).';
                break;
            }
            if ($_FILES['media']['size'][$i] > 10 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 10 MB per file.';
                break;
            }
            $tmpPath = $_FILES['media']['tmp_name'][$i];
            $imgInfo = @getimagesize($tmpPath);
            if (!$imgInfo || !in_array($imgInfo['mime'] ?? '', ['image/jpeg', 'image/png', 'image/webp'], true)) {
                $error = 'File bukan citra gambar valid atau rusak.';
                break;
            }
            $newFiles[] = ['i' => $i, 'ext' => $ext];
        }
    }

    if ($error === '') {
        if ($id) {
            $st = $conn->prepare('UPDATE gallery SET title = ? WHERE id = ?');
            $st->bind_param('si', $title, $id);
            $st->execute();
            $galleryId = $id;
        } else {
            $st = $conn->prepare('INSERT INTO gallery (title, image) VALUES (?, ?)');
            $blank = '';
            $st->bind_param('ss', $title, $blank);
            $st->execute();
            $galleryId = $conn->insert_id;
        }

        $uploadDir = __DIR__ . '/../uploads/gallery/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($newFiles as $f) {
            $i = $f['i'];
            $filename = 'gallery_' . $galleryId . '_' . time() . '_' . mt_rand(1000, 9999) . '.' . $f['ext'];
            if (!move_uploaded_file($_FILES['media']['tmp_name'][$i], $uploadDir . $filename)) {
                $error = 'Sebagian file foto gagal dipindahkan ke folder uploads/gallery.';
                break;
            }
            $mediaType = 'image';
            $st = $conn->prepare("INSERT INTO gallery_media (gallery_id, media_type, media_path) VALUES (?, ?, ?)");
            $st->bind_param('iss', $galleryId, $mediaType, $filename);
            $st->execute();

            if ($currentImage === '') {
                $st = $conn->prepare('UPDATE gallery SET image = ? WHERE id = ?');
                $st->bind_param('si', $filename, $galleryId);
                $st->execute();
                $currentImage = $filename;
            }
        }

        if ($error === '') {
            $success = 'Kategori galeri dan foto dokumentasi berhasil disimpan.';
            $editGallery = ['id' => 0, 'title' => '', 'image' => ''];
        }
    }
}

// Delete media item
if (isset($_GET['delete_media'])) {
    require_csrf();
    if (function_exists('require_permission')) {
        require_permission('gallery', 'delete');
    }
    $mid = (int)$_GET['delete_media'];
    $st = $conn->prepare('SELECT media_path, gallery_id FROM gallery_media WHERE id = ?');
    $st->bind_param('i', $mid);
    $st->execute();
    $m = $st->get_result()->fetch_assoc();
    if ($m) {
        $f = __DIR__ . '/../uploads/gallery/' . basename($m['media_path']);
        if (is_file($f)) {
            unlink($f);
        }
        $st = $conn->prepare('DELETE FROM gallery_media WHERE id = ?');
        $st->bind_param('i', $mid);
        $st->execute();
    }
    header('Location: gallery.php?edit=' . (int)($m['gallery_id'] ?? 0));
    exit;
}

// Delete whole gallery album
if (isset($_GET['delete'])) {
    require_csrf();
    if (function_exists('require_permission')) {
        require_permission('gallery', 'delete');
    }
    $id = (int)$_GET['delete'];
    $st = $conn->prepare('SELECT image FROM gallery WHERE id = ?');
    $st->bind_param('i', $id);
    $st->execute();
    $r = $st->get_result()->fetch_assoc();

    $st = $conn->prepare('SELECT media_path FROM gallery_media WHERE gallery_id = ?');
    $st->bind_param('i', $id);
    $st->execute();
    $rs = $st->get_result();
    while ($m = $rs->fetch_assoc()) {
        $f = __DIR__ . '/../uploads/gallery/' . basename($m['media_path']);
        if (is_file($f)) {
            unlink($f);
        }
    }
    $st = $conn->prepare('DELETE FROM gallery_media WHERE gallery_id = ?');
    $st->bind_param('i', $id);
    $st->execute();

    $st = $conn->prepare('DELETE FROM gallery WHERE id = ?');
    $st->bind_param('i', $id);
    $st->execute();

    header('Location: gallery.php');
    exit;
}

$gallery = $conn->query('SELECT * FROM gallery ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Galeri Foto | PT Nihon Plast Indonesia</title>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

<div class="mb-4">
    <!-- Executive Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-camera me-1"></i> PHOTOGRAPHY & FACILITY ARCHIVE
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    Total <?= $gallery->num_rows ?> Album
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">Photo Gallery Management</h1>
            <p class="text-muted small mb-0">Kelola album foto dokumentasi fasilitas, presisi manufaktur, dan aktivitas PT Nihon Plast Indonesia (100% foto statis beresolusi tinggi, bebas video/gif).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="site_sections.php?page=gallery" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-layout-text-window-reverse me-1"></i> Elemen Halaman Galeri
            </a>
            <?php if (has_permission('gallery', 'create')): ?>
                <button class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formGalleryCollapse" aria-expanded="<?= ($editGallery['id'] || isset($_GET['new'])) ? 'true' : 'false' ?>">
                    <i class="bi bi-plus-circle me-1"></i> <?= $editGallery['id'] ? 'Tutup Form' : 'Tambah Album Baru' ?>
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

    <!-- COLLAPSIBLE FORM -->
    <div class="collapse <?= ($editGallery['id'] || isset($_GET['new'])) ? 'show' : '' ?> mb-4" id="formGalleryCollapse">
        <div class="card border-0 shadow-sm rounded-4" id="formGallery">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                        <i class="bi <?= $editGallery['id'] ? 'bi-pencil' : 'bi-plus-lg' ?>"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">
                        <?= $editGallery['id'] ? 'Edit Album: ' . htmlspecialchars($editGallery['title']) : 'Tambah Album Foto Baru' ?>
                    </h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#formGalleryCollapse">
                    Tutup
                </button>
            </div>
            <div class="card-body p-4">
                <form method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$editGallery['id'] ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Album / Judul Dokumentasi *</label>
                            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($editGallery['title']) ?>" placeholder="Contoh: Fasilitas Injection Molding Cikampek">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Unggah Foto Presisi (Bisa Pilih Sekaligus Banyak)</label>
                            <input type="file" name="media[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text fs-xs">Format didukung: JPG, JPEG, PNG, WEBP. Maksimal 10 MB per foto.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button class="btn btn-emerald px-4 rounded-pill shadow-sm" type="submit">
                            <i class="bi bi-save me-1"></i> Simpan Album & Foto
                        </button>
                        <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-toggle="collapse" data-bs-target="#formGalleryCollapse">Batal</button>
                    </div>
                </form>

                <?php if ($editGallery['id']): ?>
                    <?php
                    $st = $conn->prepare('SELECT * FROM gallery_media WHERE gallery_id = ? ORDER BY id DESC');
                    $st->bind_param('i', $editGallery['id']);
                    $st->execute();
                    $mediaRows = $st->get_result();
                    ?>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">Daftar Foto dalam Album Ini (<?= $mediaRows->num_rows ?> Foto)</h6>
                    </div>
                    <div class="row g-3">
                        <?php if ($mediaRows->num_rows === 0): ?>
                            <div class="col-12 text-muted small">Belum ada foto dalam album ini. Unggah foto melalui form di atas.</div>
                        <?php else: ?>
                            <?php while ($m = $mediaRows->fetch_assoc()): ?>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card border rounded-4 overflow-hidden h-100 shadow-none">
                                        <img src="../uploads/gallery/<?= htmlspecialchars(basename($m['media_path'])) ?>" class="img-fluid" style="height: 140px; object-fit: cover; width: 100%;" alt="Foto Dokumentasi">
                                        <div class="card-body p-2 text-center">
                                            <?php if (has_permission('gallery', 'delete')): ?>
                                                <a class="btn btn-xs btn-outline-danger w-100 rounded-pill" href="<?= csrf_url('gallery.php?delete_media=' . (int)$m['id']) ?>" onclick="return confirm('Hapus foto ini dari galeri?')">
                                                    <i class="bi bi-trash me-1"></i> Hapus Foto
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- GALLERY ALBUMS GRID -->
    <div class="row g-4">
        <?php if ($gallery->num_rows === 0): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <i class="bi bi-camera fs-1 d-block mb-2 text-muted"></i>
                    Belum ada album foto tersimpan. Silakan klik <strong>Tambah Album Baru</strong>.
                </div>
            </div>
        <?php else: ?>
            <?php while ($row = $gallery->fetch_assoc()): ?>
                <?php
                $st = $conn->prepare('SELECT id, media_path FROM gallery_media WHERE gallery_id = ? ORDER BY id ASC');
                $st->bind_param('i', $row['id']);
                $st->execute();
                $mediaRes = $st->get_result();
                $count = $mediaRes->num_rows;
                $coverRow = $mediaRes->fetch_assoc();
                $coverImg = $coverRow['media_path'] ?? ($row['image'] ?? '');
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card shadow-sm h-100 border-0 rounded-4 overflow-hidden">
                        <?php if (!empty($coverImg)): ?>
                            <div style="height: 160px; overflow: hidden; background: #f8fafc;" class="border-bottom">
                                <img src="../uploads/gallery/<?= htmlspecialchars(basename($coverImg)) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Cover">
                            </div>
                        <?php else: ?>
                            <div style="height: 160px; background: #f1f5f9;" class="d-flex align-items-center justify-content-center text-muted border-bottom">
                                <i class="bi bi-images fs-1 text-slate-400"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2 py-1 fs-xs fw-bold border">
                                    <i class="bi bi-image me-1"></i> <?= $count ?> Foto
                                </span>
                            </div>
                            <h6 class="fw-bold text-dark mb-3 text-truncate" title="<?= htmlspecialchars($row['title']) ?>"><?= htmlspecialchars($row['title']) ?></h6>
                            <div class="mt-auto d-flex gap-2 pt-2 border-top">
                                <a class="btn btn-sm btn-outline-primary flex-grow-1 rounded-pill" href="gallery.php?edit=<?= (int)$row['id'] ?>#formGalleryCollapse">
                                    <i class="bi bi-pencil me-1"></i> Kelola
                                </a>
                                <?php if (has_permission('gallery', 'delete')): ?>
                                    <a class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" href="<?= csrf_url('gallery.php?delete=' . (int)$row['id']) ?>" onclick="return confirm('Hapus album ini beserta semua fotonya?')" title="Hapus Album">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
