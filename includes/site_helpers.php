<?php
/**
 * PT Nihon Plast Indonesia - Global Dynamic CMS & Section Helpers
 * Supports 100% dynamic element rendering and active/inactive toggle across all pages
 */

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
    if (isset($sections[$section_key]) && isset($sections[$section_key][$field]) && $sections[$section_key][$field] !== '' && $sections[$section_key][$field] !== null) {
        return $sections[$section_key][$field];
    }
    return $default;
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
    if ($path === "") return "";
    if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
    if (strpos($path, "uploads/") === 0) return $path;
    if ($folder !== "") return rtrim($folder, "/") . "/" . basename($path);
    return $path;
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
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $row['extra'] = $decoded;
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

