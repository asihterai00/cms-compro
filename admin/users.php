<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/../config/database.php";

require_permission('users', 'read');

$message = "";
$error = "";
$appMenus = get_app_menus();

// 1. Handle Delete User
if (isset($_GET["delete"])) {
    require_csrf();
    require_permission('users', 'delete');
    $id = (int)$_GET["delete"];

    if ($id === (int)$_SESSION["admin_id"]) {
        $error = "Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.";
    } else {
        // Prevent deleting root superadmin ID 1
        if ($id === 1) {
            $error = "Akun Root Super Administrator utama (ID 1) tidak dapat dihapus demi keamanan sistem.";
        } else {
            $stmt = $conn->prepare("DELETE FROM user_permissions WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = "User berhasil dihapus beserta seluruh hak aksesnya.";
            } else {
                $error = "Gagal menghapus user: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// 2. Fetch User for Editing
$editId = (int)($_GET['edit'] ?? 0);
$editUser = null;
$userPerms = [];

if ($editId > 0) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editUser = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($editUser) {
        $pStmt = $conn->prepare("SELECT * FROM user_permissions WHERE user_id = ?");
        $pStmt->bind_param("i", $editId);
        $pStmt->execute();
        $pRes = $pStmt->get_result();
        while ($p = $pRes->fetch_assoc()) {
            $userPerms[$p['menu_key']] = [
                'create' => (int)$p['can_create'],
                'read' => (int)$p['can_read'],
                'update' => (int)$p['can_update'],
                'delete' => (int)$p['can_delete'],
            ];
        }
        $pStmt->close();
    }
}

// 3. Handle POST: Add or Update User with Granular CRUD Permissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $userId = (int)($_POST["user_id"] ?? 0);
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $role = $_POST["role"] ?? "admin";
    $status = $_POST["status"] ?? "active";
    $postedPerms = $_POST["perms"] ?? [];

    if ($userId > 0) {
        if (!has_permission('users', 'update')) {
            $error = "Anda tidak memiliki izin untuk memperbarui pengguna.";
        }
    } else {
        if (!has_permission('users', 'create')) {
            $error = "Anda tidak memiliki izin untuk menambah pengguna baru.";
        }
    }

    if ($error === '' && $username === '') {
        $error = "Username wajib diisi.";
    }

    if ($error === '' && $userId === 0 && $password === '') {
        $error = "Password wajib diisi untuk pengguna baru.";
    }

    if ($error === '') {
        if ($userId > 0) {
            // Update User Profile
            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, full_name = ?, email = ?, role = ?, status = ? WHERE id = ?");
                $stmt->bind_param("ssssssi", $username, $hash, $fullName, $email, $role, $status, $userId);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, full_name = ?, email = ?, role = ?, status = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $username, $fullName, $email, $role, $status, $userId);
            }
            if ($stmt->execute()) {
                $targetUserId = $userId;
                $message = "Data user '{$username}' berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui user: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Check username duplicate
            $chk = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $chk->bind_param("s", $username);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) {
                $error = "Username '{$username}' sudah digunakan. Silakan pilih username lain.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, email, role, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username, $hash, $fullName, $email, $role, $status);
                if ($stmt->execute()) {
                    $targetUserId = $conn->insert_id;
                    $message = "User baru '{$username}' berhasil ditambahkan.";
                } else {
                    $error = "Gagal menambahkan user: " . $stmt->error;
                }
                $stmt->close();
            }
            $chk->close();
        }

        // Save Granular Menu Permissions
        if ($error === '' && isset($targetUserId)) {
            $stmtPerm = $conn->prepare("INSERT INTO user_permissions (user_id, menu_key, can_create, can_read, can_update, can_delete) 
                VALUES (?, ?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                can_create = VALUES(can_create),
                can_read = VALUES(can_read),
                can_update = VALUES(can_update),
                can_delete = VALUES(can_delete)");

            foreach ($appMenus as $menuKey => $mInfo) {
                $canC = isset($postedPerms[$menuKey]['create']) ? 1 : 0;
                $canR = isset($postedPerms[$menuKey]['read']) ? 1 : 0;
                $canU = isset($postedPerms[$menuKey]['update']) ? 1 : 0;
                $canD = isset($postedPerms[$menuKey]['delete']) ? 1 : 0;

                $stmtPerm->bind_param("isiiii", $targetUserId, $menuKey, $canC, $canR, $canU, $canD);
                $stmtPerm->execute();
            }
            $stmtPerm->close();

            $editUser = null;
        }
    }
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . "/../includes/head.php"; ?>
    <link rel="stylesheet" href="assets/admin.css">
    <title>NPI CMS - Manajemen Pengguna & Hak Akses | PT Nihon Plast Indonesia</title>
    <style>
        .role-badge-superadmin { background-color: #7c3aed; color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 50px; }
        .role-badge-admin { background-color: #005A36; color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 50px; }
        .role-badge-editor { background-color: #0284c7; color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 50px; }
        .role-badge-staff { background-color: #475569; color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 50px; }
        .perm-checkbox { width: 1.25rem; height: 1.25rem; cursor: pointer; }
    </style>
</head>
<body>
<?php include __DIR__ . "/includes/header.php"; ?>

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-bold rounded-pill">
                    <i class="bi bi-shield-lock me-1"></i> ACCESS CONTROL & RBAC
                </span>
                <span class="badge bg-light text-muted border px-2 py-1 fs-xs">
                    Total <?= $users->num_rows ?> Akun
                </span>
            </div>
            <h1 class="h3 fw-bold mb-1 text-dark tracking-tight">User Authorization & RBAC</h1>
            <p class="text-muted small mb-0">
                Kelola akun operator dan konfigurasi izin Create, Read, Update, Delete (CRUD) secara granular per menu sistem.
            </p>
        </div>
        <?php if (has_permission('users', 'create')): ?>
            <button class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#userFormCollapse" aria-expanded="<?= ($editUser || isset($_GET['new'])) ? 'true' : 'false' ?>">
                <i class="bi bi-person-plus-fill me-1"></i> <?= $editUser ? 'Tutup Form' : 'Tambah Pengguna Baru' ?>
            </button>
        <?php endif; ?>
    </div>

    <!-- Alert Messages -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i><?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- USER FORM (CREATE / EDIT) COLLAPSIBLE -->
    <div class="collapse <?= ($editUser || isset($_GET['new'])) ? 'show' : '' ?> mb-4" id="userFormCollapse">
        <div class="card border-0 shadow-sm rounded-4" id="userForm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar-sm rounded-3 bg-emerald-subtle text-emerald d-inline-flex align-items-center justify-content-center p-2">
                        <i class="bi <?= $editUser ? 'bi-person-gear' : 'bi-person-plus-fill' ?>"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">
                        <?= $editUser ? 'Edit Pengguna: ' . htmlspecialchars($editUser['username']) : 'Tambah Pengguna & Atur Hak Akses CRUD' ?>
                    </h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#userFormCollapse">
                    Tutup
                </button>
            </div>
            <div class="card-body p-4">
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_id" value="<?= (int)($editUser['id'] ?? 0) ?>">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($editUser['username'] ?? '') ?>" placeholder="username_login">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Password <?= $editUser ? '<span class="text-muted fw-normal">(Kosongkan jika tidak diubah)</span>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <input type="password" name="password" class="form-control" <?= $editUser ? '' : 'required' ?> placeholder="••••••••">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($editUser['full_name'] ?? '') ?>" placeholder="Nama Lengkap Staf / Operator">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email Perusahaan</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" placeholder="user@n-plast.co.id">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Peran Utama (Role)</label>
                            <select name="role" class="form-select" id="roleSelect" onchange="toggleSuperadminNotice()">
                                <option value="staff" <?= ($editUser['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff (Hanya Akses Menu Terbatas)</option>
                                <option value="editor" <?= ($editUser['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor (Kelola Konten & Media)</option>
                                <option value="admin" <?= ($editUser['role'] ?? 'admin') === 'admin' ? 'selected' : '' ?>>Admin (Pengelola Standar)</option>
                                <option value="superadmin" <?= ($editUser['role'] ?? '') === 'superadmin' ? 'selected' : '' ?>>Superadmin (Akses Penuh Semua Menu)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= ($editUser['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif (Bisa Login)</option>
                                <option value="inactive" <?= ($editUser['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif (Akses Diblokir)</option>
                            </select>
                        </div>
                    </div>

                    <!-- GRANULAR CRUD PERMISSIONS MATRIX -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">
                                    <i class="bi bi-shield-check text-success me-1"></i> Pengaturan Izin CRUD per Menu
                                </h5>
                                <p class="text-muted small mb-0">Centang hak akses yang diizinkan untuk setiap menu. (Superadmin otomatis memiliki hak akses penuh).</p>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="checkAllPerms(true)">Pilih Semua</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="checkAllPerms(false)">Hapus Semua</button>
                            </div>
                        </div>

                        <div id="superadminAlert" class="alert alert-info py-2 small <?= ($editUser['role'] ?? '') === 'superadmin' ? '' : 'd-none' ?>">
                            <i class="bi bi-info-circle-fill me-1"></i> Peran <strong>Superadmin</strong> memiliki hak akses penuh (Bypass) ke semua menu tanpa batas.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 200px;">Menu Aplikasi</th>
                                        <th class="text-center" style="width: 130px;">
                                            <span class="d-block">Create</span>
                                            <small class="text-muted fw-normal">(Tambah)</small>
                                        </th>
                                        <th class="text-center" style="width: 130px;">
                                            <span class="d-block">Read</span>
                                            <small class="text-muted fw-normal">(Lihat)</small>
                                        </th>
                                        <th class="text-center" style="width: 130px;">
                                            <span class="d-block">Update</span>
                                            <small class="text-muted fw-normal">(Ubah/Edit)</small>
                                        </th>
                                        <th class="text-center" style="width: 130px;">
                                            <span class="d-block">Delete</span>
                                            <small class="text-muted fw-normal">(Hapus)</small>
                                        </th>
                                        <th class="text-center" style="width: 110px;">Aksi Baris</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appMenus as $menuKey => $m): ?>
                                        <?php
                                        $pC = $userPerms[$menuKey]['create'] ?? 0;
                                        $pR = $userPerms[$menuKey]['read'] ?? 1;
                                        $pU = $userPerms[$menuKey]['update'] ?? 0;
                                        $pD = $userPerms[$menuKey]['delete'] ?? 0;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi <?= htmlspecialchars($m['icon']) ?> fs-5 text-success"></i>
                                                    <div>
                                                        <div class="fw-bold"><?= htmlspecialchars($m['name']) ?></div>
                                                        <code class="small text-secondary"><?= htmlspecialchars($menuKey) ?></code>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input perm-checkbox perm-<?= $menuKey ?>" type="checkbox" name="perms[<?= $menuKey ?>][create]" value="1" <?= $pC ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input perm-checkbox perm-<?= $menuKey ?>" type="checkbox" name="perms[<?= $menuKey ?>][read]" value="1" <?= $pR ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input perm-checkbox perm-<?= $menuKey ?>" type="checkbox" name="perms[<?= $menuKey ?>][update]" value="1" <?= $pU ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input perm-checkbox perm-<?= $menuKey ?>" type="checkbox" name="perms[<?= $menuKey ?>][delete]" value="1" <?= $pD ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0" onclick="toggleRowPerms('<?= $menuKey ?>')">
                                                    Toggle
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-emerald px-4 rounded-pill shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Pengguna & Izin CRUD
                        </button>
                        <button type="button" class="btn btn-light border px-4 rounded-pill" data-bs-toggle="collapse" data-bs-target="#userFormCollapse">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Daftar Pengguna Sistem</h5>
            <span class="text-muted small">Total: <strong><?= $users->num_rows ?></strong> Pengguna</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 70px;">ID</th>
                            <th>Username & Nama</th>
                            <th>Email</th>
                            <th>Peran (Role)</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?= $u["id"] ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($u["username"]) ?></div>
                                    <?php if (!empty($u["full_name"])): ?>
                                        <small class="text-muted"><?= htmlspecialchars($u["full_name"]) ?></small>
                                    <?php endif; ?>
                                    <?php if ((int)$u["id"] === (int)$_SESSION["admin_id"]): ?>
                                        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle small ms-1">Akun Anda</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($u["email"] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $role = $u["role"] ?? 'admin';
                                    $roleBadge = 'role-badge-' . $role;
                                    ?>
                                    <span class="<?= $roleBadge ?>">
                                        <?= strtoupper(htmlspecialchars($role)) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (($u["status"] ?? 'active') === 'active'): ?>
                                        <span class="badge bg-emerald-subtle text-emerald rounded-pill px-3 py-1 fs-xs fw-bold border"><i class="bi bi-check-circle-fill me-1"></i>AKTIF</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-xs fw-bold border"><i class="bi bi-dash-circle-fill me-1"></i>NONAKTIF</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= htmlspecialchars(substr($u["created_at"] ?? '', 0, 10)) ?></td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="users.php?edit=<?= $u["id"] ?>#userFormCollapse" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Edit Pengguna & Hak Akses CRUD">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ((int)$u["id"] !== (int)$_SESSION["admin_id"] && (int)$u["id"] !== 1): ?>
                                            <a href="<?= csrf_url('users.php?delete=' . (int)$u["id"]) ?>" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px; text-align: center;" title="Hapus User" onclick="return confirm('Apakah Anda yakin ingin menghapus user <?= htmlspecialchars($u['username']) ?> beserta izinnya?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
function checkAllPerms(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(function(cb) {
        cb.checked = checked;
    });
}
function toggleRowPerms(menuKey) {
    const cbs = document.querySelectorAll('.perm-' + menuKey);
    const anyChecked = Array.from(cbs).some(cb => cb.checked);
    cbs.forEach(cb => cb.checked = !anyChecked);
}
function toggleSuperadminNotice() {
    const role = document.getElementById('roleSelect').value;
    const alertBox = document.getElementById('superadminAlert');
    if (role === 'superadmin') {
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
    }
}
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>
</body>
</html>
