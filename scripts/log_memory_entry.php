<?php
require_once __DIR__ . '/../config/database.php';

$title = "Favicon Suite Multi-Platform & Standardisasi Judul Halaman NPI";
$category = "architecture";
$content = "Implementasi lengkap favicon multi-platform (SVG, PNG 64x64/32x32, ICO, Apple Touch Icon) bertema Emerald Monozukuri NPI (#005A36). Dideploy ke root server C:/Devaron/www/, root proyek C:/Devaron/www/cms-compro/, admin/assets/img/, dan admin/. Diperkuat dengan cache busting ?v=2 dan deteksi asset prefix pada includes/head.php (100% HTTP 200 OK di semua endpoint). Standardisasi seluruh judul halaman publik (12 halaman) dan admin (13 halaman) dengan prefiks 'NPI - PT Nihon Plast Indonesia | [Nama Halaman]' dan 'NPI CMS - [Menu] | PT Nihon Plast Indonesia' sehingga identitas tab browser selalu jelas. Hardening resolve_image_path() untuk otomatis membungkus fallback safe_image_url(). 26/26 dynamic test suite dan 78 PHP syntax passed dengan 0 isu.";
$meta = json_encode([
    'author' => 'antigravity-ai',
    'status' => 'completed',
    'endpoints_verified' => 7,
    'pages_standardized' => 25
]);

$stmt = $conn->prepare("INSERT INTO project_memory (category, title, content, metadata, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("ssss", $category, $title, $content, $meta);
$stmt->execute();
echo "Memory entry added successfully with ID: " . $stmt->insert_id . "\n";
$stmt->close();

// Now re-export to PROJECT_MEMORY.md
require_once __DIR__ . '/memory.php';
// We can run memory.php export-md
