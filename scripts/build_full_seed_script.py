# -*- coding: utf-8 -*-
"""
PT Nihon Plast Indonesia - Complete Site Sections & Items Multilingual Seeder Generator
Generates scripts/seed_sections_translations.php with authentic EN, JA, ZH, KO translations.
"""

import json

# Load raw sections and items
with open(r'C:\Devaron\www\cms-compro\scripts\sections_raw.json', encoding='utf-8') as f:
    secs = json.load(f)

with open(r'C:\Devaron\www\cms-compro\scripts\items_raw.json', encoding='utf-8') as f:
    items = json.load(f)

print(f"Sections count: {len(secs)}, Items count: {len(items)}")

# We will create a robust PHP script that saves translations for site_section and site_section_item
# Let's write the PHP generation script
output_file = r'C:\Devaron\www\cms-compro\scripts\seed_sections_translations.php'

# Open file for writing PHP
with open(output_file, 'w', encoding='utf-8') as out:
    out.write('<?php\n')
    out.write('/**\n')
    out.write(' * PT Nihon Plast Indonesia - Complete Multilingual Seeder for Site Sections & Items\n')
    out.write(' * Fills content_translations table with authentic EN, JA, ZH, KO data.\n')
    out.write(' * 100% Free & Open-Source, zero paid external APIs.\n')
    out.write(' */\n\n')
    out.write('require_once __DIR__ . "/../config/database.php";\n')
    out.write('require_once __DIR__ . "/../includes/translation_helpers.php";\n\n')
    out.write('$totalInserted = 0;\n\n')
    out.write('function save_t($type, $id, $lang, $field, $val) {\n')
    out.write('    global $totalInserted;\n')
    out.write('    if (save_content_translation($type, (int)$id, $lang, $field, $val)) {\n')
    out.write('        $totalInserted++;\n')
    out.write('    }\n')
    out.write('}\n\n')
    out.write('echo "=== SEEDING SITE_SECTIONS & SITE_SECTION_ITEMS TRANSLATIONS ===" . PHP_EOL;\n\n')

print("PHP header created.")
