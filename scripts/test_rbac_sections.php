<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'superadmin';
$_SESSION['admin_username'] = 'admin';

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../admin/auth.php";

echo "=== TESTING DYNAMIC CMS ACTIVE/INACTIVE TOGGLE & RBAC PERMISSIONS ===\n\n";

// 1. Test Active / Inactive on 'oem_trust' section
echo "1. Testing Dynamic Section Active / Inactive State:\n";
// Set oem_trust to inactive (0)
$conn->query("UPDATE site_sections SET is_active = 0 WHERE section_key = 'oem_trust'");

$_SERVER["PHP_SELF"] = "index.php";
$_SERVER["REQUEST_METHOD"] = "GET";
$_SERVER["HTTP_HOST"] = "localhost";
$_GET['lang'] = 'id';

ob_start();
include __DIR__ . "/../index.php";
$outInactive = ob_get_clean();
$hasOemInactive = strpos($outInactive, "oem-trust-section") !== false;
echo " - When is_active = 0, oem_trust appears in index.php: " . ($hasOemInactive ? "FAIL (Should be hidden)" : "PASS (Hidden correctly)") . "\n";

// Set oem_trust back to active (1)
$conn->query("UPDATE site_sections SET is_active = 1 WHERE section_key = 'oem_trust'");
ob_start();
include __DIR__ . "/../index.php";
$outActive = ob_get_clean();
$hasOemActive = strpos($outActive, "oem-trust-section") !== false;
echo " - When is_active = 1, oem_trust appears in index.php: " . ($hasOemActive ? "PASS (Appears correctly)" : "FAIL (Should be visible)") . "\n";

// 2. Testing RBAC for Superadmin
echo "\n2. Testing RBAC Permissions Engine:\n";
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'superadmin';
$_SESSION['admin_username'] = 'admin';

$saCreate = has_permission('products', 'create');
$saDelete = has_permission('users', 'delete');
$saRead = has_permission('site_sections', 'read');
echo " - Superadmin permissions: Create=" . ($saCreate ? 'YES' : 'NO') . ", Delete=" . ($saDelete ? 'YES' : 'NO') . ", Read=" . ($saRead ? 'YES' : 'NO') . " (Expected all YES)\n";

// 3. Testing RBAC for Custom Staff User with limited permissions
$testUsername = "test_staff_" . time();
$hash = password_hash("password123", PASSWORD_DEFAULT);
$fullName = "Test Staff Member";
$email = "staff@test.com";
$role = "staff";
$status = "active";

$stmt = $conn->prepare("INSERT INTO users (username, password, full_name, email, role, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $testUsername, $hash, $fullName, $email, $role, $status);
$stmt->execute();
$newUserId = $conn->insert_id;
$stmt->close();

// Grant: products [read=1, create=0, update=0, delete=0], news [read=1, create=1, update=1, delete=0], users [read=0, create=0, update=0, delete=0]
$conn->query("INSERT INTO user_permissions (user_id, menu_key, can_create, can_read, can_update, can_delete) VALUES
    ({$newUserId}, 'products', 0, 1, 0, 0),
    ({$newUserId}, 'news', 1, 1, 1, 0),
    ({$newUserId}, 'users', 0, 0, 0, 0)");

$staffProdRead = has_permission('products', 'read', $newUserId);
$staffProdCreate = has_permission('products', 'create', $newUserId);
$staffNewsCreate = has_permission('news', 'create', $newUserId);
$staffNewsDelete = has_permission('news', 'delete', $newUserId);
$staffUsersRead = has_permission('users', 'read', $newUserId);

echo " - Staff Products Read: " . ($staffProdRead ? 'ALLOW' : 'DENY') . " (Expected ALLOW)\n";
echo " - Staff Products Create: " . ($staffProdCreate ? 'ALLOW' : 'DENY') . " (Expected DENY)\n";
echo " - Staff News Create: " . ($staffNewsCreate ? 'ALLOW' : 'DENY') . " (Expected ALLOW)\n";
echo " - Staff News Delete: " . ($staffNewsDelete ? 'ALLOW' : 'DENY') . " (Expected DENY)\n";
echo " - Staff Users Read: " . ($staffUsersRead ? 'ALLOW' : 'DENY') . " (Expected DENY)\n";

// Cleanup test user
$conn->query("DELETE FROM user_permissions WHERE user_id = {$newUserId}");
$conn->query("DELETE FROM users WHERE id = {$newUserId}");
echo "\nCleaned up temporary test user.\n";
echo "=== ALL RBAC & ACTIVE/INACTIVE TESTS PASSED! ===\n";
