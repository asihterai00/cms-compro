<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/translation_helpers.php";

$certList = $conn->query("SELECT * FROM certifications WHERE status = 'active' ORDER BY sort_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('nav_quality', 'Kebijakan Mutu')) ?> & Sertifikasi ISO</title>
    <meta name="description" content="Standar Mutu IATF 16949, ISO 9001, ISO 14001, ISO 45001 / SMK3, dan Kepatuhan Regulasi Pemerintah Indonesia di PT Nihon Plast Indonesia.">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "quality"; include __DIR__ . "/includes/page_banner.php"; ?>

<!-- 1. FILOSOFI MUTU & PENDAHULUAN -->
<?php if (is_section_active('quality', 'quality_zero_defect')): ?>
<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('quality', 'quality_zero_defect', 'icon', 'bi-shield-check')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('quality', 'quality_zero_defect', 'badge_text', 'ZERO DEFECT PHILOSOPHY')) ?>
                </div>
                <h2 class="display-6 fw-bold text-np-green mb-3">
                    <?= htmlspecialchars(get_section_val('quality', 'quality_zero_defect', 'title', 'Dedikasi Mutu Tanpa Kompromi untuk Keselamatan Pengemudi')) ?>
                </h2>
                <p class="text-muted" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars(get_section_val('quality', 'quality_zero_defect', 'subtitle', 'Sebagai pemasok otomotif Tier-1 global, PT Nihon Plast Indonesia memproduksi komponen vital seperti roda kemudi dan modul airbag di mana keandalan produk berdampak langsung pada nyawa manusia. Oleh karena itu, prinsip "Quality First" dan "Zero Defect" ditanamkan ke dalam setiap tahap rekayasa, pencetakan resin, hingga perakitan akhir.'))) ?>
                </p>
                <?php if ($zeroDefectContent = get_section_val('quality', 'quality_zero_defect', 'content')): ?>
                <div class="p-3 bg-light rounded-3 border-start border-4 border-primary mt-4">
                    <p class="mb-0 fst-italic text-dark">
                        <?= nl2br(htmlspecialchars($zeroDefectContent)) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="card border-0 shadow-sm p-4 rounded-4 bg-light text-center h-100">
                            <i class="bi bi-patch-check-fill text-np-green fs-1 mb-2"></i>
                            <h5 class="fw-bold text-np-green mb-1">IATF 16949</h5>
                            <small class="text-muted">Standar Mutu Otomotif Global Tertinggi</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 shadow-sm p-4 rounded-4 bg-light text-center h-100">
                            <i class="bi bi-tree-fill text-success fs-1 mb-2"></i>
                            <h5 class="fw-bold text-np-green mb-1">ISO 14001</h5>
                            <small class="text-muted">Komitmen Ramah Lingkungan & AMDAL</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 shadow-sm p-4 rounded-4 bg-light text-center h-100">
                            <i class="bi bi-shield-shaded text-danger fs-1 mb-2"></i>
                            <h5 class="fw-bold text-np-green mb-1">SMK3 / ISO 45001</h5>
                            <small class="text-muted">Keselamatan Kerja Zero Accident</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 shadow-sm p-4 rounded-4 bg-light text-center h-100">
                            <i class="bi bi-flag-fill text-secondary fs-1 mb-2"></i>
                            <h5 class="fw-bold text-np-green mb-1">SNI & Kemenperin</h5>
                            <small class="text-muted">Kepatuhan Standar Industri RI & TKDN</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 2. DAFTAR SERTIFIKAT RESMI (DYNAMIC DARI DATABASE) -->
