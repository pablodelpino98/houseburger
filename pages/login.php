<?php 
include '../includes/header.php';
include '../cart/cart.php';
?>

<main class="form-page-container">
    <div class="form-container">
        <h2><?= $translations['login_title'] ?></h2>
        <form action="../process_login.php" method="POST">
            <input type="email" name="email" placeholder="<?= $translations['login_email'] ?>" required>
            <input type="password" name="password" placeholder="<?= $translations['login_password'] ?>" required>
            <button type="submit"><?= $translations['login_button'] ?></button>
        </form>
        <br><p><?= $translations['login_no_account'] ?> <a href="register.php" style='color:red;'><?= $translations['login_register_link'] ?></a></p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
