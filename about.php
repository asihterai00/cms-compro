<?php
require_once __DIR__ . "/config/database.php";

$result = $conn->query(
    "SELECT *
     FROM company_profile
     ORDER BY id ASC
     LIMIT 1"
);

$company = $result ? $result->fetch_assoc() : [];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/includes/head.php"; ?>
    <title>About <?= htmlspecialchars($company["company_name"] ?? "Company") ?></title>
</head>
<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "about"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h3 fw-bold mb-3">About Company</h2>
                        <p class="mb-0">
                            <?= nl2br(htmlspecialchars($company["about_text"] ?? "")) ?>
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h2 class="h4 fw-bold">Vision</h2>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars($company["vision"] ?? "")) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h2 class="h4 fw-bold">Mission</h2>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars($company["mission"] ?? "")) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h2 class="h4 fw-bold">Values</h2>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars($company["values_text"] ?? "")) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h4 fw-bold mb-4">Contact Information</h2>

                        <div class="mb-3">
                            <div class="fw-semibold">Address</div>
                            <div class="text-muted">
                                <?= nl2br(htmlspecialchars($company["address"] ?? "")) ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold">Phone</div>
                            <div class="text-muted">
                                <?= htmlspecialchars($company["phone"] ?? "") ?>
                            </div>
                        </div>

                        <div>
                            <div class="fw-semibold">Email</div>
                            <div class="text-muted">
                                <?= htmlspecialchars($company["email"] ?? "") ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
