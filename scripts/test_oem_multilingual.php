<?php
require_once __DIR__ . '/../config/database.php';

echo "--- Testing ID (Default) ---\n";
set_lang('id');
foreach (sec_items('oem_trust') as $it) {
    echo "{$it['title']} | Sub: {$it['subtitle']} | Color: " . ($it['extra']['brand_color'] ?? 'none') . "\n";
}

echo "\n--- Testing JA (Japanese) ---\n";
set_lang('ja');
clear_section_items_cache('oem_trust');
foreach (sec_items('oem_trust') as $it) {
    echo "{$it['title']} | Sub: {$it['subtitle']} | Color: " . ($it['extra']['brand_color'] ?? 'none') . "\n";
}
