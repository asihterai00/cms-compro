<?php
require_once __DIR__ . '/../config/database.php';

// Check columns
$cols = $conn->query("SHOW COLUMNS FROM company_profile");
echo "Columns in company_profile:\n";
while ($c = $cols->fetch_assoc()) {
    echo " - " . $c['Field'] . " (" . $c['Type'] . ")\n";
}

// Current data
$res = $conn->query("SELECT * FROM company_profile LIMIT 1");
$row = $res->fetch_assoc();
echo "\nCurrent Data:\n";
print_r($row);
