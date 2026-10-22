# -*- coding: utf-8 -*-
"""
PT Nihon Plast Indonesia - Complete Site Section & Section Item Multilingual Seeder Generator
Generates accurate, high-fidelity automotive Monozukuri translations for EN, JA, ZH, KO.
"""

import json
import os

with open(r'C:\Devaron\www\cms-compro\scripts\sections_raw.json', 'r', encoding='utf-8') as f:
    sections = json.load(f)

with open(r'C:\Devaron\www\cms-compro\scripts\items_raw.json', 'r', encoding='utf-8') as f:
    items = json.load(f)

print(f"Loaded {len(sections)} sections and {len(items)} items.")

# We will build a comprehensive translation dictionary for sections and items
# Each section: id -> {en: {title, subtitle, badge_text, button_text}, ja: {...}, zh: {...}, ko: {...}}
# Each item: id -> {en: {title, subtitle, content}, ja: {...}, zh: {...}, ko: {...}}
