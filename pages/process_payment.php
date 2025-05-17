<?php
session_start();
include '../includes/database.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$delivery_method = $_SESSION['delivery_method'] ?? 'domicilio';

$delivery_address = null;
if ($delivery_method === 'domicilio') {
    $stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $delivery_address = $user['address'] ?? null;
}

// Calcular total
$total = 0;
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $total += $item['price'] * $qty;
}

// Insertar en 'orders'
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total, delivery_method, delivery_address) VALUES (?, ?, ?, ?)");
$stmt->execute([$user_id, $total, $delivery_method, $delivery_address]);
$order_id = $pdo->lastInsertId();

// Insertar en 'order_details'
$stmtDetail = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $stmtDetail->execute([
        $order_id,
        $item['product_id'],
        $qty,
        $item['price']
    ]);
}

// Vaciar carrito
$_SESSION['cart'] = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Procesando Pago</title>
  <script>
    setTimeout(() => {
      window.location.href = 'pay_complete.php?order_id=<?= $order_id ?>';
    }, 2000);
  </script>
</head>
<body>
  <p>Pago aceptado. Redirigiendo...</p>
</body>
</html>
