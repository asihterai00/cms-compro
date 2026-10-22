<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/translation_helpers.php";

require_permission('products', 'read');

$editItem = null;
$productTranslations = [];
$uploadError = "";

if (isset($_GET["edit"])) {

    $id = (int) $_GET["edit"];

    $stmt = $conn->prepare(
        "SELECT *
         FROM products
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editItem = $stmt
        ->get_result()
        ->fetch_assoc();

    if ($editItem && function_exists('get_entity_all_translations')) {
        $productTranslations = get_entity_all_translations('product', $id);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_csrf();

    $id = (int) ($_POST["id"] ?? 0);
    if ($id > 0) {
        require_permission('products', 'update');
    } else {
        require_permission('products', 'create');
    }

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $image = trim($_POST["old_image"] ?? "");
    if (!empty($_POST["preset_image"])) {
        $image = trim($_POST["preset_image"]);
    }
    $uploadError = "";

    if (
        isset($_FILES["image_file"]) &&
        $_FILES["image_file"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        $uploadResult = cms_upload_image($_FILES["image_file"], "products", "product_");
        if ($uploadResult["success"]) {
            $image = $uploadResult["path"];
        } else {
            $uploadError = $uploadResult["error"];
        }
    }

    if ($uploadError !== "") {

        // Keep the form available so the user can correct the upload.

    } elseif ($name !== "") {

        $productId = 0;
        if ($id > 0) {

            $stmt = $conn->prepare(
                "UPDATE products
                 SET name = ?,
                     description = ?,
                     image = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "sssi",
                $name,
                $description,
                $image,
                $id
            );

            $stmt->execute();
            $productId = $id;

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO products
                 (name, description, image)
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $description,
                $image
            );

            $stmt->execute();
            $productId = $conn->insert_id;
        }

        // Save multi-language translations if submitted
        $transPost = $_POST['trans'] ?? [];
        if (!empty($transPost) && function_exists('save_entity_translations') && $productId > 0) {
            save_entity_translations('product', $productId, $transPost);
        }
    }

    if ($uploadError === "") {

        header("Location: products.php");
        exit;
    }
}

if (isset($_GET["delete"])) {

    require_csrf();
    require_permission('products', 'delete');

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM products
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}

$products = $conn->query(
    "SELECT *
     FROM products
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <link
        rel="stylesheet"
        href="assets/admin.css"
    >

    <title>NPI CMS - Produk Otomotif | PT Nihon Plast Indonesia</title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-box-seam text-emerald me-2"></i>Katalog Produk Otomotif
        </h1>
        <p class="text-muted small mb-0">
            Kelola 4 pilar komponen manufaktur presisi: Steering Wheel, Airbag Safety Modules, Cockpit Trims, dan Exterior Moulding.
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <?php if (!$editItem): ?>
            <a href="#formProduct" class="btn btn-emerald btn-sm d-inline-flex align-items-center gap-2 shadow-sm px-3 py-2 fw-bold">
                <i class="bi bi-plus-circle-fill"></i> Tambah Produk Baru
            </a>
        <?php else: ?>
            <a href="products.php" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-x-circle"></i> Batal Edit (Tambah Baru)
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($uploadError !== ""): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($uploadError) ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4" id="formProduct">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-slate-900">
            <i class="bi <?= $editItem ? 'bi-pencil-square text-primary' : 'bi-plus-circle text-emerald' ?> me-2"></i>
            <?= $editItem ? "Edit Produk: " . htmlspecialchars($editItem['name']) : "Tambah Produk Baru" ?>
        </h5>
        <?php if ($editItem): ?>
            <a href="products.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Batal
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-4">
        <form
            method="post"
            enctype="multipart/form-data"
            class="mt-1"
        >
            <?= csrf_field() ?>

            <input
                type="hidden"
                name="id"
                value="<?= (int) ($editItem["id"] ?? 0) ?>"
            >

            <input
                type="hidden"
                name="old_image"
                value="<?= htmlspecialchars($editItem["image"] ?? "") ?>"
            >

            <!-- Multi-Language Translation Tabs -->
            <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-dark text-uppercase"><i class="bi bi-translate me-1"></i> Informasi Produk Multilingual:</span>
                    <small class="text-muted">Teks otomatis tersimpan ke database & berubah saat bahasa diganti di web</small>
                </div>
                <ul class="nav nav-tabs mb-3" id="langTabsProduct" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-success" id="tab-id-tab" data-bs-toggle="tab" data-bs-target="#tab-id" type="button" role="tab">
                            <img src="../assets/img/flags/id.svg" style="width:16px;height:12px;margin-right:4px;"> Indonesia (Utama)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-en-tab" data-bs-toggle="tab" data-bs-target="#tab-en" type="button" role="tab">
                            <img src="../assets/img/flags/en.svg" style="width:16px;height:12px;margin-right:4px;"> English
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-ja-tab" data-bs-toggle="tab" data-bs-target="#tab-ja" type="button" role="tab">
                            <img src="../assets/img/flags/ja.svg" style="width:16px;height:12px;margin-right:4px;"> 日本語
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-zh-tab" data-bs-toggle="tab" data-bs-target="#tab-zh" type="button" role="tab">
                            <img src="../assets/img/flags/zh.svg" style="width:16px;height:12px;margin-right:4px;"> 简体中文
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="tab-ko-tab" data-bs-toggle="tab" data-bs-target="#tab-ko" type="button" role="tab">
                            <img src="../assets/img/flags/ko.svg" style="width:16px;height:12px;margin-right:4px;"> 한국어
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="langTabsContentProduct">
                    <!-- Tab ID -->
                    <div class="tab-pane fade show active" id="tab-id" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk (🇮🇩 Bahasa Indonesia - Default)</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editItem["name"] ?? "") ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi Produk (🇮🇩 Bahasa Indonesia - Default)</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($editItem["description"] ?? "") ?></textarea>
                        </div>
                    </div>

                    <!-- Tab EN -->
                    <div class="tab-pane fade" id="tab-en" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name (🇬🇧 English)</label>
                            <input type="text" name="trans[en][name]" class="form-control" value="<?= htmlspecialchars($productTranslations['en']['name'] ?? '') ?>" placeholder="e.g. Steering Wheel Systems">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Description (🇬🇧 English)</label>
                            <textarea name="trans[en][description]" class="form-control" rows="4" placeholder="English description..."><?= htmlspecialchars($productTranslations['en']['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab JA -->
                    <div class="tab-pane fade" id="tab-ja" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">製品名 (🇯🇵 日本語)</label>
                            <input type="text" name="trans[ja][name]" class="form-control" value="<?= htmlspecialchars($productTranslations['ja']['name'] ?? '') ?>" placeholder="ステアリングホイールシステム">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">製品説明 (🇯🇵 日本語)</label>
                            <textarea name="trans[ja][description]" class="form-control" rows="4" placeholder="製品の詳しい説明..."><?= htmlspecialchars($productTranslations['ja']['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab ZH -->
                    <div class="tab-pane fade" id="tab-zh" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">产品名称 (🇨🇳 简体中文)</label>
                            <input type="text" name="trans[zh][name]" class="form-control" value="<?= htmlspecialchars($productTranslations['zh']['name'] ?? '') ?>" placeholder="方向盘安全系统">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">产品说明 (🇨🇳 简体中文)</label>
                            <textarea name="trans[zh][description]" class="form-control" rows="4" placeholder="产品详细介绍说明..."><?= htmlspecialchars($productTranslations['zh']['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Tab KO -->
                    <div class="tab-pane fade" id="tab-ko" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">제품명 (🇰🇷 한국어)</label>
                            <input type="text" name="trans[ko][name]" class="form-control" value="<?= htmlspecialchars($productTranslations['ko']['name'] ?? '') ?>" placeholder="스티어링 휠 시스템">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">제품 설명 (🇰🇷 한국어)</label>
                            <textarea name="trans[ko][description]" class="form-control" rows="4" placeholder="제품 상세 설명..."><?= htmlspecialchars($productTranslations['ko']['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Product Image (Unggah File Baru atau Pilih Preset Resmi)
                </label>

                <input type="hidden" name="old_image" id="input_old_image" value="<?= htmlspecialchars($editItem["image"] ?? "") ?>">
                <input type="hidden" name="preset_image" id="input_preset_image" value="">

                <input
                    type="file"
                    name="image_file"
                    class="form-control mb-2"
                    accept=".jpg,.jpeg,.png,.webp,.svg"
                >

                <div class="mb-2">
                    <small class="text-muted d-block mb-1 fw-semibold">Pilih Cepat dari Asset Manufaktur Resmi:</small>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setProductPreset('uploads/products/product_steering_wheel.jpg')">
                            <i class="bi bi-disc me-1"></i> Steering Wheel
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setProductPreset('uploads/products/product_airbag_module.jpg')">
                            <i class="bi bi-shield-shaded me-1"></i> Airbag Module
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setProductPreset('uploads/products/product_cockpit_resin.svg')">
                            <i class="bi bi-speedometer2 me-1"></i> Cockpit Resin
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setProductPreset('uploads/products/product_exterior_trim.svg')">
                            <i class="bi bi-car-front me-1"></i> Exterior Trim
                        </button>
                    </div>
                </div>

                <div id="product_preview_box" class="p-2 border rounded bg-light <?= empty($editItem['image']) ? 'd-none' : '' ?>">
                    <div class="d-flex align-items-center gap-2">
                        <img src="../<?= htmlspecialchars($editItem['image'] ?? '') ?>" id="product_preview_img" style="height: 50px; width: 80px; object-fit: contain; background: #0f172a; border-radius: 4px;" alt="Preview">
                        <span class="small text-muted flex-grow-1 text-truncate" id="product_preview_label"><?= htmlspecialchars($editItem['image'] ?? '') ?></span>
                    </div>
                </div>
            </div>

            <script>
            function setProductPreset(path) {
                document.getElementById('input_preset_image').value = path;
                document.getElementById('input_old_image').value = path;
                document.getElementById('product_preview_img').src = '../' + path;
                document.getElementById('product_preview_label').innerText = path;
                document.getElementById('product_preview_box').classList.remove('d-none');
            }
            </script>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-emerald px-4 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> <?= $editItem ? "Perbarui Produk" : "Simpan Produk Baru" ?>
                </button>
                <?php if ($editItem): ?>
                    <a href="products.php" class="btn btn-outline-secondary px-3">
                        Batal
                    </a>
                <?php endif; ?>
            </div>

        </form>

    </div>

</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-slate-900">Daftar Produk Otomotif Aktif</h5>
        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle fw-bold">Katalog Presisi</span>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th style="width: 100px;">Foto</th>
                        <th style="width: 250px;">Nama Produk</th>
                        <th>Deskripsi</th>
                        <th style="width: 140px;" class="text-end pe-3">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php while ($item = $products->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <?php if ($item["image"]): ?>

                                <img
                                    src="<?= filter_var(
                                        $item["image"],
                                        FILTER_VALIDATE_URL
                                    )
                                        ? htmlspecialchars($item["image"])
                                        : "../" . htmlspecialchars($item["image"])
                                    ?>"
                                    class="rounded-3 border"
                                    style="width: 65px; height: 48px; object-fit: contain; background: #0f172a;"
                                    alt="<?= htmlspecialchars($item["name"]) ?>"
                                >

                            <?php else: ?>

                                <div class="rounded-3 border d-flex align-items-center justify-content-center bg-light text-muted" style="width: 65px; height: 48px; font-size: 0.7rem;">
                                    No Image
                                </div>

                            <?php endif; ?>

                        </td>

                        <td>
                            <div class="fw-bold text-slate-900"><?= htmlspecialchars($item["name"]) ?></div>
                            <span class="badge bg-light text-slate-600 border small mt-1 font-monospace" style="font-size: 0.65rem;">ID: #<?= (int)$item["id"] ?></span>
                        </td>

                        <td>
                            <div class="small text-muted" style="line-height: 1.5; max-width: 480px;">
                                <?= htmlspecialchars(mb_strimwidth($item["description"], 0, 140, '...')) ?>
                            </div>
                        </td>

                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a
                                    href="products.php?edit=<?= $item["id"] ?>#formProduct"
                                    class="btn btn-outline-primary"
                                    title="Edit produk ini"
                                >
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <?php if (has_permission('products', 'delete')): ?>
                                    <a
                                        href="<?= csrf_url('products.php?delete=' . (int)$item["id"]) ?>"
                                        class="btn btn-outline-danger"
                                        title="Hapus produk ini"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
