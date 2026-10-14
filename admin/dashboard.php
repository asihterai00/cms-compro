<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

$productCount = $conn
    ->query("SELECT COUNT(*) AS total FROM products")
    ->fetch_assoc()["total"];

$newsCount = $conn
    ->query("SELECT COUNT(*) AS total FROM news")
    ->fetch_assoc()["total"];

$careerCount = $conn
    ->query("SELECT COUNT(*) AS total FROM careers")
    ->fetch_assoc()["total"];

$galleryCount = $conn
    ->query("SELECT COUNT(*) AS total FROM gallery")
    ->fetch_assoc()["total"];

$messageCount = $conn
    ->query("SELECT COUNT(*) AS total FROM messages WHERE status = 'unread'")
    ->fetch_assoc()["total"];

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
        Admin Dashboard
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<h1 class="mb-1">
    Dashboard
</h1>

<p class="text-muted mb-4">
    Website administration overview
</p>

<div class="row g-4">

    <div class="col-md-6 col-xl-3">

        <div class="card stat-card">

            <div class="card-body d-flex justify-content-between">

                <div>
                    <small class="text-muted">
                        Products
                    </small>

                    <h2 class="mt-2 mb-0">
                        <?= $productCount ?>
                    </h2>
                </div>

                <div class="stat-icon">
                    P
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="card stat-card">

            <div class="card-body d-flex justify-content-between">

                <div>
                    <small class="text-muted">
                        News
                    </small>

                    <h2 class="mt-2 mb-0">
                        <?= $newsCount ?>
                    </h2>
                </div>

                <div class="stat-icon">
                    N
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="card stat-card">

            <div class="card-body d-flex justify-content-between">

                <div>
                    <small class="text-muted">
                        Career
                    </small>

                    <h2 class="mt-2 mb-0">
                        <?= $careerCount ?>
                    </h2>
                </div>

                <div class="stat-icon">
                    C
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="card stat-card">

            <div class="card-body d-flex justify-content-between">

                <div>
                    <small class="text-muted">
                        Unread Messages
                    </small>

                    <h2 class="mt-2 mb-0">
                        <?= $messageCount ?>
                    </h2>
                </div>

                <div class="stat-icon">
                    M
                </div>

            </div>

        </div>

    </div>

</div>

<div class="row g-4 mt-2">

    <div class="col-lg-8">

        <div class="card table-card">

            <div class="card-body p-4">

                <h5>
                    Quick Actions
                </h5>

                <div class="d-flex flex-wrap gap-2 mt-3">

                    <a
                        href="products.php"
                        class="btn btn-primary"
                    >
                        Manage Products
                    </a>

                    <a
                        href="news.php"
                        class="btn btn-primary"
                    >
                        Manage News
                    </a>

                    <a
                        href="career.php"
                        class="btn btn-primary"
                    >
                        Manage Career
                    </a>

                    <a
                        href="gallery.php"
                        class="btn btn-primary"
                    >
                        Manage Gallery
                    </a>

                    <a
                        href="messages.php"
                        class="btn btn-outline-primary"
                    >
                        View Messages
                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card table-card">

            <div class="card-body p-4">

                <h5>
                    System
                </h5>

                <p class="mb-2">
                    PHP + MySQL
                </p>

                <p class="mb-2">
                    Bootstrap 5
                </p>

                <p class="mb-0">
                    Admin Panel V3
                </p>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
