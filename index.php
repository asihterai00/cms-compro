<?php
require_once __DIR__ . "/config/database.php";

/*
 * HOME PAGE PT NIHON PLAST INDONESIA
 * Semua bagian utama ditampilkan berurutan saat pengunjung menggulir ke bawah.
 * Data Company Profile, Products, Gallery, dan News diambil dari database.
 */

$companyResult = $conn->query(
    "SELECT * FROM company_profile ORDER BY id ASC LIMIT 1"
);
$company = $companyResult ? $companyResult->fetch_assoc() : [];

$products = $conn->query(
    "SELECT * FROM products ORDER BY id DESC LIMIT 3"
);

$news = $conn->query(
    "SELECT * FROM news ORDER BY published_at DESC LIMIT 3"
);

$galleryItems = $conn->query(
    "SELECT id, title, image FROM gallery ORDER BY id DESC LIMIT 6"
);

/* Membuat path gambar produk konsisten, termasuk jika database menyimpan nama file saja. */
function homeImagePath($path, $folder = "") {
    $path = trim((string) $path);

    if ($path === "") {
        return "";
    }

    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }

    if (strpos($path, "uploads/") === 0) {
        return $path;
    }

    if ($folder !== "") {
        return rtrim($folder, "/") . "/" . basename($path);
    }

    return $path;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title><?= htmlspecialchars($company["company_name"] ?? "PT Nihon Plast Indonesia") ?></title>
    <meta name="description" content="Company profile PT Nihon Plast Indonesia. Automotive manufacturing, products, facilities, quality, career, and company news.">
    <link rel="stylesheet" href="assets/css/home-scroll.css">
</head>
<body>
<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "home"; include __DIR__ . "/includes/page_banner.php"; ?>

<!-- Tombol pintas ke bagian utama di halaman Home -->
<nav class="home-section-nav" aria-label="Navigasi bagian Home">
    <div class="container">
        <a href="#about-home">About</a>
        <a href="#products-home">Products</a>
        <a href="#gallery-home">Gallery</a>
        <a href="#quality-home">Quality</a>
        <a href="#facility-home">Facility</a>
        <a href="#career-home">Career</a>
        <a href="#news-home">News</a>
        <a href="#contact-home">Contact</a>
    </div>
</nav>

<!-- 1. ABOUT COMPANY -->
<section id="about-home" class="home-section section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-7">
                <p class="eyebrow">ABOUT COMPANY</p>
                <h2 class="home-section-title">
                    <?= htmlspecialchars($company["company_name"] ?? "PT Nihon Plast Indonesia") ?>
                </h2>
                <p class="home-lead">
                    <?= nl2br(htmlspecialchars($company["short_description"] ?? "Driving Quality Through Automotive Manufacturing")) ?>
                </p>
                <p>
                    <?= nl2br(htmlspecialchars($company["about_text"] ?? "")) ?>
                </p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="about.php" class="btn btn-dark">Lihat Profil Perusahaan</a>
                    <a href="contact.php" class="btn btn-outline-dark">Hubungi Kami</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="home-contact-card">
                    <p class="eyebrow">CONTACT INFORMATION</p>
                    <h3 class="h5 fw-bold mb-3">Informasi Perusahaan</h3>
                    <p><strong>Alamat</strong><br><?= nl2br(htmlspecialchars($company["address"] ?? "Jalan Raya Tambun, Bekasi, Jawa Barat, Indonesia")) ?></p>
                    <p><strong>Telepon</strong><br><?= htmlspecialchars($company["phone"] ?? "") ?></p>
                    <p class="mb-0"><strong>Email</strong><br><?= htmlspecialchars($company["email"] ?? "") ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. PRODUCTS: data diambil dari menu Admin Products -->
