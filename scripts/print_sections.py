import json

with open(r'C:\Devaron\www\cms-compro\scripts\sections_raw.json', encoding='utf-8') as f:
    secs = json.load(f)

for s in secs:
    print(f"ID:{s['id']} | Page:{s['page']} | Key:{s['section_key']}")
    print(f"  Title: {s.get('title')}")
    print(f"  Sub:   {s.get('subtitle')}")
    print(f"  Badge: {s.get('badge_text')}")
    print(f"  Btn:   {s.get('button_text')}")
