<?php

session_start();

require_once __DIR__ . "/../config/database.php";

$error = "";

// Initialize brute-force throttling tracker
if (!isset($_SESSION['login_failed_attempts'])) {
    $_SESSION['login_failed_attempts'] = 0;
    $_SESSION['login_last_attempt_time'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Check rate limit
    $now = time();
    $attempts = $_SESSION['login_failed_attempts'];
    $lastAttempt = $_SESSION['login_last_attempt_time'];

    if ($attempts >= 5 && ($now - $lastAttempt) < 30) {
        $remaining = 30 - ($now - $lastAttempt);
        $error = "Terlalu banyak percobaan login gagal. Demi keamanan, silakan tunggu {$remaining} detik.";
    } else {
        // 2. Verify CSRF
        if (!verify_csrf()) {
            $error = "Token keamanan sesi tidak valid atau kedaluwarsa. Silakan muat ulang halaman.";
        } else {
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            $stmt = $conn->prepare(
                "SELECT * FROM users WHERE username = ? LIMIT 1"
            );
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user["password"])) {
                if (($user["status"] ?? "active") !== "active") {
                    $error = "Akun Anda berstatus non-aktif. Silakan hubungi Super Administrator.";
                } else {
                    // Prevent session fixation by regenerating session ID
                    session_regenerate_id(true);

                    // Reset brute force counter
                    $_SESSION['login_failed_attempts'] = 0;
                    $_SESSION['login_last_attempt_time'] = 0;

                    $_SESSION["admin_id"] = (int)$user["id"];
                    $_SESSION["admin_username"] = $user["username"];
                    $_SESSION["admin_role"] = $user["role"] ?? "admin";
                    $_SESSION["admin_full_name"] = $user["full_name"] ?? $user["username"];

                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                // Throttling: record failure and sleep 1s to deter brute-force
                $_SESSION['login_failed_attempts']++;
                $_SESSION['login_last_attempt_time'] = time();
                sleep(1);
                $error = "Username atau password salah.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <title>NPI CMS - Login | PT Nihon Plast Indonesia</title>

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card border-0 shadow">

                <div class="card-body p-4">

                    <h2 class="mb-4">
                        Admin Login
                    </h2>

                    <?php if ($error): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endif; ?>

                    <form method="post">
                        <?= csrf_field() ?>

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
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>