<section id="products-home" class="home-section section-padding home-section-light">
    <div class="container">
        <div class="home-section-heading">
            <p class="eyebrow">OUR PRODUCTS</p>
            <h2 class="home-section-title">Produk Kami</h2>
            <p>Produk komponen otomotif dengan fokus pada kualitas dan presisi.</p>
        </div>
        <div class="row g-4">
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <?php $productImage = homeImagePath($product["image"] ?? "", "uploads/products"); ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="card home-content-card h-100">
                            <?php if ($productImage !== ""): ?>
                                <img src="<?= htmlspecialchars($productImage) ?>"
                                     class="home-card-image"
                                     alt="<?= htmlspecialchars($product["name"] ?? "Product") ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="home-image-placeholder"><span>Product Image</span></div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold"><?= htmlspecialchars($product["name"] ?? "") ?></h3>
                                <p class="mb-0"><?= htmlspecialchars($product["description"] ?? "") ?></p>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-center">Belum ada produk. Tambahkan produk melalui Admin Panel.</p></div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-outline-dark">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- 3. GALLERY: data diambil dari menu Admin Gallery -->
<section id="gallery-home" class="home-section section-padding">
    <div class="container">
        <div class="home-section-heading">
            <p class="eyebrow">COMPANY GALLERY</p>
            <h2 class="home-section-title">Galeri Perusahaan</h2>
            <p>Dokumentasi fasilitas dan aktivitas perusahaan.</p>
        </div>
        <div class="row g-4">
            <?php if ($galleryItems && $galleryItems->num_rows > 0): ?>
                <?php while ($item = $galleryItems->fetch_assoc()): ?>
                    <?php $galleryImage = homeImagePath($item["image"] ?? "", "uploads/gallery"); ?>
                    <div class="col-6 col-lg-4">
                        <article class="home-gallery-card">
                            <?php if ($galleryImage !== ""): ?>
                                <img src="<?= htmlspecialchars($galleryImage) ?>"
                                     alt="<?= htmlspecialchars($item["title"] ?? "Company gallery") ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="home-image-placeholder"><span>Gallery Image</span></div>
                            <?php endif; ?>
                            <div class="home-gallery-caption">
                                <?= htmlspecialchars($item["title"] ?? "") ?>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-center">Belum ada foto galeri. Tambahkan melalui Admin Panel.</p></div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="gallery.php" class="btn btn-outline-dark">Lihat Semua Gallery</a>
        </div>
    </div>
</section>

<!-- 4. QUALITY -->
<section id="quality-home" class="home-section section-padding home-section-dark">
    <div class="container">
        <div class="home-section-heading">
            <p class="eyebrow">QUALITY & IMPROVEMENT</p>
            <h2 class="home-section-title">Komitmen terhadap Kualitas</h2>
            <p>Menjaga kualitas proses dan produk melalui standar kerja, pemeriksaan, serta peningkatan berkelanjutan.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="home-feature-card">
                    <span class="home-feature-number">01</span>
                    <h3 class="h5 fw-bold">Quality Assurance</h3>
                    <p class="mb-0">Mendukung konsistensi kualitas melalui proses kerja yang terencana.</p>
                </article>
            </div>
            <div class="col-md-4">
                <article class="home-feature-card">
                    <span class="home-feature-number">02</span>
                    <h3 class="h5 fw-bold">Quality Control</h3>
                    <p class="mb-0">Pemeriksaan produk dan proses untuk membantu memenuhi persyaratan kualitas.</p>
                </article>
            </div>
            <div class="col-md-4">
                <article class="home-feature-card">
                    <span class="home-feature-number">03</span>
                    <h3 class="h5 fw-bold">Continuous Improvement</h3>
                    <p class="mb-0">Mendorong perbaikan berkelanjutan, keselamatan, dan efisiensi.</p>
                </article>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="quality.php" class="btn btn-light">Pelajari Quality</a>
        </div>
    </div>
</section>

