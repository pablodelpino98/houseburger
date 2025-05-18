<?php
    // Cargar idioma según la cookie
    $lang = $_COOKIE['lang'] ?? 'es';
    include "../lang/{$lang}.php";
?>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4><?= htmlspecialchars($translations['contact'] ?? 'Contacto') ?></h4>
            <p><?= htmlspecialchars($translations['phone'] ?? 'Tel') ?>: 123 456 789</p>
            <p>Email: info@houseburger.com</p>
        </div>
        <div class="footer-section">
            <p>&copy; <?= date('Y') ?> House Burger. <?= htmlspecialchars($translations['all_rights_reserved'] ?? 'Todos los derechos reservados.') ?></p>
        </div>
    </div>
</footer>

<script src="../assets/js/script.js"></script>
</body>
</html>
