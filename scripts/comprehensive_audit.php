<?php
/**
 * Comprehensive Codebase & Security Audit Script
 * PT Nihon Plast Indonesia CMS
 */

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - COMPREHENSIVE CODEBASE AUDIT\n";
echo "========================================================\n\n";

$baseDir = dirname(__DIR__);
$phpBinary = PHP_BINARY;

$issues = [];
$stats = [
    'files_checked' => 0,
    'syntax_ok' => 0,
    'syntax_errors' => 0,
    'sql_queries' => 0,
    'potential_sqli' => 0,
    'unprotected_posts' => 0,
    'file_uploads' => 0,
    'insecure_uploads' => 0,
    'laragon_refs' => 0,
    'admin_pages' => 0,
    'admin_unprotected' => 0
];

// 1. Lint all PHP files
echo "--- 1. PHP Syntax Verification (php -l) ---\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
$phpFiles = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        // Skip vendor/external if any
        $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
        if (strpos($relPath, 'vendor') === 0 || strpos($relPath, '.git') === 0) {
            continue;
        }
        $phpFiles[] = $file->getPathname();
    }
}

foreach ($phpFiles as $file) {
    $stats['files_checked']++;
    $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file);
    $cmd = escapeshellarg($phpBinary) . ' -l ' . escapeshellarg($file) . ' 2>&1';
    $output = [];
    $ret = 0;
    exec($cmd, $output, $ret);
    if ($ret !== 0) {
        $stats['syntax_errors']++;
        $issues[] = "[SYNTAX ERROR] $relPath: " . implode(' ', $output);
        echo " [FAIL] $relPath\n";
    } else {
        $stats['syntax_ok']++;
    }
}
echo " Syntax check complete: {$stats['syntax_ok']} passed, {$stats['syntax_errors']} errors.\n\n";

// 2. Check php.ini references
echo "--- 2. Checking php.ini Configuration Paths ---\n";
$phpIniPath = php_ini_loaded_file();
echo " Loaded php.ini: $phpIniPath\n";
if ($phpIniPath && file_exists($phpIniPath)) {
    $iniContent = file_get_contents($phpIniPath);
    if (preg_match_all('/^[^;\r\n]*laragon[^\r\n]*/mi', $iniContent, $matches)) {
        foreach ($matches[0] as $match) {
            $stats['laragon_refs']++;
            $issues[] = "[PHP.INI OBSOLETE PATH] Found Laragon reference: " . trim($match);
            echo " [WARN] Laragon reference in php.ini: " . trim($match) . "\n";
        }
    } else {
        echo " [PASS] No active Laragon paths found in php.ini\n";
    }
} else {
    echo " [WARN] Could not locate loaded php.ini file\n";
}
echo "\n";

// 3. Scan for Hardcoded Laragon references in project code
echo "--- 3. Codebase Scan for Hardcoded 'laragon' Paths ---\n";
foreach ($phpFiles as $file) {
    $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file);
    // Skip audit script itself to avoid false self-matching
    if ($relPath === 'scripts\comprehensive_audit.php' || $relPath === 'scripts/comprehensive_audit.php') continue;
    $content = file_get_contents($file);
    if (preg_match_all('/[a-zA-Z0-9_\-\/\\\\]*laragon[a-zA-Z0-9_\-\/\\\\]*/i', $content, $matches)) {
        foreach ($matches[0] as $match) {
            $stats['laragon_refs']++;
            $issues[] = "[OBSOLETE PATH] in $relPath: $match";
            echo " [WARN] $relPath has reference: $match\n";
        }
    }
}
echo "\n";

// 4. Admin Authentication & RBAC Coverage Check
echo "--- 4. Admin Pages Authorization & RBAC Audit ---\n";
$adminDir = $baseDir . DIRECTORY_SEPARATOR . 'admin';
$adminFiles = glob($adminDir . DIRECTORY_SEPARATOR . '*.php');

$publicAdminFiles = ['login.php', 'logout.php', 'auth.php', 'setup.php'];

foreach ($adminFiles as $adminFile) {
    $baseName = basename($adminFile);
    if (in_array($baseName, $publicAdminFiles)) {
        continue;
    }
    $stats['admin_pages']++;
    $content = file_get_contents($adminFile);
    $relPath = 'admin/' . $baseName;

    $hasAuthInclude = (strpos($content, 'auth.php') !== false);
    $hasRequirePermission = (strpos($content, 'require_permission') !== false);
    $hasHasPermission = (strpos($content, 'has_permission') !== false);

    if (!$hasAuthInclude) {
        $stats['admin_unprotected']++;
        $issues[] = "[SECURITY HIGH] $relPath does not include auth.php";
        echo " [FAIL] $relPath: Missing auth.php include!\n";
    } elseif (!$hasRequirePermission && !$hasHasPermission) {
        $issues[] = "[RBAC MEDIUM] $relPath includes auth.php but does not enforce require_permission() or has_permission()";
        echo " [WARN] $relPath: No explicit require_permission() check found.\n";
    } else {
        echo " [PASS] $relPath: Auth & RBAC checks present.\n";
    }
}
echo "\n";

