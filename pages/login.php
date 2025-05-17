<?php include '../includes/header.php'; ?>
<?php include '../cart/cart.php'; ?>

<main class="form-page-container">
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <form action="../process_login.php" method="POST">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <br><p>¿No tienes cuenta? <a href="register.php" style='color:red;'>Regístrate</a></p>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
