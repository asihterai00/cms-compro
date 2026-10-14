<?php
require_once __DIR__ . "/config/database.php";

$success = "";
$error = "";

// Fetch company profile
$companyRes = $conn->query("SELECT * FROM company_profile WHERE id = 1 LIMIT 1");
$company = $companyRes ? $companyRes->fetch_assoc() : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $companyName = trim($_POST["company_name"] ?? "");
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $category = trim($_POST["category"] ?? "B2B Sourcing & RFQ");
    $productLine = trim($_POST["product_line"] ?? "General");
    $userMsg = trim($_POST["message"] ?? "");

    if ($name !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) && $userMsg !== "") {
        // Format structured B2B inquiry message
        $formattedMessage = "=== B2B INQUIRY & RFQ ===\n" .
            "Company / Entity: " . ($companyName ?: "-") . "\n" .
            "Inquiry Category: " . $category . "\n" .
            "Component Interest: " . $productLine . "\n" .
            "-----------------------------------\n" .
            $userMsg;

        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, message, status) VALUES (?, ?, ?, 'unread')"
        );
        $stmt->bind_param("sss", $name, $email, $formattedMessage);

        if ($stmt->execute()) {
            $success = "Terima kasih. Permintaan B2B RFQ Anda telah berhasil dikirimkan ke tim Commercial & Engineering PT Nihon Plast Indonesia. Perwakilan kami akan segera menghubungi Anda.";
        } else {
            $error = "Terjadi kendala sistem saat mengirim pesan. Silakan coba kembali atau hubungi via telepon.";
        }
    } else {
        $error = "Mohon lengkapi nama, alamat email valid, dan isi pesan kebutuhan Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>Kontak & B2B RFQ - PT Nihon Plast Indonesia</title>
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>
<?php $bannerKey = "contact"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">
    <div class="container">
        <?php
        $leftActive = is_section_active('contact', 'contact_liaison_intro') || is_section_active('contact', 'contact_info_card') || is_section_active('contact', 'contact_download_deck');
        $rfqActive = is_section_active('contact', 'contact_rfq_form_intro');
        $leftColClass = $rfqActive ? "col-lg-5" : "col-lg-8 mx-auto";
        $rfqColClass = $leftActive ? "col-lg-7" : "col-lg-8 mx-auto";
        ?>
        <?php if ($leftActive || $rfqActive): ?>
        <div class="row g-5">
            <?php if ($leftActive): ?>
            <!-- Kolom Kiri: Informasi Kantor & B2B Liaison -->
            <div class="<?= $leftColClass ?>">
                <?php if (is_section_active('contact', 'contact_liaison_intro')): ?>
                <span class="eyebrow-badge">
                    <i class="bi <?= htmlspecialchars(get_section_val('contact', 'contact_liaison_intro', 'icon', 'bi-briefcase-fill')) ?>"></i>
                    <?= htmlspecialchars(get_section_val('contact', 'contact_liaison_intro', 'badge_text', 'COMMERCIAL & ENGINEERING LIAISON')) ?>
                </span>
                <h2 class="display-6 fw-bold text-np-green mb-3">
                    <?= htmlspecialchars(get_section_val('contact', 'contact_liaison_intro', 'title', 'Bermitra dengan PT Nihon Plast Indonesia')) ?>
                </h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    <?= nl2br(htmlspecialchars(get_section_val('contact', 'contact_liaison_intro', 'subtitle', 'Kami siap melayani kebutuhan OEM APM, perancangan prototipe teknis, serta audit fasilitas untuk komponen roda kemudi, modul airbag, dan komponen resin interior presisi.'))) ?>
                </p>
                <?php endif; ?>

                <?php if (is_section_active('contact', 'contact_info_card')): ?>
                <!-- Box Alamat Resmi -->
                <div class="p-4 bg-light rounded-4 border mb-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="p-2 bg-np-green text-white rounded-3 fs-5">
                            <i class="bi <?= htmlspecialchars(get_section_val('contact', 'contact_info_card', 'icon', 'bi-geo-alt-fill')) ?>"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-1"><?= htmlspecialchars(get_section_val('contact', 'contact_info_card', 'title', 'Lokasi Pabrik & Kantor Pusat')) ?></h6>
                            <p class="small text-muted mb-0">
                                <?= nl2br(htmlspecialchars($company["address"] ?? "Kawasan Industri Indotaisei Sektor 1A Blok B-3, Desa Kalihurip, Kec. Cikampek, Kab. Karawang, Jawa Barat 41373, Indonesia")) ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="p-2 bg-np-green text-white rounded-3 fs-5">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-0">Telepon / Fax</h6>
                            <p class="small text-muted mb-0">
                                <?= htmlspecialchars($company["phone"] ?? "+62 (264) 351-470 / +62 (264) 351-471") ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 bg-np-green text-white rounded-3 fs-5">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-np-green mb-0">Email Resmi</h6>
                            <p class="small text-muted mb-0">
                                <?= htmlspecialchars($company["email"] ?? "info@n-plast.co.id") ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (is_section_active('contact', 'contact_download_deck')): ?>
                <!-- Box Download Profil & WhatsApp Hotline -->
                <div class="p-4 bg-white rounded-4 border shadow-sm" id="download-profile">
                    <h5 class="fw-bold text-np-green mb-2">
                        <i class="bi <?= htmlspecialchars(get_section_val('contact', 'contact_download_deck', 'icon', 'bi-file-earmark-pdf-fill')) ?> text-danger me-1"></i>
                        <?= htmlspecialchars(get_section_val('contact', 'contact_download_deck', 'title', 'Company Profile & Capability Deck')) ?>
                    </h5>
                    <p class="small text-muted mb-3">
                        <?= htmlspecialchars(get_section_val('contact', 'contact_download_deck', 'subtitle', 'Unduh rangkuman profil korporat, data mesin injeksi plastik, kapasitas produksi, dan sertifikasi IATF 16949 / ISO 14001 dalam format PDF resmi.')) ?>
                    </p>
                    <a href="<?= htmlspecialchars(get_section_val('contact', 'contact_download_deck', 'button_link', 'about.php')) ?>" class="btn btn-outline-success w-100 fw-bold rounded-pill mb-2">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i> <?= htmlspecialchars(get_section_val('contact', 'contact_download_deck', 'button_text', 'Unduh Company Profile (PDF / Deck)')) ?>
                    </a>
                    <a href="https://wa.me/6281234567890?text=Halo%20PT%20Nihon%20Plast%20Indonesia,%20saya%20ingin%20berkonsultasi%20mengenai%20kebutuhan%20komponen%20otomotif" target="_blank" rel="noopener noreferrer" class="btn btn-success w-100 fw-bold rounded-pill">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp Commercial Hotline
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($rfqActive): ?>
            <!-- Kolom Kanan: Form B2B RFQ -->
            <div class="<?= $rfqColClass ?>">
                <div class="rfq-box">
                    <span class="badge bg-np-tint text-np-green border border-success-subtle mb-2 px-3 py-2 fw-bold">
                        <i class="bi <?= htmlspecialchars(get_section_val('contact', 'contact_rfq_form_intro', 'icon', 'bi-send-check-fill')) ?> me-1"></i>
                        <?= htmlspecialchars(get_section_val('contact', 'contact_rfq_form_intro', 'badge_text', 'FORMULIR PENGAJUAN B2B RFQ')) ?>
                    </span>
                    <h3 class="h4 fw-bold text-np-green mb-2">
                        <?= htmlspecialchars(get_section_val('contact', 'contact_rfq_form_intro', 'title', 'Formulir Permintaan Penawaran & Konsultasi')) ?>
                    </h3>
                    <p class="small text-muted mb-4">
                        <?= htmlspecialchars(get_section_val('contact', 'contact_rfq_form_intro', 'subtitle', 'Silakan lengkapi formulir di bawah ini. Tim kami akan merespons dalam waktu 1x24 jam kerja.')) ?>
                    </p>

                    <?php if ($success): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div><?= htmlspecialchars($success) ?></div>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'B2B RFQ Terkirim',
                                    text: <?= json_encode($success) ?>,
                                    confirmButtonText: 'Tutup'
                                });
                            }
                        });
                        </script>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <div><?= htmlspecialchars($error) ?></div>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kendala Pengiriman',
                                    text: <?= json_encode($error) ?>,
                                    confirmButtonText: 'Periksa Kembali'
                                });
                            }
                        });
                        </script>
                    <?php endif; ?>

                    <form method="post" action="contact.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Perusahaan / APM OEM</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Contoh: PT Astra Daihatsu Motor">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap PIC <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Perusahaan / Resmi <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="pic@perusahaan.co.id" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori Kebutuhan</label>
                                <select name="category" class="form-select">
                                    <option value="B2B OEM Sourcing & RFQ">B2B OEM Sourcing & RFQ</option>
                                    <option value="Technical & Material Consultation">Konsultasi Teknik & Material</option>
                                    <option value="Plant Audit / Factory Visit">Pengajuan Audit / Kunjungan Pabrik</option>
                                    <option value="Vendor / Supplier Registration">Registrasi Vendor / Supplier</option>
                                    <option value="General Inquiry">Pertanyaan Umum</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Lini Produk Terkait</label>
                                <select name="product_line" class="form-select">
                                    <option value="Steering Wheel Systems">Steering Wheel Systems (Roda Kemudi)</option>
                                    <option value="Airbag Safety Modules">Airbag Safety Modules (Modul Kantung Udara)</option>
                                    <option value="Cockpit & Interior Resins">Cockpit & Interior Resin Parts (Panel Instrumen, Konsol)</option>
                                    <option value="Exterior & Aero Trims">Exterior & Aero Trims (Cowl Top, Mudguard)</option>
                                    <option value="All / Full Vehicle Cabin System">Semua Lini Produk / Kustom OEM</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Rincian Kebutuhan / Spesifikasi Proyek <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Tuliskan estimasi volume, spesifikasi teknis, atau jadwal audit yang diinginkan..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-np-primary w-100 py-3 fw-bold justify-content-center">
                                    <i class="bi bi-send-fill me-2"></i> Kirimkan Permintaan RFQ
                                </button>
                                <p class="small text-muted text-center mt-2 mb-0">
                                    <i class="bi bi-shield-lock me-1"></i> Data dan spesifikasi proyek Anda dijamin kerahasiaannya di bawah NDA (Non-Disclosure Agreement).
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if (is_section_active('contact', 'contact_map_section')): ?>
        <!-- Peta Pabrik (OpenStreetMap 100% Free & Open Source) -->
        <div class="mt-5 pt-3">
            <div class="p-3 bg-white rounded-4 border shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-np-green mb-0">
                        <i class="bi <?= htmlspecialchars(get_section_val('contact', 'contact_map_section', 'icon', 'bi-map-fill')) ?> me-2"></i>
                        <?= htmlspecialchars(get_section_val('contact', 'contact_map_section', 'title', 'Peta Lokasi Pabrik: Kawasan Industri Indotaisei Cikampek')) ?>
                    </h5>
                    <span class="badge bg-light text-dark border">
                        <?= htmlspecialchars(get_section_val('contact', 'contact_map_section', 'badge_text', 'PLANT NAVIGATION MAP')) ?>
                    </span>
                </div>
                <p class="small text-muted mb-3">
                    <?= htmlspecialchars(get_section_val('contact', 'contact_map_section', 'subtitle', 'Akses strategis jalan tol Jakarta-Cikampek KM 68 Gerbang Tol Kalihurip Utama.')) ?>
                </p>
                <div class="ratio ratio-21x9 rounded-3 overflow-hidden border">
                    <iframe
                        src="https://www.openstreetmap.org/export/embed.html?bbox=107.4000%2C-6.4350%2C107.4300%2C-6.4150&amp;layer=mapnik&amp;marker=-6.4253%2C107.4121"
                        style="border: 0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi PT Nihon Plast Indonesia di Kawasan Industri Indotaisei Cikampek"
                    ></iframe>
                </div>
                <div class="text-end mt-2">
                    <a href="https://www.openstreetmap.org/?mlat=-6.4253&amp;mlon=107.4121#map=15/-6.4253/107.4121" target="_blank" rel="noopener noreferrer" class="small text-muted">
                        Buka di OpenStreetMap Lengkap ↗
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>