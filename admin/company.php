<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('company', 'read');

$success = "";
$error = "";

$result = $conn->query(
    "SELECT *
     FROM company_profile
     ORDER BY id ASC
     LIMIT 1"
);

$company = $result
    ? $result->fetch_assoc()
    : null;

if (!$company) {

    $conn->query(
        "INSERT INTO company_profile
         (company_name)
         VALUES
         ('PT Nihon Plast Indonesia')"
    );

    $result = $conn->query(
        "SELECT *
         FROM company_profile
         ORDER BY id ASC
         LIMIT 1"
    );

    $company = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    require_permission('company', 'update');

    $company_name = trim(
        $_POST["company_name"] ?? ""
    );

    $tagline = trim(
        $_POST["tagline"] ?? ""
    );

    $short_description = trim(
        $_POST["short_description"] ?? ""
    );

    $about_text = trim(
        $_POST["about_text"] ?? ""
    );

    $vision = trim(
        $_POST["vision"] ?? ""
    );

    $mission = trim(
        $_POST["mission"] ?? ""
    );

    $values_text = trim(
        $_POST["values_text"] ?? ""
    );

    $address = trim(
        $_POST["address"] ?? ""
    );

    $phone = trim(
        $_POST["phone"] ?? ""
    );

    $email = trim(
        $_POST["email"] ?? ""
    );

    $logo = $company["logo"] ?? "";

    if ($company_name === "") {

        $error = "Nama perusahaan wajib diisi.";

    } else {

        if (
            isset($_FILES["logo"]) &&
            $_FILES["logo"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {
            $upRes = cms_upload_image($_FILES["logo"], "company", "logo_", 2097152);
            if ($upRes["success"]) {
                $logo = basename($upRes["path"]);
            } else {
                $error = $upRes["error"];
            }
        }

        if ($error === "") {

            $stmt = $conn->prepare(
                "UPDATE company_profile
                 SET
                    company_name = ?,
                    tagline = ?,
                    short_description = ?,
                    about_text = ?,
                    vision = ?,
                    mission = ?,
                    values_text = ?,
                    address = ?,
                    phone = ?,
                    email = ?,
                    logo = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "sssssssssssi",
                $company_name,
                $tagline,
                $short_description,
                $about_text,
                $vision,
                $mission,
                $values_text,
                $address,
                $phone,
                $email,
                $logo,
                $company["id"]
            );

            if ($stmt->execute()) {

                $success =
                    "Company Profile berhasil disimpan.";

                $company["company_name"] =
                    $company_name;

                $company["tagline"] =
                    $tagline;

                $company["short_description"] =
                    $short_description;

                $company["about_text"] =
                    $about_text;

                $company["vision"] =
                    $vision;

                $company["mission"] =
                    $mission;

                $company["values_text"] =
                    $values_text;

                $company["address"] =
                    $address;

                $company["phone"] =
                    $phone;

                $company["email"] =
                    $email;

                $company["logo"] =
                    $logo;

            } else {

                $error =
                    "Data gagal disimpan.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <link
        rel="stylesheet"
        href="assets/admin.css"
    >

    <title>NPI CMS - Profil Perusahaan | PT Nihon Plast Indonesia</title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="mb-4">
    <!-- Executive Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-building me-1"></i> CORPORATE IDENTITY & PROFILE
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    PT Nihon Plast Indonesia
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">Company Profile & Contact</h1>
            <p class="text-muted small mb-0">Kelola informasi legalitas korporasi, visi-misi, nilai perusahaan, dan kontak operasional.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="../about.php" target="_blank" class="btn btn-outline-emerald btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Publik
            </a>
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

    <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Column: Main Corporate Data -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Informasi Utama Perusahaan</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Perusahaan *</label>
                                <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($company["company_name"] ?? "") ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tagline / Slogan</label>
                                <input type="text" name="tagline" class="form-control" value="<?= htmlspecialchars($company["tagline"] ?? "") ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Singkat (Short Description)</label>
                                <textarea name="short_description" class="form-control" rows="2"><?= htmlspecialchars($company["short_description"] ?? "") ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tentang Perusahaan (About Company)</label>
                                <textarea name="about_text" class="form-control" rows="5"><?= htmlspecialchars($company["about_text"] ?? "") ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vision, Mission & Values -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-compass"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Filosofi, Visi & Misi</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Visi Perusahaan (Vision)</label>
                                <textarea name="vision" class="form-control" rows="3"><?= htmlspecialchars($company["vision"] ?? "") ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Misi Perusahaan (Mission)</label>
                                <textarea name="mission" class="form-control" rows="3"><?= htmlspecialchars($company["mission"] ?? "") ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nilai-Nilai Inti (Corporate Values)</label>
                                <textarea name="values_text" class="form-control" rows="3"><?= htmlspecialchars($company["values_text"] ?? "") ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Logo & Contact Info -->
            <div class="col-lg-4">
                <!-- Website Logo -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-image"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Logo Perusahaan</h5>
                    </div>

                    <div class="card-body p-4 text-center">
                        <?php if (!empty($company["logo"])): ?>
                            <div class="p-3 bg-light rounded-4 border mb-3 d-inline-block w-100">
                                <img src="../uploads/company/<?= htmlspecialchars($company["logo"]) ?>" class="img-fluid" style="max-height: 90px; object-fit: contain;" alt="Company Logo">
                                <div class="fs-xs text-muted mt-2 text-truncate"><?= htmlspecialchars($company["logo"]) ?></div>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-light rounded-4 border mb-3 text-muted">
                                <i class="bi bi-image fs-1 d-block mb-1 text-muted"></i>
                                <span class="fs-xs">Belum ada logo diunggah</span>
                            </div>
                        <?php endif; ?>

                        <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                        <small class="text-muted d-block mt-2 fs-xs">
                            Format: PNG, SVG, WEBP, JPG. Maksimal 2 MB. Disarankan background transparan.
                        </small>
                    </div>
                </div>

                <!-- Contact & Factory Location -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Kontak & Lokasi Pabrik</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Alamat Lengkap Kantor & Pabrik</label>
                            <textarea name="address" class="form-control" rows="4"><?= htmlspecialchars($company["address"] ?? "") ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Telepon / Fax</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($company["phone"] ?? "") ?>" placeholder="+62 264 ...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email Resmi</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($company["email"] ?? "") ?>" placeholder="info@n-plast.co.id">
                        </div>
                    </div>
                </div>

                <!-- Save Action Button -->
                <button type="submit" class="btn btn-emerald w-100 py-2 rounded-pill shadow-sm fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Profil Perusahaan
                </button>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
