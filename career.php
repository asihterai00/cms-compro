<?php
require_once __DIR__ . "/config/database.php";

$careers = $conn->query("SELECT * FROM careers ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Karir & Peluang Kerja - PT Nihon Plast Indonesia</title>
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "career"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <!-- Intro & Culture -->
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="eyebrow-badge">
                    <i class="bi bi-people-fill"></i> PEOPLE & MONOZUKURI CULTURE
                </span>
                <h1 class="display-6 fw-bold text-np-green mb-3">
                    Tumbuh & Berkembang Bersama Produsen Otomotif Tier-1 Dunia
                </h1>
                <p class="lead text-muted mb-4">
                    Di PT Nihon Plast Indonesia, kami percaya bahwa produk berkualitas tinggi hanya lahir dari sumber daya manusia yang terampil, berintegritas, dan menjunjung tinggi keselamatan kerja (<em>Anzen Daiichi</em>).
                </p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-mortarboard-fill me-1"></i> Pelatihan Terstruktur</h6>
                            <p class="small text-muted mb-0">Program transfer teknologi dan pelatihan standar manufaktur Jepang.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-np-green mb-1"><i class="bi bi-shield-check me-1"></i> Budaya K3 / SMK3</h6>
                            <p class="small text-muted mb-0">Lingkungan kerja higienis dengan penghargaan Zero Accident.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-sm border">
                    <img
                        src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=900&q=80"
                        class="img-fluid w-100"
                        style="height: 340px; object-fit: cover;"
                        alt="Nihon Plast Indonesia Team"
                    >
                </div>
            </div>
        </div>

        <!-- Job Vacancies List -->
        <div class="mb-4">
            <span class="eyebrow-badge">
                <i class="bi bi-briefcase-fill"></i> CURRENT OPENINGS
            </span>
            <h2 class="h3 fw-bold text-np-green mb-4">Lowongan Kerja Tersedia</h2>
        </div>

        <div class="row g-4">
            <?php if ($careers && $careers->num_rows > 0): ?>
                <?php while ($job = $careers->fetch_assoc()): ?>
                    <div class="col-lg-6">
                        <div class="card h-100 border rounded-4 p-4 shadow-sm bg-white">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h3 class="h5 fw-bold text-np-green mb-0"><?= htmlspecialchars($job["position"] ?? "Posisi") ?></h3>
                                <span class="badge bg-np-tint text-np-green border border-success-subtle">
                                    Full Time
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                                <span><i class="bi bi-building me-1"></i> <?= htmlspecialchars($job["department"] ?? "General") ?></span>
                                <span><i class="bi bi-geo-alt me-1"></i> Cikampek, Karawang Plant</span>
                            </div>
                            <p class="small text-muted flex-grow-1 mb-4" style="line-height: 1.7;">
                                <?= nl2br(htmlspecialchars($job["description"] ?? "Kualifikasi dan tanggung jawab posisi.")) ?>
                            </p>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Batas Lamaran: Terbuka</span>
                                <a href="mailto:recruitment@n-plast.co.id?subject=Lamaran%20Posisi%20<?= urlencode($job['position']) ?>%20-%20PT%20Nihon%20Plast%20Indonesia" class="btn btn-np-primary btn-sm">
                                    Lamar Sekarang <i class="bi bi-send ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border text-center py-4">
                        <i class="bi bi-info-circle text-np-green fs-4 d-block mb-2"></i>
                        Saat ini belum ada posisi baru yang dibuka. Silakan kirimkan CV umum Anda ke <strong>recruitment@n-plast.co.id</strong>.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- General Application Box -->
        <div class="mt-5 p-4 bg-light rounded-4 border text-center">
            <h4 class="h5 fw-bold text-np-green mb-2">Tidak Menemukan Posisi yang Sesuai?</h4>
            <p class="small text-muted mb-3">
                Kami selalu mencari talenta berbakat di bidang mold engineering, injection process, quality assurance, dan perakitan presisi.
            </p>
            <a href="mailto:recruitment@n-plast.co.id?subject=General%20Application%20-%20PT%20Nihon%20Plast%20Indonesia" class="btn btn-np-outline btn-sm">
                <i class="bi bi-envelope me-1"></i> Kirimkan CV ke Talent Pool Kami
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>