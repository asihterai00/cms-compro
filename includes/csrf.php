<?php
/**
 * Cross-Site Request Forgery (CSRF) Protection Module
 * PT Nihon Plast Indonesia CMS
 */

// Ensure session is started with secure parameters
if (session_status() === PHP_SESSION_NONE) {
    if (!headers_sent()) {
        @ini_set('session.cookie_httponly', '1');
        @ini_set('session.use_only_cookies', '1');
        @ini_set('session.cookie_samesite', 'Lax');
    }
    @session_start();
}

/**
 * Generate or retrieve the current session's CSRF token
 *
 * @return string
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden HTML input field with the CSRF token
 *
 * @return string
 */
function csrf_field() {
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Render a meta tag containing CSRF token for AJAX / Fetch requests
 *
 * @return string
 */
function csrf_meta() {
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<meta name="csrf-token" content="' . $token . '">';
}

/**
 * Append CSRF token query parameter to a URL for safe GET actions
 *
 * @param string $url
 * @return string
 */
function csrf_url($url) {
    $token = csrf_token();
    $separator = (strpos($url, '?') !== false) ? '&' : '?';
    return $url . $separator . 'csrf_token=' . urlencode($token);
}

/**
 * Verify CSRF token from POST, GET, or HTTP request header
 *
 * @param string|null $token
 * @return bool
 */
function verify_csrf($token = null) {
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    if (empty($sessionToken)) {
        return false;
    }

    if ($token === null) {
        if (!empty($_POST['csrf_token'])) {
            $token = $_POST['csrf_token'];
        } elseif (!empty($_GET['csrf_token'])) {
            $token = $_GET['csrf_token'];
        } elseif (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }
    }

    if (!is_string($token) || empty($token)) {
        return false;
    }

    return hash_equals($sessionToken, $token);
}

/**
 * Enforce CSRF token verification. Immediately halts execution with 403 Forbidden on failure.
 *
 * @param string|null $token
 */
function require_csrf($token = null) {
    if (!verify_csrf($token)) {
        http_response_code(403);
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>403 - CSRF Token Invalid</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        </head>
        <body class="bg-light">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 text-center">
                            <div class="text-warning mb-3">
                                <i class="bi bi-shield-slash display-1"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Sesi Validasi Kedaluwarsa</h3>
                            <p class="text-muted mb-4">
                                Permintaan ditolak karena token keamanan (CSRF Token) tidak valid atau sesi Anda telah berakhir. Silakan muat ulang halaman dan ulangi tindakan Anda.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="javascript:history.back()" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="dashboard.php" class="btn btn-primary px-4">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
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
