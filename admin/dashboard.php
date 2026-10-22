<?php
/**
 * CMS Company Profile — PT Nihon Plast Indonesia
 * Executive Dashboard V14 — Modern & Minimal UI
 * Inspired by Dashboard V14 (@code.xr)
 * Built with React, Tailwind CSS, Lucide React, Recharts, Plus Jakarta Sans
 * 100% Free & Open Source | Zero Paid External APIs
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

require_permission('dashboard', 'read');

// Prepare Current User for React app
$currentUser = [
    'id'        => (int)($_SESSION['admin_id'] ?? 1),
    'username'  => $_SESSION['admin_username'] ?? 'administrator',
    'full_name' => $_SESSION['admin_full_name'] ?? 'Administrator',
    'email'     => $_SESSION['admin_email'] ?? 'admin@nihonplast.co.id',
    'role'      => $_SESSION['admin_role'] ?? 'superadmin',
    'status'    => 'active'
];

// Helper to safely fetch table records from MySQL
function fetchTableRows($conn, $query, $transform = null) {
    $rows = [];
    $res = $conn->query($query);
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $transform ? $transform($row) : $row;
        }
    }
    return $rows;
}

// 1. Products (4 automotive product pillars fallback if DB empty)
$dbProducts = fetchTableRows($conn, "SELECT id, name, description, image FROM products ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'name'        => (string)$r['name'],
        'description' => (string)($r['description'] ?? ''),
        'image'       => (string)($r['image'] ?? '')
    ];
});

if (empty($dbProducts)) {
    $dbProducts = [
        ['id' => 1, 'name' => 'Steering Wheel', 'description' => 'Driver interface components for automotive OEM programs.', 'image' => ''],
        ['id' => 2, 'name' => 'Airbag Module', 'description' => 'Occupant safety module assemblies for vehicle platforms.', 'image' => ''],
        ['id' => 3, 'name' => 'Interior Parts', 'description' => 'Precision interior trim and functional cabin components.', 'image' => ''],
        ['id' => 4, 'name' => 'Resin Parts', 'description' => 'Injection-molded resin components for automotive applications.', 'image' => '']
    ];
}

// 2. Messages (B2B RFQ Inbox)
$dbMessages = fetchTableRows($conn, "SELECT id, name, email, message, status, created_at FROM messages ORDER BY id DESC LIMIT 50", function($r) {
    return [
        'id'         => (int)$r['id'],
        'name'       => (string)$r['name'],
        'email'      => (string)$r['email'],
        'message'    => (string)$r['message'],
        'status'     => (string)$r['status'],
        'created_at' => (string)$r['created_at']
    ];
});

// 3. Users & RBAC
$dbUsers = fetchTableRows($conn, "SELECT id, username, full_name, email, role, status FROM users ORDER BY id ASC", function($r) {
    return [
        'id'        => (int)$r['id'],
        'username'  => (string)$r['username'],
        'full_name' => (string)$r['full_name'],
        'email'     => (string)$r['email'],
        'role'      => (string)$r['role'],
        'status'    => (string)$r['status']
    ];
});

// 4. News
$dbNews = fetchTableRows($conn, "SELECT id, title, content, image, published_at FROM news ORDER BY id DESC LIMIT 20", function($r) {
    return [
        'id'           => (int)$r['id'],
        'title'        => (string)$r['title'],
        'content'      => (string)($r['content'] ?? ''),
        'image'        => (string)($r['image'] ?? ''),
        'published_at' => (string)($r['published_at'] ?? date('Y-m-d'))
    ];
});

// 5. Careers
$dbCareers = fetchTableRows($conn, "SELECT id, position, department, location, description FROM careers ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'position'    => (string)$r['position'],
        'department'  => (string)$r['department'],
        'location'    => (string)($r['location'] ?? 'Karawang, West Java'),
        'description' => (string)($r['description'] ?? '')
    ];
});

// 6. Certifications
$dbCerts = fetchTableRows($conn, "SELECT id, name, category, certificate_number, issued_by, valid_until, status FROM certifications ORDER BY id ASC", function($r) {
    return [
        'id'                 => (int)$r['id'],
        'name'               => (string)$r['name'],
        'category'           => (string)($r['category'] ?? 'Automotive & Quality'),
        'certificate_number' => (string)($r['certificate_number'] ?? ''),
        'issued_by'          => (string)($r['issued_by'] ?? ''),
        'valid_until'        => (string)($r['valid_until'] ?? '2026-12-31'),
        'status'             => (string)($r['status'] ?? 'active')
    ];
});

// 7. Machinery (150T - 2500T)
$dbMachinery = fetchTableRows($conn, "SELECT id, category, model_brand, tonnage, application, automation, is_active FROM facility_machinery ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'category'    => (string)$r['category'],
        'model_brand' => (string)$r['model_brand'],
        'tonnage'     => (string)$r['tonnage'],
        'application' => (string)($r['application'] ?? ''),
        'automation'  => (string)($r['automation'] ?? ''),
        'is_active'   => (bool)$r['is_active']
    ];
});

// 8. Gallery
$dbGallery = fetchTableRows($conn, "SELECT id, title, image FROM gallery ORDER BY id ASC", function($r) {
    return [
        'id'    => (int)$r['id'],
        'title' => (string)$r['title'],
        'image' => (string)($r['image'] ?? '')
    ];
});

// 9. Site Sections
$dbSections = fetchTableRows($conn, "SELECT id, page, section_key, title, content, is_active FROM site_sections ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'page'        => (string)$r['page'],
        'section_key' => (string)$r['section_key'],
        'title'       => (string)($r['title'] ?? ''),
        'content'     => (string)($r['content'] ?? ''),
        'is_active'   => (bool)$r['is_active']
    ];
});

// 10. Site Section Items
$dbSectionItems = fetchTableRows($conn, "SELECT id, section_key, item_key, title, content FROM site_section_items ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'section_key' => (string)$r['section_key'],
        'item_key'    => (string)$r['item_key'],
        'title'       => (string)($r['title'] ?? ''),
        'content'     => (string)($r['content'] ?? '')
    ];
});

// 11. Banners
$dbBanners = fetchTableRows($conn, "SELECT id, page_key, page_title, subtitle, image FROM banners ORDER BY id ASC", function($r) {
    return [
        'id'         => (int)$r['id'],
        'page_key'   => (string)$r['page_key'],
        'page_title' => (string)($r['page_title'] ?? ''),
        'subtitle'   => (string)($r['subtitle'] ?? ''),
        'image'      => (string)($r['image'] ?? '')
    ];
});

// 12. Company Profile
$dbCompany = fetchTableRows($conn, "SELECT id, name, description, email FROM company ORDER BY id ASC", function($r) {
    return [
        'id'          => (int)$r['id'],
        'name'        => (string)$r['name'],
        'description' => (string)($r['description'] ?? ''),
        'email'       => (string)($r['email'] ?? '')
    ];
});

// 13. Translations
$dbTranslations = fetchTableRows($conn, "SELECT id, entity_type, entity_id, lang_code, field_name, translation_value FROM translations ORDER BY id ASC LIMIT 100", function($r) {
    return [
        'id'                => (int)$r['id'],
        'entity_type'       => (string)$r['entity_type'],
        'entity_id'         => (int)$r['entity_id'],
        'lang_code'         => (string)$r['lang_code'],
        'field_name'        => (string)$r['field_name'],
        'translation_value' => (string)$r['translation_value']
    ];
});

// 14. Media
$dbMedia = fetchTableRows($conn, "SELECT id, name, path, category FROM media ORDER BY id DESC LIMIT 50", function($r) {
    return [
        'id'       => (int)$r['id'],
        'name'     => (string)$r['name'],
        'path'     => (string)$r['path'],
        'category' => (string)($r['category'] ?? 'general')
    ];
});

// Bundle CMS initial data for React
$cmsData = [];
if (!empty($dbProducts))     $cmsData['products']           = $dbProducts;
if (!empty($dbMessages))     $cmsData['messages']           = $dbMessages;
if (!empty($dbUsers))        $cmsData['users']              = $dbUsers;
if (!empty($dbNews))         $cmsData['news']               = $dbNews;
if (!empty($dbCareers))      $cmsData['careers']            = $dbCareers;
if (!empty($dbCerts))        $cmsData['certifications']     = $dbCerts;
if (!empty($dbMachinery))    $cmsData['facility_machinery'] = $dbMachinery;
if (!empty($dbGallery))      $cmsData['gallery']            = $dbGallery;
if (!empty($dbSections))     $cmsData['site_sections']      = $dbSections;
if (!empty($dbSectionItems)) $cmsData['site_section_items'] = $dbSectionItems;
if (!empty($dbBanners))      $cmsData['banners']            = $dbBanners;
if (!empty($dbCompany))      $cmsData['company']            = $dbCompany;
if (!empty($dbTranslations)) $cmsData['translations']       = $dbTranslations;
if (!empty($dbMedia))        $cmsData['media']              = $dbMedia;

$cssVersion = file_exists(__DIR__ . '/dist/assets/index.css') ? filemtime(__DIR__ . '/dist/assets/index.css') : time();
$jsVersion  = file_exists(__DIR__ . '/dist/assets/index.js')  ? filemtime(__DIR__ . '/dist/assets/index.js')  : time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F8FAFC">
    <meta name="description" content="Company profile CMS for PT Nihon Plast Indonesia">
    <title>CMS Company Profile — PT Nihon Plast Indonesia | Dashboard V14</title>

    <!-- Google Fonts: Plus Jakarta Sans (300, 400, 500, 600, 700, 800) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Dashboard V14 Production Stylesheet (React + Tailwind CSS) -->
    <link rel="stylesheet" href="dist/assets/index.css?v=<?= $cssVersion ?>">
</head>
<body>
    <!-- React Root Mount Container -->
    <div id="root"></div>

    <!-- Pass Session User & Database Fixtures to React SPA -->
    <script>
        window.__CMS_DATA__ = <?= json_encode($cmsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
        window.__CURRENT_USER__ = <?= json_encode($currentUser, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
        window.__PUBLIC_URL__ = '../index.php';
    </script>

    <!-- Dashboard V14 Production Bundle (React 18, Tailwind, Lucide, Recharts) -->
    <script type="module" src="dist/assets/index.js?v=<?= $jsVersion ?>"></script>

    <noscript>
        <div style="padding: 2rem; font-family: 'Plus Jakarta Sans', sans-serif; text-align: center;">
            <h2>JavaScript Required</h2>
            <p>Please enable JavaScript in your browser to access the PT Nihon Plast Indonesia Executive Dashboard.</p>
        </div>
    </noscript>
</body>
</html>
