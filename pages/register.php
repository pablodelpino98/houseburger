<?php
include '../includes/header.php';
include '../cart/cart.php';
require '../includes/database.php';

$showForm = true;
$errors = [];
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm)) {
        $errors[] = $translations['register_error_all_fields'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $translations['register_error_invalid_email'];
    }

    if (!preg_match('/^\d{9}$/', $phone)) {
        $errors[] = $translations['register_error_phone_digits'];
    }

    if ($password !== $confirm) {
        $errors[] = $translations['register_error_password_mismatch'];
    }

    if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
        $errors[] = $translations['register_error_password_format'];
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $errors[] = $translations['register_error_exists'];
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $hashed]);
        $showForm = false;
    }
}
?>

<main class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['register_title'] ?></h2>
        <?php if (!empty($errors)) {
            echo "<ul style='color:red; text-align:left;'>";
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>";
        }

        if ($showForm) { ?>
            <form method="POST">
                <label><?= $translations['register_name'] ?>:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

                <label><?= $translations['register_email'] ?>:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

                <label><?= $translations['register_phone'] ?>:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required>

                <label><?= $translations['register_password'] ?>:</label>
                <input type="password" name="password" required>

                <label><?= $translations['register_confirm_password'] ?>:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit"><?= $translations['register_button'] ?></button>
            </form>
        <?php } else { ?>
            <p style='color:white; text-align:center;'><?= $translations['register_success'] ?> <a href='login.php' style='color:red;'><?= $translations['register_login_link'] ?></a>.</p>
        <?php } ?>    
    </div>
</main>

<?php include '../includes/footer.php'; ?>
