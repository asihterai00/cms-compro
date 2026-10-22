<?php
/**
 * Admin Authentication & Granular Menu-Level RBAC
 * PT Nihon Plast Indonesia
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

/**
 * Global Menus Definition for RBAC
 */
function get_app_menus() {
    return [
        'dashboard' => [
            'name' => 'Dashboard Overview',
            'icon' => 'bi-speedometer2',
            'url' => 'dashboard.php',
            'desc' => 'Ringkasan operasional sistem, KPI & status CMS',
            'group' => 'overview',
            'group_label' => 'RINGKASAN UTAMA'
        ],
        // 1. CMS Halaman Web (Page Sections & Banners)
        'site_sections' => [
            'name' => 'Elemen Halaman (Sections)',
            'icon' => 'bi-layout-text-window-reverse',
            'url' => 'site_sections.php',
            'desc' => 'Edit teks, judul, gambar, dan toggle aktif/nonaktif per halaman',
            'group' => 'pages',
            'group_label' => '1. CMS HALAMAN (PAGE BUILDER)'
        ],
        'site_section_items' => [
            'name' => 'Sub-Item & Kartu Komponen',
            'icon' => 'bi-collection',
            'url' => 'site_section_items.php',
            'desc' => 'Kelola kartu bento, pilar, zone kendaraan, OEM badges & sub-elemen',
            'group' => 'pages',
            'group_label' => '1. CMS HALAMAN (PAGE BUILDER)'
        ],
        'banners' => [
            'name' => 'Banner Header Halaman',
            'icon' => 'bi-image',
            'url' => 'banners.php',
            'desc' => 'Banner header dan judul jumbotron tiap halaman publik',
            'group' => 'pages',
            'group_label' => '1. CMS HALAMAN (PAGE BUILDER)'
        ],
        // 2. Posting, Katalog & Artikel (Products, News, Career, Gallery, Facility)
        'products' => [
            'name' => 'Katalog Produk Otomotif',
            'icon' => 'bi-box-seam',
            'url' => 'products.php',
            'desc' => 'Katalog 4 pilar produk manufaktur interior & safety',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        'news' => [
            'name' => 'Berita & Publikasi',
            'icon' => 'bi-newspaper',
            'url' => 'news.php',
            'desc' => 'Artikel, rilis pers, berita & agenda korporat',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        'career' => [
            'name' => 'Karir & Lowongan Kerja',
            'icon' => 'bi-briefcase',
            'url' => 'career.php',
            'desc' => 'Lowongan kerja, kualifikasi & tahap rekrutmen',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        'gallery' => [
            'name' => 'Galeri Foto Manufaktur',
            'icon' => 'bi-camera',
            'url' => 'gallery.php',
            'desc' => 'Dokumentasi foto presisi manufaktur & fasilitas',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        'machinery' => [
            'name' => 'Spesifikasi Mesin Injeksi',
            'icon' => 'bi-cpu',
            'url' => 'machinery.php',
            'desc' => 'Spesifikasi mesin injeksi presisi 150T - 2500T',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        'certifications' => [
            'name' => 'Akreditasi Mutu & ISO',
            'icon' => 'bi-patch-check',
            'url' => 'certifications.php',
            'desc' => 'Sertifikasi IATF 16949, ISO 14001, ISO 45001 & SMK3',
            'group' => 'posts',
            'group_label' => '2. POSTING & KATALOG (CONTENT)'
        ],
        // 3. Upload & File Media Assets
        'media' => [
            'name' => 'Media & Upload Library',
            'icon' => 'bi-images',
            'url' => 'media.php',
            'desc' => 'Manajemen gambar, logo OEM, foto aset & berkas upload',
            'group' => 'media',
            'group_label' => '3. UPLOAD & FILE MEDIA (ASSETS)'
        ],
        // 4. Interaksi & Leads (Messages)
        'messages' => [
            'name' => 'Pesan Masuk B2B RFQ',
            'icon' => 'bi-envelope',
            'url' => 'messages.php',
            'desc' => 'Pesan formulir penawaran masuk dari prospek & klien',
            'group' => 'inquiries',
            'group_label' => '4. INTERAKSI & LEADS B2B'
        ],
        // 5. Konfigurasi & Pengaturan Sistem (Config)
        'translations' => [
            'name' => 'Pusat Terjemahan Multibahasa',
            'icon' => 'bi-translate',
            'url' => 'translations.php',
            'desc' => 'Pusat pengelolaan translasi dinamis 5 bahasa (ID, EN, JA, ZH, KO)',
            'group' => 'config',
            'group_label' => '5. KONFIGURASI SISTEM (CONFIG)'
        ],
        'company' => [
            'name' => 'Profil Perusahaan & Kontak',
            'icon' => 'bi-building',
            'url' => 'company.php',
            'desc' => 'Profil PT Nihon Plast Indonesia, kantor pusat & pabrik',
            'group' => 'config',
            'group_label' => '5. KONFIGURASI SISTEM (CONFIG)'
        ],
        'users' => [
            'name' => 'Manajemen Pengguna & RBAC',
            'icon' => 'bi-people',
            'url' => 'users.php',
            'desc' => 'Kelola akun admin, role, dan izin CRUD per menu',
            'group' => 'config',
            'group_label' => '5. KONFIGURASI SISTEM (CONFIG)'
        ]
    ];
}

/**
 * Check if the currently logged in user is superadmin
 *
 * @return bool
 */
function is_superadmin() {
    return (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'superadmin');
}

/**
 * Check if user has permission for a specific menu and action
 * Actions: 'read', 'create', 'update', 'delete'
 *
 * @param string $menu_key
 * @param string $action
 * @param int|null $user_id
 * @return bool
 */
function has_permission($menu_key, $action = 'read', $user_id = null) {
    global $conn;

    if ($user_id === null) {
        $user_id = $_SESSION['admin_id'] ?? 0;
    }

    if (!$user_id) {
        return false;
    }

    // Superadmin has full bypass on all menus and actions
    if ($user_id === (int)($_SESSION['admin_id'] ?? 0) && is_superadmin()) {
        return true;
    }

    // Check user role from database if not superadmin
    $stmt = $conn->prepare("SELECT role, status FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$u || $u['status'] !== 'active') {
        return false;
    }

    if ($u['role'] === 'superadmin') {
        return true;
    }

    // Dashboard read is allowed for all active users
    if ($menu_key === 'dashboard' && $action === 'read') {
        return true;
    }

    // Inspect user_permissions table
    $stmt = $conn->prepare("SELECT can_create, can_read, can_update, can_delete FROM user_permissions WHERE user_id = ? AND menu_key = ? LIMIT 1");
    $stmt->bind_param("is", $user_id, $menu_key);
    $stmt->execute();
    $p = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$p) {
        return false;
    }

    switch (strtolower($action)) {
        case 'create':
            return (bool)$p['can_create'];
        case 'read':
            return (bool)$p['can_read'];
        case 'update':
            return (bool)$p['can_update'];
        case 'delete':
            return (bool)$p['can_delete'];
        default:
            return false;
    }
}

/**
 * Require permission or render 403 Forbidden screen
 *
 * @param string $menu_key
 * @param string $action
 */
function require_permission($menu_key, $action = 'read') {
    if (!has_permission($menu_key, $action)) {
        http_response_code(403);
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <?php include __DIR__ . "/../includes/head.php"; ?>
            <link rel="stylesheet" href="assets/admin.css">
            <title>NPI CMS - 403 Akses Ditolak | PT Nihon Plast Indonesia</title>
        </head>
        <body class="bg-light">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-6">
                        <div class="card border-0 shadow-sm text-center p-5 rounded-4">
                            <div class="mb-3 text-danger">
                                <i class="bi bi-shield-x display-1"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-2">403 - Akses Dibatasi</h2>
                            <p class="text-muted mb-4">
                                Akun Anda (<strong><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></strong> - Role: <strong><?= htmlspecialchars(strtoupper($_SESSION['admin_role'] ?? 'USER')) ?></strong>) tidak memiliki izin untuk melakukan tindakan <code><?= htmlspecialchars($action) ?></code> pada menu <code><?= htmlspecialchars($menu_key) ?></code>.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="dashboard.php" class="btn btn-primary px-4">
                                    <i class="bi bi-speedometer2 me-1"></i> Kembali ke Dashboard
                                </a>
                                <a href="javascript:history.back()" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}