<?php
$urls = [
    'http://localhost/cms-compro/assets/img/favicon.svg',
    'http://localhost/cms-compro/assets/img/favicon.png',
    'http://localhost/cms-compro/assets/img/favicon.ico',
    'http://localhost/cms-compro/favicon.ico',
    'http://localhost/favicon.ico',
    'http://localhost/cms-compro/admin/assets/img/favicon.svg',
    'http://localhost/cms-compro/admin/favicon.ico'
];

$allOk = true;
foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $ok = ($code === 200);
    echo ($ok ? "[PASS]" : "[FAIL]") . " $url => HTTP $code ($type)\n";
    if (!$ok) $allOk = false;
    curl_close($ch);
}

if ($allOk) {
    echo "\n>>> ALL FAVICON URLS RETURN HTTP 200 OK! <<<\n";
} else {
    echo "\n>>> SOME FAVICON URLS FAILED! <<<\n";
}
