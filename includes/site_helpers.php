<?php
/**
 * PT Nihon Plast Indonesia - Global Dynamic CMS & Section Helpers
 * Supports 100% dynamic element rendering and active/inactive toggle across all pages
 */

// Direct invocation guard
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    http_response_code(403);
    exit('Access Denied');
}

require_once __DIR__ . '/translation_helpers.php';

/**
 * Load all sections for a specific page from site_sections table
 *
 * @param string $page
 * @param bool $resetCache
 * @return array
 */
function get_page_sections($page, $resetCache = false) {
    global $conn;
    static $cachedPageSections = [];

    if ($resetCache) {
        if ($page === null) {
            $cachedPageSections = [];
            return [];
        }
        unset($cachedPageSections[$page]);
    }

    if (!isset($cachedPageSections[$page])) {
        $cachedPageSections[$page] = [];
        if (isset($conn) && $conn instanceof mysqli) {
            $stmt = $conn->prepare("SELECT * FROM site_sections WHERE page = ? ORDER BY sort_order ASC, id ASC");
            if ($stmt) {
                $stmt->bind_param("s", $page);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $cachedPageSections[$page][$row['section_key']] = $row;
                }
                $stmt->close();
            }
        }
    }
    return $cachedPageSections[$page];
}

/**
 * Clear static cache of page sections (all pages or specific page)
 *
 * @param string|null $page
 * @return void
 */
function clear_page_sections_cache($page = null) {
    get_page_sections($page, true);
}

/**
 * Check if a specific page section is active (is_active == 1)
 *
 * @param string $page
 * @param string $section_key
 * @param bool $default
 * @return bool
 */
function is_section_active($page, $section_key, $default = true) {
    $sections = get_page_sections($page);
    if (isset($sections[$section_key])) {
        return (bool)$sections[$section_key]['is_active'];
    }
    return $default;
}

/**
 * Clean public URL: strip .php extension and ensure clean routing
 *
 * @param string $url
 * @return string
 */
function clean_public_url($url) {
    if (!is_string($url) || trim($url) === '') return $url;
    $url = trim($url);
    // Don't modify external links (http/https not pointing to localhost or current domain)
    if (preg_match('~^https?://~i', $url) && !preg_match('~^(https?://[^/]+)?(/cms-compro/)?~i', $url)) {
        return $url;
    }
    // Clean index.php or /index.php to ./
    $url = preg_replace('~/(index\.php)(\?|#|$)~i', '/$2', $url);
    $url = preg_replace('~^index\.php(\?|#|$)~i', './$1', $url);
    // Strip .php extension from internal filenames
    $url = preg_replace('~\b([a-zA-Z0-9_-]+)\.php(\?|#|$)~i', '$1$2', $url);
    return $url;
}

/**
 * Get dynamic value from site_sections (title, subtitle, content, badge_text, icon, image, bg_image, button_text, button_link)
 *
 * @param string $page
 * @param string $section_key
 * @param string $field
 * @param mixed $default
 * @return mixed
 */
function get_section_val($page, $section_key, $field, $default = '') {
    $sections = get_page_sections($page);
    $val = '';
    if (isset($sections[$section_key]) && isset($sections[$section_key][$field]) && $sections[$section_key][$field] !== '' && $sections[$section_key][$field] !== null) {
        $val = $sections[$section_key][$field];
    } else {
        $val = $default;
    }

    // Multilingual Translation Support across EN, JA, ZH, KO
    if (function_exists('current_lang') && current_lang() !== 'id') {
        // 1. Check MySQL content_translations table first
        if (isset($sections[$section_key]['id']) && function_exists('get_content_translation')) {
            $dbTrans = get_content_translation('site_section', (int)$sections[$section_key]['id'], $field, current_lang());
            if ($dbTrans !== null && $dbTrans !== '') {
                return $field === 'button_link' && is_string($dbTrans) ? clean_public_url($dbTrans) : $dbTrans;
            }
        }
        // 2. Check translation keys fallback
        if (function_exists('__')) {
            $transKey = $section_key . '_' . $field;
            $t = __($transKey, null);
            if ($t !== null && $t !== $transKey) {
                return $field === 'button_link' && is_string($t) ? clean_public_url($t) : $t;
            }
            if (is_string($val) && strlen($val) < 255) {
                $tVal = __($val, null);
                if ($tVal !== null && $tVal !== $val) {
                    return $field === 'button_link' && is_string($tVal) ? clean_public_url($tVal) : $tVal;
                }
            }
        }
    }

    if ($field === 'button_link' && is_string($val)) {
        $val = clean_public_url($val);
    }

    return $val;
}

/**
 * Format image path safely for public display
 *
 * @param string $path
 * @param string $folder
 * @return string
 */
function resolve_image_path($path, $folder = "") {
    $path = trim((string)$path);
    if ($path === "") return "assets/img/placeholder.svg";
    if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
    if (strpos($path, "uploads/") === 0) return safe_image_url($path);
    if ($folder !== "") return safe_image_url(rtrim($folder, "/") . "/" . basename($path));
    return safe_image_url($path);
}

/**
 * Get all dynamic items/cards/steps for a specific section from site_section_items
 *
 * @param string $section_key
 * @param bool $onlyActive
 * @param bool $resetCache
 * @return array
 */
