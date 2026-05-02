<?php
require '../includes/db.php';
require '../includes/auth.php';
require_admin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
        try {
            $stmt->execute([$username, $hash]);
            $message = 'Admin added!';
        } catch (PDOException $e) {
            $message = 'Username must be unique.';
        }
    }
}
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    if ($_GET['del'] != $_SESSION['admin_id']) {
        $pdo->prepare("DELETE FROM admins WHERE id=?")->execute([$_GET['del']]);
        $message = 'Admin removed.';
    } else {
        $message = 'You cannot remove yourself.';
    }
}

$admins = $pdo->query("SELECT * FROM admins")->fetchAll();

include '../includes/header.php';
?>
<div class="container my-4">
    <h2>Admin Users</h2>
    <?php if($message): ?><div class="alert alert-info"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <table class="table">
        <thead><tr><th>Username</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($admins as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['username']) ?></td>
                <td>
                    <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                        <a href="?del=<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this admin?')">Delete</a>
                    <?php else: ?>
                        <span class="text-muted">Logged in</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Add New Admin</h3>
    <form method="post" class="w-50">
        <input name="username" class="form-control mb-2" placeholder="Username" required>
        <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
        <button class="btn btn-primary" name="add_admin">Add Admin</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
