<?php
require_once __DIR__ . '/../config/database.php';
$cols = $conn->query("SHOW COLUMNS FROM users");
echo "Columns in users:\n";
while ($c = $cols->fetch_assoc()) {
    echo " - " . $c['Field'] . " (" . $c['Type'] . ")\n";
}
$rows = $conn->query("SELECT id, username, email FROM users");
echo "\nRows in users:\n";
while ($r = $rows->fetch_assoc()) {
    print_r($r);
}
