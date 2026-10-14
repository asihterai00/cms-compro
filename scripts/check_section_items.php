<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT section_key, COUNT(*) as cnt FROM site_section_items GROUP BY section_key ORDER BY section_key ASC");
echo "Current Section Items Summary:\n";
while ($row = $res->fetch_assoc()) {
    echo "- " . str_pad($row['section_key'], 32) . ": " . $row['cnt'] . " items\n";
}
