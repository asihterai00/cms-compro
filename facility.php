<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/includes/head.php"; ?>

    <title>
        Facility
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "facility"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="card product-card h-100">

                    <img
                        src="https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=900&q=80"
                        class="product-image"
                        alt="Production Facility"
                    >

                    <div class="card-body p-4">

                        <h4>
                            Production
                        </h4>

                        <p>
                            Dummy production facility information.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card product-card h-100">

                    <img
                        src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=900&q=80"
                        class="product-image"
                        alt="Engineering Facility"
                    >

                    <div class="card-body p-4">

                        <h4>
                            Engineering
                        </h4>

                        <p>
                            Dummy engineering facility information.
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card product-card h-100">

                    <img
                        src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=900&q=80"
                        class="product-image"
                        alt="Quality Facility"
                    >

                    <div class="card-body p-4">

                        <h4>
                            Quality
                        </h4>

                        <p>
                            Dummy quality facility information.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>