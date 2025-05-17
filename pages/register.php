<?php
include '../includes/header.php';
include '../cart/cart.php';
require '../includes/database.php';

$mostrarFormulario = true;
$errores = [];
$nombre = '';
$email = '';
$telefono = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($email) || empty($telefono) || empty($password) || empty($confirm)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    if (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = "Teléfono debe tener 9 dígitos.";
    }

    if ($password !== $confirm) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres y contener al menos 1 número.";
    }

    // Verificar email y teléfono únicos
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $telefono]);
    $existe = $stmt->fetchColumn();

    if ($existe > 0) {
        $errores[] = "El correo o teléfono ya está registrado.";
    }

    // Si no hay errores, registrar usuario
    if (empty($errores)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $telefono, $hashed]);
        $mostrarFormulario = false;
    }
}
?>

<main class="form-page-container">
    <div class="form-container">
        <h2>Registro</h2>
        <?php
        // Mostrar errores si hay
        if (!empty($errores)) {
            echo "<ul style='color:red; text-align:left;'>";
            foreach ($errores as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>";
        }

        // Mostrar formulario si corresponde
        if ($mostrarFormulario) { ?>
            <form method="POST">
                <label>Nombre completo:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($nombre) ?>" required>

                <label>Correo electrónico:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

                <label>Teléfono móvil:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($telefono) ?>" required>

                <label>Contraseña:</label>
                <input type="password" name="password" required>

                <label>Repetir contraseña:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Registrarse</button>
            </form>
        <?php } else { ?>
            <p style='color:white; text-align:center;'>Usuario registrado correctamente. <a href='login.php' style='color:red;'>Inicia sesión</a>.</p>
        <?php } ?>    
    </div>
</main>

<?php include '../includes/footer.php'; ?>