// 5. CSRF Protection Check
echo "--- 5. Form CSRF Protection Audit ---\n";
foreach ($phpFiles as $file) {
    $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file);
    $content = file_get_contents($file);

    // Look for POST handling
    if (strpos($content, '$_SERVER["REQUEST_METHOD"] === "POST"') !== false || 
        strpos($content, '$_SERVER[\'REQUEST_METHOD\'] === \'POST\'') !== false ||
        strpos($content, '$_SERVER[\'REQUEST_METHOD\'] == \'POST\'') !== false ||
        strpos($content, 'isset($_POST[') !== false) {
        
        $hasCsrfCheck = (
            strpos($content, 'csrf') !== false || 
            strpos($content, '_token') !== false ||
            strpos($content, 'verify_csrf') !== false
        );

        if (!$hasCsrfCheck) {
            $stats['unprotected_posts']++;
            $issues[] = "[CSRF MEDIUM] $relPath handles POST requests without CSRF verification token";
            echo " [WARN] POST handled without CSRF token in: $relPath\n";
        }
    }
}
echo " CSRF check complete: {$stats['unprotected_posts']} forms lack CSRF validation.\n\n";

// 6. SQL Injection Surface Scan
echo "--- 6. SQL Query Security Audit ---\n";
foreach ($phpFiles as $file) {
    $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file);
    // skip test/scratch scripts
    if (strpos($relPath, 'scripts') === 0) continue;

    $content = file_get_contents($file);
    
    // Check for $conn->query() with direct variable interpolation
    if (preg_match_all('/\$conn->query\s*\(\s*(["\'])(.*?)\1\s*\)/s', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $quote = $m[1];
            $sqlText = $m[2];
            // If double quotes and contains a variable ($foo)
            if ($quote === '"' && preg_match('/\$[a-zA-Z0-9_]+/', $sqlText)) {
                $stats['potential_sqli']++;
                $issues[] = "[SQLI HIGH] Potential SQL Injection in $relPath: " . trim(substr($sqlText, 0, 100));
                echo " [FAIL] Potential SQLi in $relPath: " . trim(substr($sqlText, 0, 80)) . "...\n";
            }
        }
    }
    // Also check for $conn->query("..." . $var) concatenation
    if (preg_match_all('/\$conn->query\s*\(\s*[^;]*?\.\s*\$[a-zA-Z0-9_]+[^;]*?\)/s', $content, $concatMatches)) {
        foreach ($concatMatches[0] as $m) {
            $stats['potential_sqli']++;
            $issues[] = "[SQLI HIGH] Potential SQL Injection in $relPath: " . trim(substr($m, 0, 100));
            echo " [FAIL] Potential SQLi concatenation in $relPath: " . trim(substr($m, 0, 80)) . "...\n";
        }
    }
}
if ($stats['potential_sqli'] === 0) {
    echo " [PASS] No obvious direct variable interpolations in \$conn->query found.\n";
}
echo "\n";

// 7. File Upload Audit
echo "--- 7. File Upload Handlers Audit ---\n";
foreach ($phpFiles as $file) {
    $relPath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $file);
    $content = file_get_contents($file);

    if (strpos($content, '$_FILES') !== false) {
        $stats['file_uploads']++;
        $hasExtCheck = (
            strpos($content, 'cms_upload_image') !== false ||
            strpos($content, 'getimagesize') !== false ||
            strpos($content, 'pathinfo') !== false || 
            strpos($content, 'mime_content_type') !== false || 
            strpos($content, 'finfo') !== false || 
            strpos($content, 'in_array') !== false
        );
        $hasPhpDenial = (strpos($content, 'php') !== false);

        echo " [*] File upload handler found in: $relPath\n";
        if (!$hasExtCheck) {
            $stats['insecure_uploads']++;
            $issues[] = "[FILE UPLOAD HIGH] Insecure file upload in $relPath without strict extension/MIME validation";
            echo "  [FAIL] Missing strict file extension / MIME check in $relPath\n";
        } else {
            echo "  [PASS] File extension checking detected in $relPath\n";
        }
    }
}
echo "\n";

// 8. Uploads Directory Execution Security Check (.htaccess)
echo "--- 8. Uploads Directory Execution Security ---\n";
$uploadsHtaccess = $baseDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '.htaccess';
if (!file_exists($uploadsHtaccess)) {
    $issues[] = "[SECURITY HIGH] uploads/.htaccess is MISSING! Malicious uploaded PHP files could be directly executed by the web server.";
    echo " [FAIL] uploads/.htaccess does not exist! Risk of arbitrary PHP execution.\n";
} else {
    echo " [PASS] uploads/.htaccess exists.\n";
}
echo "\n";

// 9. Public Form Spam Protection Audit
echo "--- 9. Public Form (contact.php) Spam & Honeypot Audit ---\n";
$contactContent = file_get_contents($baseDir . DIRECTORY_SEPARATOR . 'contact.php');
if (strpos($contactContent, 'honeypot') === false && strpos($contactContent, 'h_pot') === false && strpos($contactContent, 'website_url') === false) {
    $issues[] = "[ROBUSTNESS MEDIUM] contact.php does not have a honeypot field or rate limiting against automated spam bots.";
    echo " [WARN] contact.php lacks bot honeypot / rate limiting protection.\n";
} else {
    echo " [PASS] Honeypot/Spam protection detected in contact.php.\n";
}
echo "\n";

// SUMMARY
echo "========================================================\n";
echo "AUDIT SUMMARY & ISSUES FOUND (" . count($issues) . ")\n";
echo "========================================================\n";
foreach ($issues as $idx => $issue) {
    echo ($idx + 1) . ". $issue\n";
}

echo "\nAudit complete.\n";
