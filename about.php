<?php
require_once __DIR__ . "/config/database.php";

$result = $conn->query("SELECT * FROM company_profile ORDER BY id ASC LIMIT 1");
$company = $result ? $result->fetch_assoc() : [];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars(__('nav_about', 'Tentang Kami')) ?> - <?= htmlspecialchars($company["company_name"] ?? __('brand_name', 'PT Nihon Plast Indonesia')) ?></title>
    <meta name="description" content="<?= htmlspecialchars(get_section_val('about', 'about_dna', 'subtitle', 'PT Nihon Plast Indonesia - Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi, Modul Airbag, dan Komponen Resin.')) ?>">
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "about"; include __DIR__ . "/includes/page_banner.php"; ?>

<!-- 1. Corporate Profile Intro -->
<?php if (is_section_active('about', 'about_dna')): ?>
    <section class="section-padding">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(get_section_val('about', 'about_dna', 'icon', 'bi-shield-check')) ?>"></i> 
                        <?= htmlspecialchars(get_section_val('about', 'about_dna', 'badge_text', 'NIHON PLAST GROUP DNA')) ?>
                    </span>
                    <h1 class="display-6 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(get_section_val('about', 'about_dna', 'title', 'Dedikasi Keselamatan & Presisi Monozukuri Otomotif Dunia')) ?>
                    </h1>
                    <p class="lead text-muted mb-4">
                        <?= htmlspecialchars(get_section_val('about', 'about_dna', 'subtitle', $company["tagline"] ?? 'Driving Safety & Precision Through Japanese Monozukuri')) ?>
                    </p>
                    <p class="text-muted" style="line-height: 1.8;">
                        <?= nl2br(htmlspecialchars(get_section_val('about', 'about_dna', 'content', $company["about_text"] ?? 'PT Nihon Plast Indonesia beroperasi di Kawasan Industri Cikampek, Karawang, Jawa Barat sebagai anak perusahaan dari Nihon Plast Co., Ltd. (Jepang).'))) ?>
                    </p>
                    <?php if (get_section_val('about', 'about_dna', 'button_text')): ?>
                        <div class="mt-4">
                            <a href="<?= htmlspecialchars(get_section_val('about', 'about_dna', 'button_link', 'contact.php')) ?>" class="btn btn-np-primary">
                                <?= htmlspecialchars(get_section_val('about', 'about_dna', 'button_text', __('contact_us', 'Hubungi Kami'))) ?> <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg border">
                        <?php
                        $dnaImg = get_section_val('about', 'about_dna', 'image', 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1200&q=80');
                        $dnaImgSrc = resolve_image_path($dnaImg);
                        ?>
                        <img
                            src="<?= htmlspecialchars($dnaImgSrc) ?>"
                            class="img-fluid w-100"
                            style="height: 420px; object-fit: cover;"
                            alt="PT Nihon Plast Indonesia Plant & Engineering Team"
                            loading="lazy"
                        >
                        <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(180deg, transparent 0%, rgba(0,38,22,0.92) 100%);">
                            <span class="badge bg-success mb-1">Global Tier-1 Supplier</span>
                            <h5 class="text-white fw-bold mb-0">Crafting Safety & Automotive Aesthetics Since 1948</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 2. Vision, Mission & Values -->
<?php if (is_section_active('about', 'about_vision_mission')): ?>
    <section class="section-padding bg-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="eyebrow-badge mx-auto">
                    <i class="bi <?= htmlspecialchars(get_section_val('about', 'about_vision_mission', 'icon', 'bi-compass')) ?>"></i> 
                    <?= htmlspecialchars(get_section_val('about', 'about_vision_mission', 'badge_text', 'CORPORATE FOUNDATION')) ?>
                </span>
                <h2 class="display-6 fw-bold text-np-green mb-2">
                    <?= htmlspecialchars(get_section_val('about', 'about_vision_mission', 'title', 'Visi, Misi & Nilai Fundamental')) ?>
                </h2>
                <p class="text-muted">
                    <?= htmlspecialchars(get_section_val('about', 'about_vision_mission', 'subtitle', 'Prinsip kerja yang memandu setiap inovasi dan lini manufaktur di PT Nihon Plast Indonesia.')) ?>
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <h3 class="h4 fw-bold text-np-green mb-0">Visi Korporat</h3>
                        </div>
                        <p class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($company["vision"] ?? "Menjadi mitra manufaktur otomotif 'One-of-a-Kind' yang tak tergantikan bagi pelanggan melalui penguasaan teknologi unik, dedikasi keselamatan tanpa kompromi, dan produk berkualitas tinggi.")) ?>
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                <i class="bi bi-rocket-takeoff-fill"></i>
                            </div>
                            <h3 class="h4 fw-bold text-np-green mb-0">Misi Perusahaan</h3>
                        </div>
                        <div class="text-muted" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($company["mission"] ?? "1. Menghasilkan produk otomotif yang membanggakan serta memenuhi standar mutu internasional tertinggi (IATF 16949:2016).\n2. Menjalankan filosofi Monozukuri dan Kaizen secara konsisten.\n3. Mematuhi regulasi ketenagakerjaan, K3 (ISO 45001), dan kepedulian lingkungan (ISO 14001).")) ?>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 bg-np-tint text-np-green rounded-3 fs-3">
                                        <i class="bi bi-award-fill"></i>
                                    </div>
                                    <div>
                                        <h3 class="h4 fw-bold text-np-green mb-0">Nilai Inti (Core Values)</h3>
                                        <span class="small text-muted">Budaya Kerja Kami</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-np-tint text-np-green p-3 rounded-pill border border-success-subtle fs-6">
                                        <i class="bi bi-hammer me-1"></i> Monozukuri (Craftsmanship)
                                    </span>
                                    <span class="badge bg-np-tint text-np-green p-3 rounded-pill border border-success-subtle fs-6">
                                        <i class="bi bi-shield-fill-check me-1"></i> Safety First (Anzen Daiichi)
                                    </span>
                                    <span class="badge bg-np-tint text-np-green p-3 rounded-pill border border-success-subtle fs-6">
                                        <i class="bi bi-arrow-repeat me-1"></i> Continuous Kaizen
                                    </span>
                                    <span class="badge bg-np-tint text-np-green p-3 rounded-pill border border-success-subtle fs-6">
                                        <i class="bi bi-people-fill me-1"></i> Integrity & Harmony
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 3. Global Network & Data Matrix -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5">
            <?php if (is_section_active('about', 'about_global_footprint')): ?>
                <div class="col-lg-6">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(get_section_val('about', 'about_global_footprint', 'icon', 'bi-globe-americas')) ?>"></i> 
                        <?= htmlspecialchars(get_section_val('about', 'about_global_footprint', 'badge_text', 'GLOBAL FOOTPRINT')) ?>
                    </span>
                    <h2 class="display-6 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(get_section_val('about', 'about_global_footprint', 'title', 'Jejaring Global Nihon Plast Co., Ltd.')) ?>
                    </h2>
                    <p class="text-muted mb-4">
                        <?= htmlspecialchars(get_section_val('about', 'about_global_footprint', 'subtitle', 'Nihon Plast Co., Ltd. berkantor pusat di Fuji City, Shizuoka Prefecture, Jepang, dan telah melayani industri otomotif dunia sejak 1948 dengan jaringan pabrik manufaktur di berbagai benua:')) ?>
                    </p>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                            <i class="bi bi-geo-alt-fill text-np-green fs-4"></i>
                            <div>
                                <strong>Jepang (Headquarters & R&D Center)</strong>
                                <div class="small text-muted">Fuji Plant, Aoshima Plant, Tomioka Plant</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                            <i class="bi bi-geo-alt-fill text-np-green fs-4"></i>
                            <div>
                                <strong>Asia Pasifik (ASEAN & China)</strong>
                                <div class="small text-muted">Indonesia (Cikampek Plant), Thailand, China (Wuhan & Zhongshan)</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                            <i class="bi bi-geo-alt-fill text-np-green fs-4"></i>
                            <div>
                                <strong>Amerika Utara (North America)</strong>
                                <div class="small text-muted">Amerika Serikat (Ohio) & Mexico (Matamoros & Silao)</div>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (is_section_active('about', 'about_datasheet')): ?>
                <div class="col-lg-6">
                    <span class="eyebrow-badge">
                        <i class="bi <?= htmlspecialchars(get_section_val('about', 'about_datasheet', 'icon', 'bi-info-circle-fill')) ?>"></i> 
                        <?= htmlspecialchars(get_section_val('about', 'about_datasheet', 'badge_text', 'CORPORATE DATASHEET')) ?>
                    </span>
                    <h2 class="display-6 fw-bold text-np-green mb-3">
                        <?= htmlspecialchars(get_section_val('about', 'about_datasheet', 'title', 'Ringkasan Fakta Perusahaan')) ?>
                    </h2>
                    
                    <div class="machinery-table-wrapper">
                        <table class="table machinery-table">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;">Nama Perusahaan</th>
                                    <td><strong><?= htmlspecialchars($company["company_name"] ?? "PT Nihon Plast Indonesia") ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Kepemilikan Saham</th>
                                    <td>Nihon Plast Co., Ltd. (Jepang) - Tier-1 Automotive Group</td>
                                </tr>
                                <tr>
                                    <th>Lokasi Pabrik</th>
                                    <td><?= htmlspecialchars($company["address"] ?? "Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang, Jawa Barat") ?></td>
                                </tr>
                                <tr>
                                    <th>Luas Area Pabrik</th>
                                    <td>Total Lahan: 28.500 m² | Bangunan Produksi: 18.200 m²</td>
                                </tr>
                                <tr>
                                    <th>Lini Produk Utama</th>
                                    <td>Steering Wheel Systems, Airbag Modules, Cockpit & Exterior Trims</td>
                                </tr>
                                <tr>
                                    <th>Sertifikasi Mutu</th>
                                    <td><span class="badge bg-success">IATF 16949:2016</span> <span class="badge bg-success">ISO 14001:2015</span> <span class="badge bg-success">SMK3 / ISO 45001</span></td>
                                </tr>
                                <tr>
                                    <th>Mitra Pelanggan OEM</th>
                                    <td>Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4. CTA to Facility & Quality -->
