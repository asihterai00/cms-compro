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
        "SELECT page_title, subtitle, image
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

    $bannerImage = $storedBannerImage;
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
    class="page-banner"
    style="background-image: linear-gradient(90deg, rgba(0, 38, 22, 0.90), rgba(0, 90, 54, 0.45)), url('<?= htmlspecialchars($bannerImage, ENT_QUOTES) ?><?= strpos($bannerImage, "uploads/") === 0 ? "?v=" . @filemtime(__DIR__ . "/../" . $bannerImage) : "" ?>');"
>
    <div class="container">
        <p class="page-banner-eyebrow">
            <?= htmlspecialchars(strtoupper($bannerKey === "home" ? "Company Profile" : $bannerKey)) ?>
        </p>
        <h1>
            <?= nl2br(htmlspecialchars($banner["title"])) ?>
        </h1>
        <?php if (!empty($banner["subtitle"])): ?>
            <p class="page-banner-subtitle">
                <?= nl2br(htmlspecialchars($banner["subtitle"])) ?>
            </p>
        <?php endif; ?>
    </div>
</section>
