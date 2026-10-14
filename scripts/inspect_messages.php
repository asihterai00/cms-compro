<?php
require_once __DIR__ . '/../config/database.php';
$cols = $conn->query("SHOW COLUMNS FROM messages");
while ($c = $cols->fetch_assoc()) {
    echo " - " . $c['Field'] . " (" . $c['Type'] . ")\n";
}
