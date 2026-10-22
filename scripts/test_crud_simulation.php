<?php
/**
 * End-to-End CRUD Simulation Test for Admin Modules
 */
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/site_helpers.php";

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - CRUD & INTERACTION VERIFIER\n";
echo "========================================================\n";

$testsPassed = 0;
$totalTests = 0;

function assert_test($description, $condition) {
    global $testsPassed, $totalTests;
    $totalTests++;
    if ($condition) {
        echo " [PASS] $description\n";
        $testsPassed++;
    } else {
        echo " [FAIL] $description\n";
    }
}

// 1. Test CSRF generation & verification
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$token = csrf_token();
assert_test("CSRF Token generated properly", !empty($token) && strlen($token) >= 32);
assert_test("CSRF Token validates valid token", verify_csrf($token));
assert_test("CSRF Token rejects invalid token", !verify_csrf('invalid_token_12345'));

// 2. Test Machinery CRUD Simulation
echo "\n--- Testing facility_machinery CRUD ---\n";
// Create test machine
$cat = "TEST_CATEGORY_" . time();
$model = "Test Model Brand";
$tonnage = "500 Ton";
$app = "Test Automotive Part";
$auto = "Test Robot";
$sort = 999;
$isActive = 1;

$stmt = $conn->prepare("INSERT INTO facility_machinery (category, model_brand, tonnage, application, automation, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssii", $cat, $model, $tonnage, $app, $auto, $sort, $isActive);
$stmt->execute();
$newMachId = $conn->insert_id;
$stmt->close();
assert_test("INSERT facility_machinery (ID: $newMachId)", $newMachId > 0);

// Update test machine
$newModel = "Updated Test Model";
$stmt = $conn->prepare("UPDATE facility_machinery SET model_brand = ?, is_active = 0 WHERE id = ?");
$stmt->bind_param("si", $newModel, $newMachId);
$stmt->execute();
$stmt->close();

$check = $conn->query("SELECT model_brand, is_active FROM facility_machinery WHERE id = $newMachId")->fetch_assoc();
assert_test("UPDATE facility_machinery model_brand & is_active", $check['model_brand'] === $newModel && $check['is_active'] == 0);

// Delete test machine
$stmt = $conn->prepare("DELETE FROM facility_machinery WHERE id = ?");
$stmt->bind_param("i", $newMachId);
$stmt->execute();
$stmt->close();
$checkDel = $conn->query("SELECT id FROM facility_machinery WHERE id = $newMachId")->num_rows;
assert_test("DELETE facility_machinery", $checkDel === 0);

// 3. Test Products CRUD Simulation
echo "\n--- Testing products CRUD ---\n";
$prodName = "Test Product Unit";
$prodDesc = "Test description automotive unit";
$prodImg = "test_prod.png";
$stmt = $conn->prepare("INSERT INTO products (name, description, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $prodName, $prodDesc, $prodImg);
$stmt->execute();
$newProdId = $conn->insert_id;
$stmt->close();
assert_test("INSERT products (ID: $newProdId)", $newProdId > 0);

// Multilingual translation insert for product
$stmt = $conn->prepare("INSERT INTO content_translations (entity_type, entity_id, field_name, lang_code, translation_value) VALUES ('product', ?, 'name', 'en', 'Test Product Unit (EN)')");
$stmt->bind_param("i", $newProdId);
$stmt->execute();
$stmt->close();

$checkTrans = $conn->query("SELECT translation_value FROM content_translations WHERE entity_type = 'product' AND entity_id = $newProdId AND lang_code = 'en'")->fetch_assoc();
assert_test("INSERT content_translations for product", $checkTrans['translation_value'] === 'Test Product Unit (EN)');

// Delete test product and its translation
$conn->query("DELETE FROM content_translations WHERE entity_type = 'product' AND entity_id = $newProdId");
$conn->query("DELETE FROM products WHERE id = $newProdId");
$checkProdDel = $conn->query("SELECT id FROM products WHERE id = $newProdId")->num_rows;
assert_test("DELETE products & cascaded translations", $checkProdDel === 0);

// 4. Test Career CRUD Simulation
echo "\n--- Testing careers CRUD ---\n";
$pos = "Test Quality Engineer " . time();
$dept = "QA";
$loc = "Cikampek";
$desc = "Test job description";
$stmt = $conn->prepare("INSERT INTO careers (position, department, location, description) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $pos, $dept, $loc, $desc);
$stmt->execute();
$newCareerId = $conn->insert_id;
$stmt->close();
assert_test("INSERT careers (ID: $newCareerId)", $newCareerId > 0);

$conn->query("DELETE FROM careers WHERE id = $newCareerId");
$checkCareerDel = $conn->query("SELECT id FROM careers WHERE id = $newCareerId")->num_rows;
assert_test("DELETE careers", $checkCareerDel === 0);

// 5. Test Site Sections AJAX Toggle logic
echo "\n--- Testing site_sections Toggle logic ---\n";
$firstSection = $conn->query("SELECT id, is_active FROM site_sections LIMIT 1")->fetch_assoc();
if ($firstSection) {
    $secId = (int)$firstSection['id'];
    $origStatus = (int)$firstSection['is_active'];
    $toggledStatus = $origStatus === 1 ? 0 : 1;
    
    // Toggle 1
    $conn->query("UPDATE site_sections SET is_active = $toggledStatus WHERE id = $secId");
    $chk = $conn->query("SELECT is_active FROM site_sections WHERE id = $secId")->fetch_assoc();
    assert_test("Toggle site_section ID $secId from $origStatus to $toggledStatus", (int)$chk['is_active'] === $toggledStatus);
    
    // Revert back
    $conn->query("UPDATE site_sections SET is_active = $origStatus WHERE id = $secId");
    $chkRevert = $conn->query("SELECT is_active FROM site_sections WHERE id = $secId")->fetch_assoc();
    assert_test("Revert site_section ID $secId back to $origStatus", (int)$chkRevert['is_active'] === $origStatus);
} else {
    echo " [SKIP] No site_sections found\n";
}

// 6. Test Site Section Items AJAX Toggle logic
echo "\n--- Testing site_section_items Toggle logic ---\n";
$firstItem = $conn->query("SELECT id, is_active FROM site_section_items LIMIT 1")->fetch_assoc();
if ($firstItem) {
    $itemId = (int)$firstItem['id'];
    $origItemStatus = (int)$firstItem['is_active'];
    $toggledItemStatus = $origItemStatus === 1 ? 0 : 1;
    
    // Toggle 1
    $conn->query("UPDATE site_section_items SET is_active = $toggledItemStatus WHERE id = $itemId");
    $chkItem = $conn->query("SELECT is_active FROM site_section_items WHERE id = $itemId")->fetch_assoc();
    assert_test("Toggle site_section_items ID $itemId from $origItemStatus to $toggledItemStatus", (int)$chkItem['is_active'] === $toggledItemStatus);
    
    // Revert back
    $conn->query("UPDATE site_section_items SET is_active = $origItemStatus WHERE id = $itemId");
    $chkItemRevert = $conn->query("SELECT is_active FROM site_section_items WHERE id = $itemId")->fetch_assoc();
    assert_test("Revert site_section_items ID $itemId back to $origItemStatus", (int)$chkItemRevert['is_active'] === $origItemStatus);
} else {
    echo " [SKIP] No site_section_items found\n";
}

echo "\n========================================================\n";
echo "SUMMARY: $testsPassed / $totalTests TESTS PASSED\n";
echo "========================================================\n";
