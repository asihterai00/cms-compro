<?php
/**
 * PT Nihon Plast Indonesia - Universal CMS Record Delete AJAX Endpoint
 * 100% Free & Open-Source.
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

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
$id = (int)($input['id'] ?? 0);

if ($id <= 0 || empty($module)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid module or ID.']);
    exit;
}

$moduleMap = [
    'products'           => ['table' => 'products',           'entity' => 'product',           'perm' => 'products'],
    'site_sections'      => ['table' => 'site_sections',      'entity' => 'site_section',      'perm' => 'site_sections'],
    'site_section_items' => ['table' => 'site_section_items', 'entity' => 'site_section_item', 'perm' => 'site_section_items'],
    'certifications'     => ['table' => 'certifications',     'entity' => 'certification',     'perm' => 'certifications'],
    'facility_machinery' => ['table' => 'facility_machinery', 'entity' => 'facility_machinery', 'perm' => 'machinery'],
    'gallery'            => ['table' => 'gallery',            'entity' => 'gallery',            'perm' => 'gallery'],
    'news'               => ['table' => 'news',               'entity' => 'news',               'perm' => 'news'],
    'careers'            => ['table' => 'careers',            'entity' => 'career',            'perm' => 'career'],
    'messages'           => ['table' => 'messages',           'entity' => 'message',           'perm' => 'messages']
];

if (!isset($moduleMap[$module])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => "Module '$module' does not allow deletion."]);
    exit;
}

$cfg = $moduleMap[$module];
$table = $cfg['table'];
$entityType = $cfg['entity'];
$permKey = $cfg['perm'];

if (!has_permission($permKey, 'delete')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => "Permission denied for deleting in module '$module'."]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Also delete any translations
    $tStmt = $conn->prepare("DELETE FROM content_translations WHERE entity_type = ? AND entity_id = ?");
    if ($tStmt) {
        $tStmt->bind_param("si", $entityType, $id);
        $tStmt->execute();
        $tStmt->close();
    }

    echo json_encode([
        'success' => true,
        'id'      => $id,
        'message' => 'Data berhasil dihapus dari database MySQL.',
        'module'  => $module
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database delete error: ' . $e->getMessage()]);
    exit;
}
