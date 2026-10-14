<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

$editItem = null;
$message = "";
$error = "";

// Handle Delete
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $stmt = $conn->prepare("SELECT image FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    if ($item && !empty($item["image"])) {
        $filePath = __DIR__ . "/../uploads/certifications/" . basename($item["image"]);
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    $delStmt = $conn->prepare("DELETE FROM certifications WHERE id = ?");
    $delStmt->bind_param("i", $id);
    $delStmt->execute();
    header("Location: certifications.php");
    exit;
}

// Handle Edit Fetch
if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];
    $stmt = $conn->prepare("SELECT * FROM certifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $editItem = $stmt->get_result()->fetch_assoc();
}

// Handle Form Submit (Insert / Update)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "Automotive & Quality");
    $certificateNumber = trim($_POST["certificate_number"] ?? "");
    $issuedBy = trim($_POST["issued_by"] ?? "");
    $validUntil = !empty($_POST["valid_until"]) ? $_POST["valid_until"] : null;
    $description = trim($_POST["description"] ?? "");
    $sortOrder = (int)($_POST["sort_order"] ?? 0);
    $status = in_array($_POST["status"] ?? "", ["active", "archived"], true) ? $_POST["status"] : "active";
    $image = trim($_POST["old_image"] ?? "");

    if ($name === "") {
        $error = "Nama sertifikasi wajib diisi.";
    } else {
        // Handle file upload
        if (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] === UPLOAD_ERR_OK) {
            $allowedMimes = ["image/jpeg", "image/png", "image/webp"];
            $fileType = mime_content_type($_FILES["image_file"]["tmp_name"]);

            if (in_array($fileType, $allowedMimes, true)) {
                $ext = strtolower(pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION));
                $newFilename = "cert_" . uniqid() . "." . $ext;
                $uploadDir = __DIR__ . "/../uploads/certifications/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $dest = $uploadDir . $newFilename;
                if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $dest)) {
                    // remove old file if exists
                    if (!empty($image)) {
                        $oldPath = $uploadDir . basename($image);
                        if (is_file($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    $image = $newFilename;
                } else {
                    $error = "Gagal memindahkan file yang diunggah.";
                }
            } else {
                $error = "Format file tidak didukung. Harap unggah file JPG, PNG, atau WEBP.";
            }
        }

        if (empty($error)) {
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE certifications SET name = ?, category = ?, certificate_number = ?, issued_by = ?, valid_until = ?, description = ?, image = ?, status = ?, sort_order = ? WHERE id = ?");
                $stmt->bind_param("ssssssssii", $name, $category, $certificateNumber, $issuedBy, $validUntil, $description, $image, $status, $sortOrder, $id);
                if ($stmt->execute()) {
                    $message = "Sertifikasi berhasil diperbarui.";
                    $editItem = null;
                } else {
                    $error = "Gagal memperbarui: " . $conn->error;
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO certifications (name, category, certificate_number, issued_by, valid_until, description, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssi", $name, $category, $certificateNumber, $issuedBy, $validUntil, $description, $image, $status, $sortOrder);
                if ($stmt->execute()) {
                    $message = "Sertifikasi baru berhasil ditambahkan.";
                } else {
                    $error = "Gagal menambahkan: " . $conn->error;
                }
            }
        }
    }
}

// Fetch all certifications
$certList = $conn->query("SELECT * FROM certifications ORDER BY sort_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <title>Manajemen Sertifikasi & ISO - Admin</title>
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold mb-1">Sertifikasi & Kepatuhan Regulasi</h2>
        <p class="text-muted mb-0">Kelola sertifikat standar otomotif (IATF 16949), mutu (ISO 9001), lingkungan (ISO 14001), keselamatan kerja (ISO 45001 / SMK3 PP 50/2012), dan SNI Kemenperin.</p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Tambah / Edit -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">
                    <?= $editItem ? "Edit Sertifikasi" : "Tambah Sertifikasi Baru" ?>
                </h5>
            </div>
            <div class="card-body pt-0">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= (int)($editItem['id'] ?? 0) ?>">
                    <input type="hidden" name="old_image" value="<?= htmlspecialchars($editItem['image'] ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Sertifikasi / Standar *</label>
                        <input type="text" name="name" class="form-control" placeholder="contoh: IATF 16949:2016" value="<?= htmlspecialchars($editItem['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Standar</label>
                        <select name="category" class="form-select">
                            <?php
                            $categories = [
                                "Automotive Quality Management",
                                "Quality Management System",
                                "Environmental Management System",
                                "Occupational Health & Safety (K3)",
                                "National Regulatory Standards",
                                "Information Security"
                            ];
                            $currCat = $editItem['category'] ?? "Automotive Quality Management";
                            foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" <?= $currCat === $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Sertifikat</label>
                        <input type="text" name="certificate_number" class="form-control" placeholder="contoh: IATF-0482910" value="<?= htmlspecialchars($editItem['certificate_number'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Badan Penerbit / Sertifikasi</label>
                        <input type="text" name="issued_by" class="form-control" placeholder="contoh: TÜV Rheinland / SGS" value="<?= htmlspecialchars($editItem['issued_by'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Masa Berlaku Sampai</label>
                        <input type="date" name="valid_until" class="form-control" value="<?= htmlspecialchars($editItem['valid_until'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi / Ruang Lingkup</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan ruang lingkup kepatuhan atau fungsi sertifikasi..."><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= (int)($editItem['sort_order'] ?? 0) ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= ($editItem['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="archived" <?= ($editItem['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Arsip</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Gambar / Badge Sertifikat</label>
                        <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP (Maks 3 MB).</small>
                        <?php if (!empty($editItem['image'])): ?>
                            <div class="mt-2 p-2 border rounded bg-light text-center">
                                <img src="../uploads/certifications/<?= htmlspecialchars($editItem['image']) ?>" alt="Preview" class="img-fluid" style="max-height: 80px;">
                                <div class="small text-muted mt-1"><?= htmlspecialchars($editItem['image']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <?= $editItem ? "Simpan Perubahan" : "Tambah Sertifikasi" ?>
                        </button>
                        <?php if ($editItem): ?>
                            <a href="certifications.php" class="btn btn-outline-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Sertifikasi -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Sertifikat Terdaftar (<?= $certList ? $certList->num_rows : 0 ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Urut</th>
                                <th>Sertifikasi & Kategori</th>
                                <th>No. Sertifikat & Penerbit</th>
                                <th>Masa Berlaku</th>
                                <th>Status</th>
                                <th style="width: 120px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($certList && $certList->num_rows > 0): ?>
                                <?php while ($row = $certList->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-muted fw-bold"><?= (int)$row['sort_order'] ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['name']) ?></div>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle small"><?= htmlspecialchars($row['category']) ?></span>
                                            <?php if (!empty($row['description'])): ?>
                                                <div class="small text-muted text-truncate mt-1" style="max-width: 250px;">
                                                    <?= htmlspecialchars($row['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold"><?= htmlspecialchars($row['certificate_number'] ?: '-') ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($row['issued_by'] ?: '-') ?></div>
                                        </td>
                                        <td>
                                            <?php if ($row['valid_until']): ?>
                                                <div class="small"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($row['valid_until'])) ?></div>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] === 'active'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Arsip</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="certifications.php?edit=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="certifications.php?delete=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus sertifikasi ini?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data sertifikasi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
