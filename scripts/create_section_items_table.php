<?php
require_once __DIR__ . '/../config/database.php';

$sql = "CREATE TABLE IF NOT EXISTS site_section_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(50) NOT NULL,
    item_key VARCHAR(50) NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NULL,
    content TEXT NULL,
    icon VARCHAR(100) NULL,
    image VARCHAR(255) NULL,
    badge VARCHAR(100) NULL,
    extra_data TEXT NULL,
    sort_order INT NOT NULL DEFAULT 10,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_section (section_key),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql)) {
    echo "Table site_section_items created or already exists.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
    exit(1);
}
