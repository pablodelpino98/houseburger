<?php
include '../includes/header.php';
include '../cart/cart.php';
include '../includes/database.php';

// Asegúrate de que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['account_title'] ?></h2>
        <div style="text-align: left; font-size: 1.1rem; line-height: 1.8;">
            <p><strong><?= $translations['name'] ?>:</strong> <?= htmlspecialchars($user['name']); ?></p>
            <p><strong><?= $translations['email'] ?>:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong><?= $translations['phone'] ?>:</strong> <?= htmlspecialchars($user['phone']); ?></p>
            <p><strong><?= $translations['address'] ?>:</strong> <?= htmlspecialchars($user['address']); ?></p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="edit_account.php" class="btn-menu btn-large"><?= $translations['edit_account'] ?></a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
