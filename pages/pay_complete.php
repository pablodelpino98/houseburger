<?php
include '../includes/header.php';
include '../includes/database.php';

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT delivery_method FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<p>Error: Pedido no encontrado.</p>";
    include '../includes/footer.php';
    exit();
}

$now = new DateTime();
if ($order['delivery_method'] === 'domicilio') {
    $now->modify('+40 minutes');
    $message = "Recibirá su pedido a las " . $now->format('H:i') . " aproximadamente.";
} else {
    $now->modify('+20 minutes');
    $message = "Su pedido estará listo a las " . $now->format('H:i') . " aproximadamente.";
}
?>

<main class="form-page-container">
    <div class="pay-complete">
        <h1>Su pedido ha sido completado</h1>
        <p>Su pedido es el número: <strong><?= htmlspecialchars($order_id) ?></strong></p>
        <p><?= $message ?></p>
        <p>Puede contactar con el restaurante en el <strong>928 123 456</strong></p>
        <form action="last_orders.php" method="get">
            <button type="submit" class="btn-menu">Aceptar</button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
