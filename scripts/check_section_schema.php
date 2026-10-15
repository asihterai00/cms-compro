<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("DESCRIBE site_sections");
echo "=== site_sections columns ===\n";
while ($row = $res->fetch_assoc()) {
    echo " - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

$res2 = $conn->query("DESCRIBE site_section_items");
echo "=== site_section_items columns ===\n";
while ($row = $res2->fetch_assoc()) {
    echo " - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
