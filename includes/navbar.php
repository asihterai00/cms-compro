<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/translation_helpers.php";

$companyNav = null;
if (isset($conn)) {
    $companyResult = $conn->query(
        "SELECT id, company_name, logo, phone, email, address
         FROM company_profile
         ORDER BY id ASC
         LIMIT 1"
    );
    if ($companyResult) {
        $companyNav = $companyResult->fetch_assoc();
        if ($companyNav && function_exists('translate_entity_row')) {
            $companyNav = translate_entity_row('company_profile', $companyNav);
        }
    }
}

$brandName = $companyNav["company_name"] ?? "PT Nihon Plast Indonesia";
$brandLogo = $companyNav["logo"] ?? "";
$navPhone  = $companyNav["phone"] ?? "+62-264-351-888";
$navEmail  = $companyNav["email"] ?? "corporate@n-plast.co.id";
$currentPage = basename($_SERVER["PHP_SELF"]);
$currentClean = preg_replace('/\.php$/i', '', $currentPage);
$currentLang = current_lang();
$currentLangInfo = get_current_lang_info();
$supportedLanguages = get_supported_languages();
?>

<!-- 1. Sleek Topbar Utility Bar (Clears Main Nav Clutter) -->
<div class="site-topbar d-none d-lg-block">
    <div class="container-fluid px-3 px-xl-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <span class="site-topbar-badge">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> <?= htmlspecialchars(__('topbar_cert', 'IATF 16949 • ISO 14001 • ISO 45001 CERTIFIED')) ?>
                </span>
                <span class="text-secondary opacity-50">|</span>
                <span class="text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= htmlspecialchars(__('topbar_location', 'Kawasan Industri Indotaisei, Karawang, Jawa Barat')) ?>
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="tel:<?= htmlspecialchars($navPhone) ?>" class="site-topbar-link">
                    <i class="bi bi-telephone-fill text-success"></i> <?= htmlspecialchars($navPhone) ?>
                </a>
                <span class="text-secondary opacity-50">|</span>
                <a href="mailto:<?= htmlspecialchars($navEmail) ?>" class="site-topbar-link">
                    <i class="bi bi-envelope-fill text-primary"></i> <?= htmlspecialchars($navEmail) ?>
                </a>
                <span class="text-secondary opacity-50">|</span>
                <!-- Sleek Single Flag Combobox -->
                <div class="dropdown lang-combobox-wrapper" id="langSwitcherDesktop">
                    <button class="btn btn-sm lang-combobox-btn dropdown-toggle d-inline-flex align-items-center gap-2" type="button" id="langDropdownDesktop" data-bs-toggle="dropdown" aria-expanded="false" title="<?= htmlspecialchars(__('choose_language', 'Pilih Bahasa')) ?>">
                        <img src="<?= htmlspecialchars($currentLangInfo['flag']) ?>" alt="<?= htmlspecialchars($currentLangInfo['short']) ?>" class="lang-flag-current">
                        <span class="lang-name-current fw-bold"><?= htmlspecialchars($currentLangInfo['short']) ?></span>
                        <i class="bi bi-chevron-down lang-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end lang-dropdown-menu shadow-lg border-0 py-2" aria-labelledby="langDropdownDesktop">
                        <li class="dropdown-header text-uppercase small text-muted px-3 py-1 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">
                            <i class="bi bi-translate me-1"></i> Language / 言語 / 언어
                        </li>
                        <?php foreach ($supportedLanguages as $code => $langInfo): ?>
                            <li>
                                <a class="dropdown-item lang-dropdown-item d-flex align-items-center gap-2 px-3 py-2 <?= $currentLang === $code ? 'active-lang' : '' ?>" href="<?= htmlspecialchars(get_lang_switch_url($code)) ?>">
                                    <img src="<?= htmlspecialchars($langInfo['flag']) ?>" alt="<?= htmlspecialchars($langInfo['short']) ?>" class="lang-flag-item">
                                    <span class="lang-name-text"><?= htmlspecialchars($langInfo['name']) ?></span>
                                    <?php if ($currentLang === $code): ?>
                                        <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border ms-auto small"><?= htmlspecialchars($langInfo['short']) ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Primary Navigation Bar (Strict No-Wrap, Fluid Spacing) -->
