<?php
/**
 * PT Nihon Plast Indonesia - Universal Re-Order API & Controller
 * Supports Drag-and-Drop, Move Up/Down, and Numeric Inline Re-ordering across CMS elements.
 * 100% Free & Open Source.
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';

if (!headers_sent()) {
    header('Content-Type: application/json; charset=UTF-8');
}

// Whitelisted tables for re-ordering
$allowedTables = [
    'site_sections'       => 'site_sections',
    'site_section_items' => 'site_section_items',
    'products'            => 'products',
    'certifications'      => 'certifications',
    'facility_machinery'  => 'machinery',
    'gallery'             => 'gallery',
    'news'                => 'news',
    'careers'             => 'career'
];

$table = trim($_REQUEST['table'] ?? '');
if (!array_key_exists($table, $allowedTables)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tabel tidak valid untuk operasi re-order.']);
    exit;
}

$permKey = $allowedTables[$table];
if (!has_permission($permKey, 'update')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk mengubah urutan data ini.']);
    exit;
}

// 1. Batch Re-Order (Drag-and-Drop Order Array)
if (isset($_POST['order_ids']) && is_array($_POST['order_ids'])) {
    $ids = array_map('intval', $_POST['order_ids']);
    if (!empty($ids)) {
        $stmt = $conn->prepare("UPDATE `$table` SET sort_order = ? WHERE id = ?");
        if ($stmt) {
            $step = 10;
            $pos = 10;
            foreach ($ids as $id) {
                if ($id > 0) {
                    $stmt->bind_param("ii", $pos, $id);
                    $stmt->execute();
                    $pos += $step;
                }
            }
            $stmt->close();
            echo json_encode(['success' => true, 'message' => 'Urutan elemen berhasil diperbarui.']);
            exit;
        }
    }
}

// 2. Direct Value Set (e.g. inline number change)
if (isset($_POST['id']) && isset($_POST['sort_order'])) {
    $id = (int)$_POST['id'];
    $order = (int)$_POST['sort_order'];
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE `$table` SET sort_order = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $order, $id);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true, 'id' => $id, 'sort_order' => $order, 'message' => 'Urutan berhasil disimpan.']);
            exit;
        }
    }
}

// 3. Move Up or Down Action (Single Swap)
$action = trim($_REQUEST['action'] ?? '');
$id = (int)($_REQUEST['id'] ?? 0);

if ($id > 0 && ($action === 'move_up' || $action === 'move_down')) {
    // Determine scope condition (e.g. same page for site_sections, same section_key for site_section_items)
    $scopeCol = null;
    $scopeVal = null;
    $currentOrder = 0;

    if ($table === 'site_sections') {
        $stmtScope = $conn->prepare("SELECT page, sort_order FROM site_sections WHERE id = ?");
        $stmtScope->bind_param("i", $id);
        $stmtScope->execute();
        $r = $stmtScope->get_result()->fetch_assoc();
        $stmtScope->close();
        $scopeCol = 'page';
        $scopeVal = $r['page'] ?? null;
        $currentOrder = (int)($r['sort_order'] ?? 0);
    } elseif ($table === 'site_section_items') {
        $stmtScope = $conn->prepare("SELECT section_key, sort_order FROM site_section_items WHERE id = ?");
        $stmtScope->bind_param("i", $id);
        $stmtScope->execute();
        $r = $stmtScope->get_result()->fetch_assoc();
        $stmtScope->close();
        $scopeCol = 'section_key';
        $scopeVal = $r['section_key'] ?? null;
        $currentOrder = (int)($r['sort_order'] ?? 0);
    } else {
        $stmtScope = $conn->prepare("SELECT sort_order FROM `$table` WHERE id = ?");
        $stmtScope->bind_param("i", $id);
        $stmtScope->execute();
        $r = $stmtScope->get_result()->fetch_assoc();
        $stmtScope->close();
        $currentOrder = (int)($r['sort_order'] ?? 0);
    }

    $target = null;
    if ($scopeCol !== null && $scopeVal !== null) {
        if ($action === 'move_up') {
            $tStmt = $conn->prepare("SELECT id, sort_order FROM `$table` WHERE sort_order <= ? AND id != ? AND `$scopeCol` = ? ORDER BY sort_order DESC, id DESC LIMIT 1");
            $tStmt->bind_param("iis", $currentOrder, $id, $scopeVal);
        } else {
            $tStmt = $conn->prepare("SELECT id, sort_order FROM `$table` WHERE sort_order >= ? AND id != ? AND `$scopeCol` = ? ORDER BY sort_order ASC, id ASC LIMIT 1");
            $tStmt->bind_param("iis", $currentOrder, $id, $scopeVal);
        }
        $tStmt->execute();
        $target = $tStmt->get_result()->fetch_assoc();
        $tStmt->close();
    } else {
        if ($action === 'move_up') {
            $tStmt = $conn->prepare("SELECT id, sort_order FROM `$table` WHERE sort_order <= ? AND id != ? ORDER BY sort_order DESC, id DESC LIMIT 1");
            $tStmt->bind_param("ii", $currentOrder, $id);
        } else {
            $tStmt = $conn->prepare("SELECT id, sort_order FROM `$table` WHERE sort_order >= ? AND id != ? ORDER BY sort_order ASC, id ASC LIMIT 1");
            $tStmt->bind_param("ii", $currentOrder, $id);
        }
        $tStmt->execute();
        $target = $tStmt->get_result()->fetch_assoc();
        $tStmt->close();
    }

    if ($target) {
        $targetId = (int)$target['id'];
        $targetOrder = (int)$target['sort_order'];

        if ($targetOrder === $currentOrder) {
            $targetOrder = ($action === 'move_up') ? max(0, $currentOrder - 10) : ($currentOrder + 10);
        }

        // Swap orders with prepared statements
        $updStmt1 = $conn->prepare("UPDATE `$table` SET sort_order = ? WHERE id = ?");
        $updStmt1->bind_param("ii", $targetOrder, $id);
        $updStmt1->execute();
        $updStmt1->close();

        $updStmt2 = $conn->prepare("UPDATE `$table` SET sort_order = ? WHERE id = ?");
        $updStmt2->bind_param("ii", $currentOrder, $targetId);
        $updStmt2->execute();
        $updStmt2->close();
    }

    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 1)
              || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
              || (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] === 'empty');

    if ($isAjax) {
        echo json_encode(['success' => true, 'id' => $id, 'message' => 'Posisi urutan berhasil ditukar.']);
        exit;
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
        header("Location: $referer");
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Aksi re-order tidak dikenal.']);
