<?php
/**
 * PT Nihon Plast Indonesia - Global Dynamic CMS & Section Helpers
 * Supports 100% dynamic element rendering and active/inactive toggle across all pages
 */

/**
 * Load all sections for a specific page from site_sections table
 *
 * @param string $page
 * @return array
 */
function get_page_sections($page) {
    global $conn;
    static $cachedPageSections = [];

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
