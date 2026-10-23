<?php
/**
 * PT Nihon Plast Indonesia - Professional Error & Security Redirection Handler
 * Clean URL: /error?code=403 or /error?code=404
 * 100% Free & Open-Source, Multilingual (ID, EN, JA, ZH, KO)
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/translation_helpers.php';

$code = isset($_GET['code']) ? (int)$_GET['code'] : 404;
if (!in_array($code, [400, 401, 403, 404, 500], true)) {
    $code = 404;
}

http_response_code($code);

// If invoked internally by Apache ErrorDocument for a forbidden directory/file (e.g. /config/ or .env),
// send an immediate HTTP 302 redirect so browser address bar changes to /error?code=... in the active language.
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$cleanBasePath = rtrim(preg_replace('#/(admin|config|includes|scripts|database)$#', '', str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
$expectedErrorPath = $cleanBasePath . '/error';

if ($requestPath !== $expectedErrorPath && $requestPath !== $expectedErrorPath . '.php') {
    $activeLang = current_lang();
    $targetUrl = $expectedErrorPath . '?code=' . $code;
    if ($activeLang !== 'id') {
        $targetUrl .= '&lang=' . urlencode($activeLang);
    }
    header("Location: " . $targetUrl, true, 302);
    exit;
}

$currLang = current_lang();

// Multilingual URLs preserving active language for CTAs & Auto-redirect
$homeUrl = $currLang !== 'id' ? './?lang=' . urlencode($currLang) : './';
$productsUrl = $currLang !== 'id' ? 'products?lang=' . urlencode($currLang) : 'products';
$contactUrl = $currLang !== 'id' ? 'contact?lang=' . urlencode($currLang) : 'contact';

// Multilingual error details mapping
$errorDetails = [
    403 => [
        'badge'       => __('error_403_badge', '403 · AKSES SISTEM DIBATASI'),
        'icon'        => 'bi-shield-lock-fill',
        'color'       => '#dc3545',
        'title'       => __('error_403_title', 'Akses Direktori / File Sistem Dibatasi'),
        'description' => __('error_403_desc', 'Untuk melindungi integritas data dan keamanan sistem PT Nihon Plast Indonesia, akses langsung ke direktori sistem, konfigurasi, atau script internal dibatasi secara ketat.'),
        'help'        => __('error_403_help', 'Jika Anda adalah administrator yang sah, silakan masuk melalui portal otorisasi resmi.')
    ],
    404 => [
        'badge'       => __('error_404_badge', '404 · HALAMAN TIDAK DITEMUKAN'),
        'icon'        => 'bi-compass-fill',
        'color'       => '#0d6efd',
        'title'       => __('error_404_title', 'Halaman Tidak Ditemukan (Page Not Found)'),
        'description' => __('error_404_desc', 'Halaman atau sumber daya yang Anda cari tidak tersedia, telah dipindahkan, atau alamat URL yang Anda masukkan kurang tepat.'),
        'help'        => __('error_404_help', 'Silakan periksa kembali tautan Anda atau gunakan menu navigasi untuk menjelajahi situs kami.')
    ],
    500 => [
        'badge'       => __('error_500_badge', '500 · KENDALA SISTEM INTERNAL'),
        'icon'        => 'bi-exclamation-triangle-fill',
        'color'       => '#fd7e14',
        'title'       => __('error_500_title', 'Terjadi Kendala pada Server'),
        'description' => __('error_500_desc', 'Sistem kami sedang mengalami pemeliharaan sementara atau proses tidak dapat diselesaikan saat ini.'),
        'help'        => __('error_500_help', 'Tim teknis kami sedang menangani hal ini. Silakan coba kembali beberapa saat lagi.')
    ]
];

$info = $errorDetails[$code] ?? $errorDetails[404];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_html_lang()) ?>">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <title><?= htmlspecialchars($info['badge']) ?> - PT Nihon Plast Indonesia</title>
    <!-- Auto Redirect to Home retaining active language -->
    <meta http-equiv="refresh" content="6;url=<?= htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8') ?>">
    <style>
        .error-hero-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e9ecef;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.07);
            padding: 3rem 2rem;
            max-width: 680px;
            margin: 4rem auto;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .error-hero-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #005A36, #10B981, #2563EB);
        }
        .error-icon-box {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            background: rgba(16, 185, 129, 0.1);
            color: #005A36;
        }
        .error-icon-box.text-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc3545;
        }
        .countdown-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4b5563;
            background: #f3f4f6;
            padding: 8px 18px;
            border-radius: 9999px;
            margin-bottom: 2rem;
        }
        .btn-home-cta {
            background: #005A36;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 9999px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-home-cta:hover {
            background: #004227;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 90, 54, 0.25);
        }
    </style>
</head>
<body class="bg-light">
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <main class="container py-4">
        <div class="error-hero-card">
            <div class="error-icon-box <?= $code === 403 ? 'text-danger' : '' ?>">
                <i class="bi <?= htmlspecialchars($info['icon']) ?>"></i>
            </div>

            <div class="mb-2">
                <span class="badge rounded-pill px-3 py-2 <?= $code === 403 ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?> fw-bold" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                    <?= htmlspecialchars($info['badge']) ?>
                </span>
            </div>

            <h1 class="h3 fw-bold text-dark mt-3 mb-3">
                <?= htmlspecialchars($info['title']) ?>
            </h1>

            <p class="text-secondary lead fs-6 mb-3 px-md-4">
                <?= htmlspecialchars($info['description']) ?>
            </p>

            <p class="small text-muted mb-4">
                <?= htmlspecialchars($info['help']) ?>
            </p>

            <div class="countdown-pill">
                <i class="bi bi-clock-history text-primary"></i>
                <span><?= htmlspecialchars(__('error_countdown_prefix', 'Mengalihkan otomatis ke Beranda dalam')) ?></span>
                <span id="countdownSec" class="text-dark fw-bold">5</span>
                <span><?= htmlspecialchars(__('error_countdown_suffix', 'detik...')) ?></span>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="<?= htmlspecialchars($homeUrl) ?>" class="btn-home-cta">
                    <i class="bi bi-house-door-fill"></i> <?= htmlspecialchars(__('back_to_home', 'Kembali ke Beranda')) ?>
                </a>
                <a href="<?= htmlspecialchars($productsUrl) ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-box-seam me-1"></i> <?= htmlspecialchars(__('error_btn_catalog', 'Katalog Produk')) ?>
                </a>
                <a href="<?= htmlspecialchars($contactUrl) ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-envelope-paper me-1"></i> <?= htmlspecialchars(__('contact_us', 'Hubungi Kami')) ?>
                </a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Interactive countdown and auto-redirection preserving active language
        let timeLeft = 5;
        const countdownEl = document.getElementById('countdownSec');
        const targetUrl = <?= json_encode($homeUrl) ?>;
        const timer = setInterval(() => {
            timeLeft--;
            if (countdownEl) {
                countdownEl.textContent = timeLeft;
            }
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = targetUrl;
            }
        }, 1000);
    </script>
</body>
</html>