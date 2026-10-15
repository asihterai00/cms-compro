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
            'name' => 'Dashboard',
            'icon' => 'bi-speedometer2',
            'url' => 'dashboard.php',
            'desc' => 'Ringkasan sistem & statistik'
        ],
        'site_sections' => [
            'name' => 'Dynamic CMS Elements',
            'icon' => 'bi-layout-text-window-reverse',
            'url' => 'site_sections.php',
            'desc' => 'Ubah teks, judul, icon, background, gambar & toggle aktif/nonaktif'
        ],
        'site_section_items' => [
            'name' => 'Sub-Item & Card Elements',
            'icon' => 'bi-collection',
            'url' => 'site_section_items.php',
            'desc' => 'Kelola kartu, pilar, zone kendaraan, OEM badges & sub-elemen'
        ],
        'banners' => [
            'name' => 'Page Banners',
            'icon' => 'bi-image',
            'url' => 'banners.php',
            'desc' => 'Banner header tiap halaman'
        ],
        'company' => [
            'name' => 'Company Profile',
            'icon' => 'bi-building',
            'url' => 'company.php',
            'desc' => 'Profil perusahaan, kontak & logo'
        ],
        'certifications' => [
            'name' => 'Certifications & ISO',
            'icon' => 'bi-patch-check',
            'url' => 'certifications.php',
            'desc' => 'Akreditasi IATF 16949, ISO & regulasi'
        ],
        'products' => [
            'name' => 'Products Management',
            'icon' => 'bi-box-seam',
            'url' => 'products.php',
            'desc' => 'Katalog 4 pilar produk manufaktur'
        ],
        'machinery' => [
            'name' => 'Machinery Matrix',
            'icon' => 'bi-cpu',
            'url' => 'machinery.php',
            'desc' => 'Spesifikasi mesin injeksi 150T-2500T'
        ],
        'gallery' => [
            'name' => 'Photo Gallery',
            'icon' => 'bi-camera',
            'url' => 'gallery.php',
            'desc' => 'Dokumentasi foto presisi manufaktur'
        ],
        'news' => [
            'name' => 'News & Updates',
            'icon' => 'bi-newspaper',
            'url' => 'news.php',
            'desc' => 'Berita & agenda korporat'
        ],
        'career' => [
            'name' => 'Career & Recruitment',
            'icon' => 'bi-briefcase',
            'url' => 'career.php',
            'desc' => 'Lowongan karir & rekrutmen'
        ],
        'messages' => [
            'name' => 'B2B RFQ Messages',
            'icon' => 'bi-envelope',
            'url' => 'messages.php',
            'desc' => 'Pesan formulir penawaran masuk'
        ],
        'users' => [
            'name' => 'Users & RBAC Authorization',
            'icon' => 'bi-people',
            'url' => 'users.php',
            'desc' => 'Pengguna & izin CRUD tiap menu'
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