<?php 
include '../includes/header.php';
include '../cart/cart.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = $translations['email_invalid'];
    } elseif (empty($password)) {
        $error = $translations['password_required'];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = $translations['login_failed'];
        }
    }
}
?>

<main class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['login_title'] ?></h2>

        <?php if ($error): ?>
            <div style="color: red;">
                <p><?= htmlspecialchars($error) ?></p><br>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="<?= $translations['login_email'] ?>">
            <input type="password" name="password" placeholder="<?= $translations['login_password'] ?>">
            <button type="submit"><?= $translations['login_button'] ?></button>
        </form>
        <br>
        <p><?= $translations['login_no_account'] ?> 
            <a href="register.php" style="color:red;"><?= $translations['login_register_link'] ?></a>
        </p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
