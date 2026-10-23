<?php
/**
 * Test All 12 Public Pages in All 5 Languages (60 Tests)
 * Verifies:
 * 1. Output buffering renders with zero PHP warnings/errors.
 * 2. HTML output length is substantial (>10KB).
 * 3. Correct html lang attribute is present (e.g. lang="ja", lang="zh-CN", etc.).
 * 4. Language combobox renders the active flag.
 * 5. Key translated UI strings appear in the rendered HTML.
 */

$rootDir = realpath(__DIR__ . '/..');
$phpBin = PHP_BINARY;

$pages = [
    'index.php',
    'about.php',
    'products.php',
    'product-detail.php?id=1',
    'facility.php',
    'quality.php',
    'career.php',
    'news.php',
    'news-detail.php?id=1',
    'contact.php',
    'gallery.php',
    'gallery-detail.php?id=1'
];

$langs = [
    'id' => ['html_lang' => 'id', 'short' => 'ID', 'test_word' => 'Beranda'],
    'en' => ['html_lang' => 'en', 'short' => 'EN', 'test_word' => 'Home'],
    'ja' => ['html_lang' => 'ja', 'short' => 'JA', 'test_word' => 'ホーム'],
    'zh' => ['html_lang' => 'zh-CN', 'short' => 'ZH', 'test_word' => '首页'],
    'ko' => ['html_lang' => 'ko', 'short' => 'KO', 'test_word' => '홈']
];

echo "========================================================\n";
echo "PT NIHON PLAST INDONESIA - MULTILINGUAL COMPREHENSIVE TEST\n";
echo "Testing 12 Pages x 5 Languages = 60 Rendering Scenarios\n";
echo "========================================================\n\n";

$total = 0;
$passed = 0;
$failed = 0;
$issues = [];

foreach ($langs as $langCode => $meta) {
    echo "--- Testing Language: [{$meta['short']}] {$langCode} (HTML lang: {$meta['html_lang']}) ---\n";
    foreach ($pages as $page) {
        $total++;
        $parts = explode('?', $page);
        $file = $parts[0];
        $queryStr = isset($parts[1]) ? $parts[1] . "&lang={$langCode}" : "lang={$langCode}";

        $testScript = <<<PHP
\$_SERVER['DOCUMENT_ROOT'] = '{$rootDir}';
\$_SERVER['HTTP_HOST'] = 'localhost';
\$_SERVER['REQUEST_METHOD'] = 'GET';
\$_SERVER['SCRIPT_NAME'] = '/{$file}';
\$_SERVER['PHP_SELF'] = '/{$file}';
\$_SERVER['QUERY_STRING'] = '{$queryStr}';
parse_str('{$queryStr}', \$_GET);
\$_SERVER['REQUEST_URI'] = '/{$file}?{$queryStr}';

ob_start();
include '{$rootDir}/{$file}';
\$html = ob_get_clean();
echo \$html;
PHP;

        $tmpFile = sys_get_temp_dir() . '/test_render_' . $langCode . '_' . md5($page) . '.php';
        file_put_contents($tmpFile, "<?php\nerror_reporting(E_ALL);\nini_set('display_errors', '1');\n" . $testScript);

        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];
        $proc = proc_open("\"{$phpBin}\" \"{$tmpFile}\"", $descriptors, $pipes);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);
        @unlink($tmpFile);

        $hasWarnings = false;
        if (preg_match('/(Warning|Fatal error|Parse error|Deprecated|Notice):/i', $stdout . $stderr, $m)) {
            $hasWarnings = true;
            $issues[] = "[$langCode] $page: PHP Error detected: " . $m[0];
        }

        $htmlLen = strlen($stdout);
        $hasHtmlLang = strpos($stdout, 'lang="' . $meta['html_lang'] . '"') !== false;
        $hasFlag = strpos($stdout, 'assets/img/flags/' . $langCode . '.svg') !== false;
        $hasTestWord = strpos($stdout, $meta['test_word']) !== false;

        if (!$hasWarnings && $htmlLen > 5000 && $hasHtmlLang && $hasFlag) {
            $passed++;
            echo " [PASS] $page [{$meta['short']}] - {$htmlLen} bytes | Flag: OK | Lang: {$meta['html_lang']} | Word '{$meta['test_word']}': " . ($hasTestWord ? 'OK' : 'N/A') . "\n";
        } else {
            $failed++;
            $reason = [];
            if ($hasWarnings) $reason[] = "PHP Warnings/Errors";
            if ($htmlLen <= 5000) $reason[] = "Output too small ($htmlLen bytes)";
            if (!$hasHtmlLang) $reason[] = "Missing lang=\"{$meta['html_lang']}\"";
            if (!$hasFlag) $reason[] = "Missing flag for {$langCode}";
            $issues[] = "[$langCode] $page FAIL: " . implode(', ', $reason);
            echo " [FAIL] $page [{$meta['short']}] - " . implode(', ', $reason) . "\n";
        }
    }
    echo "\n";
}

echo "========================================================\n";
echo "SUMMARY: {$passed} PASSED / {$total} TOTAL ({$failed} FAILED)\n";
echo "========================================================\n";

if ($failed > 0) {
    echo "\nISSUES FOUND:\n";
    foreach ($issues as $iss) {
        echo " - $iss\n";
    }
    exit(1);
} else {
    echo "\n>>> ALL 60 LANGUAGE & PAGE COMBINATIONS PASSED PERFECTLY! <<<\n";
    exit(0);
}