<?php if (is_section_active('about', 'about_cta')): ?>
    <section class="section-padding bg-np-green text-white">
        <div class="container text-center max-w-700 mx-auto">
            <h2 class="display-6 fw-bold text-white mb-3">
                <?= htmlspecialchars(get_section_val('about', 'about_cta', 'title', 'Ingin Mengetahui Lebih Dalam Mengenai Fasilitas & Kualitas Kami?')) ?>
            </h2>
            <p class="lead mb-4 text-white-50">
                <?= htmlspecialchars(get_section_val('about', 'about_cta', 'subtitle', 'Jelajahi kapabilitas permesinan presisi kami atau tinjau sertifikasi sistem mutu internasional IATF 16949 yang kami miliki.')) ?>
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= htmlspecialchars(get_section_val('about', 'about_cta', 'button_link', 'facility.php')) ?>" class="btn btn-light px-4 py-3 fw-bold rounded-pill text-np-green shadow">
                    <i class="bi bi-cpu me-2"></i> <?= htmlspecialchars(get_section_val('about', 'about_cta', 'button_text', 'Tinjau Fasilitas & Mesin')) ?>
                </a>
                <a href="quality.php" class="btn btn-outline-light px-4 py-3 fw-bold rounded-pill">
                    <i class="bi bi-patch-check me-2"></i> <?= htmlspecialchars(__('nav_quality', 'Standar Mutu & ISO')) ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
