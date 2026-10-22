<?php
require_once __DIR__ . '/../config/database.php';

$res = $conn->query("SELECT entity_type, COUNT(*) as cnt FROM content_translations GROUP BY entity_type");
echo "=== CONTENT TRANSLATIONS BY ENTITY TYPE ===" . PHP_EOL;
while ($r = $res->fetch_assoc()) {
    echo "Entity: " . str_pad($r['entity_type'], 25) . " | Count: " . $r['cnt'] . PHP_EOL;
}
