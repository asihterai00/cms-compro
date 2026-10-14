<?php
require_once __DIR__ . '/../config/database.php';

echo "Database: " . $database . "\n";
echo "Host: " . $host . "\n";
echo "Tables:\n";
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $t = $row[0];
    $count = $conn->query("SELECT COUNT(*) FROM `$t`")->fetch_row()[0];
    echo sprintf(" - %-20s : %d rows\n", $t, $count);
}
