<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $conn->prepare("UPDATE company_profile SET logo = 'logo_nihon_plast_green.png' WHERE id = 1");
if ($stmt->execute()) {
    echo "Logo updated successfully in database!\n";
} else {
    echo "Error: " . $conn->error . "\n";
}
