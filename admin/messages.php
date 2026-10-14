<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

if (isset($_GET["read"])) {

    $id = (int) $_GET["read"];

    $stmt = $conn->prepare(
        "UPDATE messages
         SET status = 'read'
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: messages.php");
    exit;
}

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    $stmt = $conn->prepare(
        "DELETE FROM messages
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: messages.php");
    exit;
}

$messages = $conn->query(
    "SELECT *
     FROM messages
     ORDER BY created_at DESC"
);

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
        Messages
    </title>

</head>

<body>

<?php include __DIR__ . "/includes/header.php"; ?>

<h1>
    Contact Messages
</h1>

<p class="text-muted">
    Messages received from the public website.
</p>

<div class="card table-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Status</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php while ($item = $messages->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <?php if ($item["status"] === "unread"): ?>

                                <span class="badge text-bg-warning">
                                    Unread
                                </span>

                            <?php else: ?>

                                <span class="badge text-bg-success">
                                    Read
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= htmlspecialchars($item["name"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item["email"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item["message"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($item["created_at"]) ?>
                        </td>

                        <td>

                            <?php if ($item["status"] === "unread"): ?>

                                <a
                                    href="messages.php?read=<?= $item["id"] ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Mark Read
                                </a>

                            <?php endif; ?>

                            <a
                                href="messages.php?delete=<?= $item["id"] ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this message?')"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
