<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/translation_helpers.php';

$langs = ['id', 'en', 'ja', 'zh', 'ko'];
foreach ($langs as $l) {
    set_lang($l);
    echo "[$l] nav_home=" . __('nav_home') . " | nav_about=" . __('nav_about') . " | nav_products=" . __('nav_products') . PHP_EOL;
}
