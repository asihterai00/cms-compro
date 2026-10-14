<?php
$currentPage = basename($_SERVER["PHP_SELF"]);
?>

<aside class="admin-sidebar">

    <div class="admin-brand">
        NIHON PLAST ADMIN
    </div>

    <nav class="admin-menu">

        <a
            href="dashboard.php"
            class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>"
        >
            Dashboard
        </a>

        <a
            href="banners.php"
            class="<?= $currentPage === 'banners.php' ? 'active' : '' ?>"
        >
            Page Banners
        </a>

        <a
            href="company.php"
            class="<?= $currentPage === 'company.php' ? 'active' : '' ?>"
        >
            Company Profile
        </a>

        <a
            href="products.php"
            class="<?= $currentPage === 'products.php' ? 'active' : '' ?>"
        >
            Products
        </a>

        <a
            href="news.php"
            class="<?= $currentPage === 'news.php' ? 'active' : '' ?>"
        >
            News
        </a>

        <a
            href="career.php"
            class="<?= $currentPage === 'career.php' ? 'active' : '' ?>"
        >
            Career
        </a>

        <a
            href="gallery.php"
            class="<?= $currentPage === 'gallery.php' ? 'active' : '' ?>"
        >
            Gallery
        </a>

        <a
            href="messages.php"
            class="<?= $currentPage === 'messages.php' ? 'active' : '' ?>"
        >
            Messages
        </a>

        <a
            href="users.php"
            class="<?= $currentPage === 'users.php' ? 'active' : '' ?>"
        >
            Users
        </a>

        <hr class="border-secondary">

        <a href="../index.php">
            View Website
        </a>

        <a href="logout.php">
            Logout
        </a>

    </nav>

</aside>

<main class="admin-main">

    <header class="admin-topbar d-flex align-items-center justify-content-between px-4">

        <div>
            <strong>
                PT Nihon Plast Indonesia
            </strong>
        </div>

        <div class="text-muted small">
            <?= htmlspecialchars($_SESSION["admin_username"] ?? "") ?>
        </div>

    </header>

    <div class="container-fluid p-4">
