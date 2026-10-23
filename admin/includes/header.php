<?php
if (!function_exists('current_lang')) {
    require_once __DIR__ . '/../../includes/lang.php';
}
$adminLangs = [
    'id' => ['short' => 'ID', 'name' => 'Bahasa Indonesia', 'flag' => '../assets/img/flags/id.svg'],
    'en' => ['short' => 'EN', 'name' => 'English', 'flag' => '../assets/img/flags/en.svg'],
    'ja' => ['short' => 'JA', 'name' => '日本語', 'flag' => '../assets/img/flags/ja.svg'],
    'zh' => ['short' => 'ZH', 'name' => '简体中文', 'flag' => '../assets/img/flags/zh.svg'],
    'ko' => ['short' => 'KO', 'name' => '한국어', 'flag' => '../assets/img/flags/ko.svg'],
];
$adminCurrentLang = function_exists('current_lang') ? current_lang() : ($_SESSION['cms_lang'] ?? 'id');
$adminCurrentLangInfo = $adminLangs[$adminCurrentLang] ?? $adminLangs['id'];

$rawCurrent = basename($_SERVER["PHP_SELF"]);
$currentPage = ($rawCurrent === 'preview_dashboard.php' || $rawCurrent === 'index.php') ? 'dashboard.php' : $rawCurrent;
$userRole = $_SESSION["admin_role"] ?? "admin";
$userName = $_SESSION["admin_full_name"] ?? ($_SESSION["admin_username"] ?? "Admin");
$userInitials = strtoupper(substr($userName, 0, 1)) ?: 'A';

// Unread messages count for sidebar badge
$unreadMessagesCount = 0;
if (isset($conn) && $conn instanceof mysqli) {
    $resMsg = $conn->query("SELECT COUNT(*) AS total FROM messages WHERE status = 'unread'");
    if ($resMsg) {
        $unreadMessagesCount = (int)($resMsg->fetch_assoc()['total'] ?? 0);
    }
}
?>

<!-- Mobile Sidebar Backdrop -->
<div class="modal-backdrop fade d-none" id="adminSidebarBackdrop" style="z-index: 1030;"></div>

