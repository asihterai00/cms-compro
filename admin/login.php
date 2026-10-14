<?php

session_start();

require_once __DIR__ . "/../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT *
         FROM users
         WHERE username = ?
         LIMIT 1"
    );

    $stmt->bind_param(
        "s",
        $username
    );

    $stmt->execute();

    $user = $stmt
        ->get_result()
        ->fetch_assoc();

    if (
        $user &&
        password_verify(
            $password,
            $user["password"]
        )
    ) {

        $_SESSION["admin_id"] = $user["id"];
        $_SESSION["admin_username"] = $user["username"];

        header("Location: dashboard.php");

        exit;
    }

    $error = "Username atau password salah.";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/../includes/head.php"; ?>

    <title>
        Admin Login
    </title>

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