<?php
include '../includes/header.php';
include '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Procesar formulario si se envió
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validación básica
    if ($name && $email && $phone && $address) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $phone, $address, $user_id]);

        header('Location: account.php');
        exit();
    } else {
        $error = $translations['all_fields_required'];
    }
}

// Obtener datos actuales del usuario
$sql = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['edit_account'] ?></h2>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="edit_account.php">
            <label><?= $translations['name'] ?>:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>

            <label><?= $translations['email'] ?>:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label><?= $translations['phone'] ?>:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required>

            <label><?= $translations['address'] ?>:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']); ?>" required>

            <button type="submit" class="btn-menu btn-large" style="margin-top: 20px;">
                <?= $translations['save_changes'] ?>
            </button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
