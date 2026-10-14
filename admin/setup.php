<?php

require_once __DIR__ . "/../config/database.php";

// Cek apakah akun admin sudah dibuat
$check = $conn->query("SELECT COUNT(*) AS total FROM users");
$row = $check->fetch_assoc();
if ((int)($row['total'] ?? 0) > 0) {
    // Jika admin sudah ada, kunci akses dan arahkan ke login
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        $username === "" ||
        $password === ""
    ) {

        $message = "Username dan password wajib diisi.";

    } else {

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
                "Admin berhasil dibuat. Hapus file setup.php.";

        } else {

            $message =
                "Gagal membuat admin. Username mungkin sudah ada.";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <title>
        Setup Admin
    </title>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card border-0 shadow">

                <div class="card-body p-4">

                    <h2>
                        Setup Admin
                    </h2>

                    <?php if ($message): ?>

                        <div class="alert alert-info">
                            <?= htmlspecialchars($message) ?>
                        </div>

                    <?php endif; ?>

                    <form method="post">

                        <div class="mb-3">

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

                        <div class="mb-3">

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

                        <button
                            class="btn btn-primary"
                        >
                            Create Admin
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>