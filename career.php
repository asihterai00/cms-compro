<?php
require_once __DIR__ . "/config/database.php";

$careers = $conn->query("SELECT * FROM careers ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('nav_career', 'Karir')) ?> & Peluang Kerja</title>
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "career"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <!-- 1. Intro & Culture -->
        <?php if (is_section_active('career', 'career_intro')): ?>
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('career', 'career_intro', 'icon', 'bi-people-fill')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('career', 'career_intro', 'badge_text', 'CAREER & TALENT DEVELOPMENT')) ?>
                </span>
                <h1 class="display-6 fw-bold text-np-green mb-3">
                    <?= htmlspecialchars(get_section_val('career', 'career_intro', 'title', 'Membangun Karir Berkelanjutan di Industri Manufaktur Presisi')) ?>
                </h1>
                <p class="lead text-muted mb-4">
                    <?= nl2br(htmlspecialchars(get_section_val('career', 'career_intro', 'subtitle', 'Kami percaya bahwa keunggulan produk berawal dari sumber daya manusia yang kompeten, berintegritas, dan menjunjung tinggi budaya keselamatan kerja.'))) ?>
                </p>
                <div class="row g-3">
                    <?php 
                    $introFeats = sec_items('career_intro_features');
                    if (!empty($introFeats)):
                        foreach ($introFeats as $feat):
                    ?>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold text-np-green mb-1"><i class="bi <?= htmlspecialchars($feat['icon'] ?: 'bi-check-circle') ?> me-1"></i> <?= htmlspecialchars($feat['title']) ?></h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($feat['subtitle'] ?? $feat['content'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="rounded-4 overflow-hidden shadow-sm border">
                    <?php
                    $careerImg = resolve_image_path(
                        get_section_val('career', 'career_intro', 'image', 'uploads/gallery/facility_cleanroom_lab.svg'),
                        'uploads/pages'
                    );
                    ?>
                    <img
                        src="<?= htmlspecialchars($careerImg) ?>"
                        class="img-fluid w-100"
                        style="height: 340px; object-fit: cover;"
                        alt="Nihon Plast Indonesia Team"
                    >
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 2. Workplace Culture & Benefits -->
        <?php if (is_section_active('career', 'career_culture_benefits')): ?>
        <div class="mb-5 p-4 p-md-5 bg-light rounded-4 border">
            <div class="text-center max-w-700 mx-auto mb-4">
                <span class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(get_section_val('career', 'career_culture_benefits', 'icon', 'bi-heart-pulse-fill')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('career', 'career_culture_benefits', 'badge_text', 'WORKPLACE CULTURE & PERKS')) ?>
                </span>
                <h2 class="h3 fw-bold text-np-green mb-2">
                    <?= htmlspecialchars(get_section_val('career', 'career_culture_benefits', 'title', 'Kesejahteraan Karyawan & Pengembangan Profesional')) ?>
                </h2>
                <p class="text-muted small">
                    <?= htmlspecialchars(get_section_val('career', 'career_culture_benefits', 'subtitle', 'Pelatihan teknik Monozukuri langsung, fasilitas BPJS Kesehatan & Ketenagakerjaan lengkap, kantin higienis, dan bus jemputan karyawan.')) ?>
                </p>
            </div>
            <div class="row g-4 text-center">
                <?php 
                $benefits = sec_items('career_culture_benefits');
                if (!empty($benefits)):
                    foreach ($benefits as $b):
                ?>
                    <div class="col-6 col-md-4">
                        <div class="p-3 bg-white rounded-3 shadow-sm h-100 border">
                            <i class="bi <?= htmlspecialchars($b['icon'] ?: 'bi-award') ?> text-np-green fs-2 mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($b['title']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($b['content'] ?? '') ?></small>
                        </div>
                    </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 3. Recruitment Stages -->
        <?php if (is_section_active('career', 'career_hiring_process')): ?>
        <div class="mb-5 p-4 p-md-5 bg-white rounded-4 border shadow-sm">
            <div class="text-center max-w-700 mx-auto mb-4">
                <span class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(get_section_val('career', 'career_hiring_process', 'icon', 'bi-check2-circle')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('career', 'career_hiring_process', 'badge_text', 'RECRUITMENT STAGES')) ?>
                </span>
                <h2 class="h3 fw-bold text-np-green mb-2">
                    <?= htmlspecialchars(get_section_val('career', 'career_hiring_process', 'title', 'Tahapan Rekrutmen Transparan & Bebas Biaya')) ?>
                </h2>
                <p class="text-muted small">
                    <?= htmlspecialchars(get_section_val('career', 'career_hiring_process', 'subtitle', '1. Seleksi Berkas Administrasi, 2. Tes Kemampuan Teknis & Psikotes, 3. Wawancara HR & User, 4. Pemeriksaan Kesehatan (MCU) & Onboarding.')) ?>
                </p>
            </div>
            <div class="row g-3 text-center">
                <?php 
                $stages = sec_items('career_hiring_process');
                if (!empty($stages)):
                    foreach ($stages as $idx => $stg):
                ?>
                    <div class="col-sm-6 col-lg-3">
                        <div class="p-3 bg-light rounded-3 h-100">
                            <div class="badge bg-np-green text-white rounded-pill px-3 py-1 mb-2">
                                <?= !empty($stg['subtitle']) ? 'Tahap ' . htmlspecialchars($stg['subtitle']) : 'Langkah ' . ($idx + 1) ?>
                            </div>
                            <h6 class="fw-bold text-np-green mb-1"><?= htmlspecialchars($stg['title']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($stg['content'] ?? '') ?></small>
                        </div>
                    </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 4. Job Vacancies List -->
        <?php if (is_section_active('career', 'career_vacancies_intro')): ?>
        <div class="mb-4">
            <span class="eyebrow-badge">
                <i class="bi <?= htmlspecialchars(get_section_val('career', 'career_vacancies_intro', 'icon', 'bi-briefcase-fill')) ?>"></i>
                <?= htmlspecialchars(get_section_val('career', 'career_vacancies_intro', 'badge_text', 'OPEN VACANCIES')) ?>
            </span>
            <h2 class="h3 fw-bold text-np-green mb-2">
                <?= htmlspecialchars(get_section_val('career', 'career_vacancies_intro', 'title', 'Posisi Lowongan yang Sedang Dibuka')) ?>
            </h2>
            <p class="text-muted">
                <?= htmlspecialchars(get_section_val('career', 'career_vacancies_intro', 'subtitle', 'Lihat kesempatan karir aktif di fasilitas pabrik Cikampek, Karawang dan kirimkan lamaran Anda.')) ?>
            </p>
        </div>
        <?php endif; ?>

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