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
        $message = $GLOBALS['translations']['address_saved'] ?? 'Address saved successfully.';
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
    <h1><?= $translations['order_summary'] ?? 'Order Summary' ?></h1>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <p><?= $translations['cart_empty'] ?? 'Your cart is empty.' ?></p>
    <?php else: ?>
        <ul>
            <?php foreach ($cart as $item): ?>
                <li>
                    <strong><?= htmlspecialchars($item['name']) ?></strong> - 
                    <?= $item['quantity'] ?> x <?= number_format($item['price'], 2) ?> € = <?= number_format($item['price'] * $item['quantity'], 2) ?> €
                </li>
            <?php endforeach; ?>
        </ul>

        <p><strong><?= $translations['delivery_fee'] ?? 'Delivery fee' ?>:</strong> <span id="deliveryFee"><?= $delivery_fee > 0 ? number_format($delivery_fee, 2) . ' €' : '0.00 €' ?></span></p>
        <p><strong><?= $translations['total'] ?? 'Total' ?>:</strong> <span id="totalPrice"><?= number_format($total_with_fee, 2) ?> €</span></p>

        <form method="POST" id="deliveryForm" action="">
            <label for="delivery_method"><?= $translations['delivery_method'] ?? 'Delivery method' ?>:</label>
            <select name="delivery_method" id="delivery_method" onchange="updateTotals()" required>
                <option value="" <?= $delivery_method === null ? 'selected' : '' ?>>-- <?= $translations['select_method'] ?? 'Select method' ?> --</option>
                <option value="domicilio" <?= $delivery_method === 'domicilio' ? 'selected' : '' ?>><?= $translations['home_delivery'] ?? 'Home delivery' ?></option>
                <option value="recoger" <?= $delivery_method === 'recoger' ? 'selected' : '' ?>><?= $translations['pickup_restaurant'] ?? 'Pick up at restaurant' ?></option>
            </select>

            <div id="address-section" style="<?= $delivery_method === 'domicilio' ? 'display:block;' : 'display:none;' ?>">
                <?php if (!empty($user['address'])): ?>
                    <p><strong><?= $translations['registered_address'] ?? 'Registered address' ?>:</strong> <?= htmlspecialchars($user['address']) ?></p>
                    <p><strong><?= $translations['delivery_time_info'] ?? 'Your home delivery will arrive approximately 30-45 minutes after payment.' ?></strong></p>
                    <label for="new_address"><?= $translations['change_address'] ?? 'Change address' ?>:</label>
                    <input type="text" name="new_address" id="new_address" placeholder="<?= $translations['enter_new_address'] ?? 'Enter new address' ?>">

                    <button type="submit" name="save_address" class="btn-menu" style="margin-top: 10px;"><?= $translations['save_address'] ?? 'Save address' ?></button>
                <?php else: ?>
                    <label for="new_address"><?= $translations['enter_address'] ?? 'Enter your address' ?>:</label>
                    <input type="text" name="new_address" id="new_address" required>

                    <button type="submit" name="save_address" class="btn-menu" style="margin-top: 10px;"><?= $translations['save_address'] ?? 'Save address' ?></button>
                <?php endif; ?>
            </div>

            <div id="pickup-section" style="<?= $delivery_method === 'recoger' ? 'display:block;' : 'display:none;' ?>">
                <p><strong><?= $translations['restaurant_address'] ?? 'Restaurant address' ?>:</strong> C. Ana Benítez, 15, 35014 Las Palmas</p>
                <p><strong><?= $translations['pickup_time_info'] ?? 'Your order will be ready to pick up approximately 20 minutes after payment.' ?></strong></p>
            </div>

            <button type="submit" name="proceed_payment" class="btn-menu" style="margin-top: 20px;"><?= $translations['pay'] ?? 'Pay' ?></button>
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
