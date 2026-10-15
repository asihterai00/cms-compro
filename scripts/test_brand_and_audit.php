<?php
require_once __DIR__ . "/../config/database.php";

$res = $conn->query("SELECT * FROM company_profile LIMIT 1");
$row = $res->fetch_assoc();

echo "Company Name: " . ($row['company_name'] ?? 'N/A') . "\n";
echo "Logo: " . ($row['logo'] ?? 'N/A') . "\n";

$logoPath = $row['logo'] ?? '';
if ($logoPath) {
    if (strpos($logoPath, 'uploads/') !== 0) {
        $logoPath = 'uploads/company/' . $logoPath;
    }
    echo "Resolved logo path: $logoPath\n";
    echo "File on disk: " . (file_exists(__DIR__ . '/../' . $logoPath) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
