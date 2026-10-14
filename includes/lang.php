<?php
/**
 * PT Nihon Plast Indonesia - Multilingual Engine (FOSS)
 * Supports 5 Languages: Indonesian (id), English (en), Japanese (ja), Chinese (zh), Korean (ko)
 * 100% Free & Open-Source, zero paid external APIs
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

global $LANG_LIST, $CURRENT_LANG, $TRANSLATIONS, $FALLBACK_TRANSLATIONS;

$LANG_LIST = [
    'id' => [
        'code' => 'id',
        'short' => 'ID',
        'name' => 'Bahasa Indonesia',
        'html_lang' => 'id'
    ],
    'en' => [
        'code' => 'en',
        'short' => 'EN',
        'name' => 'English',
        'html_lang' => 'en'
    ],
    'ja' => [
        'code' => 'ja',
        'short' => 'JA',
        'name' => '日本語',
        'html_lang' => 'ja'
    ],
    'zh' => [
        'code' => 'zh',
        'short' => 'ZH',
        'name' => '简体中文',
        'html_lang' => 'zh-CN'
    ],
    'ko' => [
        'code' => 'ko',
        'short' => 'KO',
        'name' => '한국어',
        'html_lang' => 'ko'
    ]
];

/**
 * Switch or set the active system language dynamically
 *
 * @param string $langCode
 */
function set_lang($langCode) {
    global $LANG_LIST, $CURRENT_LANG, $TRANSLATIONS, $FALLBACK_TRANSLATIONS;
    if (isset($LANG_LIST[$langCode])) {
        $CURRENT_LANG = $langCode;
        $_SESSION['cms_lang'] = $langCode;
        if (!headers_sent()) {
            setcookie('cms_lang', $langCode, time() + (86400 * 30), '/');
        }
        $langDir = __DIR__ . '/../languages';
        $langFile = $langDir . '/' . $CURRENT_LANG . '.php';
        $fallbackFile = $langDir . '/id.php';

        $TRANSLATIONS = is_file($langFile) ? (require $langFile) : [];
        $FALLBACK_TRANSLATIONS = ($CURRENT_LANG !== 'id' && is_file($fallbackFile)) ? (require $fallbackFile) : $TRANSLATIONS;
    }
}

// Initial language resolution
$initLang = null;

if (isset($_GET['lang']) && isset($LANG_LIST[$_GET['lang']])) {
    $initLang = $_GET['lang'];
} elseif (isset($_SESSION['cms_lang']) && isset($LANG_LIST[$_SESSION['cms_lang']])) {
    $initLang = $_SESSION['cms_lang'];
} elseif (isset($_COOKIE['cms_lang']) && isset($LANG_LIST[$_COOKIE['cms_lang']])) {
    $initLang = $_COOKIE['cms_lang'];
} else {
    // Intelligent auto-detection from Browser Accept-Language header
    $acceptLang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '');
    if (strpos($acceptLang, 'ja') !== false) {
        $initLang = 'ja';
    } elseif (strpos($acceptLang, 'zh') !== false) {
        $initLang = 'zh';
    } elseif (strpos($acceptLang, 'ko') !== false) {
        $initLang = 'ko';
    } elseif (strpos($acceptLang, 'en') !== false) {
        $initLang = 'en';
    } else {
        $initLang = 'id';
    }
}

set_lang($initLang);

/**
 * Translate a key with optional fallback default
 *
 * @param string $key
 * @param string|null $default
 * @return string
 */
function __($key, $default = null) {
    global $TRANSLATIONS, $FALLBACK_TRANSLATIONS, $CURRENT_LANG;

    // Check if $_GET['lang'] changed on the fly
    if (isset($_GET['lang']) && $_GET['lang'] !== $CURRENT_LANG) {
        set_lang($_GET['lang']);
    }

    if (isset($TRANSLATIONS[$key]) && $TRANSLATIONS[$key] !== '') {
        return $TRANSLATIONS[$key];
    }
    if (isset($FALLBACK_TRANSLATIONS[$key]) && $FALLBACK_TRANSLATIONS[$key] !== '') {
        return $FALLBACK_TRANSLATIONS[$key];
    }
    return $default !== null ? $default : $key;
}

/**
 * Get current language code (e.g. 'id', 'en', 'ja', 'zh', 'ko')
 *
 * @return string
 */
function current_lang() {
    global $CURRENT_LANG;
    if (isset($_GET['lang']) && $_GET['lang'] !== $CURRENT_LANG) {
        set_lang($_GET['lang']);
    }
    return $CURRENT_LANG ?? 'id';
}

/**
 * Get HTML lang attribute (e.g. 'zh-CN', 'ja', 'en', 'id')
 *
 * @return string
 */
function get_html_lang() {
    global $LANG_LIST, $CURRENT_LANG;
    if (isset($_GET['lang']) && $_GET['lang'] !== $CURRENT_LANG) {
        set_lang($_GET['lang']);
    }
    return $LANG_LIST[$CURRENT_LANG]['html_lang'] ?? 'id';
}

/**
 * Get all supported languages list
 *
 * @return array
 */
function get_supported_languages() {
    global $LANG_LIST;
    return $LANG_LIST;
}

/**
 * Generate URL with changed language parameter preserving existing query params
 *
 * @param string $langCode
 * @return string
 */
function get_lang_switch_url($langCode) {
    $queryParams = $_GET;
    $queryParams['lang'] = $langCode;
    $currentScript = basename($_SERVER['PHP_SELF'] ?? 'index.php');
    return $currentScript . '?' . http_build_query($queryParams);
}
