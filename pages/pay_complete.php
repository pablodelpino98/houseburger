<?php
include '../includes/header.php';
include '../includes/database.php';

// Establecer hora Londres
date_default_timezone_set('Europe/London');

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT delivery_method FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<p>" . ($translations['error_order_not_found'] ?? "Error: Pedido no encontrado.") . "</p>";
    include '../includes/footer.php';
    exit();
}

$now = new DateTime();
if ($order['delivery_method'] === 'domicilio') {
    $now->modify('+40 minutes');
    $message = ($translations['order_will_arrive'] ?? "Recibirá su pedido a las") . " " . $now->format('H:i') . " " . ($translations['approximately'] ?? "aproximadamente.") ;
} else {
    $now->modify('+20 minutes');
    $message = ($translations['order_will_be_ready'] ?? "Su pedido estará listo a las") . " " . $now->format('H:i') . " " . ($translations['approximately'] ?? "aproximadamente.");
}
?>

<main class="form-page-container">
    <div class="pay-complete">
        <h1><?= $translations['order_completed'] ?? 'Su pedido ha sido completado' ?></h1>
        <p><?= $translations['order_number'] ?? 'Su pedido es el número:' ?> <strong><?= htmlspecialchars($order_id) ?></strong></p>
        <p><?= htmlspecialchars($message) ?></p>
        <p><?= $translations['contact_restaurant'] ?? 'Puede contactar con el restaurante en el' ?> <strong>928 123 456</strong></p>
        <form action="last_orders.php" method="get">
            <button type="submit" class="btn-menu"><?= $translations['accept'] ?? 'Aceptar' ?></button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
