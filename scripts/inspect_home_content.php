<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT id, section_key, title, subtitle, content, button_text, button_link FROM site_sections WHERE page = 'home'");
while ($row = $res->fetch_assoc()) {
    echo "=== [{$row['section_key']}] ===\n";
    echo "Title: " . ($row['title'] ?? '') . "\n";
    echo "Subtitle: " . ($row['subtitle'] ?? '') . "\n";
    echo "Content:\n" . ($row['content'] ?? '') . "\n\n";
}
