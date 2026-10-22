<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/translation_helpers.php';
require_once __DIR__ . '/../includes/site_helpers.php';

echo "=== SITE SECTIONS IN DB ===" . PHP_EOL;
$res = $conn->query("SELECT id, page, section_key, title, subtitle FROM site_sections");
while ($r = $res->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | Page: " . $r['page'] . " | Key: " . $r['section_key'] . " | Title: " . $r['title'] . PHP_EOL;
}

echo PHP_EOL . "=== TESTING get_section_val in JA ===" . PHP_EOL;
set_lang('ja');
echo "current_lang: " . current_lang() . PHP_EOL;
echo "sec_val products_grid title: " . get_section_val('home', 'products_grid', 'title', 'DEFAULT') . PHP_EOL;
echo "sec_val facility title: " . get_section_val('home', 'facility', 'title', 'DEFAULT') . PHP_EOL;
echo "sec_val certifications title: " . get_section_val('home', 'certifications', 'title', 'DEFAULT') . PHP_EOL;
echo "sec_val news title: " . get_section_val('home', 'news', 'title', 'DEFAULT') . PHP_EOL;

echo PHP_EOL . "=== TESTING get_section_items in JA ===" . PHP_EOL;
$stats = get_section_items('stats');
foreach ($stats as $s) {
    echo "Stat: " . $s['title'] . " | " . ($s['subtitle'] ?? '') . PHP_EOL;
}

$heroSlides = get_section_items('hero_slides');
foreach ($heroSlides as $h) {
    echo "Hero Slide: " . $h['title'] . " | " . $h['subtitle'] . PHP_EOL;
}
