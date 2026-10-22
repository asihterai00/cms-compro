<?php
/**
 * Setup Multilingual Database Schema
 * Creates content_translations table in MySQL
 */
require_once __DIR__ . '/../config/database.php';

$sql = "CREATE TABLE IF NOT EXISTS `content_translations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT NOT NULL,
    `lang_code` VARCHAR(10) NOT NULL,
    `field_name` VARCHAR(50) NOT NULL,
    `translation_value` LONGTEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_entity_lang_field` (`entity_type`, `entity_id`, `lang_code`, `field_name`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    KEY `idx_lang` (`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql)) {
    echo "[SUCCESS] Table `content_translations` created or already exists.\n";
} else {
    echo "[ERROR] Failed to create table: " . $conn->error . "\n";
    exit(1);
}
