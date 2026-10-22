<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/translation_helpers.php";

$footerComp = null;
if (isset($conn)) {
    $res = $conn->query("SELECT id, company_name, address, phone, email, tagline FROM company_profile WHERE id = 1 LIMIT 1");
    if ($res) {
        $footerComp = $res->fetch_assoc();
        if ($footerComp && function_exists('translate_entity_row')) {
            $footerComp = translate_entity_row('company_profile', $footerComp);
        }
    }
}
$companyName = $footerComp["company_name"] ?? "PT Nihon Plast Indonesia";
$address = $footerComp["address"] ?? "Kawasan Industri Indotaisei Sektor 1A Blok P-1, Cikampek, Karawang 41373, Jawa Barat, Indonesia";
$phone = $footerComp["phone"] ?? "+62-264-351-888";
$email = $footerComp["email"] ?? "corporate@n-plast.co.id";
?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4 justify-content-between">
            <!-- Kolom 1: Profil & Monozukuri -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-np-green text-white p-2 rounded-2"><i class="bi bi-shield-check fs-5"></i></span>
                    <h5 class="mb-0 text-white fw-bold"><?= htmlspecialchars($companyName) ?></h5>
                </div>
                <p class="small text-muted mb-3">
                    <?= htmlspecialchars(__('footer_about', 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Kantung Udara (Airbags), serta Komponen Resin Plastik Interior & Eksterior presisi tinggi dengan standar mutu kelas dunia.')) ?>
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-dark border border-secondary text-white small"><i class="bi bi-patch-check-fill text-success me-1"></i>IATF 16949</span>
                    <span class="badge bg-dark border border-secondary text-white small">ISO 14001</span>
                    <span class="badge bg-dark border border-secondary text-white small">SMK3 / ISO 45001</span>
                </div>
            </div>

            <!-- Kolom 2: Produk & Pilar Bisnis -->
            <div class="col-6 col-md-3 col-lg-2">
                <h5><?= htmlspecialchars(__('footer_products', 'Lini Produk')) ?></h5>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="products.php"><?= htmlspecialchars(__('prod_steering', 'Steering Wheel Systems')) ?></a></li>
                    <li><a href="products.php"><?= htmlspecialchars(__('prod_airbag', 'Airbag Safety Modules')) ?></a></li>
                    <li><a href="products.php"><?= htmlspecialchars(__('prod_interior', 'Cockpit & Interior Resins')) ?></a></li>
                    <li><a href="products.php"><?= htmlspecialchars(__('prod_exterior', 'Exterior & Aero Trims')) ?></a></li>
                    <li><a href="quality.php"><?= htmlspecialchars(__('nav_quality', 'Quality & ISO')) ?></a></li>
                </ul>
            </div>

            <!-- Kolom 3: Navigasi Cepat -->
            <div class="col-6 col-md-3 col-lg-2">
                <h5><?= htmlspecialchars(__('footer_quick_links', 'Tautan Cepat')) ?></h5>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li><a href="about.php"><?= htmlspecialchars(__('nav_about', 'About')) ?></a></li>
                    <li><a href="quality.php"><?= htmlspecialchars(__('nav_quality', 'Quality & ISO')) ?></a></li>
                    <li><a href="facility.php"><?= htmlspecialchars(__('nav_facility', 'Facility')) ?></a></li>
                    <li><a href="gallery.php"><?= htmlspecialchars(__('nav_gallery', 'Gallery')) ?></a></li>
                    <li><a href="news.php"><?= htmlspecialchars(__('nav_news', 'News')) ?></a></li>
                    <li><a href="career.php"><?= htmlspecialchars(__('nav_career', 'Career')) ?></a></li>
                </ul>
            </div>

            <!-- Kolom 4: Lokasi Pabrik & Kontak -->
            <div class="col-md-6 col-lg-3">
                <h5><?= htmlspecialchars(__('footer_contact_info', 'Fasilitas Pabrik')) ?></h5>
                <p class="small text-muted mb-2">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                    <?= nl2br(htmlspecialchars($address)) ?>
                </p>
                <p class="small text-muted mb-1">
                    <i class="bi bi-telephone-fill text-primary me-1"></i> <?= htmlspecialchars($phone) ?>
                </p>
                <p class="small text-muted mb-0">
                    <i class="bi bi-envelope-fill text-primary me-1"></i> <?= htmlspecialchars($email) ?>
                </p>
            </div>
        </div>

        <!-- Bagian Bawah Footer -->
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                &copy; <?= date("Y") ?> <?= htmlspecialchars($companyName) ?>. <?= htmlspecialchars(__('all_rights_reserved', 'All rights reserved.')) ?>
            </div>
            <div class="small text-muted">
                <?= htmlspecialchars(__('footer_tagline', 'Automotive Safety & Precision Monozukuri • Member of Nihon Plast Group (Japan)')) ?>
            </div>
        </div>
    </div>
</footer>

<!-- Core JavaScript Libraries (100% Free & Open Source) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize AOS (Animate on Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            once: true,
            duration: 750,
            easing: 'ease-out-cubic',
            offset: 40
        });
    }

    // 2. Global Toast Helper using SweetAlert2
    window.showToast = function(icon, title) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({
                icon: icon || 'success',
                title: title || ''
            });
        }
    };
});
</script>

