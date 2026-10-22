<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/includes/translation_helpers.php";

// Fetch dynamic machinery matrix from database
$machineryQuery = $conn->query("SELECT * FROM facility_machinery WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
$machineryRows = [];
if ($machineryQuery) {
    while ($m = $machineryQuery->fetch_assoc()) {
        $machineryRows[] = $m;
    }
}
if (function_exists('translate_entity_rows')) {
    $machineryRows = translate_entity_rows('facility_machinery', $machineryRows);
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>NPI - PT Nihon Plast Indonesia | <?= htmlspecialchars(__('nav_facility', 'Fasilitas & Teknologi')) ?> & Mesin Presisi</title>
    <meta name="description" content="<?= htmlspecialchars(get_section_val('facility', 'facility_plant_overview', 'subtitle', 'Fasilitas manufaktur presisi tinggi berstandar IATF 16949 di Kawasan Industri Cikampek, Karawang.')) ?>">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "facility"; include __DIR__ . "/includes/page_banner.php"; ?>

<!-- 1. Strategic Plant Overview -->
<?php if (is_section_active('facility', 'facility_plant_overview')): ?>
    <section class="section-padding pb-4">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(get_section_val('facility', 'facility_plant_overview', 'icon', 'bi-geo-alt-fill')) ?>"></i> 
                        <?= htmlspecialchars(get_section_val('facility', 'facility_plant_overview', 'badge_text', 'Kawasan Industri Indotaisei Cikampek')) ?>
                    </span>
                    <h2 class="display-6 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(get_section_val('facility', 'facility_plant_overview', 'title', 'Fasilitas Manufaktur Otomotif Berstandar Global Tier-1')) ?>
                    </h2>
                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars(get_section_val('facility', 'facility_plant_overview', 'subtitle', 'Pabrik PT Nihon Plast Indonesia didesain mengikuti standar ketat prinsipal Nihon Plast Co., Ltd. (Jepang) untuk menghadirkan presisi Monozukuri tanpa cela.')) ?>
                    </p>
                    <div class="row g-3 mb-4">
                        <?php 
                        $plantStats = sec_items('facility_plant_stats');
                        if (!empty($plantStats)):
                            foreach ($plantStats as $pStat):
                        ?>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="text-np-green fw-bold fs-4"><?= htmlspecialchars($pStat['title']) ?></div>
                                    <div class="small text-muted fw-semibold"><?= htmlspecialchars($pStat['subtitle']) ?></div>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg border">
                        <?php
                        $plantImg = get_section_val('facility', 'facility_plant_overview', 'image', 'uploads/gallery/facility_injection_molding.jpg');
                        $plantImgSrc = resolve_image_path($plantImg);
                        ?>
                        <img
                            src="<?= htmlspecialchars($plantImgSrc) ?>"
                            class="img-fluid w-100"
                            style="height: 440px; object-fit: cover;"
                            alt="PT Nihon Plast Indonesia Advanced Factory Plant"
                            loading="lazy"
                        >
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, transparent 0%, rgba(0,38,22,0.92) 100%);">
                            <span class="badge bg-success mb-1">Cikampek Plant • West Java</span>
                            <h5 class="text-white fw-bold mb-0">High-Precision Automotive Production & Assembly Line</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 2. Core Manufacturing Cells (Bento Grid) -->
<?php if (is_section_active('facility', 'facility_capabilities')): ?>
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('facility', 'facility_capabilities', 'icon', 'bi-cpu-fill')) ?>"></i> 
                    <?= htmlspecialchars(get_section_val('facility', 'facility_capabilities', 'badge_text', 'Kapabilitas Lini Produksi')) ?>
                </span>
                <h2 class="display-6 fw-bold mb-3">
                    <?= htmlspecialchars(get_section_val('facility', 'facility_capabilities', 'title', 'Teknologi Manufaktur Unggulan')) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(get_section_val('facility', 'facility_capabilities', 'subtitle', 'Kombinasi teknologi permesinan injeksi otomatis bertekanan tinggi dengan keahlian tangan pengrajin (Takumi Craftsmanship).')) ?>
                </p>
            </div>

            <div class="row g-4">
                <?php 
                $caps = sec_items('facility_capabilities');
                if (!empty($caps)):
                    foreach ($caps as $cap):
                        $hasImage = !empty($cap['image']);
                        $colClass = $hasImage ? 'col-lg-4 col-md-6' : 'col-lg-6 col-md-6';
                ?>
                    <div class="<?= $colClass ?>">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden <?= $hasImage ? '' : 'p-4' ?>">
                            <?php if ($hasImage): ?>
                                <img
                                    src="<?= htmlspecialchars(resolve_image_path($cap['image'])) ?>"
                                    class="card-img-top"
                                    style="height: 220px; object-fit: cover;"
                                    alt="<?= htmlspecialchars($cap['title']) ?>"
                                    loading="lazy"
                                >
                                <div class="card-body p-4">
                                    <?php if (!empty($cap['badge'])): ?>
                                        <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2"><?= htmlspecialchars($cap['badge']) ?></span>
                                    <?php endif; ?>
                                    <h4 class="h5 fw-bold mb-2"><?= htmlspecialchars($cap['title']) ?></h4>
                                    <?php if (!empty($cap['subtitle'])): ?>
                                        <p class="small text-muted mb-3"><?= htmlspecialchars($cap['subtitle']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($cap['content'])): ?>
                                        <p class="small text-muted mb-3"><?= htmlspecialchars($cap['content']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($cap['extra_data']) && is_array($cap['extra_data'])): ?>
                                        <ul class="list-unstyled small text-muted mb-0">
                                            <?php foreach ($cap['extra_data'] as $point): ?>
                                                <li><i class="bi bi-check2-circle text-success me-1"></i> <?= htmlspecialchars($point) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                        <i class="bi <?= htmlspecialchars($cap['icon'] ?: 'bi-gear-fill') ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="h5 fw-bold mb-0"><?= htmlspecialchars($cap['title']) ?></h4>
                                        <?php if (!empty($cap['subtitle'])): ?>
                                            <span class="small text-muted"><?= htmlspecialchars($cap['subtitle']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($cap['content'])): ?>
                                    <p class="text-muted small mb-0"><?= htmlspecialchars($cap['content']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($cap['extra_data']) && is_array($cap['extra_data'])): ?>
                                    <ul class="list-unstyled small text-muted mt-3 mb-0">
                                        <?php foreach ($cap['extra_data'] as $point): ?>
                                            <li><i class="bi bi-check2-circle text-success me-1"></i> <?= htmlspecialchars($point) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endif; ?>
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

