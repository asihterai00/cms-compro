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
    $uploadError = "";

    if (
        isset($_FILES["image_file"]) &&
        $_FILES["image_file"]["error"] === UPLOAD_ERR_OK
    ) {

        $allowed = [
            "image/jpeg",
            "image/png",
            "image/webp"
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

                <label class="form-label">
                    Product Image
                </label>

                <input
                    type="file"
                    name="image_file"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp"
                >

            </div>

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