<aside class="admin-sidebar" id="adminSidebar">

    <!-- Dashboard V14 Brand Header (Forest Green Identity) -->
    <a href="dashboard.php" class="admin-brand">
        <div class="admin-brand-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="12" width="4" height="9" rx="1.5" fill="white" />
                <rect x="10" y="7" width="4" height="14" rx="1.5" fill="white" />
                <rect x="17" y="3" width="4" height="18" rx="1.5" fill="white" />
            </svg>
        </div>
        <div class="admin-brand-title">Dashboard</div>
    </a>

    <!-- Dashboard V14 Nav Menu (Strict 1-to-1 Aligned with Public Site Structure) -->
    <nav class="admin-menu">
        <a href="dashboard.php" class="admin-menu-link <?= ($currentPage === 'dashboard.php') ? 'active' : '' ?>" title="Ringkasan Utama & Status CMS">
            <div class="d-flex align-items-center">
                <i class="bi bi-grid-1x2-fill admin-menu-icon"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <a href="site_sections.php?page=home" class="admin-menu-link <?= (($currentPage === 'site_sections.php' || $currentPage === 'site_section_items.php') && ($_GET['page'] ?? 'home') === 'home') || ($currentPage === 'banners.php' && ($_GET['page_key'] ?? '') === 'home') ? 'active' : '' ?>" title="Seksi & Banner Halaman Beranda">
            <div class="d-flex align-items-center">
                <i class="bi bi-house-door-fill admin-menu-icon"></i>
                <span>Beranda</span>
            </div>
        </a>

        <a href="company.php" class="admin-menu-link <?= ($currentPage === 'company.php') || (($currentPage === 'site_sections.php' || $currentPage === 'site_section_items.php') && ($_GET['page'] ?? '') === 'about') ? 'active' : '' ?>" title="Profil Perusahaan, Visi Misi & Seksi Tentang Kami">
            <div class="d-flex align-items-center">
                <i class="bi bi-building admin-menu-icon"></i>
                <span>Tentang Kami</span>
            </div>
        </a>

        <a href="products.php" class="admin-menu-link <?= ($currentPage === 'products.php') ? 'active' : '' ?>" title="Katalog Produk Otomotif Tier-1">
            <div class="d-flex align-items-center">
                <i class="bi bi-box-seam admin-menu-icon"></i>
                <span>Produk</span>
            </div>
        </a>

        <a href="certifications.php" class="admin-menu-link <?= ($currentPage === 'certifications.php') ? 'active' : '' ?>" title="Sertifikasi Mutu IATF 16949 & ISO">
            <div class="d-flex align-items-center">
                <i class="bi bi-patch-check admin-menu-icon"></i>
                <span>Mutu & ISO</span>
            </div>
        </a>

        <a href="machinery.php" class="admin-menu-link <?= ($currentPage === 'machinery.php') ? 'active' : '' ?>" title="Fasilitas Pabrik & Mesin Injeksi Presisi">
            <div class="d-flex align-items-center">
                <i class="bi bi-cpu admin-menu-icon"></i>
                <span>Fasilitas</span>
            </div>
        </a>

        <a href="gallery.php" class="admin-menu-link <?= in_array($currentPage, ['gallery.php', 'media.php']) ? 'active' : '' ?>" title="Galeri Foto Manufaktur & Media">
            <div class="d-flex align-items-center">
                <i class="bi bi-images admin-menu-icon"></i>
                <span>Galeri</span>
            </div>
        </a>

        <a href="news.php" class="admin-menu-link <?= ($currentPage === 'news.php') ? 'active' : '' ?>" title="Berita & Publikasi Perusahaan">
            <div class="d-flex align-items-center">
                <i class="bi bi-newspaper admin-menu-icon"></i>
                <span>Berita</span>
            </div>
        </a>

        <a href="career.php" class="admin-menu-link <?= ($currentPage === 'career.php') ? 'active' : '' ?>" title="Lowongan Kerja & Rekrutmen">
            <div class="d-flex align-items-center">
                <i class="bi bi-briefcase admin-menu-icon"></i>
                <span>Karir</span>
            </div>
        </a>

        <a href="messages.php" class="admin-menu-link <?= ($currentPage === 'messages.php') ? 'active' : '' ?>" title="Pesan Masuk Permintaan Penawaran">
            <div class="d-flex align-items-center">
                <i class="bi bi-chat-dots admin-menu-icon"></i>
                <span>B2B RFQ</span>
            </div>
            <?php if ($unreadMessagesCount > 0): ?>
                <span class="nav-dot-badge"></span>
            <?php endif; ?>
        </a>

        <a href="translations.php" class="admin-menu-link <?= in_array($currentPage, ['translations.php', 'users.php']) ? 'active' : '' ?>" title="Pusat Bahasa Multibahasa & Manajemen Pengguna RBAC">
            <div class="d-flex align-items-center">
                <i class="bi bi-gear admin-menu-icon"></i>
                <span>Pengaturan</span>
            </div>
        </a>

        <!-- Extra Quick Links at Bottom -->
        <div class="mt-auto pt-3 border-top border-light-subtle">
            <a href="../index.php" target="_blank" class="admin-menu-link text-muted" title="Buka Web Publik">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-arrow-up-right admin-menu-icon text-muted"></i>
                    <span style="font-size: 0.8rem;">Preview Website</span>
                </div>
            </a>
            <a href="logout.php" class="admin-menu-link text-danger mt-1" title="Keluar">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-arrow-right admin-menu-icon text-danger"></i>
                    <span style="font-size: 0.8rem;">Logout</span>
                </div>
            </a>
        </div>
    </nav>

</aside>