function get_section_items($section_key, $onlyActive = true, $resetCache = false) {
    global $conn;
    static $cachedSectionItems = [];

    if ($resetCache) {
        if ($section_key === null) {
            $cachedSectionItems = [];
            return [];
        }
        unset($cachedSectionItems[$section_key . '_active']);
        unset($cachedSectionItems[$section_key . '_all']);
        return [];
    }

    $cacheKey = $section_key . ($onlyActive ? '_active' : '_all');
    if (!isset($cachedSectionItems[$cacheKey])) {
        $cachedSectionItems[$cacheKey] = [];
        if (isset($conn) && $conn instanceof mysqli) {
            $sql = "SELECT * FROM site_section_items WHERE section_key = ?";
            if ($onlyActive) {
                $sql .= " AND is_active = 1";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $section_key);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    if (!empty($row['extra_data'])) {
                        $decoded = json_decode($row['extra_data'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            foreach ($decoded as $dKey => $dVal) {
                                if (is_string($dVal) && (strpos($dKey, 'link') !== false || strpos($dKey, 'url') !== false)) {
                                    $decoded[$dKey] = clean_public_url($dVal);
                                }
                            }
                            $row['extra'] = $decoded;
                        }
                    }
                    if (function_exists('translate_entity_row')) {
                        $row = translate_entity_row('site_section_item', $row);
                    }
                    if (function_exists('current_lang') && current_lang() !== 'id' && function_exists('__')) {
                        $itemTitleKey = ($row['item_key'] ?? '') . '_title';
                        $tTitle = __($itemTitleKey, null);
                        if ($tTitle !== null && $tTitle !== $itemTitleKey) {
                            $row['title'] = $tTitle;
                        }
                        $itemSubKey = ($row['item_key'] ?? '') . '_sub';
                        $tSub = __($itemSubKey, null);
                        if ($tSub !== null && $tSub !== $itemSubKey) {
                            $row['subtitle'] = $tSub;
                        }
                    }
                    $cachedSectionItems[$cacheKey][] = $row;
                }
                $stmt->close();
            }
        }
    }
    return $cachedSectionItems[$cacheKey];
}

/**
 * Clear static cache of section items
 *
 * @param string|null $section_key
 * @return void
 */
function clear_section_items_cache($section_key = null) {
    get_section_items($section_key, true, true);
}

/**
 * Shortcut to get active section items
 */
function sec_items($section_key) {
    return get_section_items($section_key, true);
}

/**
 * Standardized Secure File Upload Helper
 *
 * @param array $fileArray $_FILES['key']
 * @param string $targetSubdir e.g. 'products', 'news', 'certifications'
 * @param string $prefix File name prefix
 * @param int $maxBytes Max file size (default 5MB)
 * @param array $allowedExts Whitelist of allowed extensions
 * @return array ['success' => bool, 'path' => string, 'error' => string]
 */
function cms_upload_image($fileArray, $targetSubdir, $prefix = 'img_', $maxBytes = 5242880, $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg']) {
    if (!isset($fileArray) || !is_array($fileArray)) {
        return ['success' => false, 'error' => 'Data file tidak valid.'];
    }

    $error = $fileArray['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($error === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'Tidak ada file yang dipilih.'];
    }
    if ($error !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Kode error upload file: ' . $error];
    }

    $tmpName = $fileArray['tmp_name'];
    if (!is_uploaded_file($tmpName)) {
        return ['success' => false, 'error' => 'File bukan merupakan file upload HTTP yang sah.'];
    }

    // Size validation
    $size = (int)($fileArray['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        $mb = round($maxBytes / 1048576, 1);
        return ['success' => false, 'error' => "Ukuran file melebihi batas maksimal ({$mb} MB)."];
    }

    // Extension validation
    $ext = strtolower(pathinfo($fileArray['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts, true)) {
        return ['success' => false, 'error' => 'Format file tidak diizinkan. Gunakan: ' . implode(', ', $allowedExts)];
    }

    // Content & MIME inspection
    if ($ext === 'svg') {
        $svgContent = @file_get_contents($tmpName);
        if ($svgContent === false || stripos($svgContent, '<svg') === false) {
            return ['success' => false, 'error' => 'File SVG tidak valid.'];
        }
        // Basic SVG sanitize check: prevent embedded scripts or event handlers
        if (preg_match('/<script|onload=|onerror=|onclick=/i', $svgContent)) {
            return ['success' => false, 'error' => 'File SVG mengandung skrip yang tidak aman.'];
        }
    } else {
        $imgInfo = @getimagesize($tmpName);
        if ($imgInfo === false) {
            return ['success' => false, 'error' => 'File yang diupload bukan gambar bitmap yang valid.'];
        }
    }

    // Target directory resolution
    $cleanSubdir = trim(str_replace(['..', '/', '\\'], '', $targetSubdir));
    $baseUploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $cleanSubdir . DIRECTORY_SEPARATOR;

    if (!is_dir($baseUploadDir)) {
        if (!@mkdir($baseUploadDir, 0755, true)) {
            return ['success' => false, 'error' => 'Gagal membuat direktori upload: ' . $cleanSubdir];
        }
    }

    if (!is_writable($baseUploadDir)) {
        return ['success' => false, 'error' => 'Direktori upload tidak memiliki izin tulis: ' . $cleanSubdir];
    }

    // Unique random filename
    $filename = $prefix . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destination = $baseUploadDir . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        return ['success' => false, 'error' => 'Gagal memindahkan file upload ke tujuan akhir.'];
    }

    $relativePath = 'uploads/' . $cleanSubdir . '/' . $filename;
    return ['success' => true, 'path' => $relativePath, 'error' => ''];
}

/**
 * Fallback-safe image URL resolver
 *
 * @param string|null $path
 * @param string $fallback
 * @return string
 */
function safe_image_url($path, $fallback = 'assets/img/placeholder.svg') {
    if (empty($path)) {
        return $fallback;
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0 || strpos($path, 'data:') === 0) {
        return $path;
    }
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    $localFile = $root . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($path, '/\\'));
    if (file_exists($localFile)) {
        return $path;
    }
    return $fallback;
}