<?php if (is_section_active('quality', 'quality_certifications_intro')): ?>
<section class="section-padding section-light">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="eyebrow-badge mx-auto">
                <i class="bi <?= htmlspecialchars(get_section_val('quality', 'quality_certifications_intro', 'icon', 'bi-award-fill')) ?>"></i>
                <?= htmlspecialchars(get_section_val('quality', 'quality_certifications_intro', 'badge_text', 'ACCREDITED CERTIFICATIONS')) ?>
            </div>
            <h2 class="h1 fw-bold text-np-green">
                <?= htmlspecialchars(get_section_val('quality', 'quality_certifications_intro', 'title', 'Sertifikasi & Akreditasi Resmi')) ?>
            </h2>
            <p class="text-muted">
                <?= htmlspecialchars(get_section_val('quality', 'quality_certifications_intro', 'subtitle', 'Seluruh fasilitas dan proses manufaktur kami diaudit secara berkala oleh badan sertifikasi independen internasional terkemuka.')) ?>
            </p>
        </div>

        <div class="row g-4">
            <?php if ($certList && $certList->num_rows > 0): ?>
                <?php while ($cert = $certList->fetch_assoc()): 
                    if (function_exists('translate_entity_row')) $cert = translate_entity_row('certification', $cert);
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="cert-card <?= str_contains($cert['name'], 'IATF') ? 'featured-iatf' : '' ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="cert-card-icon d-flex align-items-center justify-content-center p-1">
                                    <?php if (!empty($cert['image'])): ?>
                                        <img src="<?= htmlspecialchars(safe_image_url($cert['image'])) ?>" alt="<?= htmlspecialchars($cert['name']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                    <?php else: ?>
                                        <i class="bi bi-award"></i>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-np-tint text-np-green border border-success-subtle small">
                                    <?= htmlspecialchars($cert['category']) ?>
                                </span>
                            </div>

                            <h4 class="h5 fw-bold text-np-green mb-2">
                                <?= htmlspecialchars($cert['name']) ?>
                            </h4>

                            <?php if (!empty($cert['certificate_number'])): ?>
                                <div>
                                    <span class="cert-number-badge">
                                        No: <?= htmlspecialchars($cert['certificate_number']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <p class="small text-muted flex-grow-1 mb-3" style="line-height: 1.7;">
                                <?= htmlspecialchars($cert['description']) ?>
                            </p>

                            <div class="pt-3 border-top mt-auto small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Badan Penerbit:</span>
                                    <strong class="text-dark"><?= htmlspecialchars($cert['issued_by'] ?: '-') ?></strong>
                                </div>
                                <?php if (!empty($cert['valid_until'])): ?>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Masa Berlaku:</span>
                                        <span class="badge bg-light text-dark border">
                                            <?= date('d M Y', strtotime($cert['valid_until'])) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 3. KEPATUHAN REGULASI PEMERINTAH INDONESIA -->
<?php if (is_section_active('quality', 'quality_regulatory_compliance')): ?>
<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('quality', 'quality_regulatory_compliance', 'icon', 'bi-shield-check')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('quality', 'quality_regulatory_compliance', 'badge_text', 'INDONESIAN REGULATORY COMPLIANCE')) ?>
                </div>
                <h2 class="h1 fw-bold text-np-green mb-3">
                    <?= htmlspecialchars(get_section_val('quality', 'quality_regulatory_compliance', 'title', 'Kepatuhan Terhadap Hukum & Regulasi Republik Indonesia')) ?>
                </h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars(get_section_val('quality', 'quality_regulatory_compliance', 'subtitle', 'PT Nihon Plast Indonesia senantiasa patuh terhadap undang-undang ketenagakerjaan, kesehatan & keselamatan kerja, perlindungan lingkungan hidup, serta standar industri nasional yang ditetapkan pemerintah:'))) ?>
                </p>

                <div class="d-flex flex-column gap-3">
                    <?php 
                    $regList = sec_items('quality_regulatory_compliance');
                    if (!empty($regList)):
                        foreach ($regList as $reg):
                    ?>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="badge bg-success-subtle text-success p-2 rounded-circle">
                                <i class="bi <?= htmlspecialchars($reg['icon'] ?: 'bi-check-lg') ?> fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-np-green mb-1"><?= htmlspecialchars($reg['title']) ?></h6>
                                <p class="small text-muted mb-0"><?= htmlspecialchars($reg['content'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </div>
            </div>

            <div class="col-lg-6 order-lg-1">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <?php 
                    $complianceImg = resolve_image_path(
                        get_section_val('quality', 'quality_regulatory_compliance', 'image', 'uploads/gallery/facility_cleanroom_lab.svg'),
                        'uploads/pages'
                    );
                    ?>
                    <img src="<?= htmlspecialchars($complianceImg) ?>" alt="Regulatory Compliance Lab" class="img-fluid" style="height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 4. EMPAT PILAR SISTEM KUALITAS PABRIK -->
<?php if (is_section_active('quality', 'quality_flowchart')): ?>
<section class="section-padding section-light">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="eyebrow-badge mx-auto">
                <i class="bi <?= htmlspecialchars(get_section_val('quality', 'quality_flowchart', 'icon', 'bi-diagram-3-fill')) ?>"></i>
                <?= htmlspecialchars(get_section_val('quality', 'quality_flowchart', 'badge_text', 'QUALITY FRAMEWORK')) ?>
            </div>
            <h2 class="h1 fw-bold text-np-green">
                <?= htmlspecialchars(get_section_val('quality', 'quality_flowchart', 'title', 'Sistem Pengendalian Mutu Menyeluruh')) ?>
            </h2>
            <p class="text-muted">
                <?= htmlspecialchars(get_section_val('quality', 'quality_flowchart', 'subtitle', 'Empat pilar operasional yang memastikan setiap komponen memenuhi ekspektasi OEM otomotif terkemuka.')) ?>
            </p>
        </div>

        <div class="row g-4">
            <?php 
            $pillars = sec_items('quality_flowchart');
            if (!empty($pillars)):
                foreach ($pillars as $pil):
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                        <div class="text-np-green fs-1 mb-2">
                            <i class="bi <?= htmlspecialchars($pil['icon'] ?: 'bi-check2-circle') ?>"></i>
                        </div>
                        <h5 class="fw-bold text-np-green mb-1"><?= htmlspecialchars($pil['title']) ?></h5>
                        <?php if (!empty($pil['subtitle'])): ?>
                            <div class="badge bg-np-tint text-np-green border border-success-subtle mb-2 small"><?= htmlspecialchars($pil['subtitle']) ?></div>
                        <?php endif; ?>
                        <p class="small text-muted mb-0"><?= htmlspecialchars($pil['content'] ?? '') ?></p>
                    </div>
                </div>
            <?php 
                endforeach;
            endif; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 5. CTA KONSULTASI STANDAR MUTU -->
<?php if (is_section_active('quality', 'quality_cta')): ?>
<section class="section-padding bg-np-green text-white">
    <div class="container text-center max-w-700 mx-auto">
        <div class="badge bg-white text-np-green fw-bold px-3 py-2 rounded-pill mb-3">
            <i class="bi <?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'icon', 'bi-patch-check-fill')) ?> me-1"></i>
            <?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'badge_text', 'GET CERTIFICATION COPIES')) ?>
        </div>
        <h2 class="display-6 fw-bold mb-3 text-white">
            <?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'title', 'Butuh Lembar Kepatuhan Mutu atau Salinan Sertifikat ISO?')) ?>
        </h2>
        <p class="lead text-white-50 mb-4">
            <?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'subtitle', 'Hubungi divisi Quality Assurance kami untuk mendapatkan salinan dokumen sertifikat resmi IATF 16949, ISO 14001, atau kepatuhan RoHS/ELV.')) ?>
        </p>
        <a href="<?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'button_link', 'contact.php')) ?>" class="btn btn-light text-np-green fw-bold px-4 py-3 rounded-pill">
            <?= htmlspecialchars(get_section_val('quality', 'quality_cta', 'button_text', 'Hubungi Tim QA & Compliance')) ?>
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>