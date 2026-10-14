<?php
require_once __DIR__ . "/config/database.php";

$certList = $conn->query("SELECT * FROM certifications WHERE status = 'active' ORDER BY sort_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Quality & ISO Certifications - PT Nihon Plast Indonesia</title>
    <meta name="description" content="Standar Mutu IATF 16949, ISO 9001, ISO 14001, ISO 45001 / SMK3, dan Kepatuhan Regulasi Pemerintah Indonesia di PT Nihon Plast Indonesia.">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "quality"; include __DIR__ . "/includes/page_banner.php"; ?>

<!-- 1. FILOSOFI MUTU & PENDAHULUAN -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="eyebrow-badge">
                    <i class="bi bi-shield-check"></i> ZERO DEFECT PHILOSOPHY
                </div>
                <h2 class="display-6 fw-bold text-np-green mb-3">
                    Dedikasi Mutu Tanpa Kompromi untuk Keselamatan Pengemudi
                </h2>
                <p class="text-muted" style="line-height: 1.8;">
                    Sebagai pemasok otomotif Tier-1 global, PT Nihon Plast Indonesia memproduksi komponen vital seperti roda kemudi dan modul airbag di mana keandalan produk berdampak langsung pada nyawa manusia. Oleh karena itu, prinsip <strong>"Quality First"</strong> dan <strong>"Zero Defect"</strong> ditanamkan ke dalam setiap tahap rekayasa, pencetakan resin, hingga perakitan akhir.
                </p>
                <div class="p-3 bg-light rounded-3 border-start border-4 border-primary mt-4">
                    <p class="mb-0 fst-italic text-dark">
                        "誇れる製品をつくり、顧客に奉仕し、社会に貢献する"<br>
                        <em>(Selalu menciptakan produk yang membanggakan, melayani pelanggan, dan berkontribusi kepada masyarakat).</em>
                    </p>
                </div>
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

<!-- 2. DAFTAR SERTIFIKAT RESMI (DYNAMIC DARI DATABASE) -->
<section class="section-padding section-light">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="eyebrow-badge mx-auto">
                <i class="bi bi-award-fill"></i> ACCREDITED CERTIFICATIONS
            </div>
            <h2 class="h1 fw-bold text-np-green">Sertifikasi & Akreditasi Resmi</h2>
            <p class="text-muted">Seluruh fasilitas dan proses manufaktur kami diaudit secara berkala oleh badan sertifikasi independen internasional terkemuka.</p>
        </div>

        <div class="row g-4">
            <?php if ($certList && $certList->num_rows > 0): ?>
                <?php while ($cert = $certList->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="cert-card <?= str_contains($cert['name'], 'IATF') ? 'featured-iatf' : '' ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="cert-card-icon">
                                    <i class="bi bi-award"></i>
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

<!-- 3. KEPATUHAN REGULASI PEMERINTAH INDONESIA -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="eyebrow-badge">
                    <i class="bi bi-shield-check"></i> INDONESIAN REGULATORY COMPLIANCE
                </div>
                <h2 class="h1 fw-bold text-np-green mb-3">
                    Kepatuhan Terhadap Hukum & Regulasi Republik Indonesia
                </h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    PT Nihon Plast Indonesia senantiasa patuh terhadap undang-undang ketenagakerjaan, kesehatan & keselamatan kerja, perlindungan lingkungan hidup, serta standar industri nasional yang ditetapkan pemerintah:
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="badge bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-check-lg fs-5"></i></div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-1">SMK3 (Peraturan Pemerintah No. 50 Tahun 2012)</h6>
                            <p class="small text-muted mb-0">Penerapan wajib Sistem Manajemen K3 Kemnaker RI dengan audit berkala, pelatihan tanggap darurat, dan fasilitas APD berstandar penuh.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="badge bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-check-lg fs-5"></i></div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-1">Pengelolaan Lingkungan & Limbah B3 (KLHK RI)</h6>
                            <p class="small text-muted mb-0">Izin TPS Limbah B3 resmi, dokumen lingkungan AMDAL / UKL-UPL terverifikasi, dan kepatuhan baku mutu emisi pabrik.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="badge bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-check-lg fs-5"></i></div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-1">TKDN & SNI Otomotif (Kementerian Perindustrian)</h6>
                            <p class="small text-muted mb-0">Mendukung peningkatan penggunaan komponen produksi dalam negeri dan pemenuhan spesifikasi teknis kendaraan bermotor nasional.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-lg-1">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1000&q=80" alt="Testing lab" class="img-fluid" style="height: 400px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. EMPAT PILAR SISTEM KUALITAS PABRIK -->
<section class="section-padding section-light">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <div class="eyebrow-badge mx-auto">
                <i class="bi bi-diagram-3-fill"></i> QUALITY FRAMEWORK
            </div>
            <h2 class="h1 fw-bold text-np-green">Sistem Pengendalian Mutu Menyeluruh</h2>
            <p class="text-muted">Empat pilar operasional yang memastikan setiap komponen memenuhi ekspektasi OEM otomotif terkemuka.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="text-np-green fs-1 mb-2"><i class="bi bi-check2-circle"></i></div>
                    <h5 class="fw-bold text-np-green">Quality Assurance (QA)</h5>
                    <p class="small text-muted mb-0">Perencanaan kualitas matang mengadopsi metodologi APQP, FMEA, PPAP, dan MSA untuk mencegah potensi kecacatan sejak fase desain mold.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="text-np-green fs-1 mb-2"><i class="bi bi-sliders"></i></div>
                    <h5 class="fw-bold text-np-green">Quality Control (QC)</h5>
                    <p class="small text-muted mb-0">Inspeksi terintegrasi pada setiap stasiun kerja menggunakan sensor otomatis, CMM 3D scanning, dan inspeksi visual 100%.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="text-np-green fs-1 mb-2"><i class="bi bi-arrow-repeat"></i></div>
                    <h5 class="fw-bold text-np-green">Kaizen & 5S Culture</h5>
                    <p class="small text-muted mb-0">Budaya perbaikan berkelanjutan (Kaizen) dan penerapan 5S di lantai produksi untuk efisiensi, kerapihan, dan disiplin tinggi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
                    <div class="text-np-green fs-1 mb-2"><i class="bi bi-heart-pulse-fill"></i></div>
                    <h5 class="fw-bold text-np-green">EHS & Safety First</h5>
                    <p class="small text-muted mb-0">Pencegahan kecelakaan kerja secara proaktif dan konservasi energi pabrik untuk keberlanjutan masa depan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>