<!-- 5. FACILITY -->
<section id="facility-home" class="home-section section-padding home-section-light">
    <div class="container">
        <div class="home-section-heading">
            <p class="eyebrow">OUR FACILITIES</p>
            <h2 class="home-section-title">Fasilitas Perusahaan</h2>
            <p>Gambaran area dan aktivitas pendukung proses manufaktur.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="home-content-card h-100">
                    <img class="home-card-image"
                         src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=900&q=80"
                         alt="Engineering facility" loading="lazy">
                    <div class="p-4">
                        <h3 class="h5 fw-bold">Engineering</h3>
                        <p class="mb-0">Dukungan engineering untuk kebutuhan teknis dan proses manufaktur.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="home-content-card h-100">
                    <img class="home-card-image"
                         src="https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=900&q=80"
                         alt="Production facility" loading="lazy">
                    <div class="p-4">
                        <h3 class="h5 fw-bold">Production</h3>
                        <p class="mb-0">Area produksi sebagai bagian dari kegiatan manufaktur.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="home-content-card h-100">
                    <img class="home-card-image"
                         src="https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=900&q=80"
                         alt="Quality inspection" loading="lazy">
                    <div class="p-4">
                        <h3 class="h5 fw-bold">Quality</h3>
                        <p class="mb-0">Pemeriksaan dan pemantauan kualitas produk serta proses.</p>
                    </div>
                </article>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="facility.php" class="btn btn-outline-dark">Lihat Fasilitas</a>
        </div>
    </div>
</section>

<!-- 6. CAREER -->
<section id="career-home" class="home-section section-padding">
    <div class="container">
        <div class="home-career-panel">
            <div>
                <p class="eyebrow">CAREER OPPORTUNITIES</p>
                <h2 class="home-section-title">Berkembang Bersama Kami</h2>
                <p class="mb-0">Jelajahi peluang karier dan hubungi tim kami untuk informasi lebih lanjut.</p>
            </div>
            <a href="career.php" class="btn btn-dark">Lihat Karier</a>
        </div>
    </div>
</section>

<!-- 7. NEWS: data diambil dari menu Admin News -->
<section id="news-home" class="home-section section-padding home-section-light">
    <div class="container">
        <div class="home-section-heading">
            <p class="eyebrow">LATEST NEWS</p>
            <h2 class="home-section-title">Berita dan Informasi</h2>
            <p>Informasi terbaru yang diterbitkan melalui Admin Panel.</p>
        </div>
        <div class="row g-4">
            <?php if ($news && $news->num_rows > 0): ?>
                <?php while ($item = $news->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="home-content-card h-100">
                            <div class="card-body p-4">
                                <small class="text-muted"><?= htmlspecialchars($item["published_at"] ?? "") ?></small>
                                <h3 class="h5 fw-bold mt-3"><?= htmlspecialchars($item["title"] ?? "") ?></h3>
                                <p><?= htmlspecialchars(mb_strimwidth(strip_tags($item["content"] ?? ""), 0, 160, "...")) ?></p>
                                <a href="news.php" class="home-text-link">Baca berita <span aria-hidden="true">→</span></a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-center">Belum ada berita yang diterbitkan.</p></div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="news.php" class="btn btn-outline-dark">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<!-- 8. CONTACT -->
<section id="contact-home" class="home-section section-padding home-contact-section">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <p class="eyebrow">CONTACT US</p>
                <h2 class="home-section-title">Mari Terhubung dengan Kami</h2>
                <p class="mb-0">Untuk pertanyaan mengenai perusahaan atau informasi lainnya, silakan hubungi tim PT Nihon Plast Indonesia.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="contact.php" class="btn btn-primary btn-lg">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

<!-- =========================================================
     MODAL FOTO HOME
     Hanya untuk foto pada Produk Kami, Galeri Perusahaan,
     dan Fasilitas Perusahaan. Tidak mengubah gaya tautan.
========================================================= -->
<style>
/* Foto pada tiga bagian ini menunjukkan bahwa foto bisa diperbesar. */
#products-home .home-card-image,
#gallery-home .home-gallery-card img,
#facility-home .home-card-image {
    cursor: zoom-in;
}

/* Lapisan modal memenuhi layar, tetapi tersembunyi sebelum dibuka. */
#homePhotoOnlyModal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 100000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* Menampilkan modal ketika JavaScript menambahkan kelas is-open. */
#homePhotoOnlyModal.is-open {
    display: flex;
}

