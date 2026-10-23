<?php
/**
 * CLI Admin Account Bootstrap Tool
 * PT Nihon Plast Indonesia CMS
 *
 * Usage:
 *   php scripts/create_admin.php --username=admin --password=YourStrongPassword
 *   php scripts/create_admin.php --username=admin --generate
 */

if (php_sapi_name() !== 'cli') {
    fwrite(STDERR, "Error: This script can only be run from the command line.\n");
    exit(1);
}

require_once __DIR__ . '/../config/database.php';

$options = getopt('', ['username:', 'password::', 'generate', 'fullname::', 'email::']);

$username = trim($options['username'] ?? '');
$password = $options['password'] ?? null;
$generate = isset($options['generate']);
$fullName = trim($options['fullname'] ?? 'Super Administrator');
$email = trim($options['email'] ?? 'admin@nihonplast.co.id');

if (empty($username)) {
    echo "========================================================\n";
    echo "PT NIHON PLAST INDONESIA - SECURE ADMIN BOOTSTRAP\n";
    echo "========================================================\n";
    echo "Usage:\n";
    echo "  php scripts/create_admin.php --username=<username> --password=<password>\n";
    echo "  php scripts/create_admin.php --username=<username> --generate\n\n";
    exit(1);
}

if ($generate || empty($password)) {
    // Generate secure random 16-character password
    $password = bin2hex(random_bytes(8)) . '!Np' . rand(10, 99);
    $generated = true;
} else {
    $generated = false;
}

if (strlen($password) < 8) {
    fwrite(STDERR, "[ERROR] Password must be at least 8 characters long.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    $id = (int)$existing['id'];
    $uStmt = $conn->prepare("UPDATE users SET password = ?, role = 'superadmin', status = 'active', full_name = ?, email = ? WHERE id = ?");
    $uStmt->bind_param("sssi", $hash, $fullName, $email, $id);
    if ($uStmt->execute()) {
        echo "[SUCCESS] Existing administrator '$username' (ID: $id) credentials updated.\n";
        if ($generated) {
            echo "[GENERATED PASSWORD] $password\n";
            echo ">> Store this password immediately. It will not be shown again.\n";
        }
    } else {
        fwrite(STDERR, "[ERROR] Failed to update user: " . $uStmt->error . "\n");
        exit(1);
    }
    $uStmt->close();
} else {
    $iStmt = $conn->prepare("INSERT INTO users (username, password, full_name, email, role, status) VALUES (?, ?, ?, ?, 'superadmin', 'active')");
    $iStmt->bind_param("ssss", $username, $hash, $fullName, $email);
    if ($iStmt->execute()) {
        $newId = $conn->insert_id;
        echo "[SUCCESS] New superadmin '$username' created successfully (ID: $newId).\n";
        if ($generated) {
            echo "[GENERATED PASSWORD] $password\n";
            echo ">> Store this password immediately. It will not be shown again.\n";
        }
    } else {
        fwrite(STDERR, "[ERROR] Failed to create user: " . $iStmt->error . "\n");
        exit(1);
    }
    $iStmt->close();
}
