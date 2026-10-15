<?php
/**
 * Database Connection & Global Configuration
 * PT Nihon Plast Indonesia CMS
 */

// 1. Simple Environment Variable Loader (.env)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            // Strip surrounding quotes
            $val = trim($val, "\"'");
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$val");
                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
            }
        }
    }
}

// 2. Database Parameters (Environment with Fallbacks)
$host     = getenv('DB_HOST') ?: "192.168.1.6";
$port     = (int)(getenv('DB_PORT') ?: 3306);
$user     = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "Emina.1525";
$database = getenv('DB_NAME') ?: "db_cms_compro";

// Suppress raw error output to prevent credential leakage
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    error_log("PT Nihon Plast CMS Database Connection Error: " . $conn->connect_error);
    
    // In CLI environment, report error clearly
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "[CRITICAL] Database connection failed: " . $conn->connect_error . PHP_EOL);
        exit(1);
    }

    // In HTTP/Web environment, display a graceful 503 Maintenance page
    http_response_code(503);
    header('Retry-After: 60');
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Sedang Dalam Pemeliharaan - PT Nihon Plast Indonesia</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <style>
            body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; min-height: 100vh; align-items: center; justify-content: center; }
            .card-maintenance { max-width: 540px; border-radius: 1.25rem; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.06); padding: 2.5rem; text-align: center; }
            .status-icon { width: 72px; height: 72px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem; }
        </style>
    </head>
    <body>
        <div class="card card-maintenance">
            <div class="status-icon">⚙️</div>
            <h4 class="fw-bold text-dark mb-2">Sistem Sedang Dalam Pemeliharaan</h4>
            <p class="text-muted mb-4">
                Koneksi basis data sedang dalam proses pemeliharaan rutin untuk meningkatkan performa layanan PT Nihon Plast Indonesia. Silakan muat ulang halaman dalam beberapa saat.
            </p>
            <div class="d-flex justify-content-center gap-2">
                <a href="javascript:location.reload()" class="btn btn-primary px-4 rounded-pill">
                    Muat Ulang Halaman
                </a>
            </div>
            <div class="mt-4 pt-3 border-top text-muted small">
                &copy; <?= date('Y') ?> PT Nihon Plast Indonesia. All Rights Reserved.
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$conn->set_charset("utf8mb4");

// 3. Global Helpers & Security Modules
require_once __DIR__ . "/../includes/lang.php";
require_once __DIR__ . "/../includes/site_helpers.php";
require_once __DIR__ . "/../includes/csrf.php";