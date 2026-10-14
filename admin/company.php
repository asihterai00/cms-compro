<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

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
            $_FILES["logo"]["error"] === UPLOAD_ERR_OK
        ) {

            $allowed = [
                "jpg",
                "jpeg",
                "png",
                "webp",
                "svg"
            ];

            $extension = strtolower(
                pathinfo(
                    $_FILES["logo"]["name"],
                    PATHINFO_EXTENSION
                )
            );

            if (!in_array(
                $extension,
                $allowed,
                true
            )) {

                $error =
                    "Format logo tidak didukung.";

            } elseif (
                $_FILES["logo"]["size"]
                > 2 * 1024 * 1024
            ) {

                $error =
                    "Ukuran logo maksimal 2 MB.";

            } else {

                $filename =
                    "logo_" .
                    time() .
                    "." .
                    $extension;

                $uploadDir =
                    __DIR__ .
                    "/../uploads/company/";

                if (!is_dir($uploadDir)) {

                    mkdir(
                        $uploadDir,
                        0755,
                        true
                    );
                }

                if (!is_writable($uploadDir)) {

                    $error =
                        "Folder upload logo tidak dapat ditulis. " .
                        "Periksa permission folder uploads/company.";

                } else {

                    $target =
                        $uploadDir .
                        $filename;

                    if (
                        move_uploaded_file(
                            $_FILES["logo"]["tmp_name"],
                            $target
                        )
                    ) {

                    $logo = $filename;

                    } else {

                        $error =
                            "Logo gagal diupload.";
                    }
                }
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

    <title>
        Company Profile
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="container py-5">

    <div
        class="d-flex
               justify-content-between
               align-items-center
               mb-4"
    >

        <div>

            <h1>
                Company Profile
            </h1>

            <p class="text-muted mb-0">
                Kelola informasi perusahaan
                dan logo website.
            </p>

        </div>

        <a
            href="dashboard.php"
            class="btn btn-secondary"
        >
            Dashboard
        </a>

    </div>

    <?php if ($success !== ""): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>

    <?php if ($error !== ""): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form
        method="post"
        enctype="multipart/form-data"
    >

        <div class="row g-4">

            <div class="col-lg-8">

                <div
                    class="card
                           border-0
                           shadow-sm"
                >

                    <div class="card-body p-4">

                        <h4 class="mb-4">
                            Informasi Perusahaan
                        </h4>

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Perusahaan
                            </label>

                            <input
                                type="text"
                                name="company_name"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $company["company_name"] ?? ""
                                ) ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Tagline
                            </label>

                            <input
                                type="text"
                                name="tagline"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $company["tagline"] ?? ""
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                class="form-control"
                                rows="3"
                            ><?= htmlspecialchars(
                                $company["short_description"] ?? ""
                            ) ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                About Company
                            </label>

                            <textarea
                                name="about_text"
                                class="form-control"
                                rows="6"
                            ><?= htmlspecialchars(
                                $company["about_text"] ?? ""
                            ) ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Vision
                            </label>

                            <textarea
                                name="vision"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars(
                                $company["vision"] ?? ""
                            ) ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Mission
                            </label>

                            <textarea
                                name="mission"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars(
                                $company["mission"] ?? ""
                            ) ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Values
                            </label>

                            <textarea
                                name="values_text"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars(
                                $company["values_text"] ?? ""
                            ) ?></textarea>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div
                    class="card
                           border-0
                           shadow-sm
                           mb-4"
                >

                    <div class="card-body p-4">

                        <h4 class="mb-4">
                            Logo Website
                        </h4>

                        <?php if (
                            !empty($company["logo"])
                        ): ?>

                            <div
                                class="text-center
                                       mb-3"
                            >

                                <img
                                    src="../uploads/company/<?= htmlspecialchars(
                                        $company["logo"]
                                    ) ?>"
                                    class="img-fluid"
                                    style="
                                        max-height:150px;
                                    "
                                    alt="Company Logo"
                                >

                            </div>

                        <?php endif; ?>

                        <input
                            type="file"
                            name="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,.svg"
                        >

                        <small class="text-muted">
                            JPG, PNG, WEBP, SVG.
                            Maksimal 2 MB.
                        </small>

                    </div>

                </div>

                <div
                    class="card
                           border-0
                           shadow-sm
                           mb-4"
                >

                    <div class="card-body p-4">

                        <h4 class="mb-4">
                            Contact
                        </h4>

                        <div class="mb-3">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars(
                                $company["address"] ?? ""
                            ) ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $company["phone"] ?? ""
                                ) ?>"
                            >

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $company["email"] ?? ""
                                ) ?>"
                            >

                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Simpan Company Profile
                </button>

            </div>

        </div>

    </form>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>

</html>
