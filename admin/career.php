<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('career', 'read');

$success = "";
$error = "";

$edit_id = (int) (
    $_GET["edit"] ?? 0
);

$editCareer = [
    "id" => 0,
    "position" => "",
    "department" => "",
    "location" => "",
    "description" => ""
];

if ($edit_id > 0) {

    $stmt = $conn->prepare(
        "SELECT *
         FROM careers
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $edit_id
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $editCareer = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_csrf();

    $id = (int) (
        $_POST["id"] ?? 0
    );

    if ($id > 0) {
        require_permission('career', 'update');
    } else {
        require_permission('career', 'create');
    }

    $position = trim(
        $_POST["position"] ?? ""
    );

    $department = trim(
        $_POST["department"] ?? ""
    );

    $location = trim(
        $_POST["location"] ?? ""
    );

    $description = trim(
        $_POST["description"] ?? ""
    );

    $sort_order = (int)(
        $_POST["sort_order"] ?? 10
    );

    if ($position === "") {

        $error =
            "Position wajib diisi.";

    } elseif ($id > 0) {

        $stmt = $conn->prepare(
            "UPDATE careers
             SET
                position = ?,
                department = ?,
                location = ?,
                description = ?,
                sort_order = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssii",
            $position,
            $department,
            $location,
            $description,
            $sort_order,
            $id
        );

        if ($stmt->execute()) {
            $success =
                "Career berhasil diperbarui.";
        } else {
            $error =
                "Career gagal diperbarui.";
        }

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO careers
             (
                position,
                department,
                location,
                description,
                sort_order
             )
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssi",
            $position,
            $department,
            $location,
            $description,
            $sort_order
        );

        if ($stmt->execute()) {

            $success =
                "Career berhasil ditambahkan.";

            $position = "";
            $department = "";
            $location = "";
            $description = "";

        } else {

            $error =
                "Career gagal ditambahkan.";
        }
    }

    $editCareer = [
        "id" => $id,
        "position" => $position,
        "department" => $department,
        "location" => $location,
        "description" => $description
    ];
}

if (isset($_GET["delete"])) {

    require_csrf();
    require_permission('career', 'delete');

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM careers
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    header(
        "Location: career.php"
    );

    exit;
}

$careers = $conn->query(
    "SELECT *
     FROM careers
     ORDER BY sort_order ASC, id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <link
        rel="stylesheet"
        href="assets/admin.css"
    >

    <title>NPI CMS - Karir & Lowongan | PT Nihon Plast Indonesia</title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="mb-4">
    <!-- Executive Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-briefcase me-1"></i> CAREERS & TALENT ACQUISITION
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    Total <?= $careers->num_rows ?> Lowongan
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">Career & Job Openings</h1>
            <p class="text-muted small mb-0">Kelola posisi lowongan kerja, kualifikasi persyaratan, dan departemen operasional pabrik Cikampek.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="site_sections.php?page=career" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-layout-text-window-reverse me-1"></i> Elemen Halaman Karir
            </a>
            <?php if (has_permission('career', 'create')): ?>
                <button class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#careerFormCollapse" aria-expanded="<?= ($editCareer['id'] > 0 || isset($_GET['new'])) ? 'true' : 'false' ?>">
                    <i class="bi bi-plus-circle me-1"></i> <?= $editCareer['id'] > 0 ? 'Tutup Form' : 'Tambah Lowongan Baru' ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- COLLAPSIBLE FORM -->
    <div class="collapse <?= ($editCareer['id'] > 0 || isset($_GET['new'])) ? 'show' : '' ?> mb-4" id="careerFormCollapse">
        <div class="card border-0 shadow-sm rounded-4" id="formCareer">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                        <i class="bi <?= $editCareer['id'] > 0 ? 'bi-pencil' : 'bi-plus-lg' ?>"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">
                        <?= $editCareer["id"] > 0 ? "Edit Lowongan: " . htmlspecialchars($editCareer["position"]) : "Tambah Posisi Lowongan Baru" ?>
                    </h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#careerFormCollapse">
                    Tutup
                </button>
            </div>

            <div class="card-body p-4">
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $editCareer["id"] ?>">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Posisi / Jabatan *</label>
                            <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($editCareer["position"]) ?>" placeholder="Contoh: Mold Maintenance Engineer" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Departemen</label>
                            <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($editCareer["department"]) ?>" placeholder="Contoh: Engineering & Tooling">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Lokasi Penempatan</label>
                            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($editCareer["location"] ?: 'Cikampek, Jawa Barat') ?>" placeholder="Contoh: Plant Cikampek">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Urutan</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= (int)($editCareer["sort_order"] ?? 10) ?>" step="5">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Tugas & Kualifikasi</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Tuliskan ringkasan tanggung jawab dan persyaratan kualifikasi kandidat..."><?= htmlspecialchars($editCareer["description"]) ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-emerald px-4 rounded-pill shadow-sm">
                            <i class="bi bi-save me-1"></i> <?= $editCareer["id"] > 0 ? "Update Lowongan" : "Simpan Lowongan" ?>
                        </button>
                        <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-toggle="collapse" data-bs-target="#careerFormCollapse">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CAREER TABLE -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Daftar Lowongan Kerja</h5>
            <span class="text-muted small">Total: <strong><?= $careers->num_rows ?></strong> Posisi</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 120px;">Urutan</th>
                            <th>Posisi / Jabatan</th>
                            <th>Departemen</th>
                            <th>Lokasi</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($careers->num_rows === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-briefcase fs-1 d-block mb-2 text-muted"></i>
                                    Belum ada lowongan pekerjaan dibuka.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = $careers->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="ajax_reorder.php?table=careers&action=move_up&id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Atas">
                                                <i class="bi bi-arrow-up"></i>
                                            </a>
                                            <a href="ajax_reorder.php?table=careers&action=move_down&id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-secondary p-1" title="Pindah ke Bawah">
                                                <i class="bi bi-arrow-down"></i>
                                            </a>
                                            <span class="badge bg-light text-dark border px-2 py-1 font-monospace"><?= (int)($row['sort_order'] ?? 10) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row["position"]) ?></div>
                                        <?php if (!empty($row["description"])): ?>
                                            <div class="text-muted fs-xs text-truncate" style="max-width: 320px;">
                                                <?= htmlspecialchars(strip_tags($row["description"])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                            <?= htmlspecialchars($row["department"] ?: 'Umum') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($row["location"] ?: 'Cikampek') ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            <a href="career.php?edit=<?= (int) $row["id"] ?>#careerFormCollapse" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Edit Lowongan">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if (has_permission('career', 'delete')): ?>
                                                <a href="<?= csrf_url('career.php?delete=' . (int) $row["id"]) ?>" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Hapus Lowongan" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">
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

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
