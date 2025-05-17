<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Cart Modal -->
<div class="cart-modal" id="cartModal">
    <div class="cart-content">
        <div class="cart-header">
            <h3>Carrito</h3>
            <button class="close-btn" onclick="toggleCart()">&times;</button>
        </div>
        <div class="cart-body">
            <?php if (isLoggedIn()): ?>
                <?php
                $cart = $_SESSION['cart'] ?? [];
                $total = 0;
                ?>
                <div id="cart-content">
                    <?php if (empty($cart)): ?>
                        <p>El carrito está vacío.</p>
                    <?php else: ?>
                        <?php foreach ($cart as $index => $item): ?>
                            <div class="cart-item">
                                <p><strong><?= htmlspecialchars($item['name']) ?></strong> - <?= number_format($item['price'], 2) ?> €</p>
                                <form action="../cart/remove_from_cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button type="submit" class="btn-menu">Eliminar</button>
                                </form>
                            </div>
                            <?php $total += $item['price']; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p id="total">Total: <?= number_format($total, 2) ?> €</p>
                <form action="../cart/clear_cart.php" method="POST">
                    <button type="submit" class="btn-menu">Vaciar carrito</button>
                </form>
                <form action="process_order.php" method="POST">
                    <button type="submit" class="btn-menu">Tramitar pedido</button>
                </form>
            <?php else: ?>
                <p class="login-alert">Inicie sesión para usar el carrito</p>
            <?php endif; ?>
        </div>
    </div>
</div>
