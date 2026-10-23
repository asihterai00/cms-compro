<?php
/**
 * PT Nihon Plast Indonesia - Multilingual Content Translation Management CMS
 * Manage, edit, and dynamically save translations into `content_translations` table.
 * 100% Free & Open-Source, zero paid external APIs.
 */

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/translation_helpers.php";

require_permission('translations', 'read');

$supportedLangs = [
    'id' => ['name' => 'Bahasa Indonesia (Utama)', 'flag' => '../assets/img/flags/id.svg', 'short' => 'ID'],
    'en' => ['name' => 'English', 'flag' => '../assets/img/flags/en.svg', 'short' => 'EN'],
    'ja' => ['name' => '日本語 (Japanese)', 'flag' => '../assets/img/flags/ja.svg', 'short' => 'JA'],
    'zh' => ['name' => '简体中文 (Chinese)', 'flag' => '../assets/img/flags/zh.svg', 'short' => 'ZH'],
    'ko' => ['name' => '한국어 (Korean)', 'flag' => '../assets/img/flags/ko.svg', 'short' => 'KO'],
];

$entities = [
    'product' => [
        'name' => '🚗 Produk Otomotif',
        'table' => 'products',
        'fields' => ['name' => 'Nama Produk', 'description' => 'Deskripsi Produk'],
        'title_field' => 'name'
    ],
    'news' => [
        'name' => '📰 Berita & Publikasi',
        'table' => 'news',
        'fields' => ['title' => 'Judul Berita', 'content' => 'Konten Lengkap Berita'],
        'title_field' => 'title'
    ],
    'company_profile' => [
        'name' => '🏢 Profil Perusahaan',
        'table' => 'company_profile',
        'fields' => ['tagline' => 'Tagline Utama', 'short_description' => 'Ringkasan Profil', 'about_text' => 'Tentang Kami', 'vision' => 'Visi Perusahaan', 'mission' => 'Misi Perusahaan', 'values_text' => 'Nilai Perusahaan'],
        'title_field' => 'company_name'
    ],
    'page_banner' => [
        'name' => '🖼️ Page Banners',
        'table' => 'page_banners',
        'fields' => ['page_title' => 'Judul Banner', 'subtitle' => 'Sub-judul Banner'],
        'title_field' => 'page_key'
    ],
    'certification' => [
        'name' => '📜 Sertifikasi & Mutu ISO',
        'table' => 'certifications',
        'fields' => ['name' => 'Nama Standar Mutu', 'category' => 'Kategori Akreditasi'],
        'title_field' => 'name'
    ],
    'career' => [
        'name' => '💼 Karir & Rekrutmen',
        'table' => 'careers',
        'fields' => ['position' => 'Nama Posisi', 'department' => 'Departemen'],
        'title_field' => 'position'
    ],
    'facility_machinery' => [
        'name' => '⚙️ Mesin Injeksi Presisi',
        'table' => 'facility_machinery',
        'fields' => ['model_brand' => 'Merk/Model Mesin', 'application' => 'Aplikasi Komponen'],
        'title_field' => 'model_brand'
    ],
    'site_section' => [
        'name' => '🧩 Dynamic CMS Sections',
        'table' => 'site_sections',
        'fields' => ['title' => 'Judul Section', 'subtitle' => 'Sub-judul', 'badge_text' => 'Teks Badge', 'button_text' => 'Teks Tombol'],
        'title_field' => 'section_name'
    ],
    'site_section_item' => [
        'name' => '🗂️ Dynamic Sub-Items & Cards',
        'table' => 'site_section_items',
        'fields' => ['title' => 'Judul Item', 'subtitle' => 'Sub-judul Item', 'content' => 'Isi Konten'],
        'title_field' => 'title'
    ]
];

$currentLang = trim($_GET['lang'] ?? 'id');
if (!isset($supportedLangs[$currentLang])) {
    $currentLang = 'id';
}

$currentEntity = trim($_GET['entity'] ?? 'product');
if (!isset($entities[$currentEntity])) {
    $currentEntity = 'product';
}

$entityConfig = $entities[$currentEntity];
$successMsg = "";
$errorMsg = "";

