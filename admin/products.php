<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

$editItem = null;
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
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = (int) ($_POST["id"] ?? 0);
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $image = trim($_POST["old_image"] ?? "");
    if (!empty($_POST["preset_image"])) {
        $image = trim($_POST["preset_image"]);
    }
    $uploadError = "";

    if (
        isset($_FILES["image_file"]) &&
        $_FILES["image_file"]["error"] === UPLOAD_ERR_OK
    ) {

        $allowed = [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/svg+xml"
        ];

        if (
            in_array(
                $_FILES["image_file"]["type"],
                $allowed,
                true
            )
        ) {

            $extension = pathinfo(
                $_FILES["image_file"]["name"],
                PATHINFO_EXTENSION
            );

            $filename =
                uniqid("product_", true)
                . "."
                . strtolower($extension);

            $uploadDir =
                __DIR__
                . "/../uploads/products/";

            if (!is_dir($uploadDir)) {

                mkdir(
                    $uploadDir,
                    0755,
                    true
                );
            }

            if (!is_writable($uploadDir)) {

                $uploadError =
                    "Folder upload product tidak dapat ditulis.";

            } else {

                $destination =
                    $uploadDir
                    . $filename;

                if (
                    move_uploaded_file(
                        $_FILES["image_file"]["tmp_name"],
                        $destination
                    )
                ) {

                    $image =
                        "uploads/products/"
                        . $filename;

                } else {

                    $uploadError =
                        "Gambar product gagal diupload.";
                }
            }
        }
    }

    if ($uploadError !== "") {

        // Keep the form available so the user can correct the upload.

    } elseif ($name !== "") {

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
        }
    }

    if ($uploadError === "") {

        header("Location: products.php");
        exit;
    }
}

if (isset($_GET["delete"])) {

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

    <title>
        Manage Products
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<h1>
    Products
</h1>

<p class="text-muted">
    Add, edit, delete, and upload product images.
</p>

<?php if ($uploadError !== ""): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($uploadError) ?>
    </div>

<?php endif; ?>

<div class="card table-card mb-4">

    <div class="card-body p-4">

        <h5>
            <?= $editItem ? "Edit Product" : "Add Product" ?>
        </h5>

        <form
            method="post"
            enctype="multipart/form-data"
            class="mt-3"
        >

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

            <div class="mb-3">

                <label class="form-label">
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($editItem["name"] ?? "") ?>"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                ><?= htmlspecialchars($editItem["description"] ?? "") ?></textarea>

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

            <button class="btn btn-primary">
                <?= $editItem ? "Update Product" : "Save Product" ?>
            </button>

            <?php if ($editItem): ?>

                <a
                    href="products.php"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            <?php endif; ?>

        </form>

    </div>

</div>

<div class="card table-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
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
                                    class="thumb"
                                    alt="<?= htmlspecialchars($item["name"]) ?>"
                                >

                            <?php else: ?>

                                <span class="text-muted">
                                    No image
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= htmlspecialchars($item["name"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item["description"]) ?>
                        </td>

                        <td>

                            <a
                                href="products.php?edit=<?= $item["id"] ?>"
                                class="btn btn-sm btn-outline-primary"
                            >
                                Edit
                            </a>

                            <a
                                href="products.php?delete=<?= $item["id"] ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this product?')"
                            >
                                Delete
                            </a>

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
