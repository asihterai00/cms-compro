<?php

require_once __DIR__ . "/config/database.php";

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (
        $name !== "" &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $message !== ""
    ) {

        $stmt = $conn->prepare(
            "INSERT INTO messages
             (name, email, message, status)
             VALUES (?, ?, ?, 'unread')"
        );

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $message
        );

        $stmt->execute();

        $success =
            "Thank you. Your message has been received.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <?php include __DIR__ . "/includes/head.php"; ?>

    <title>
        Contact
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/navbar.php"; ?>

<?php $bannerKey = "contact"; include __DIR__ . "/includes/page_banner.php"; ?>

<section class="section-padding">

    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5">

                <h2>
                    Get In Touch
                </h2>

                <p>
                    PT Nihon Plast Indonesia
                </p>

                <p>
                    Jalan Raya Tambun KM.38.2
                    <br>
                    Bekasi, Jawa Barat, Indonesia
                </p>

                <p>
                    <strong>
                        Phone:
                    </strong>
                    (021) 000-0000
                </p>

                <p>
                    <strong>
                        Email:
                    </strong>
                    info@example.com
                </p>

            </div>

            <div class="col-lg-7">

                <?php if ($success): ?>

                    <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                    </div>

                <?php endif; ?>

                <form
                    method="post"
                >

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Message
                        </label>

                        <textarea
                            name="message"
                            class="form-control"
                            rows="6"
                            required
                        ></textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Send Message
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>