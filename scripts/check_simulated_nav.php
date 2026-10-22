<?php
$_GET['lang'] = 'ja';
ob_start();
include __DIR__ . '/../index.php';
$html = ob_get_clean();

// Find navbar links in output
preg_match('/<ul class="navbar-nav[^>]*>(.*?)<\/ul>/s', $html, $matches);
if (!empty($matches[1])) {
    echo "NAVBAR CONTENT:" . PHP_EOL;
    echo strip_tags($matches[1]) . PHP_EOL;
} else {
    echo "Navbar not found" . PHP_EOL;
}
