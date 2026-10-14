<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()["total"] ?? 0;
$newsCount = $conn->query("SELECT COUNT(*) AS total FROM news")->fetch_assoc()["total"] ?? 0;
$careerCount = $conn->query("SELECT COUNT(*) AS total FROM careers")->fetch_assoc()["total"] ?? 0;
$galleryCount = $conn->query("SELECT COUNT(*) AS total FROM gallery")->fetch_assoc()["total"] ?? 0;
$messageCount = $conn->query("SELECT COUNT(*) AS total FROM messages WHERE status = 'unread'")->fetch_assoc()["total"] ?? 0;
$certCount = $conn->query("SELECT COUNT(*) AS total FROM certifications WHERE status = 'active'")->fetch_assoc()["total"] ?? 0;
$secActiveCount = $conn->query("SELECT COUNT(*) AS total FROM site_sections WHERE is_active = 1")->fetch_assoc()["total"] ?? 0;
$secTotalCount = $conn->query("SELECT COUNT(*) AS total FROM site_sections")->fetch_assoc()["total"] ?? 0;
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'active'")->fetch_assoc()["total"] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>Admin Dashboard - PT Nihon Plast Indonesia</title>
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Corporate CMS Dashboard</h1>
        <p class="text-muted small mb-0">Overview administrasi sistem, elemen website dinamis, dan hak akses pengguna.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="site_sections.php" class="btn btn-outline-success btn-sm">
            <i class="bi bi-layout-text-window-reverse me-1"></i> Kelola Elemen CMS
        </a>
        <a href="../index.php" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Website
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Dynamic CMS Elements Widget -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">Dynamic CMS Elements</small>
                    <h2 class="mt-2 mb-0 fw-bold text-success"><?= $secActiveCount ?><span class="fs-6 text-muted fw-normal">/<?= $secTotalCount ?></span></h2>
                    <small class="text-muted">Elemen Aktif di Web</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success p-3 rounded-circle">
                    <i class="bi bi-layout-text-window-reverse fs-3"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="site_sections.php" class="small text-success text-decoration-none fw-semibold">
                    Atur Tiap Elemen & Toggle <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Products Widget -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">Produk Otomotif</small>
                    <h2 class="mt-2 mb-0 fw-bold"><?= $productCount ?></h2>
                    <small class="text-muted">Lini Komponen</small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary p-3 rounded-circle">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="products.php" class="small text-primary text-decoration-none fw-semibold">
                    Kelola Produk <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ISO & Certifications Widget -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">Sertifikasi & Mutu</small>
                    <h2 class="mt-2 mb-0 fw-bold"><?= $certCount ?></h2>
                    <small class="text-muted">Standar ISO & Regulasi RI</small>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning p-3 rounded-circle">
                    <i class="bi bi-patch-check-fill fs-3"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="certifications.php" class="small text-warning text-decoration-none fw-semibold">
                    Kelola Akreditasi <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Users & RBAC Widget -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-semibold">User & Hak Akses</small>
                    <h2 class="mt-2 mb-0 fw-bold"><?= $userCount ?></h2>
                    <small class="text-muted">Pengguna Aktif (RBAC)</small>
                </div>
                <div class="stat-icon bg-info-subtle text-info p-3 rounded-circle">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                <a href="users.php" class="small text-info text-decoration-none fw-semibold">
                    Atur Izin CRUD <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-grid-fill text-success me-2"></i>Aksi Cepat Menu Manajemen
                </h5>
                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="site_sections.php" class="btn btn-outline-success w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-layout-text-window-reverse fs-4 d-block mb-1 text-success"></i>
                            <div class="fw-bold">Dynamic CMS</div>
                            <small class="text-muted d-block">Ubah teks, judul, icon, background & aktif/nonaktif</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="users.php" class="btn btn-outline-dark w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-person-gear fs-4 d-block mb-1 text-dark"></i>
                            <div class="fw-bold">User & Izin CRUD</div>
                            <small class="text-muted d-block">Otorisasi multiple user & hak menu</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="certifications.php" class="btn btn-outline-dark w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-patch-check fs-4 d-block mb-1 text-dark"></i>
                            <div class="fw-bold">ISO & Regulasi</div>
                            <small class="text-muted d-block">IATF 16949, ISO 14001, SMK3</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="products.php" class="btn btn-outline-primary w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-box-seam fs-4 d-block mb-1 text-primary"></i>
                            <div class="fw-bold">Katalog Produk</div>
                            <small class="text-muted d-block">Steering wheels, airbags, resin</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="gallery.php" class="btn btn-outline-primary w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-camera fs-4 d-block mb-1 text-primary"></i>
                            <div class="fw-bold">Galeri Foto</div>
                            <small class="text-muted d-block">100% foto statis presisi bebas video</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="messages.php" class="btn btn-outline-warning w-100 p-3 text-start h-100 rounded-3">
                            <i class="bi bi-envelope fs-4 d-block mb-1 text-warning"></i>
                            <div class="fw-bold">Pesan Masuk (<?= $messageCount ?>)</div>
                            <small class="text-muted d-block">Permintaan penawaran B2B RFQ</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-shield-check text-success me-2"></i>Spesifikasi Sistem
                </h5>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Arsitektur:</span>
                        <strong class="text-dark">PHP 8.3 + MySQL (FOSS)</strong>
                    </li>
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Multibahasa:</span>
                        <strong class="text-success">5 Bahasa (ID, EN, JA, ZH, KO)</strong>
                    </li>
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Media Konten:</span>
                        <strong class="text-dark">Pure Static High-Res Photos</strong>
                    </li>
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Otorisasi:</span>
                        <strong class="text-dark">Granular Menu-Level CRUD RBAC</strong>
                    </li>
                    <li class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Elemen CMS:</span>
                        <strong class="text-success">100% Dynamic & Active/Inactive</strong>
                    </li>
                    <li class="d-flex justify-content-between py-1">
                        <span class="text-muted">Standar Mutu:</span>
                        <strong class="text-dark">IATF 16949 / ISO 14001</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
