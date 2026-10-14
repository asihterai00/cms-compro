<?php
$_SERVER["PHP_SELF"] = "index.php";
$_SERVER["REQUEST_METHOD"] = "GET";
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["HTTP_ACCEPT_LANGUAGE"] = "id,en;q=0.9";

ob_start();
include __DIR__ . "/../index.php";
$out = ob_get_clean();

echo "INDEX_OUTPUT_LEN: " . strlen($out) . "\n";
echo "CONTAINS_NAV: " . (strpos($out, "lang-switcher") !== false ? "YES" : "NO") . "\n";
echo "CONTAINS_5_LANGS: " . ((strpos($out, ">JA<") !== false && strpos($out, ">ZH<") !== false && strpos($out, ">KO<") !== false) ? "YES" : "NO") . "\n";
echo "CONTAINS_DYNAMIC_HERO: " . (strpos($out, "hero-corporate") !== false ? "YES" : "NO") . "\n";
echo "CONTAINS_VIDEO_TAG: " . (strpos($out, "<video") !== false ? "YES" : "NO") . "\n";
echo "CONTAINS_IFRAME_VIDEO: " . (strpos($out, "youtube") !== false ? "YES" : "NO") . "\n";

// Test Japanese switch
$_GET['lang'] = 'ja';
ob_start();
include __DIR__ . "/../index.php";
$outJa = ob_get_clean();
echo "JA_HTML_LANG: " . (strpos($outJa, '<html lang="ja">') !== false ? "YES" : "NO") . "\n";
echo "JA_NAV_ABOUT: " . (strpos($outJa, "会社情報") !== false ? "YES" : "NO") . "\n";
echo "JA_NAV_PRODUCTS: " . (strpos($outJa, "製品情報") !== false ? "YES" : "NO") . "\n";
echo "JA_NAV_FACILITY: " . (strpos($outJa, "生産拠点・設備技術") !== false ? "YES" : "NO") . "\n";
echo "JA_NAV_RFQ: " . (strpos($outJa, "B2B見積依頼") !== false ? "YES" : "NO") . "\n";

// Test Korean switch
$_GET['lang'] = 'ko';
ob_start();
include __DIR__ . "/../index.php";
$outKo = ob_get_clean();
echo "KO_HTML_LANG: " . (strpos($outKo, '<html lang="ko">') !== false ? "YES" : "NO") . "\n";
echo "KO_NAV_ABOUT: " . (strpos($outKo, "회사 소개") !== false ? "YES" : "NO") . "\n";
echo "KO_NAV_PRODUCTS: " . (strpos($outKo, "제품 정보") !== false ? "YES" : "NO") . "\n";
echo "KO_NAV_CAREER: " . (strpos($outKo, "채용 정보") !== false ? "YES" : "NO") . "\n";

// Test Chinese switch
$_GET['lang'] = 'zh';
ob_start();
include __DIR__ . "/../index.php";
$outZh = ob_get_clean();
echo "ZH_HTML_LANG: " . (strpos($outZh, '<html lang="zh-CN">') !== false ? "YES" : "NO") . "\n";
echo "ZH_NAV_ABOUT: " . (strpos($outZh, "关于我们") !== false ? "YES" : "NO") . "\n";
echo "ZH_NAV_PRODUCTS: " . (strpos($outZh, "产品中心") !== false ? "YES" : "NO") . "\n";
echo "ZH_NAV_RFQ: " . (strpos($outZh, "B2B询价") !== false ? "YES" : "NO") . "\n";
