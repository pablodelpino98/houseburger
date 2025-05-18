<?php
include '../includes/header.php';
include '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $errors = [];

    if (!$name || !preg_match('/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/u', $name)) {
        $errors[] = $translations['name_invalid'];
    }

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $translations['email_invalid'];
    }

    if (!$phone || !preg_match('/^\d{9}$/', $phone)) {
        $errors[] = $translations['phone_invalid'];
    }

    if (empty($errors)) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $phone, $address, $user_id]);

        header('Location: account.php');
        exit();
    }
}

// Obtener datos actuales
$sql = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['edit_account'] ?></h2>

        <?php if (!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="edit_account.php">
            <label><?= $translations['name'] ?>:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>">

            <label><?= $translations['email'] ?>:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>">

            <label><?= $translations['phone'] ?>:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>">

            <label><?= $translations['address'] ?>:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']); ?>">

            <button type="submit" class="btn-menu btn-large" style="margin-top: 20px;">
                <?= $translations['save_changes'] ?>
            </button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