<!-- 3. Dynamic Machinery Specifications Matrix (FROM DATABASE) -->
<?php if (is_section_active('facility', 'facility_machinery_intro')): ?>
    <section class="section-padding">
        <div class="container">
            <div class="row align-items-end justify-content-between mb-4">
                <div class="col-lg-7">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(get_section_val('facility', 'facility_machinery_intro', 'icon', 'bi-table')) ?>"></i> 
                        <?= htmlspecialchars(get_section_val('facility', 'facility_machinery_intro', 'badge_text', 'Technical Machinery Matrix')) ?>
                    </span>
                    <h2 class="display-6 fw-bold mb-2">
                        <?= htmlspecialchars(get_section_val('facility', 'facility_machinery_intro', 'title', 'Spesifikasi Mesin & Kapasitas Produksi')) ?>
                    </h2>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars(get_section_val('facility', 'facility_machinery_intro', 'subtitle', 'Daftar permesinan produksi utama yang beroperasi di fasilitas PT Nihon Plast Indonesia.')) ?>
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                    <span class="badge bg-np-tint text-np-green px-3 py-2 border border-success-subtle">
                        <i class="bi bi-arrow-repeat me-1"></i> Standar Pemeliharaan TPM (Total Productive Maintenance)
                    </span>
                </div>
            </div>

            <div class="machinery-table-wrapper">
                <div class="table-responsive">
                    <table class="table machinery-table">
                        <thead>
                            <tr>
                                <th>Kategori Mesin</th>
                                <th>Model / Merk Spesifikasi</th>
                                <th>Kapasitas / Tonase</th>
                                <th>Aplikasi Utama Komponen</th>
                                <th>Sistem Otomasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($machineryRows)): ?>
                                <?php foreach ($machineryRows as $row): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($row['category']) ?></td>
                                        <td><?= htmlspecialchars($row['model_brand']) ?></td>
                                        <td><span class="spec-mono"><?= htmlspecialchars($row['tonnage']) ?></span></td>
                                        <td><?= htmlspecialchars($row['application']) ?></td>
                                        <td><?= htmlspecialchars($row['automation']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data mesin aktif.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 4. Laboratories & Metrology -->
<?php if (is_section_active('facility', 'facility_laboratories')): ?>
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('facility', 'facility_laboratories', 'icon', 'bi-moisture')) ?>"></i> 
                    <?= htmlspecialchars(get_section_val('facility', 'facility_laboratories', 'badge_text', 'Testing & Metrology Labs')) ?>
                </span>
                <h2 class="display-6 fw-bold mb-3">
                    <?= htmlspecialchars(get_section_val('facility', 'facility_laboratories', 'title', 'Laboratorium Kontrol Mutu & Pengujian Dinamis')) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(get_section_val('facility', 'facility_laboratories', 'subtitle', 'Peralatan inspeksi Coordinate Measuring Machine (CMM), chamber uji suhu ekstrim, dan sensor lab deploy airbag.')) ?>
                </p>
            </div>

            <div class="row g-4">
                <?php 
                $labs = sec_items('facility_laboratories');
                if (!empty($labs)):
                    foreach ($labs as $lab):
                ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="cleanroom-card h-100 p-4 bg-white rounded-4 border">
                            <?php if (!empty($lab['badge'])): ?>
                                <div class="cleanroom-badge mb-2">
                                    <i class="bi <?= htmlspecialchars($lab['icon'] ?: 'bi-award') ?> me-1"></i><?= htmlspecialchars($lab['badge']) ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="fw-bold mb-2"><?= htmlspecialchars($lab['title']) ?></h5>
                            <p class="small text-muted mb-0"><?= htmlspecialchars($lab['content'] ?? '') ?></p>
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