<main class="admin-main">

    <!-- Dashboard V14 Topbar -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center gap-2">
            <!-- Mobile Toggle -->
            <button class="topbar-circle-btn d-lg-none" id="adminSidebarToggle" aria-label="Toggle Navigation">
                <i class="bi bi-list"></i>
            </button>

            <!-- Search anything... -->
            <div class="admin-search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="adminQuickSearchInput" placeholder="Search anything..." autocomplete="off">
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- 5-Language Switcher Combobox -->
            <div class="dropdown">
                <button class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2 rounded-pill px-2 py-1 shadow-xs" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Ganti Bahasa Sistem">
                    <img src="<?= htmlspecialchars($adminCurrentLangInfo['flag']) ?>" alt="<?= htmlspecialchars($adminCurrentLangInfo['short']) ?>" style="width: 18px; height: 13px; object-fit: cover; border-radius: 2px;">
                    <span class="small fw-bold text-dark"><?= htmlspecialchars($adminCurrentLangInfo['short']) ?></span>
                    <i class="bi bi-chevron-down text-muted" style="font-size: 0.65rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 rounded-4 p-2" style="min-width: 190px;">
                    <li class="px-2 py-1 small fw-bold text-muted text-uppercase" style="font-size: 0.68rem;"><i class="bi bi-translate me-1"></i> Bahasa CMS / Publik</li>
                    <?php foreach ($adminLangs as $lCode => $lInfo): ?>
                        <li>
                            <a class="dropdown-item rounded-3 py-1 px-2 small d-flex align-items-center gap-2 <?= $adminCurrentLang === $lCode ? 'active bg-success text-white' : '' ?>" href="?lang=<?= urlencode($lCode) ?><?= !empty($_SERVER['QUERY_STRING']) ? '&' . preg_replace('/(^|&)lang=[^&]*/', '', $_SERVER['QUERY_STRING']) : '' ?>">
                                <img src="<?= htmlspecialchars($lInfo['flag']) ?>" alt="<?= htmlspecialchars($lInfo['short']) ?>" style="width: 18px; height: 13px; object-fit: cover; border-radius: 2px;">
                                <span class="flex-grow-1"><?= htmlspecialchars($lInfo['name']) ?></span>
                                <?php if ($adminCurrentLang === $lCode): ?>
                                    <i class="bi bi-check-circle-fill"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item rounded-3 py-1 px-2 small text-muted" href="translations.php"><i class="bi bi-pencil-square me-1"></i> Edit Terjemahan Database</a></li>
                </ul>
            </div>

            <!-- Theme toggle button -->
            <button class="topbar-circle-btn" type="button" title="Theme Mode" id="themeToggleBtn">
                <i class="bi bi-sun text-success"></i>
            </button>

            <!-- Notification bell with red dot -->
            <a href="messages.php" class="topbar-circle-btn position-relative" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="nav-dot-badge position-absolute" style="top: 8px; right: 8px;"></span>
            </a>

            <!-- User avatar dropdown (Lavender circle + A + chevron) -->
            <div class="dropdown">
                <button class="topbar-avatar-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="admin-user-avatar"><?= htmlspecialchars($userInitials) ?></div>
                    <i class="bi bi-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 rounded-4 p-2" style="min-width: 210px;">
                    <li class="px-3 py-2 border-bottom">
                        <div class="small fw-bold text-slate-800"><?= htmlspecialchars($userName) ?></div>
                        <div class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($userRole) ?> (RBAC Active)</div>
                    </li>
                    <li><a class="dropdown-item rounded-3 py-2 small mt-1" href="company.php"><i class="bi bi-gear me-2 text-muted"></i>Company Settings</a></li>
                    <li><a class="dropdown-item rounded-3 py-2 small" href="site_sections.php"><i class="bi bi-layout-text-window me-2 text-muted"></i>Page Builder</a></li>
                    <li><a class="dropdown-item rounded-3 py-2 small" href="translations.php"><i class="bi bi-translate me-2 text-muted"></i>Multilingual (5 Lang)</a></li>
                    <li><a class="dropdown-item rounded-3 py-2 small" href="../index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2 text-muted"></i>Public Website</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item rounded-3 py-2 small text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar Sistem</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4 py-3">
