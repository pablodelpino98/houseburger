<?php
session_start();

// Eliminar todos los datos de sesión
$_SESSION = [];
session_unset();
session_destroy();

// Opcional: eliminar cookies de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirigir al login o al inicio
header("Location: pages/index.php");
exit;