<!-- 5. CTA Kunjungan Pabrik & B2B RFQ -->
<?php if (is_section_active('facility', 'facility_cta')): ?>
    <section class="section-padding bg-np-green text-white">
        <div class="container text-center max-w-700 mx-auto">
            <h2 class="display-6 fw-bold text-white mb-3">
                <?= htmlspecialchars(get_section_val('facility', 'facility_cta', 'title', 'Rencanakan Audit Fasilitas atau Diskusikan Proyek OEM Anda')) ?>
            </h2>
            <p class="lead mb-4 text-white-50">
                <?= htmlspecialchars(get_section_val('facility', 'facility_cta', 'subtitle', 'Tim Commercial & Engineering kami siap menyambut kunjungan teknis atau meninjau kebutuhan penawaran spesifikasi komponen Anda.')) ?>
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= htmlspecialchars(get_section_val('facility', 'facility_cta', 'button_link', 'contact.php')) ?>" class="btn btn-light px-5 py-3 fw-bold rounded-pill text-np-green shadow">
                    <i class="bi bi-file-earmark-text me-2"></i> <?= htmlspecialchars(get_section_val('facility', 'facility_cta', 'button_text', 'Kirim Pengajuan B2B RFQ')) ?>
                </a>
                <a href="about.php" class="btn btn-outline-light px-4 py-3 fw-bold rounded-pill">
                    <?= htmlspecialchars(__('nav_about', 'Tentang Kami')) ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>