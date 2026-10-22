<?php
require_once __DIR__ . "/../config/database.php";

echo "=== USERS ===\n";
$res = $conn->query("SELECT id, username, role, status FROM users");
while ($r = $res->fetch_assoc()) {
    echo "User ID: {$r['id']}, Username: {$r['username']}, Role: {$r['role']}, Status: {$r['status']}\n";
}

echo "\n=== PERMISSIONS MENU KEYS ===\n";
$res2 = $conn->query("SELECT DISTINCT menu_key FROM user_permissions");
while ($r = $res2->fetch_assoc()) {
    echo "Menu Key in DB: {$r['menu_key']}\n";
}

echo "\n=== SITE SECTIONS COUNT ===\n";
$res3 = $conn->query("SELECT COUNT(*) as total FROM site_sections");
echo "Total site_sections: " . $res3->fetch_assoc()['total'] . "\n";

echo "\n=== SITE SECTION ITEMS COUNT ===\n";
$res4 = $conn->query("SELECT COUNT(*) as total FROM site_section_items");
echo "Total site_section_items: " . $res4->fetch_assoc()['total'] . "\n";
