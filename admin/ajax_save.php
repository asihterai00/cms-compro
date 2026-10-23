<?php
/**
 * PT Nihon Plast Indonesia - Universal CMS Record Save & Update AJAX Endpoint
 * Directly persists record changes and multi-language translations into MySQL database.
 * 100% Free & Open-Source.
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/translation_helpers.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$module = trim($input['module'] ?? '');
$record = $input['record'] ?? [];
$translations = $input['translations'] ?? [];

if (!is_array($record) || empty($module)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid payload. Module and record are required.']);
    exit;
}

// Module to table & entity map
$moduleMap = [
    'products'           => ['table' => 'products',           'entity' => 'product',           'perm' => 'products'],
    'site_sections'      => ['table' => 'site_sections',      'entity' => 'site_section',      'perm' => 'site_sections'],
    'site_section_items' => ['table' => 'site_section_items', 'entity' => 'site_section_item', 'perm' => 'site_section_items'],
    'certifications'     => ['table' => 'certifications',     'entity' => 'certification',     'perm' => 'certifications'],
    'facility_machinery' => ['table' => 'facility_machinery', 'entity' => 'facility_machinery', 'perm' => 'machinery'],
    'gallery'            => ['table' => 'gallery',            'entity' => 'gallery',            'perm' => 'gallery'],
    'news'               => ['table' => 'news',               'entity' => 'news',               'perm' => 'news'],
    'careers'            => ['table' => 'careers',            'entity' => 'career',            'perm' => 'career'],
    'banners'            => ['table' => 'page_banners',       'entity' => 'page_banner',       'perm' => 'banners'],
    'company'            => ['table' => 'company_profile',    'entity' => 'company_profile',    'perm' => 'company'],
    'messages'           => ['table' => 'messages',           'entity' => 'message',           'perm' => 'messages']
];

if (!isset($moduleMap[$module])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => "Module '$module' is not supported for save."]);
    exit;
}

$cfg = $moduleMap[$module];
$table = $cfg['table'];
$entityType = $cfg['entity'];
$permKey = $cfg['perm'];

$id = (int)($record['id'] ?? 0);
$action = ($id > 0) ? 'update' : 'create';

if (!has_permission($permKey, $action)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => "Permission denied for module '$module'."]);
    exit;
}

$savedId = $id;

try {
    switch ($module) {
        case 'site_sections':
            $page        = trim($record['page'] ?? 'home');
            $section_key = trim($record['section_key'] ?? '');
            $title       = trim($record['title'] ?? '');
            $subtitle    = trim($record['subtitle'] ?? '');
            $content     = trim($record['content'] ?? '');
            $badge_text  = trim($record['badge_text'] ?? '');
            $icon        = trim($record['icon'] ?? 'bi-shield-check');
            $image       = trim($record['image'] ?? '');
            $bg_image    = trim($record['bg_image'] ?? '');
            $button_text = trim($record['button_text'] ?? '');
            $button_link = trim($record['button_link'] ?? '');
            $is_active   = isset($record['is_active']) ? ((bool)$record['is_active'] ? 1 : 0) : 1;
            $sort_order  = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE site_sections SET page=?, section_key=?, title=?, subtitle=?, content=?, badge_text=?, icon=?, image=?, bg_image=?, button_text=?, button_link=?, is_active=?, sort_order=? WHERE id=?");
                $stmt->bind_param("sssssssssssiii", $page, $section_key, $title, $subtitle, $content, $badge_text, $icon, $image, $bg_image, $button_text, $button_link, $is_active, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO site_sections (page, section_key, title, subtitle, content, badge_text, icon, image, bg_image, button_text, button_link, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssssii", $page, $section_key, $title, $subtitle, $content, $badge_text, $icon, $image, $bg_image, $button_text, $button_link, $is_active, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'site_section_items':
            $section_key = trim($record['section_key'] ?? 'home_intro');
            $item_key    = trim($record['item_key'] ?? '');
            $title       = trim($record['title'] ?? '');
            $subtitle    = trim($record['subtitle'] ?? '');
            $content     = trim($record['content'] ?? '');
            $badge       = trim($record['badge'] ?? '');
            $icon        = trim($record['icon'] ?? 'bi-check-circle');
            $image       = trim($record['image'] ?? '');
            $sort_order  = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;
            $is_active   = isset($record['is_active']) ? ((bool)$record['is_active'] ? 1 : 0) : 1;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE site_section_items SET section_key=?, item_key=?, title=?, subtitle=?, content=?, badge=?, icon=?, image=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?");
                $stmt->bind_param("ssssssssiii", $section_key, $item_key, $title, $subtitle, $content, $badge, $icon, $image, $sort_order, $is_active, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO site_section_items (section_key, item_key, title, subtitle, content, badge, icon, image, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssii", $section_key, $item_key, $title, $subtitle, $content, $badge, $icon, $image, $sort_order, $is_active);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'products':
            $name        = trim($record['name'] ?? '');
            $description = trim($record['description'] ?? '');
            $image       = trim($record['image'] ?? '');
            $sort_order  = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE products SET name=?, description=?, image=?, sort_order=? WHERE id=?");
                $stmt->bind_param("sssii", $name, $description, $image, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO products (name, description, image, sort_order) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $name, $description, $image, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'news':
            $title        = trim($record['title'] ?? '');
            $content      = trim($record['content'] ?? '');
            $image        = trim($record['image'] ?? '');
            $published_at = trim($record['published_at'] ?? date('Y-m-d'));
            $sort_order   = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE news SET title=?, content=?, image=?, published_at=?, sort_order=? WHERE id=?");
                $stmt->bind_param("ssssii", $title, $content, $image, $published_at, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO news (title, content, image, published_at, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $title, $content, $image, $published_at, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'careers':
            $position    = trim($record['position'] ?? '');
            $department  = trim($record['department'] ?? 'Operations');
            $location    = trim($record['location'] ?? 'Karawang, West Java');
            $description = trim($record['description'] ?? '');
            $sort_order  = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE careers SET position=?, department=?, location=?, description=?, sort_order=? WHERE id=?");
                $stmt->bind_param("ssssii", $position, $department, $location, $description, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO careers (position, department, location, description, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $position, $department, $location, $description, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'certifications':
            $name               = trim($record['name'] ?? '');
            $category           = trim($record['category'] ?? 'Automotive & Quality');
            $certificate_number = trim($record['certificate_number'] ?? '');
            $issued_by          = trim($record['issued_by'] ?? '');
            $valid_until        = trim($record['valid_until'] ?? '2026-12-31');
            $status             = trim($record['status'] ?? 'active');
            $sort_order         = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE certifications SET name=?, category=?, certificate_number=?, issued_by=?, valid_until=?, status=?, sort_order=? WHERE id=?");
                $stmt->bind_param("ssssssii", $name, $category, $certificate_number, $issued_by, $valid_until, $status, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO certifications (name, category, certificate_number, issued_by, valid_until, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssi", $name, $category, $certificate_number, $issued_by, $valid_until, $status, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'facility_machinery':
            $category    = trim($record['category'] ?? 'Injection molding');
            $model_brand = trim($record['model_brand'] ?? '');
            $tonnage     = trim($record['tonnage'] ?? '150T');
            $application = trim($record['application'] ?? '');
            $automation  = trim($record['automation'] ?? '');
            $is_active   = isset($record['is_active']) ? ((bool)$record['is_active'] ? 1 : 0) : 1;
            $sort_order  = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE facility_machinery SET category=?, model_brand=?, tonnage=?, application=?, automation=?, is_active=?, sort_order=? WHERE id=?");
                $stmt->bind_param("sssssiii", $category, $model_brand, $tonnage, $application, $automation, $is_active, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO facility_machinery (category, model_brand, tonnage, application, automation, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssii", $category, $model_brand, $tonnage, $application, $automation, $is_active, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'gallery':
            $title      = trim($record['title'] ?? '');
            $image      = trim($record['image'] ?? '');
            $sort_order = isset($record['sort_order']) ? (int)$record['sort_order'] : 10;

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE gallery SET title=?, image=?, sort_order=? WHERE id=?");
                $stmt->bind_param("ssii", $title, $image, $sort_order, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO gallery (title, image, sort_order) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $title, $image, $sort_order);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;

        case 'company':
            $company_name      = trim($record['name'] ?? ($record['company_name'] ?? 'PT Nihon Plast Indonesia'));
            $short_description = trim($record['description'] ?? ($record['short_description'] ?? ''));
            $tagline           = trim($record['tagline'] ?? '');
            $about_text        = trim($record['about_text'] ?? '');
            $vision            = trim($record['vision'] ?? '');
            $mission           = trim($record['mission'] ?? '');
            $values_text       = trim($record['values_text'] ?? '');
            $email             = trim($record['email'] ?? 'contact@nihonplast.co.id');

            $stmt = $conn->prepare("UPDATE company_profile SET company_name=?, short_description=?, tagline=?, about_text=?, vision=?, mission=?, values_text=?, email=? WHERE id=1");
            $stmt->bind_param("ssssssss", $company_name, $short_description, $tagline, $about_text, $vision, $mission, $values_text, $email);
            $stmt->execute();
            $stmt->close();
            $savedId = 1;
            break;

        case 'messages':
            $status = trim($record['status'] ?? 'read');
            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE messages SET status=? WHERE id=?");
                $stmt->bind_param("si", $status, $id);
                $stmt->execute();
                $stmt->close();
            }
            break;

        case 'banners':
            $page_key   = trim($record['page_key'] ?? '');
            $page_title = trim($record['page_title'] ?? ($record['title'] ?? ''));
            $subtitle   = trim($record['subtitle'] ?? '');
            $image      = trim($record['image'] ?? '');

            if ($id > 0) {
                $stmt = $conn->prepare("UPDATE page_banners SET page_key=?, page_title=?, subtitle=?, image=? WHERE id=?");
                $stmt->bind_param("ssssi", $page_key, $page_title, $subtitle, $image, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO page_banners (page_key, page_title, subtitle, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $page_key, $page_title, $subtitle, $image);
                $stmt->execute();
                $savedId = $conn->insert_id;
                $stmt->close();
            }
            break;
    }

    // Save multi-language translations if provided
    if (!empty($translations) && is_array($translations) && $savedId > 0 && function_exists('save_entity_translations')) {
        save_entity_translations($entityType, $savedId, $translations);
    }

    echo json_encode([
        'success' => true,
        'id'      => $savedId,
        'message' => 'Data berhasil disimpan ke database MySQL.',
        'module'  => $module
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
