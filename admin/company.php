<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/translation_helpers.php";

require_permission('company', 'read');

$success = "";
$error = "";

$result = $conn->query(
    "SELECT *
     FROM company_profile
     ORDER BY id ASC
     LIMIT 1"
);

$company = $result
    ? $result->fetch_assoc()
    : null;

if (!$company) {

    $conn->query(
        "INSERT INTO company_profile
         (company_name)
         VALUES
         ('PT Nihon Plast Indonesia')"
    );

    $result = $conn->query(
        "SELECT *
         FROM company_profile
         ORDER BY id ASC
         LIMIT 1"
    );

    $company = $result->fetch_assoc();
}

$companyTranslations = ($company && isset($company['id'])) ? get_entity_all_translations('company_profile', (int)$company['id']) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    require_permission('company', 'update');

    $company_name = trim(
        $_POST["company_name"] ?? ""
    );

    $tagline = trim(
        $_POST["tagline"] ?? ""
    );

    $short_description = trim(
        $_POST["short_description"] ?? ""
    );

    $about_text = trim(
        $_POST["about_text"] ?? ""
    );

    $vision = trim(
        $_POST["vision"] ?? ""
    );

    $mission = trim(
        $_POST["mission"] ?? ""
    );

    $values_text = trim(
        $_POST["values_text"] ?? ""
    );

    $address = trim(
        $_POST["address"] ?? ""
    );

    $phone = trim(
        $_POST["phone"] ?? ""
    );

    $email = trim(
        $_POST["email"] ?? ""
    );

    $logo = $company["logo"] ?? "";

    if ($company_name === "") {

        $error = "Nama perusahaan wajib diisi.";

    } else {

        if (
            isset($_FILES["logo"]) &&
            $_FILES["logo"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {
            $upRes = cms_upload_image($_FILES["logo"], "company", "logo_", 2097152);
            if ($upRes["success"]) {
                $logo = basename($upRes["path"]);
            } else {
                $error = $upRes["error"];
            }
        }

        if ($error === "") {

            $stmt = $conn->prepare(
                "UPDATE company_profile
                 SET
                    company_name = ?,
                    tagline = ?,
                    short_description = ?,
                    about_text = ?,
                    vision = ?,
                    mission = ?,
                    values_text = ?,
                    address = ?,
                    phone = ?,
                    email = ?,
                    logo = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "sssssssssssi",
                $company_name,
                $tagline,
                $short_description,
                $about_text,
                $vision,
                $mission,
                $values_text,
                $address,
                $phone,
                $email,
                $logo,
                $company["id"]
            );

            if ($stmt->execute()) {

                $success =
                    "Company Profile berhasil disimpan.";

                if (!empty($_POST['trans']) && is_array($_POST['trans']) && isset($company['id'])) {
                    save_entity_translations('company_profile', (int)$company['id'], $_POST['trans']);
                    $companyTranslations = get_entity_all_translations('company_profile', (int)$company['id']);
                }

                $company["company_name"] =
                    $company_name;

                $company["tagline"] =
                    $tagline;

                $company["short_description"] =
                    $short_description;

                $company["about_text"] =
                    $about_text;

                $company["vision"] =
                    $vision;

                $company["mission"] =
                    $mission;

                $company["values_text"] =
                    $values_text;

                $company["address"] =
                    $address;

                $company["phone"] =
                    $phone;

                $company["email"] =
                    $email;

                $company["logo"] =
                    $logo;

            } else {

                $error =
                    "Data gagal disimpan.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <link
        rel="stylesheet"
        href="assets/admin.css"
    >

    <title>NPI CMS - Profil Perusahaan | PT Nihon Plast Indonesia</title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="mb-4">
    <!-- Executive Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-building me-1"></i> CORPORATE IDENTITY & PROFILE
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    PT Nihon Plast Indonesia
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">Company Profile & Contact</h1>
            <p class="text-muted small mb-0">Kelola informasi legalitas korporasi, visi-misi, nilai perusahaan, dan kontak operasional.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="../about.php" target="_blank" class="btn btn-outline-emerald btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Publik
            </a>
        </div>
    </div>

    <!-- Quick Navigation Tabs for Tentang Kami -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="company.php" class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-building me-1"></i> Profil & Kontak Perusahaan
        </a>
        <a href="site_sections.php?page=about" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-layout-text-window-reverse me-1"></i> Seksi Halaman Tentang Kami
        </a>
        <a href="site_section_items.php?section_key=about_values" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-collection me-1"></i> Sub-Item & Nilai Perusahaan
        </a>
        <a href="banners.php?page_key=about" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-image me-1"></i> Banner Header About
        </a>
    </div>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i><?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Column: Main Corporate Data -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-info-circle"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Informasi Utama Perusahaan</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Legal Perusahaan *</label>
                                <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($company["company_name"] ?? "") ?>" required>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="card bg-light border-0 rounded-3 p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="small fw-bold text-dark text-uppercase"><i class="bi bi-translate me-1"></i> Konten Profil Perusahaan Multilingual (5 Bahasa):</span>
                                        <small class="text-muted">Tersimpan ke database & berganti otomatis saat bahasa situs dipilih</small>
                                    </div>
                                    <ul class="nav nav-tabs mb-3" id="langTabsCompany" role="tablist">
                                        <li class="nav-item">
                                            <button class="nav-link active fw-bold text-success" id="comp-id-tab" data-bs-toggle="tab" data-bs-target="#comp-id" type="button" role="tab">
                                                <img src="../assets/img/flags/id.svg" style="width:16px;height:12px;margin-right:4px;"> Indonesia (Utama)
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link fw-bold text-secondary" id="comp-en-tab" data-bs-toggle="tab" data-bs-target="#comp-en" type="button" role="tab">
                                                <img src="../assets/img/flags/en.svg" style="width:16px;height:12px;margin-right:4px;"> English
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link fw-bold text-secondary" id="comp-ja-tab" data-bs-toggle="tab" data-bs-target="#comp-ja" type="button" role="tab">
                                                <img src="../assets/img/flags/ja.svg" style="width:16px;height:12px;margin-right:4px;"> 日本語
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link fw-bold text-secondary" id="comp-zh-tab" data-bs-toggle="tab" data-bs-target="#comp-zh" type="button" role="tab">
                                                <img src="../assets/img/flags/zh.svg" style="width:16px;height:12px;margin-right:4px;"> 简体中文
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link fw-bold text-secondary" id="comp-ko-tab" data-bs-toggle="tab" data-bs-target="#comp-ko" type="button" role="tab">
                                                <img src="../assets/img/flags/ko.svg" style="width:16px;height:12px;margin-right:4px;"> 한국어
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="langTabsContentCompany">
                                        <!-- ID Tab (Base) -->
                                        <div class="tab-pane fade show active" id="comp-id" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tagline / Slogan (🇮🇩 Indonesia)</label>
                                                    <input type="text" name="tagline" class="form-control" value="<?= htmlspecialchars($company["tagline"] ?? "") ?>">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Singkat (🇮🇩 Indonesia)</label>
                                                    <textarea name="short_description" class="form-control" rows="2"><?= htmlspecialchars($company["short_description"] ?? "") ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tentang Perusahaan (🇮🇩 Indonesia)</label>
                                                    <textarea name="about_text" class="form-control" rows="4"><?= htmlspecialchars($company["about_text"] ?? "") ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Visi Perusahaan (🇮🇩 Indonesia)</label>
                                                    <textarea name="vision" class="form-control" rows="3"><?= htmlspecialchars($company["vision"] ?? "") ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Misi Perusahaan (🇮🇩 Indonesia)</label>
                                                    <textarea name="mission" class="form-control" rows="3"><?= htmlspecialchars($company["mission"] ?? "") ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Nilai-Nilai Inti (🇮🇩 Indonesia)</label>
                                                    <textarea name="values_text" class="form-control" rows="2"><?= htmlspecialchars($company["values_text"] ?? "") ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- EN Tab -->
                                        <div class="tab-pane fade" id="comp-en" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tagline / Slogan (🇬🇧 English)</label>
                                                    <input type="text" name="trans[en][tagline]" class="form-control" value="<?= htmlspecialchars($companyTranslations['en']['tagline'] ?? '') ?>" placeholder="e.g. Precision Automotive Interior & Safety Systems">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Short Description (🇬🇧 English)</label>
                                                    <textarea name="trans[en][short_description]" class="form-control" rows="2" placeholder="Brief corporate summary..."><?= htmlspecialchars($companyTranslations['en']['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">About Company (🇬🇧 English)</label>
                                                    <textarea name="trans[en][about_text]" class="form-control" rows="4" placeholder="Full about company text..."><?= htmlspecialchars($companyTranslations['en']['about_text'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Corporate Vision (🇬🇧 English)</label>
                                                    <textarea name="trans[en][vision]" class="form-control" rows="3" placeholder="Vision statement..."><?= htmlspecialchars($companyTranslations['en']['vision'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Corporate Mission (🇬🇧 English)</label>
                                                    <textarea name="trans[en][mission]" class="form-control" rows="3" placeholder="Mission statement..."><?= htmlspecialchars($companyTranslations['en']['mission'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Corporate Values (🇬🇧 English)</label>
                                                    <textarea name="trans[en][values_text]" class="form-control" rows="2" placeholder="Key organizational values..."><?= htmlspecialchars($companyTranslations['en']['values_text'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- JA Tab -->
                                        <div class="tab-pane fade" id="comp-ja" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">スローガン (🇯🇵 日本語)</label>
                                                    <input type="text" name="trans[ja][tagline]" class="form-control" value="<?= htmlspecialchars($companyTranslations['ja']['tagline'] ?? '') ?>" placeholder="精密自動車内装・安全システム">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">会社概要 (🇯🇵 日本語)</label>
                                                    <textarea name="trans[ja][short_description]" class="form-control" rows="2" placeholder="簡潔な会社概要..."><?= htmlspecialchars($companyTranslations['ja']['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">会社紹介 (🇯🇵 日本語)</label>
                                                    <textarea name="trans[ja][about_text]" class="form-control" rows="4" placeholder="詳細な企業紹介..."><?= htmlspecialchars($companyTranslations['ja']['about_text'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">企業ビジョン (🇯🇵 日本語)</label>
                                                    <textarea name="trans[ja][vision]" class="form-control" rows="3" placeholder="企業のビジョン..."><?= htmlspecialchars($companyTranslations['ja']['vision'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">ミッション (🇯🇵 日本語)</label>
                                                    <textarea name="trans[ja][mission]" class="form-control" rows="3" placeholder="企業の使命..."><?= htmlspecialchars($companyTranslations['ja']['mission'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">行動指針・価値観 (🇯🇵 日本語)</label>
                                                    <textarea name="trans[ja][values_text]" class="form-control" rows="2" placeholder="行動指針・共有価値観..."><?= htmlspecialchars($companyTranslations['ja']['values_text'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ZH Tab -->
                                        <div class="tab-pane fade" id="comp-zh" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">企业口号 (🇨🇳 简体中文)</label>
                                                    <input type="text" name="trans[zh][tagline]" class="form-control" value="<?= htmlspecialchars($companyTranslations['zh']['tagline'] ?? '') ?>" placeholder="精密汽车内饰与安全系统">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">公司简介 (🇨🇳 简体中文)</label>
                                                    <textarea name="trans[zh][short_description]" class="form-control" rows="2" placeholder="简要公司介绍..."><?= htmlspecialchars($companyTranslations['zh']['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">关于公司 (🇨🇳 简体中文)</label>
                                                    <textarea name="trans[zh][about_text]" class="form-control" rows="4" placeholder="详细公司介绍..."><?= htmlspecialchars($companyTranslations['zh']['about_text'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">企业愿景 (🇨🇳 简体中文)</label>
                                                    <textarea name="trans[zh][vision]" class="form-control" rows="3" placeholder="企业愿景..."><?= htmlspecialchars($companyTranslations['zh']['vision'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">企业使命 (🇨🇳 简体中文)</label>
                                                    <textarea name="trans[zh][mission]" class="form-control" rows="3" placeholder="企业使命..."><?= htmlspecialchars($companyTranslations['zh']['mission'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">核心价值观 (🇨🇳 简体中文)</label>
                                                    <textarea name="trans[zh][values_text]" class="form-control" rows="2" placeholder="核心价值观..."><?= htmlspecialchars($companyTranslations['zh']['values_text'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KO Tab -->
                                        <div class="tab-pane fade" id="comp-ko" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">슬로건 (🇰🇷 한국어)</label>
                                                    <input type="text" name="trans[ko][tagline]" class="form-control" value="<?= htmlspecialchars($companyTranslations['ko']['tagline'] ?? '') ?>" placeholder="정밀 자동차 내장 및 안전 시스템">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">회사 요약 (🇰🇷 한국어)</label>
                                                    <textarea name="trans[ko][short_description]" class="form-control" rows="2" placeholder="간단한 회사 소개..."><?= htmlspecialchars($companyTranslations['ko']['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">회사 소개 (🇰🇷 한국어)</label>
                                                    <textarea name="trans[ko][about_text]" class="form-control" rows="4" placeholder="상세 회사 소개..."><?= htmlspecialchars($companyTranslations['ko']['about_text'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">비전 (🇰🇷 한국어)</label>
                                                    <textarea name="trans[ko][vision]" class="form-control" rows="3" placeholder="기업 비전..."><?= htmlspecialchars($companyTranslations['ko']['vision'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">미션 (🇰🇷 한국어)</label>
                                                    <textarea name="trans[ko][mission]" class="form-control" rows="3" placeholder="기업 미션..."><?= htmlspecialchars($companyTranslations['ko']['mission'] ?? '') ?></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">핵심 가치 (🇰🇷 한국어)</label>
                                                    <textarea name="trans[ko][values_text]" class="form-control" rows="2" placeholder="핵심 가치..."><?= htmlspecialchars($companyTranslations['ko']['values_text'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Right Column: Logo & Contact Info -->
            <div class="col-lg-4">
                <!-- Website Logo -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-image"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Logo Perusahaan</h5>
                    </div>

                    <div class="card-body p-4 text-center">
                        <?php if (!empty($company["logo"])): ?>
                            <div class="p-3 bg-light rounded-4 border mb-3 d-inline-block w-100">
                                <img src="../uploads/company/<?= htmlspecialchars($company["logo"]) ?>" class="img-fluid" style="max-height: 90px; object-fit: contain;" alt="Company Logo">
                                <div class="fs-xs text-muted mt-2 text-truncate"><?= htmlspecialchars($company["logo"]) ?></div>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-light rounded-4 border mb-3 text-muted">
                                <i class="bi bi-image fs-1 d-block mb-1 text-muted"></i>
                                <span class="fs-xs">Belum ada logo diunggah</span>
                            </div>
                        <?php endif; ?>

                        <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                        <small class="text-muted d-block mt-2 fs-xs">
                            Format: PNG, SVG, WEBP, JPG. Maksimal 2 MB. Disarankan background transparan.
                        </small>
                    </div>
                </div>

                <!-- Contact & Factory Location -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <h5 class="fw-bold mb-0 text-dark">Kontak & Lokasi Pabrik</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Alamat Lengkap Kantor & Pabrik</label>
                            <textarea name="address" class="form-control" rows="4"><?= htmlspecialchars($company["address"] ?? "") ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Telepon / Fax</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($company["phone"] ?? "") ?>" placeholder="+62 264 ...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email Resmi</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($company["email"] ?? "") ?>" placeholder="info@n-plast.co.id">
                        </div>
                    </div>
                </div>

                <!-- Save Action Button -->
                <button type="submit" class="btn btn-emerald w-100 py-2 rounded-pill shadow-sm fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Profil Perusahaan
                </button>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
