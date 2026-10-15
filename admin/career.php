<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('career', 'read');

$success = "";
$error = "";

$edit_id = (int) (
    $_GET["edit"] ?? 0
);

$editCareer = [
    "id" => 0,
    "position" => "",
    "department" => "",
    "location" => "",
    "description" => ""
];

if ($edit_id > 0) {

    $stmt = $conn->prepare(
        "SELECT *
         FROM careers
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $edit_id
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $editCareer = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_csrf();

    $id = (int) (
        $_POST["id"] ?? 0
    );

    if ($id > 0) {
        require_permission('career', 'update');
    } else {
        require_permission('career', 'create');
    }

    $position = trim(
        $_POST["position"] ?? ""
    );

    $department = trim(
        $_POST["department"] ?? ""
    );

    $location = trim(
        $_POST["location"] ?? ""
    );

    $description = trim(
        $_POST["description"] ?? ""
    );

    if ($position === "") {

        $error =
            "Position wajib diisi.";

    } elseif ($id > 0) {

        $stmt = $conn->prepare(
            "UPDATE careers
             SET
                position = ?,
                department = ?,
                location = ?,
                description = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $position,
            $department,
            $location,
            $description,
            $id
        );

        if ($stmt->execute()) {
            $success =
                "Career berhasil diperbarui.";
        } else {
            $error =
                "Career gagal diperbarui.";
        }

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO careers
             (
                position,
                department,
                location,
                description
             )
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $position,
            $department,
            $location,
            $description
        );

        if ($stmt->execute()) {

            $success =
                "Career berhasil ditambahkan.";

            $position = "";
            $department = "";
            $location = "";
            $description = "";

        } else {

            $error =
                "Career gagal ditambahkan.";
        }
    }

    $editCareer = [
        "id" => $id,
        "position" => $position,
        "department" => $department,
        "location" => $location,
        "description" => $description
    ];
}

if (isset($_GET["delete"])) {

    require_csrf();
    require_permission('career', 'delete');

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM careers
         WHERE id = ?"
    );

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    header(
        "Location: career.php"
    );

    exit;
}

$careers = $conn->query(
    "SELECT *
     FROM careers
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

    <title>NPI CMS - Karir & Lowongan | PT Nihon Plast Indonesia</title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<div class="container py-5">

    <div
        class="d-flex
               justify-content-between
               mb-4"
    >

        <div>

            <h1>
                Career
            </h1>

            <p class="text-muted">
                Tambah, edit, dan hapus
                lowongan pekerjaan.
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

    <div
        class="card
               border-0
               shadow-sm
               mb-4"
    >

        <div class="card-body p-4">

            <h3 class="mb-4">

                <?= $editCareer["id"] > 0
                    ? "Edit Career"
                    : "Tambah Career"
                ?>

            </h3>

            <form method="post">
                <?= csrf_field() ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $editCareer["id"] ?>"
                >

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Position
                        </label>

                        <input
                            type="text"
                            name="position"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $editCareer["position"]
                            ) ?>"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Department
                        </label>

                        <input
                            type="text"
                            name="department"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $editCareer["department"]
                            ) ?>"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $editCareer["location"]
                            ) ?>"
                        >

                    </div>

                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="5"
                        ><?= htmlspecialchars(
                            $editCareer["description"]
                        ) ?></textarea>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <?= $editCareer["id"] > 0
                        ? "Update Career"
                        : "Simpan Career"
                    ?>
                </button>

                <?php if (
                    $editCareer["id"] > 0
                ): ?>

                    <a
                        href="career.php"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                <?php endif; ?>

            </form>

        </div>

    </div>

    <div
        class="card
               border-0
               shadow-sm"
    >

        <div class="card-body p-4">

            <h3 class="mb-4">
                Daftar Career
            </h3>

            <div class="table-responsive">

                <table
                    class="table
                           table-hover
                           align-middle"
                >

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Position
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while (
                        $row =
                        $careers->fetch_assoc()
                    ):
                    ?>

                        <tr>

                            <td>
                                <?= $no++ ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row["position"]
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row["department"]
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row["location"]
                                ) ?>
                            </td>

                            <td>

                                <a
                                    href="career.php?edit=<?= (int) $row["id"] ?>"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <?php if (has_permission('career', 'delete')): ?>
                                    <a
                                        href="<?= csrf_url('career.php?delete=' . (int) $row["id"]) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="
                                            return confirm(
                                                'Hapus career ini?'
                                            );
                                        "
                                    >
                                        Hapus
                                    </a>
                                <?php endif; ?>

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