<nav class="navbar navbar-expand-lg navbar-light site-navbar sticky-top">
    <div class="container-fluid px-3 px-xl-5">
        <a class="navbar-brand d-flex align-items-center gap-2 py-0" href="./">
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
            class="navbar-toggler border-0 shadow-none px-2"
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
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 my-3 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= in_array($currentClean, ['index', '']) ? 'active' : '' ?>" href="./">
                        <?= htmlspecialchars(__('nav_home', 'Home')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'about' ? 'active' : '' ?>" href="about">
                        <?= htmlspecialchars(__('nav_about', 'Tentang Kami')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'products' ? 'active' : '' ?>" href="products">
                        <?= htmlspecialchars(__('nav_products', 'Produk')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'quality' ? 'active' : '' ?>" href="quality">
                        <?= htmlspecialchars(__('nav_quality', 'Mutu & ISO')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'facility' ? 'active' : '' ?>" href="facility">
                        <?= htmlspecialchars(__('nav_facility', 'Fasilitas')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'gallery' ? 'active' : '' ?>" href="gallery">
                        <?= htmlspecialchars(__('nav_gallery', 'Galeri')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'news' ? 'active' : '' ?>" href="news">
                        <?= htmlspecialchars(__('nav_news', 'Berita')) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentClean === 'career' ? 'active' : '' ?>" href="career">
                        <?= htmlspecialchars(__('nav_career', 'Karir')) ?>
                    </a>
                </li>

                <!-- Mobile Only Language Switcher (Combobox) -->
                <li class="nav-item d-lg-none pt-2 pb-2 border-top border-light">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small text-muted fw-bold"><i class="bi bi-translate me-1"></i> Language:</span>
                        <div class="dropdown lang-combobox-wrapper" id="langSwitcherMobile">
                            <button class="btn btn-sm lang-combobox-btn dropdown-toggle d-inline-flex align-items-center gap-2" type="button" id="langDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= htmlspecialchars($currentLangInfo['flag']) ?>" alt="<?= htmlspecialchars($currentLangInfo['short']) ?>" class="lang-flag-current">
                                <span class="lang-name-current fw-bold"><?= htmlspecialchars($currentLangInfo['short']) ?></span>
                                <i class="bi bi-chevron-down lang-chevron"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end lang-dropdown-menu shadow-lg border-0 py-2" aria-labelledby="langDropdownMobile">
                                <?php foreach ($supportedLanguages as $code => $langInfo): ?>
                                    <li>
                                        <a class="dropdown-item lang-dropdown-item d-flex align-items-center gap-2 px-3 py-2 <?= $currentLang === $code ? 'active-lang' : '' ?>" href="<?= htmlspecialchars(get_lang_switch_url($code)) ?>">
                                            <img src="<?= htmlspecialchars($langInfo['flag']) ?>" alt="<?= htmlspecialchars($langInfo['short']) ?>" class="lang-flag-item">
                                            <span class="lang-name-text"><?= htmlspecialchars($langInfo['name']) ?></span>
                                            <?php if ($currentLang === $code): ?>
                                                <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border ms-auto small"><?= htmlspecialchars($langInfo['short']) ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- B2B RFQ Button -->
                <li class="nav-item ms-lg-2 my-2 my-lg-0">
                    <a class="btn btn-np-nav w-100 justify-content-center" href="contact">
                        <i class="bi bi-file-earmark-text me-1"></i> <?= htmlspecialchars(__('nav_rfq', 'B2B RFQ')) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

