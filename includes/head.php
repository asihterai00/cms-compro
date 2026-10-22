<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$headAssetPrefix = (isset($is_admin) && $is_admin) || (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') !== false) ? '../' : '';
?>
<!-- Favicon & Touch Icons (NPI Emerald Monozukuri Edition) -->
<link rel="icon" type="image/svg+xml" href="<?= $headAssetPrefix ?>assets/img/favicon.svg?v=2">
<link rel="icon" type="image/png" sizes="64x64" href="<?= $headAssetPrefix ?>assets/img/favicon.png?v=2">
<link rel="alternate icon" type="image/png" sizes="32x32" href="<?= $headAssetPrefix ?>assets/img/favicon.png?v=2">
<link rel="shortcut icon" href="<?= $headAssetPrefix ?>favicon.ico?v=2">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $headAssetPrefix ?>assets/img/apple-touch-icon.png?v=2">
<!-- Google Fonts: Plus Jakarta Sans for Japanese precision automotive look -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Bootstrap 5.3.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Vendor Stylesheets (100% Free & Open Source) -->
<link rel="stylesheet" href="<?= $headAssetPrefix ?>assets/vendor/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="<?= $headAssetPrefix ?>assets/vendor/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" href="<?= $headAssetPrefix ?>assets/vendor/aos/aos.css">
<!-- Main Corporate Stylesheet -->
<link rel="stylesheet" href="<?= $headAssetPrefix ?>assets/css/style.css?v=<?= file_exists(__DIR__ . '/../assets/css/style.css') ? filemtime(__DIR__ . '/../assets/css/style.css') : time() ?>">
<!-- Modern Dribbble Automotive Design System -->
<link rel="stylesheet" href="<?= $headAssetPrefix ?>assets/css/modern-dribbble.css?v=<?= file_exists(__DIR__ . '/../assets/css/modern-dribbble.css') ? filemtime(__DIR__ . '/../assets/css/modern-dribbble.css') : time() ?>">

