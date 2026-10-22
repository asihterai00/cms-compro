<?php
/**
 * PT Nihon Plast Indonesia - Dynamic Multilingual Content Helpers
 * Manages database-backed translations stored in `content_translations` table.
 * 100% Free & Open-Source, zero paid external APIs.
 */

require_once __DIR__ . '/../config/database.php';
if (!function_exists('current_lang')) {
    require_once __DIR__ . '/lang.php';
}

/**
 * In-memory translation registry
 * Structure: $GLOBALS['__CMS_TRANSLATIONS'][entity_type][entity_id][lang_code][field_name] = translation_value
 */
$GLOBALS['__CMS_TRANSLATIONS'] = [];

/**
 * Pre-load translations for multiple entities in a single query
 *
 * @param string $entityType
 * @param array $entityIds
 * @param string|null $langCode
 * @return void
 */
function load_translations_bulk($entityType, array $entityIds, $langCode = null) {
    global $conn;
    if ($langCode === null) {
        $langCode = current_lang();
    }
    if ($langCode === 'id' || empty($entityIds) || !isset($conn) || !($conn instanceof mysqli)) {
        return;
    }

    $sanitizedIds = [];
    foreach ($entityIds as $id) {
        $id = (int)$id;
        if ($id > 0 && !isset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$id][$langCode])) {
            $sanitizedIds[] = $id;
        }
    }

    if (empty($sanitizedIds)) {
        return;
    }

    $inClause = implode(',', $sanitizedIds);
    $stmt = $conn->prepare("SELECT entity_id, field_name, translation_value FROM content_translations WHERE entity_type = ? AND entity_id IN ($inClause) AND lang_code = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $entityType, $langCode);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $eId = (int)$row['entity_id'];
            $fName = $row['field_name'];
            $tVal = $row['translation_value'];
            $GLOBALS['__CMS_TRANSLATIONS'][$entityType][$eId][$langCode][$fName] = $tVal;
        }
        $stmt->close();
    }

    // Mark missing entities as cached empty so we don't re-query
    foreach ($sanitizedIds as $id) {
        if (!isset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$id][$langCode])) {
            $GLOBALS['__CMS_TRANSLATIONS'][$entityType][$id][$langCode] = [];
        }
    }
}

/**
 * Get a specific content translation from database
 *
 * @param string $entityType
 * @param int $entityId
 * @param string $fieldName
 * @param string|null $langCode
 * @param mixed $default
 * @return mixed
 */
function get_content_translation($entityType, $entityId, $fieldName, $langCode = null, $default = null) {
    global $conn;
    $entityId = (int)$entityId;
    if ($entityId <= 0) {
        return $default;
    }

    if ($langCode === null) {
        $langCode = current_lang();
    }

    if ($langCode === 'id') {
        return $default;
    }

    if (!isset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$entityId][$langCode])) {
        load_translations_bulk($entityType, [$entityId], $langCode);
    }

    if (isset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$entityId][$langCode][$fieldName])) {
        $val = $GLOBALS['__CMS_TRANSLATIONS'][$entityType][$entityId][$langCode][$fieldName];
        if ($val !== '' && $val !== null) {
            return $val;
        }
    }

    return $default;
}

/**
 * Translate an associative row array fetched from database
 * Automatically overlays translated fields for the active language.
 *
 * @param string $entityType
 * @param array $row
 * @param string|null $langCode
 * @return array
 */
function translate_entity_row($entityType, array $row, $langCode = null) {
    if (empty($row) || !isset($row['id'])) {
        return $row;
    }

    if ($langCode === null) {
        $langCode = current_lang();
    }

    if ($langCode === 'id') {
        return $row;
    }

    $id = (int)$row['id'];
    if (!isset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$id][$langCode])) {
        load_translations_bulk($entityType, [$id], $langCode);
    }

    $trans = $GLOBALS['__CMS_TRANSLATIONS'][$entityType][$id][$langCode] ?? [];
    foreach ($trans as $field => $val) {
        if ($val !== '' && $val !== null && array_key_exists($field, $row)) {
            $row[$field] = $val;
        }
    }

    return $row;
}

/**
 * Translate multiple rows fetched from database
 * Performs efficient single-query bulk lookup.
 *
 * @param string $entityType
 * @param array $rows
 * @param string|null $langCode
 * @return array
 */
function translate_entity_rows($entityType, array $rows, $langCode = null) {
    if (empty($rows)) {
        return $rows;
    }

    if ($langCode === null) {
        $langCode = current_lang();
    }

    if ($langCode === 'id') {
        return $rows;
    }

    $ids = [];
    foreach ($rows as $r) {
        if (isset($r['id'])) {
            $ids[] = (int)$r['id'];
        }
    }

    load_translations_bulk($entityType, $ids, $langCode);

    $result = [];
    foreach ($rows as $k => $r) {
        $result[$k] = translate_entity_row($entityType, $r, $langCode);
    }

    return $result;
}

/**
 * Save or update a single translation in content_translations table
 *
 * @param string $entityType
 * @param int $entityId
 * @param string $langCode
 * @param string $fieldName
 * @param string $value
 * @return bool
 */
function save_content_translation($entityType, $entityId, $langCode, $fieldName, $value) {
    global $conn;
    $entityId = (int)$entityId;
    $langCode = trim(strtolower($langCode));
    $fieldName = trim($fieldName);
    $value = trim($value);

    if ($entityId <= 0 || $langCode === '' || $fieldName === '' || !isset($conn)) {
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO content_translations (entity_type, entity_id, lang_code, field_name, translation_value)
                            VALUES (?, ?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value), updated_at = NOW()");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("sisss", $entityType, $entityId, $langCode, $fieldName, $value);
    $ok = $stmt->execute();
    $stmt->close();

    // Invalidate local memory cache
    unset($GLOBALS['__CMS_TRANSLATIONS'][$entityType][$entityId][$langCode][$fieldName]);

    return $ok;
}

/**
 * Get all existing translations for an entity across all languages
 * Returns: ['en' => ['name' => '...', 'desc' => '...'], 'ja' => [...], ...]
 *
 * @param string $entityType
 * @param int $entityId
 * @return array
 */
function get_entity_all_translations($entityType, $entityId) {
    global $conn;
    $entityId = (int)$entityId;
    $res = [];
    if ($entityId <= 0 || !isset($conn)) {
        return $res;
    }

    $stmt = $conn->prepare("SELECT lang_code, field_name, translation_value FROM content_translations WHERE entity_type = ? AND entity_id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $entityType, $entityId);
        $stmt->execute();
        $q = $stmt->get_result();
        while ($row = $q->fetch_assoc()) {
            $res[$row['lang_code']][$row['field_name']] = $row['translation_value'];
        }
        $stmt->close();
    }
    return $res;
}

/**
 * Save multi-language translations array for an entity
 * Expected format:
 * [
 *   'en' => ['name' => '...', 'description' => '...'],
 *   'ja' => ['name' => '...', 'description' => '...'],
 *   'zh' => [...],
 *   'ko' => [...]
 * ]
 *
 * @param string $entityType
 * @param int $entityId
 * @param array $langData
 * @return int Number of saved fields
 */
function save_entity_translations($entityType, $entityId, array $langData) {
    $count = 0;
    foreach ($langData as $langCode => $fields) {
        if (!is_array($fields)) continue;
        foreach ($fields as $fieldName => $val) {
            if (save_content_translation($entityType, $entityId, $langCode, $fieldName, (string)$val)) {
                $count++;
            }
        }
    }
    return $count;
}
