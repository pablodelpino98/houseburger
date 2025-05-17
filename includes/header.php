<?php
include 'auth.php';
include 'database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $translations['title'] ?></title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <h1 class="logo"><a href="index.php">House Burger</a></h1>
            
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="../pages/index.php">Inicio</a></li>
                    <li><a href="../pages/menu.php">Carta</a></li>
                    <?php if(isLoggedIn()): ?>
                        <li><a href="../pages/profile.php">Mi Perfil</a></li>
                        <li><a href="../pages/orders.php">Mis Pedidos</a></li>
                        <li><a href="../logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="../pages/login.php">Iniciar Sesión</a></li>
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
