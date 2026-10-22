<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $res->fetch_row()) {
    $tables[] = $row[0];
}

foreach ($tables as $t) {
    echo "=== TABLE: $t ===\n";
    $cRes = $conn->query("DESCRIBE `$t`");
    while ($c = $cRes->fetch_assoc()) {
        echo sprintf("  %-25s %-20s %-5s %s\n", $c['Field'], $c['Type'], $c['Null'], $c['Key']);
    }
    $cntRes = $conn->query("SELECT COUNT(*) FROM `$t`");
    $cnt = $cntRes ? $cntRes->fetch_row()[0] : 0;
    echo "  Total Rows: $cnt\n\n";
}
