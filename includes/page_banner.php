<?php
if (!isset($bannerKey)) {
    $bannerKey = "about";
}

$bannerDefaults = [
    "home" => [
        "title" => "PT Nihon Plast Indonesia",
        "subtitle" => "Driving Quality Through Automotive Manufacturing"
    ],
    "about" => [
        "title" => "About Company",
        "subtitle" => "Learn more about our company, vision, and mission."
    ],
    "products" => [
        "title" => "Our Products",
        "subtitle" => "Automotive components built with quality and precision."
    ],
    "gallery" => [
        "title" => "Our Gallery",
        "subtitle" => "Photos of our facilities and company activities."
    ],
    "quality" => [
        "title" => "Quality & ISO Certifications",
        "subtitle" => "Standar Mutu IATF 16949, ISO 9001, ISO 14001, ISO 45001 / SMK3, dan Kepatuhan Regulasi Indonesia."
    ],
    "facility" => [
        "title" => "Factory & Facility",
        "subtitle" => "Explore our manufacturing facilities."
    ],
    "career" => [
        "title" => "Join Our Team",
        "subtitle" => "Explore career opportunities with us."
    ],
    "news" => [
        "title" => "Company News",
        "subtitle" => "Latest updates and company activities."
    ],
    "contact" => [
        "title" => "Contact Us",
        "subtitle" => "Get in touch with our team."
    ]
];

$banner = $bannerDefaults[$bannerKey] ?? $bannerDefaults["about"];

if (isset($conn)) {
    $bannerStmt = $conn->prepare(
        "SELECT id, page_title, subtitle, image
         FROM page_banners
         WHERE page_key = ?
         LIMIT 1"
    );

    if ($bannerStmt) {
        $bannerStmt->bind_param("s", $bannerKey);
        if ($bannerStmt->execute()) {
            $bannerResult = $bannerStmt->get_result();
            $bannerData = $bannerResult->fetch_assoc();

            if ($bannerData) {
                if (function_exists('translate_entity_row')) {
                    $bannerData = translate_entity_row('page_banner', $bannerData);
                }
                if (!empty($bannerData["page_title"])) {
                    $banner["title"] = $bannerData["page_title"];
                }
                if (!empty($bannerData["subtitle"])) {
                    $banner["subtitle"] = $bannerData["subtitle"];
                }
                // Ambil path gambar banner yang disimpan dari Admin ke database.
                $banner["image"] = $bannerData["image"] ?? "";
            }
        }
    }
}

$defaultBannerImages = [
    "home" => "https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=2000&q=85",
    "about" => "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=2000&q=85",
    "products" => "https://images.unsplash.com/photo-1504222490345-c075b6008014?auto=format&fit=crop&w=2000&q=85",
    "gallery" => "https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=2000&q=85",
    "quality" => "https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=2000&q=85",
    "facility" => "https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=2000&q=85",
    "career" => "https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=85",
    "news" => "https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=85",
    "contact" => "https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=2000&q=85"
];

$storedBannerImage = trim($banner["image"] ?? "");

/*
 * File upload stores paths such as "uploads/pages/banner_home_x.jpg".
 * Public pages live at the project root, so use that relative path directly.
 * Also accept older records containing "../uploads/..." or only a filename.
 */
if ($storedBannerImage !== "") {
    $storedBannerImage = str_replace("\\", "/", $storedBannerImage);

    if (strpos($storedBannerImage, "../") === 0) {
        $storedBannerImage = substr($storedBannerImage, 3);
    }

    if (strpos($storedBannerImage, "uploads/") !== 0 &&
        !preg_match("~^https?://~i", $storedBannerImage)) {
        $storedBannerImage = "uploads/pages/" . basename($storedBannerImage);
    }

    if (!preg_match("~^https?://~i", $storedBannerImage) && !file_exists(__DIR__ . "/../" . $storedBannerImage)) {
        $bannerImage = $defaultBannerImages[$bannerKey] ?? $defaultBannerImages["about"];
    } else {
        $bannerImage = $storedBannerImage;
    }
} else {
    $bannerImage = $defaultBannerImages[$bannerKey] ?? $defaultBannerImages["about"];
}
$bannerImageUrl = "";

if ($bannerImage !== "") {
    if (filter_var($bannerImage, FILTER_VALIDATE_URL)) {
        $bannerImageUrl = $bannerImage;
    } else {
        $bannerImageUrl = $bannerImage;
    }
}
?>
<section
    class="page-banner position-relative overflow-hidden"
    style="background-image: linear-gradient(105deg, rgba(0, 38, 22, 0.94) 0%, rgba(0, 56, 34, 0.88) 55%, rgba(0, 90, 54, 0.70) 100%), url('<?= htmlspecialchars($bannerImage, ENT_QUOTES) ?><?= strpos($bannerImage, "uploads/") === 0 ? "?v=" . @filemtime(__DIR__ . "/../" . $bannerImage) : "" ?>'); background-size: cover; background-position: center;"
>
    <!-- Precision Background Grid Texture -->
    <div class="position-absolute pointer-events-none opacity-25" style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 28px 28px; inset: 0;"></div>

    <div class="container position-relative py-3 py-lg-4" style="z-index: 2;">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <!-- Breadcrumbs & Eyebrow Badge -->
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-2 rounded-pill fw-bold text-uppercase" style="letter-spacing: 0.12em; font-size: 0.72rem;">
                        <i class="bi bi-shield-check text-success me-1"></i> PT NIHON PLAST INDONESIA
                    </span>
                    <span class="text-white-50 small d-none d-sm-inline">&bull;</span>
                    <nav aria-label="breadcrumb" class="d-none d-sm-inline-block">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>Beranda</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page"><?= htmlspecialchars(strtoupper($bannerKey === "home" ? "Company Profile" : $bannerKey)) ?></li>
                        </ol>
                    </nav>
                </div>

                <h1 class="display-6 fw-bold text-white mb-2" style="letter-spacing: -0.02em; line-height: 1.2;">
                    <?= nl2br(htmlspecialchars($banner["title"])) ?>
                </h1>

                <?php if (!empty($banner["subtitle"])): ?>
                    <p class="lead text-white-50 mb-0" style="max-width: 680px; font-size: 1.05rem; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($banner["subtitle"])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 shadow-sm text-white" style="max-width: 300px; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-patch-check-fill text-success fs-5"></i>
                        <span class="fw-bold small">Tier-1 OEM Manufacturing</span>
                    </div>
                    <div class="small text-white-50" style="font-size: 0.8rem; line-height: 1.5;">
                        IATF 16949 &bull; ISO 14001 &bull; ISO 45001<br>
                        Kawasan Industri Indotaisei, Cikampek
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
