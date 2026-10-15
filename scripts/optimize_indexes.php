<?php
require_once __DIR__ . '/../config/database.php';

echo "Optimizing database indexes...\n";

// Check existing indexes on site_section_items
$res = $conn->query("SHOW INDEX FROM site_section_items WHERE Key_name = 'idx_section_active_sort'");
if ($res && $res->num_rows > 0) {
    echo "Index idx_section_active_sort already exists.\n";
} else {
    $q = $conn->query("CREATE INDEX idx_section_active_sort ON site_section_items (section_key, is_active, sort_order)");
    if ($q) {
        echo "Successfully created composite index idx_section_active_sort on site_section_items.\n";
    } else {
        echo "Error creating index: " . $conn->error . "\n";
    }
}

// Check existing indexes on products
$resProd = $conn->query("SHOW INDEX FROM products WHERE Key_name = 'idx_created_at'");
if ($resProd && $resProd->num_rows > 0) {
    echo "Index idx_created_at already exists on products.\n";
} else {
    $q = $conn->query("CREATE INDEX idx_created_at ON products (created_at)");
    if ($q) {
        echo "Successfully created index idx_created_at on products.\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

echo "Database optimization completed!\n";
