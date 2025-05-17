<?php
include '../includes/header.php';
include '../includes/database.php';
include '../cart/cart.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Guardar el método de entrega enviado para mantenerlo en sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivery_method'])) {
    $_SESSION['delivery_method'] = $_POST['delivery_method'];
} else if (!isset($_SESSION['delivery_method'])) {
    // Por defecto, domicilio
    $_SESSION['delivery_method'] = 'domicilio';
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$cart = $_SESSION['cart'] ?? [];
$total = array_reduce($cart, fn($acc, $item) => $acc + $item['price'], 0);

// Guardar dirección nueva si se envía desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_address'])) {
    $new_address = trim($_POST['new_address']);
    if (!empty($new_address)) {
        $stmt = $pdo->prepare("UPDATE users SET address = ? WHERE id = ?");
        $stmt->execute([$new_address, $user_id]);
        $user['address'] = $new_address;
    }
}

?>

<main class="order-summary">
    <h1>Resumen del Pedido</h1>

    <?php if (empty($cart)): ?>
        <p>Tu carrito está vacío.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cart as $item): ?>
                <li><strong><?= htmlspecialchars($item['name']) ?></strong> - <?= number_format($item['price'], 2) ?> €</li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total:</strong> <?= number_format($total, 2) ?> €</p>

        <form method="POST" id="deliveryForm" action="process_order.php">
            <label for="delivery_method">Método de entrega:</label>
            <select name="delivery_method" id="delivery_method" onchange="toggleDeliveryFields()">
                <option value="domicilio" <?= $_SESSION['delivery_method'] === 'domicilio' ? 'selected' : '' ?>>A domicilio</option>
                <option value="recoger" <?= $_SESSION['delivery_method'] === 'recoger' ? 'selected' : '' ?>>Recoger en restaurante</option>
            </select>

            <div id="address-section" style="<?= $_SESSION['delivery_method'] === 'domicilio' ? 'display:block;' : 'display:none;' ?>">
                <?php if (!empty($user['address'])): ?>
                    <p><strong>Dirección registrada:</strong> <?= htmlspecialchars($user['address']) ?></p>
                    <p><strong>Su pedido a domicilio llegará aproximadamente 30-45 minutos después de realizar el pago.</strong></p>
                <?php else: ?>
                    <label for="new_address">Introduce tu dirección:</label>
                    <input type="text" name="new_address" id="new_address" required>
                    <button type="submit">Guardar dirección</button>
                <?php endif; ?>
            </div>

            <div id="pickup-section" style="<?= $_SESSION['delivery_method'] === 'recoger' ? 'display:block;' : 'display:none;' ?>">
                <p><strong>Dirección del restaurante:</strong> C. Ana Benítez, 15, 35014 Las Palmas</p>
                <p><strong>Su pedido estará listo para recoger aproximadamente 20 minutos después de realizar el pago.</strong></p>
            </div>
        </form>

        <form action="payments.php" method="POST">
            <button type="submit" class="btn-menu">Pagar</button>
        </form>
    <?php endif; ?>
</main>

<script>
function toggleDeliveryFields() {
    const method = document.getElementById('delivery_method').value;
    const addressSection = document.getElementById('address-section');
    const pickupSection = document.getElementById('pickup-section');

    if (method === 'domicilio') {
        addressSection.style.display = 'block';
        pickupSection.style.display = 'none';
    } else {
        addressSection.style.display = 'none';
        pickupSection.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', toggleDeliveryFields);
</script>

<?php include '../includes/footer.php'; ?>
