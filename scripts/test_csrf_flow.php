<?php
session_start();
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'superadmin';
$_SESSION['admin_username'] = 'admin';

require_once __DIR__ . "/../includes/csrf.php";

echo "Session CSRF Token: " . csrf_token() . "\n";
$testUrl = csrf_url("site_sections.php?action=toggle&id=1&page=home");
echo "Generated URL: " . $testUrl . "\n";

// Parse URL and test verify_csrf
$parsed = parse_url($testUrl);
parse_str($parsed['query'], $query);
echo "Extracted Token from query: " . $query['csrf_token'] . "\n";

$verified = verify_csrf($query['csrf_token']);
echo "Verified result: " . ($verified ? "PASS" : "FAIL") . "\n";
