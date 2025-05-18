<?php
include 'auth.php';
include 'database.php';
?>
<!DOCTYPE html>
<html lang="<?= $translations['lang_code'] ?? 'es' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($translations['title'] ?? 'House Burger') ?></title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>

            <div>
                <a href="index.php">
                    <img src="../assets/images/logopng.png" alt="House Burger Logo" class="logo-img">
                </a>
            </div>
            
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="../pages/index.php"><?= htmlspecialchars($translations['home'] ?? 'Inicio') ?></a></li>
                    <li><a href="../pages/menu.php"><?= htmlspecialchars($translations['menu'] ?? 'Carta') ?></a></li>
                    <?php if(isLoggedIn()): ?>
                        <li><a href="../pages/account.php"><?= htmlspecialchars($translations['my_account'] ?? 'Mi Cuenta') ?></a></li>
                        <li><a href="../pages/last_orders.php"><?= htmlspecialchars($translations['my_orders'] ?? 'Mis Pedidos') ?></a></li>
                        <li><a href="../logout.php"><?= htmlspecialchars($translations['logout'] ?? 'Cerrar Sesión') ?></a></li>
                    <?php else: ?>
                        <li><a href="../pages/login.php"><?= htmlspecialchars($translations['login'] ?? 'Iniciar Sesión') ?></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="header-controls">
                <div class="language-switcher">
                    <a href="?lang=es">ES</a> | 
                    <a href="?lang=en">EN</a>
                </div>
                <button class="cart-btn" onclick="toggleCart()">
                    🛒
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </header>
</body>