/* Area gelap di belakang foto. */
#homePhotoOnlyModal .home-photo-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, .84);
}

/* Wadah foto saja; tidak memuat teks halaman atau navigasi. */
#homePhotoOnlyModal .home-photo-modal-panel {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 96vw;
    max-height: 94vh;
}

/* Ukuran foto menyesuaikan layar dan mempertahankan rasio aslinya. */
#homePhotoOnlyModal .home-photo-modal-image {
    display: block;
    max-width: 94vw;
    max-height: 88vh;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, .45);
}

/* Tombol untuk menutup modal. */
#homePhotoOnlyModal .home-photo-modal-close {
    position: absolute;
    top: -15px;
    right: -15px;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 50%;
    background: #fff;
    color: #222;
    font-size: 28px;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .35);
}

/* Mencegah halaman Home ikut bergulir ketika modal terbuka. */
body.home-photo-modal-open {
    overflow: hidden;
}

@media (max-width: 600px) {
    #homePhotoOnlyModal { padding: 12px; }
    #homePhotoOnlyModal .home-photo-modal-image {
        max-width: 94vw;
        max-height: 84vh;
    }
    #homePhotoOnlyModal .home-photo-modal-close {
        top: -10px;
        right: -6px;
    }
}
</style>

<!-- Struktur modal hanya terdiri dari latar gelap, tombol tutup, dan foto. -->
<div id="homePhotoOnlyModal" aria-hidden="true">
    <div class="home-photo-modal-backdrop" data-home-photo-close></div>
    <div class="home-photo-modal-panel" role="dialog" aria-modal="true" aria-label="Pratinjau foto">
        <button type="button" class="home-photo-modal-close" aria-label="Tutup foto" data-home-photo-close>&times;</button>
        <img id="homePhotoOnlyModalImage" class="home-photo-modal-image" src="" alt="">
    </div>
</div>

<script>
(function () {
    'use strict';

    /* Mengambil elemen modal dan foto yang akan diperbesar. */
    const modal = document.getElementById('homePhotoOnlyModal');
    const modalImage = document.getElementById('homePhotoOnlyModalImage');

    /* Jika elemen modal tidak ada, hentikan script dengan aman. */
    if (!modal || !modalImage) {
        return;
    }

    /* Membuka modal dan menampilkan foto yang dipilih saja. */
    function openHomePhotoModal(photoUrl, photoAlt) {
        modalImage.src = photoUrl;
        modalImage.alt = photoAlt || 'Foto perusahaan';
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('home-photo-modal-open');
    }

    /* Menutup modal dan mengosongkan foto agar tidak tertinggal. */
    function closeHomePhotoModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('home-photo-modal-open');
        modalImage.removeAttribute('src');
        modalImage.alt = '';
    }

    /* Menangkap klik foto hanya dari tiga section yang diminta. */
    document.addEventListener('click', function (event) {
        const clickedImage = event.target.closest(
            '#products-home img, #gallery-home img, #facility-home img'
        );

        /* Klik teks, tombol, atau foto di bagian lain tidak diubah. */
        if (!clickedImage) {
            return;
        }

        /* Ambil alamat foto asli dari elemen gambar. */
        const photoUrl = clickedImage.currentSrc || clickedImage.src;
        if (!photoUrl) {
            return;
        }

        /* Hentikan aksi tautan pembungkus bila ada, lalu tampilkan modal foto. */
        event.preventDefault();
        event.stopPropagation();
        openHomePhotoModal(photoUrl, clickedImage.alt);
    }, true);

    /* Tutup modal jika tombol X atau latar gelap ditekan. */
    modal.querySelectorAll('[data-home-photo-close]').forEach(function (button) {
        button.addEventListener('click', closeHomePhotoModal);
    });

    /* Tutup modal dengan tombol Escape pada keyboard. */
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeHomePhotoModal();
        }
    });
})();
</script>

</body>
</html>
