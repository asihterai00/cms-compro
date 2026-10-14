<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        $username !== "" &&
        $password !== ""
    ) {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO users
             (username, password)
             VALUES (?, ?)"
        );

        $stmt->bind_param(
            "ss",
            $username,
            $hash
        );

        if ($stmt->execute()) {

            $message =
                "User berhasil ditambahkan.";

        } else {

            $message =
                "User gagal ditambahkan.";

        }
    }
}

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    if ($id !== (int) $_SESSION["admin_id"]) {

        $stmt = $conn->prepare(
            "DELETE FROM users
             WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: users.php");
    exit;
}

$users = $conn->query(
    "SELECT id, username, created_at
     FROM users
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
        Users
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<h1>
    Users
</h1>

<?php if ($message): ?>

    <div class="alert alert-info">
        <?= htmlspecialchars($message) ?>
    </div>

<?php endif; ?>

<div class="card table-card mb-4">

    <div class="card-body p-4">

        <h5>
            Add User
        </h5>

        <form
            method="post"
            class="row g-3 mt-1"
        >

            <div class="col-md-5">

                <label class="form-label">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    required
                >

            </div>

            <div class="col-md-5">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >

            </div>

            <div class="col-md-2 d-flex align-items-end">

                <button
                    class="btn btn-primary w-100"
                >
                    Add User
                </button>

            </div>

        </form>

    </div>

</div>

<div class="card table-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php while ($user = $users->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= $user["id"] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user["username"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user["created_at"]) ?>
                        </td>

                        <td>

                            <?php if (
                                (int) $user["id"]
                                !==
                                (int) $_SESSION["admin_id"]
                            ): ?>

                                <a
                                    href="users.php?delete=<?= $user["id"] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Delete this user?')"
                                >
                                    Delete
                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Current user
                                </span>

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
