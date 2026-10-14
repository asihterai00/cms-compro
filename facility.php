<?php
require_once __DIR__ . "/config/database.php";

// Fetch dynamic machinery matrix from database
$machineryQuery = $conn->query("SELECT * FROM facility_machinery WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
$machineryRows = [];
if ($machineryQuery) {
    while ($m = $machineryQuery->fetch_assoc()) {
        $machineryRows[] = $m;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars(__('nav_facility', 'Fasilitas & Teknologi')) ?> - <?= htmlspecialchars(__('brand_name', 'PT Nihon Plast Indonesia')) ?></title>
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
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="text-np-green fw-bold fs-4">28.500 m²</div>
                                <div class="small text-muted fw-semibold">Total Luas Lahan Pabrik</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="text-np-green fw-bold fs-4">18.200 m²</div>
                                <div class="small text-muted fw-semibold">Luas Bangunan Produksi</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="text-np-green fw-bold fs-4">120.000 / Bln</div>
                                <div class="small text-muted fw-semibold">Kapasitas Steering Wheel</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="text-np-green fw-bold fs-4">95.000 / Bln</div>
                                <div class="small text-muted fw-semibold">Kapasitas Modul Airbag</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg border">
                        <?php
                        $plantImg = get_section_val('facility', 'facility_plant_overview', 'image', 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1200&q=80');
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
                <!-- Cell 1: Injection Molding -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=800&q=80"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="Precision Injection Molding Machine"
                            loading="lazy"
                        >
                        <div class="card-body p-4">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Injeksi Resin Presisi</span>
                            <h4 class="h5 fw-bold mb-2">High-Tonnage Injection Molding</h4>
                            <p class="small text-muted mb-3">
                                Armada 22 mesin injeksi plastik berkisar dari <strong>150-ton hingga 2.500-ton</strong> yang dilengkapi servo-robotik otomatis untuk mencetak komponen presisi tinggi dari material PP, ABS, PC, dan PA66.
                            </p>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Multi-cavity mold compatibility</li>
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Gas-assist injection capability</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cell 2: Cleanroom Safety Assembly -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="Cleanroom Airbag Assembly"
                            loading="lazy"
                        >
                        <div class="card-body p-4">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Cleanroom Class 8</span>
                            <h4 class="h5 fw-bold mb-2">Modul Airbag Cleanroom Assembly</h4>
                            <p class="small text-muted mb-3">
                                Area perakitan steril berstandar <strong>ISO 14644-1 Class 8</strong> dengan kontrol partikel mikro dan electrostatic discharge (ESD) terproteksi penuh.
                            </p>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Traceability barcode 100% individual</li>
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Automatic torque recording system</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cell 3: Leather Wrapping Craftsmanship -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="Genuine Leather Steering Wheel Wrapping"
                            loading="lazy"
                        >
                        <div class="card-body p-4">
                            <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2">Takumi Craftsmanship</span>
                            <h4 class="h5 fw-bold mb-2">Leather & Urethane Steering Cell</h4>
                            <p class="small text-muted mb-3">
                                Keahlian pembungkusan kulit asli dan sintetis berteknologi jahitan tangan presisi dipadukan dengan busa polyurethane molding berdensitas tinggi.
                            </p>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Euro-stitch, Cross-stitch, Baseball-stitch</li>
                                <li><i class="bi bi-check2-circle text-success me-1"></i> Integrated steering heater option</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cell 4: Ultrasonic & Vibration Welding -->
                <div class="col-lg-6 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <h4 class="h5 fw-bold mb-0">Automated Vibration & Ultrasonic Welding</h4>
                                <span class="small text-muted">Pengelasan Frekuensi Tinggi Komponen Interior</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Stasiun pengelasan otomatis bergetar dan ultrasonik untuk merekatkan modul saluran udara (air ducts), penutup airbag, dan konsol tengah dengan kekuatan rekat setara material cetak utuh tanpa penggunaan lem kimiawi beracun.
                        </p>
                    </div>
                </div>

                <!-- Cell 5: Polyurethane Foaming -->
                <div class="col-lg-6 col-md-12">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                <i class="bi bi-shield-shaded"></i>
                            </div>
                            <div>
                                <h4 class="h5 fw-bold mb-0">High-Pressure Polyurethane (PU) Foaming</h4>
                                <span class="small text-muted">Inti Rangka Kemudi & Komponen Peredam</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Sistem injeksi busa poliuretan bertekanan tinggi pada rangka inti logam roda kemudi, menjamin daya redam getaran berkendara yang optimal dan keselamatan deselerasi tabrakan pengemudi.
                        </p>
                    </div>
                </div>
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
                <div class="col-md-6 col-lg-3">
                    <div class="cleanroom-card h-100 p-4 bg-white rounded-4 border">
                        <div class="cleanroom-badge mb-2"><i class="bi bi-rulers me-1"></i>3D Metrology</div>
                        <h5 class="fw-bold mb-2">CMM Geometric Lab</h5>
                        <p class="small text-muted mb-0">Mesin pengukur koordinat 3 dimensi beresolusi 0,001 mm (mikron) untuk validasi cetakan mold presisi tinggi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="cleanroom-card h-100 p-4 bg-white rounded-4 border">
                        <div class="cleanroom-badge mb-2"><i class="bi bi-thermometer-half me-1"></i>Thermal Stress</div>
                        <h5 class="fw-bold mb-2">Chamber Suhu Ekstrim</h5>
                        <p class="small text-muted mb-0">Ruang simulasi termal dengan rentang suhu dari <strong>-40°C hingga +120°C</strong> serta kelembaban 95% RH untuk uji penuaan material.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="cleanroom-card h-100 p-4 bg-white rounded-4 border">
                        <div class="cleanroom-badge mb-2"><i class="bi bi-speedometer2 me-1"></i>Safety Inflation</div>
                        <h5 class="fw-bold mb-2">High-Speed Sensor Deployment Lab</h5>
                        <p class="small text-muted mb-0">Sensor optik presisi tinggi untuk menganalisis kurva tekanan inflasi kantung udara dalam 30 milidetik.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="cleanroom-card h-100 p-4 bg-white rounded-4 border">
                        <div class="cleanroom-badge mb-2"><i class="bi bi-hammer me-1"></i>Mechanical Strength</div>
                        <h5 class="fw-bold mb-2">Drop Tower & Tensile Rig</h5>
                        <p class="small text-muted mb-0">Alat uji impak jatuh bebas dan kekuatan tarik sambungan kulit untuk menjamin daya tahan mekanik roda kemudi saat terjadi benturan.</p>
                    </div>
                </div>
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