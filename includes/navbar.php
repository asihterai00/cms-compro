<?php
require_once __DIR__ . "/../config/database.php";

$companyNav = null;

if (isset($conn)) {
    $companyResult = $conn->query(
        "SELECT company_name, logo
         FROM company_profile
         ORDER BY id ASC
         LIMIT 1"
    );

    if ($companyResult) {
        $companyNav = $companyResult->fetch_assoc();
    }
}

$brandName = $companyNav["company_name"] ?? "PT Nihon Plast Indonesia";
$brandLogo = $companyNav["logo"] ?? "";
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a
            class="navbar-brand fw-bold d-flex align-items-center gap-2"
            href="index.php"
        >
            <?php if ($brandLogo !== ""): ?>
                <?php
                if (strpos($brandLogo, "uploads/") === 0) {
                    $logoSrc = $brandLogo;
                } else {
                    $logoSrc = "uploads/company/" . $brandLogo;
                }
                ?>
                <img
                    src="<?= htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8') ?>"
                    alt="Company Logo"
                    style="max-height:42px;max-width:150px;object-fit:contain;background:#fff;padding:3px;border-radius:4px;"
                >
            <?php endif; ?>

            <span>
                <?= htmlspecialchars($brandName) ?>
            </span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="quality.php">Quality</a></li>
                <li class="nav-item"><a class="nav-link" href="facility.php">Facility</a></li>
                <li class="nav-item"><a class="nav-link" href="career.php">Career</a></li>
                <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>
