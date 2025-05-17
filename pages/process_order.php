<?php
include '../includes/header.php';
include '../includes/database.php';
include '../cart/cart.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delivery_method'])) {
        $_SESSION['delivery_method'] = $_POST['delivery_method'];
    }
    $delivery_method = $_SESSION['delivery_method'] ?? null;

    if (isset($_POST['save_address']) && !empty(trim($_POST['new_address']))) {
        $new_address = trim($_POST['new_address']);
        $stmt = $pdo->prepare("UPDATE users SET address = ? WHERE id = ?");
        $stmt->execute([$new_address, $user_id]);
        $message = 'Dirección guardada correctamente.';
    }

    if (isset($_POST['proceed_payment'])) {
        if (isset($_POST['new_address']) && !empty(trim($_POST['new_address']))) {
            $new_address = trim($_POST['new_address']);
            $stmt = $pdo->prepare("UPDATE users SET address = ? WHERE id = ?");
            $stmt->execute([$new_address, $user_id]);
        }
        header("Location: payments.php");
        exit();
    }
}

$delivery_method = $_SESSION['delivery_method'] ?? null;

$stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $total += $item['price'] * $qty;
}

$delivery_fee = ($delivery_method === 'domicilio') ? 1.99 : 0;
$total_with_fee = $total + $delivery_fee;
?>

<main class="order-summary">
    <h1>Resumen del Pedido</h1>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <p>Tu carrito está vacío.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cart as $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['name']) ?></strong> - 
                    <?= $item['quantity'] ?> x <?= number_format($item['price'], 2) ?> € = <?= number_format($item['price'] * $item['quantity'], 2) ?> €
                </li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Cargo por envío a domicilio:</strong> <span id="deliveryFee"><?= $delivery_fee > 0 ? number_format($delivery_fee, 2) . ' €' : '0.00 €' ?></span></p>
        <p><strong>Total:</strong> <span id="totalPrice"><?= number_format($total_with_fee, 2) ?> €</span></p>

        <form method="POST" id="deliveryForm" action="">
            <label for="delivery_method">Método de entrega:</label>
            <select name="delivery_method" id="delivery_method" onchange="updateTotals()" required>
                <option value="" <?= $delivery_method === null ? 'selected' : '' ?>>-- Selecciona método --</option>
                <option value="domicilio" <?= $delivery_method === 'domicilio' ? 'selected' : '' ?>>A domicilio</option>
                <option value="recoger" <?= $delivery_method === 'recoger' ? 'selected' : '' ?>>Recoger en restaurante</option>
            </select>

            <div id="address-section" style="<?= $delivery_method === 'domicilio' ? 'display:block;' : 'display:none;' ?>">
                <?php if (!empty($user['address'])): ?>
                    <p><strong>Dirección registrada:</strong> <?= htmlspecialchars($user['address']) ?></p>
                    <p><strong>Su pedido a domicilio llegará aproximadamente 30-45 minutos después de realizar el pago.</strong></p>
                    <label for="new_address">Cambiar dirección:</label>
                    <input type="text" name="new_address" id="new_address" placeholder="Introduce nueva dirección">

                    <button type="submit" name="save_address" class="btn-menu" style="margin-top: 10px;">Guardar dirección</button>
                <?php else: ?>
                    <label for="new_address">Introduce tu dirección:</label>
                    <input type="text" name="new_address" id="new_address" required>

                    <button type="submit" name="save_address" class="btn-menu" style="margin-top: 10px;">Guardar dirección</button>
                <?php endif; ?>
            </div>

            <div id="pickup-section" style="<?= $delivery_method === 'recoger' ? 'display:block;' : 'display:none;' ?>">
                <p><strong>Dirección del restaurante:</strong> C. Ana Benítez, 15, 35014 Las Palmas</p>
                <p><strong>Su pedido estará listo para recoger aproximadamente 20 minutos después de realizar el pago.</strong></p>
            </div>

            <button type="submit" name="proceed_payment" class="btn-menu" style="margin-top: 20px;">Pagar</button>
        </form>
    <?php endif; ?>
</main>

<script>
const baseTotal = <?= json_encode($total) ?>;
const deliveryFeeAmount = 1.99;

function updateTotals() {
    const deliveryMethod = document.getElementById('delivery_method').value;
    const addressSection = document.getElementById('address-section');
    const pickupSection = document.getElementById('pickup-section');
    const deliveryFeeEl = document.getElementById('deliveryFee');
    const totalPriceEl = document.getElementById('totalPrice');

    if (deliveryMethod === 'domicilio') {
        addressSection.style.display = 'block';
        pickupSection.style.display = 'none';

        deliveryFeeEl.textContent = deliveryFeeAmount.toFixed(2) + ' €';
        totalPriceEl.textContent = (baseTotal + deliveryFeeAmount).toFixed(2) + ' €';
    } else if (deliveryMethod === 'recoger') {
        addressSection.style.display = 'none';
        pickupSection.style.display = 'block';

        deliveryFeeEl.textContent = '0.00 €';
        totalPriceEl.textContent = baseTotal.toFixed(2) + ' €';
    } else {
        addressSection.style.display = 'none';
        pickupSection.style.display = 'none';

        deliveryFeeEl.textContent = '0.00 €';
        totalPriceEl.textContent = baseTotal.toFixed(2) + ' €';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateTotals();
});
</script>

<?php include '../includes/footer.php'; ?>
