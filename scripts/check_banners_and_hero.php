<?php
require_once __DIR__ . '/../config/database.php';

echo "PAGE_BANNERS TABLE:\n";
$res = $conn->query("SELECT * FROM page_banners");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo "- ID: {$r['id']} | Page: {$r['page_key']} | Title: {$r['title']} | Image: {$r['image']}\n";
    }
} else {
    echo "Query error: " . $conn->error . "\n";
}

echo "\nSITE_SECTIONS FOR HERO:\n";
$res2 = $conn->query("SELECT * FROM site_sections WHERE page = 'home' AND (section_key = 'hero' OR section_key LIKE '%hero%')");
if ($res2) {
    while ($r2 = $res2->fetch_assoc()) {
        echo "- ID: {$r2['id']} | Key: {$r2['section_key']} | Title: {$r2['title']} | Subtitle: {$r2['subtitle']} | Img: {$r2['image']} | Active: {$r2['is_active']}\n";
    }
}
