<?php
$currentPage = basename($_SERVER["PHP_SELF"]);
$menus = function_exists('get_app_menus') ? get_app_menus() : [];
$userRole = $_SESSION["admin_role"] ?? "admin";
$userName = $_SESSION["admin_full_name"] ?? ($_SESSION["admin_username"] ?? "Admin");
?>

<aside class="admin-sidebar">

    <div class="admin-brand d-flex align-items-center gap-2">
        <i class="bi bi-shield-check text-success fs-4"></i>
        <span>NIHON PLAST</span>
    </div>

    <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-2">
        <div class="small fw-bold text-white text-truncate"><?= htmlspecialchars($userName) ?></div>
        <div class="badge bg-success-subtle text-success border border-success-subtle small text-uppercase" style="font-size: 0.68rem;">
            <?= htmlspecialchars($userRole) ?>
        </div>
    </div>

    <nav class="admin-menu">

        <?php foreach ($menus as $menuKey => $m): ?>
            <?php if (!function_exists('has_permission') || has_permission($menuKey, 'read')): ?>
                <a
                    href="<?= htmlspecialchars($m['url']) ?>"
                    class="<?= $currentPage === $m['url'] ? 'active' : '' ?>"
                    title="<?= htmlspecialchars($m['name']) ?>"
                >
                    <i class="bi <?= htmlspecialchars($m['icon']) ?> me-2"></i>
                    <span><?= htmlspecialchars($m['name']) ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <hr class="border-secondary my-3">

        <a href="../index.php" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-2"></i>
            <span>View Website</span>
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right me-2 text-danger"></i>
            <span class="text-danger">Logout</span>
        </a>

    </nav>

</aside>

<main class="admin-main">

    <header class="admin-topbar d-flex align-items-center justify-content-between px-4">

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success p-1 rounded-circle"></span>
            <strong>PT Nihon Plast Indonesia</strong>
            <span class="text-muted d-none d-md-inline small">| Tier-1 Automotive CMS</span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>
                <strong><?= htmlspecialchars($_SESSION["admin_username"] ?? "") ?></strong>
                <span class="badge bg-secondary ms-1"><?= strtoupper(htmlspecialchars($userRole)) ?></span>
            </div>
        </div>

    </header>

    <div class="container-fluid p-4">
