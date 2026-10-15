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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Photo Gallery Management</h1>
            <p class="text-muted small mb-0">Kelola album foto dokumentasi fasilitas, presisi manufaktur, dan aktivitas PT Nihon Plast Indonesia (100% foto statis beresolusi tinggi, bebas video/gif).</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold mb-3"><?= $editGallery['id'] ? 'Edit Album' : 'Tambah Album Foto Baru' ?></h4>
            <form method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)$editGallery['id'] ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Album / Judul Dokumentasi</label>
                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($editGallery['title']) ?>" placeholder="Contoh: Fasilitas Injection Molding Cikampek">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Unggah Foto Presisi (Bisa Pilih Banyak)</label>
                    <input type="file" name="media[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 10 MB per foto. Sesuai standar perusahaan, media video dan gif dinonaktifkan.</div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-save me-1"></i> Simpan Album & Foto
                    </button>
                    <?php if ($editGallery['id']): ?>
                        <a class="btn btn-secondary" href="gallery.php">Batal / Selesai Edit</a>
                    <?php endif; ?>
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
                <h5 class="fw-bold mb-3">Daftar Foto dalam Album Ini (<?= $mediaRows->num_rows ?> Foto)</h5>
                <div class="row g-3">
                    <?php while ($m = $mediaRows->fetch_assoc()): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="border rounded p-2 text-center h-100 bg-light">
                                <img src="../uploads/gallery/<?= htmlspecialchars(basename($m['media_path'])) ?>" class="img-fluid rounded mb-2" style="height: 140px; object-fit: cover; width: 100%;" alt="Foto Dokumentasi">
                                <?php if (has_permission('gallery', 'delete')): ?>
                                    <a class="btn btn-sm btn-outline-danger w-100" href="<?= csrf_url('gallery.php?delete_media=' . (int)$m['id']) ?>" onclick="return confirm('Hapus foto ini dari galeri?')">
                                        <i class="bi bi-trash me-1"></i> Hapus Foto
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php while ($row = $gallery->fetch_assoc()): ?>
            <?php
            $st = $conn->prepare('SELECT id FROM gallery_media WHERE gallery_id = ?');
            $st->bind_param('i', $row['id']);
            $st->execute();
            $count = $st->get_result()->num_rows;
            ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold mb-2"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="text-muted small mb-3"><?= $count ?> foto presisi</p>
                        <div class="mt-auto d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="gallery.php?edit=<?= (int)$row['id'] ?>">
                                <i class="bi bi-pencil me-1"></i> Kelola Foto
                            </a>
                            <?php if (has_permission('gallery', 'delete')): ?>
                                <a class="btn btn-sm btn-outline-danger" href="<?= csrf_url('gallery.php?delete=' . (int)$row['id']) ?>" onclick="return confirm('Hapus album ini beserta semua fotonya?')">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
