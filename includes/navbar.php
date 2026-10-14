<?php
require_once __DIR__ . "/../config/database.php";

$companyNav = null;
if (isset($conn)) {
    $companyResult = $conn->query(
        "SELECT company_name, logo
         FROM company_profile
         ORDER BY id ASC
         LIMIT 1"
    );
    if ($companyResult) {
        $companyNav = $companyResult->fetch_assoc();
    }
}

$brandName = $companyNav["company_name"] ?? "PT Nihon Plast Indonesia";
$brandLogo = $companyNav["logo"] ?? "";
$currentPage = basename($_SERVER["PHP_SELF"]);
$currentLang = current_lang();
$supportedLanguages = get_supported_languages();
?>
<nav class="navbar navbar-expand-lg navbar-light site-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <?php if ($brandLogo !== ""): ?>
                <?php
                if (strpos($brandLogo, "uploads/") === 0) {
                    $logoSrc = $brandLogo;
                } else {
                    $logoSrc = "uploads/company/" . $brandLogo;
                }
                ?>
                <img
                    src="<?= htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($brandName) ?>"
                    class="site-logo-img"
                >
            <?php else: ?>
                <span class="site-logo-placeholder">
                    <i class="bi bi-shield-check"></i>
                </span>
                <span class="fw-bold tracking-tight text-dark">
                    <?= htmlspecialchars($brandName) ?>
                </span>
            <?php endif; ?>
        </a>

        <button
            class="navbar-toggler border-0 shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">
                        <?= htmlspecialchars(__('nav_home', 'Home')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'about.php' ? 'active' : '' ?>" href="about.php">
                        <?= htmlspecialchars(__('nav_about', 'About')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'products.php' ? 'active' : '' ?>" href="products.php">
                        <?= htmlspecialchars(__('nav_products', 'Products')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'quality.php' ? 'active' : '' ?>" href="quality.php">
                        <?= htmlspecialchars(__('nav_quality', 'Quality & ISO')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'facility.php' ? 'active' : '' ?>" href="facility.php">
                        <?= htmlspecialchars(__('nav_facility', 'Facility')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'gallery.php' ? 'active' : '' ?>" href="gallery.php">
                        <?= htmlspecialchars(__('nav_gallery', 'Gallery')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'news.php' ? 'active' : '' ?>" href="news.php">
                        <?= htmlspecialchars(__('nav_news', 'News')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'career.php' ? 'active' : '' ?>" href="career.php">
                        <?= htmlspecialchars(__('nav_career', 'Career')) ?>
                    </a>
                </li>
                <li class="nav-item ms-lg-2 my-2 my-lg-0 d-flex align-items-center">
                    <div class="lang-switcher" id="langSwitcher" role="group" aria-label="Language selection">
                        <?php foreach ($supportedLanguages as $code => $langInfo): ?>
                            <a href="<?= htmlspecialchars(get_lang_switch_url($code)) ?>" class="lang-btn <?= $currentLang === $code ? 'active' : '' ?>" title="<?= htmlspecialchars($langInfo['name']) ?>"><?= htmlspecialchars($langInfo['short']) ?></a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <li class="nav-item ms-lg-2 my-2 my-lg-0">
                    <a class="btn btn-np-nav w-100 justify-content-center justify-content-lg-start" href="contact.php">
                        <i class="bi bi-file-earmark-text me-1"></i> <?= htmlspecialchars(__('nav_rfq', 'B2B RFQ')) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