// Handle Form Submission: Save Translations
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    require_permission('translations', 'update');

    $submittedData = $_POST['trans'] ?? [];
    $savedCount = 0;

    if (is_array($submittedData)) {
        foreach ($submittedData as $eId => $fields) {
            $eId = (int)$eId;
            if ($eId <= 0 || !is_array($fields)) continue;

            foreach ($fields as $fName => $val) {
                if (array_key_exists($fName, $entityConfig['fields'])) {
                    if (save_content_translation($currentEntity, $eId, $currentLang, $fName, (string)$val)) {
                        $savedCount++;
                    }
                }
            }

            // If updating Indonesian, sync back to base table
            if ($currentLang === 'id') {
                $tbl = $entityConfig['table'];
                $allowedCols = array_keys($entityConfig['fields']);
                $updates = [];
                $types = '';
                $vals = [];
                foreach ($fields as $fName => $val) {
                    if (in_array($fName, $allowedCols, true)) {
                        $updates[] = "`$fName` = ?";
                        $types .= 's';
                        $vals[] = (string)$val;
                    }
                }
                if (!empty($updates)) {
                    $sql = "UPDATE `$tbl` SET " . implode(', ', $updates) . " WHERE id = ?";
                    $types .= 'i';
                    $vals[] = $eId;
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param($types, ...$vals);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    }

    $successMsg = "Berhasil memperbarui $savedCount kolom konten/terjemahan bahasa [{$supportedLangs[$currentLang]['name']}] ke database!";
}

// Fetch source records with safe static queries (respecting sort_order)
$records = [];
switch ($currentEntity) {
    case 'news':
        $q = $conn->query("SELECT * FROM `news` ORDER BY sort_order ASC, id ASC");
        break;
    case 'company_profile':
        $q = $conn->query("SELECT * FROM `company_profile` ORDER BY id ASC");
        break;
    case 'page_banner':
        $q = $conn->query("SELECT * FROM `page_banners` ORDER BY id ASC");
        break;
    case 'certification':
        $q = $conn->query("SELECT * FROM `certifications` ORDER BY sort_order ASC, id ASC");
        break;
    case 'career':
        $q = $conn->query("SELECT * FROM `careers` ORDER BY sort_order ASC, id ASC");
        break;
    case 'facility_machinery':
        $q = $conn->query("SELECT * FROM `facility_machinery` ORDER BY sort_order ASC, id ASC");
        break;
    case 'site_section':
        $q = $conn->query("SELECT * FROM `site_sections` ORDER BY page ASC, sort_order ASC, id ASC");
        break;
    case 'site_section_item':
        $q = $conn->query("SELECT * FROM `site_section_items` ORDER BY section_key ASC, sort_order ASC, id ASC");
        break;
    case 'product':
    default:
        $q = $conn->query("SELECT * FROM `products` ORDER BY sort_order ASC, id ASC");
        break;
}
if ($q) {
    while ($r = $q->fetch_assoc()) {
        $records[] = $r;
    }
}

// Fetch existing translations for these records in the active language using prepared statement
$existingTranslations = [];
$tStmt = $conn->prepare("SELECT entity_id, field_name, translation_value 
                         FROM content_translations 
                         WHERE entity_type = ? 
                           AND lang_code = ?");
if ($tStmt) {
    $tStmt->bind_param("ss", $currentEntity, $currentLang);
    $tStmt->execute();
    $tRes = $tStmt->get_result();
    while ($tRow = $tRes->fetch_assoc()) {
        $existingTranslations[(int)$tRow['entity_id']][$tRow['field_name']] = $tRow['translation_value'];
    }
    $tStmt->close();
}

function get_trans_entity_count($eKey) {
    global $conn;
    switch ($eKey) {
        case 'product': $res = $conn->query("SELECT COUNT(*) FROM `products`"); break;
        case 'news': $res = $conn->query("SELECT COUNT(*) FROM `news`"); break;
        case 'company_profile': $res = $conn->query("SELECT COUNT(*) FROM `company_profile`"); break;
        case 'page_banner': $res = $conn->query("SELECT COUNT(*) FROM `page_banners`"); break;
        case 'certification': $res = $conn->query("SELECT COUNT(*) FROM `certifications`"); break;
        case 'career': $res = $conn->query("SELECT COUNT(*) FROM `careers`"); break;
        case 'facility_machinery': $res = $conn->query("SELECT COUNT(*) FROM `facility_machinery`"); break;
        case 'site_section': $res = $conn->query("SELECT COUNT(*) FROM `site_sections`"); break;
        case 'site_section_item': $res = $conn->query("SELECT COUNT(*) FROM `site_section_items`"); break;
        default: return 0;
    }
    return $res ? (int)$res->fetch_row()[0] : 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Content Translations | PT Nihon Plast Indonesia</title>
    <style>
        .flag-tab-img {
            width: 20px;
            height: 14px;
            object-fit: cover;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            border: 1px solid rgba(0,0,0,0.1);
        }
        .translation-row-card {
            background: #FFFFFF;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .translation-row-card:hover {
            border-color: #005A36;
            box-shadow: 0 4px 12px rgba(0, 90, 54, 0.08);
        }
        .source-text-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 0.65rem 0.85rem;
            font-size: 0.86rem;
            color: #334155;
            line-height: 1.5;
        }
        .target-input {
            border-color: #cbd5e1;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .target-input:focus {
            border-color: #005A36;
            box-shadow: 0 0 0 0.25rem rgba(0, 90, 54, 0.15);
        }
    </style>
</head>
<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-np-green">
            <i class="bi bi-translate me-2"></i>Pusat Terjemahan Konten (Dynamic Multilingual)
        </h1>
        <p class="text-muted small mb-0">
            Kelola dan ubah terjemahan langsung ke database MySQL. Semua perubahan otomatis terhubung ke tampilan pengunjung saat memilih bahasa.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="../index.php" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat di Web
        </a>
    </div>
</div>

<?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><?= htmlspecialchars($successMsg) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- 1. Language Tabs (Pilihan Bahasa Target) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="small fw-bold text-muted text-uppercase me-2"><i class="bi bi-globe2 me-1"></i>Pilih Bahasa Target:</span>
                <div class="btn-group" role="group">
                    <?php foreach ($supportedLangs as $lCode => $lMeta): ?>
                        <a href="translations.php?entity=<?= urlencode($currentEntity) ?>&lang=<?= $lCode ?>" 
                           class="btn btn-sm <?= $currentLang === $lCode ? 'btn-success fw-bold active' : 'btn-outline-secondary' ?> d-flex align-items-center gap-2">
                            <img src="<?= htmlspecialchars($lMeta['flag']) ?>" alt="<?= htmlspecialchars($lMeta['short']) ?>" class="flag-tab-img">
                            <span><?= htmlspecialchars($lMeta['name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Live Search in Table -->
            <div class="ms-auto" style="min-width: 260px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filterInput" class="form-control" placeholder="Cari item terjemahan...">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Entity Selector Pills -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <span class="small fw-bold text-muted text-uppercase d-block mb-2">Pilih Entitas Konten yang Ingin Diterjemahkan:</span>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($entities as $eKey => $eConf): ?>
                <a href="translations.php?entity=<?= $eKey ?>&lang=<?= $currentLang ?>" 
                   class="btn btn-sm <?= $currentEntity === $eKey ? 'btn-np-primary fw-bold' : 'btn-light border text-muted' ?>">
                    <?= htmlspecialchars($eConf['name']) ?>
                    <span class="badge <?= $currentEntity === $eKey ? 'bg-white text-dark' : 'bg-secondary' ?> ms-1">
                        <?= get_trans_entity_count($eKey) ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- 3. Translation Form -->
<form method="POST" action="translations.php?entity=<?= urlencode($currentEntity) ?>&lang=<?= urlencode($currentLang) ?>">
    <?= csrf_field() ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <?= htmlspecialchars($entityConfig['name']) ?> 
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-2">
                    Bahasa: <?= htmlspecialchars($supportedLangs[$currentLang]['name']) ?>
                </span>
            </h5>
            <small class="text-muted">Total <?= count($records) ?> data tersedia. Terjemahan disimpan permanen di database MySQL.</small>
        </div>
        <div>
            <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">
                <i class="bi bi-save me-1"></i> Simpan Semua Terjemahan
            </button>
        </div>
    </div>

    <?php if (empty($records)): ?>
        <div class="alert alert-info">Tidak ada data ditemukan untuk entitas ini di database.</div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3" id="transContainer">
            <?php foreach ($records as $rec): 
                $rId = (int)$rec['id'];
                $rTitle = $rec[$entityConfig['title_field']] ?? ("ID #" . $rId);
            ?>
                <div class="translation-row-card p-3 p-md-4 search-target" data-search="<?= strtolower(htmlspecialchars($rTitle)) ?>">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark text-white">#<?= $rId ?></span>
                            <strong class="text-dark fs-6"><?= htmlspecialchars($rTitle) ?></strong>
                        </div>
                        <span class="badge bg-light text-muted border small"><?= htmlspecialchars($entityConfig['table']) ?></span>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($entityConfig['fields'] as $fKey => $fLabel): 
                            $sourceVal = $rec[$fKey] ?? '';
                            $currentTransVal = $existingTranslations[$rId][$fKey] ?? ($currentLang === 'id' ? $sourceVal : '');
                            $isLong = strlen($sourceVal) > 100 || str_contains($sourceVal, "\n");
                        ?>
                            <div class="col-12">
                                <div class="row g-2 align-items-start">
                                    <!-- Label & Original Indonesian Text -->
                                    <div class="col-lg-5">
                                        <label class="form-label small fw-bold text-dark mb-1 d-flex align-items-center justify-content-between">
                                            <span><?= htmlspecialchars($fLabel) ?> (🇮🇩 Indonesia / Default)</span>
                                            <button type="button" class="btn btn-link btn-sm p-0 text-muted copy-source-btn" 
                                                    data-target="input_<?= $rId ?>_<?= $fKey ?>" 
                                                    data-source="<?= htmlspecialchars($sourceVal, ENT_QUOTES) ?>" 
                                                    title="Salin teks sumber">
                                                <i class="bi bi-clipboard me-1"></i>Salin
                                            </button>
                                        </label>
                                        <div class="source-text-box">
                                            <?= nl2br(htmlspecialchars($sourceVal !== '' ? $sourceVal : '(Kosong)')) ?>
                                        </div>
                                    </div>

                                    <!-- Translated Target Input -->
                                    <div class="col-lg-7">
                                        <label class="form-label small fw-bold text-success mb-1">
                                            <img src="<?= htmlspecialchars($supportedLangs[$currentLang]['flag']) ?>" class="flag-tab-img me-1">
                                            Terjemahan (<?= htmlspecialchars($supportedLangs[$currentLang]['name']) ?>)
                                        </label>
                                        <?php if ($isLong): ?>
                                            <textarea 
                                                id="input_<?= $rId ?>_<?= $fKey ?>"
                                                name="trans[<?= $rId ?>][<?= $fKey ?>]" 
                                                class="form-control target-input" 
                                                rows="3" 
                                                placeholder="Ketikkan terjemahan <?= htmlspecialchars($fLabel) ?>..."><?= htmlspecialchars($currentTransVal) ?></textarea>
                                        <?php else: ?>
                                            <input 
                                                type="text" 
                                                id="input_<?= $rId ?>_<?= $fKey ?>"
                                                name="trans[<?= $rId ?>][<?= $fKey ?>]" 
                                                class="form-control target-input" 
                                                value="<?= htmlspecialchars($currentTransVal) ?>" 
                                                placeholder="Ketikkan terjemahan <?= htmlspecialchars($fLabel) ?>...">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-end mt-4 mb-5">
            <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow-sm">
                <i class="bi bi-save me-1"></i> Simpan Semua Terjemahan (<?= htmlspecialchars($supportedLangs[$currentLang]['name']) ?>)
            </button>
        </div>
    <?php endif; ?>
</form>

<?php include __DIR__ . "/includes/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Live Search Filter
    const filterInput = document.getElementById('filterInput');
    if (filterInput) {
        filterInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.search-target');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 2. Quick Copy Source Button
    document.querySelectorAll('.copy-source-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const sourceText = this.getAttribute('data-source');
            const targetEl = document.getElementById(targetId);
            if (targetEl && sourceText) {
                targetEl.value = sourceText;
                targetEl.focus();
            }
        });
    });
});
</script>

</body>
</html>
