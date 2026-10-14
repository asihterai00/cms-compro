<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT id, page, section_key, section_name, title, subtitle, content, is_active FROM site_sections ORDER BY page, sort_order");
while ($row = $res->fetch_assoc()) {
    echo sprintf("[%02d] %-10s | %-25s | active: %d | title: %s\n", $row['id'], $row['page'], $row['section_key'], $row['is_active'], substr($row['title'] ?? '', 0, 40));
}
