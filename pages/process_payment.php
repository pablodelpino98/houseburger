<?php
session_start();
include '../includes/database.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Actualizar método de entrega con el enviado por POST (prioridad)
if (isset($_POST['delivery_method'])) {
    $_SESSION['delivery_method'] = $_POST['delivery_method'];
}
$delivery_method = $_SESSION['delivery_method'] ?? 'domicilio';

// Obtener dirección según el método
$delivery_address = null;
if ($delivery_method === 'domicilio') {
    $stmt = $pdo->prepare("SELECT address FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $delivery_address = $user['address'] ?? null;
} else if ($delivery_method === 'recoger') {
    // Dirección fija del restaurante para recogida
    $delivery_address = 'C. Ana Benítez, 15, 35014 Las Palmas';
}

// Calcular total sin suplemento
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $qty = $item['quantity'] ?? 1;
    $total += $item['price'] * $qty;
}

// Añadir suplemento si el método es domicilio
$delivery_fee = 0;
if ($delivery_method === 'domicilio') {
    $delivery_fee = 1.99;
}

$total_with_fee = $total + $delivery_fee;

// Insertar en 'orders' con el total que incluye el suplemento
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total, delivery_method, delivery_address) VALUES (?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $total_with_fee, $delivery_method, $delivery_address]);
$order_id = $pdo->lastInsertId();

// Insertar en 'order_details'
$stmtDetail = $pdo->prepare("
    INSERT INTO order_details (order_id, product_id, quantity, price, is_combo) 
    VALUES (?, ?, ?, ?, ?)
");

foreach ($_SESSION['cart'] as $item) {
    $qty = $item['quantity'] ?? 1;
    $productId = $item['product_id'] ?? $item['id'];
    $price = $item['price'];
    $isCombo = ($item['type'] ?? 'normal') === 'combo' ? 1 : 0;

    $stmtDetail->execute([
        $order_id,
        $productId,
        $qty,
        $price,
        $isCombo
    ]);
}


// Vaciar carrito
$_SESSION['cart'] = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Procesando Pago</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../images/background.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    
    .processing-container {
      background-color: rgba(44, 44, 44, 0.95);
      border: 2px solid #8b0000;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      padding: 40px;
      max-width: 600px;
      width: 90%;
      margin: 0 auto;
    }
    
    .processing-container h1 {
      color: #ffa600;
      margin-bottom: 20px;
    }
    
    .processing-message {
      font-size: 1.2rem;
      margin-bottom: 20px;
      color: #ddd;
    }
    
    .loading-spinner {
      border: 4px solid rgba(255, 166, 0, 0.3);
      border-radius: 50%;
      border-top: 4px solid #ffa600;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <script>
    setTimeout(() => {
      window.location.href = 'pay_complete.php?order_id=<?= $order_id ?>';
    }, 2000);
  </script>
</head>
<body>
  <div class="processing-container">
    <h1>Procesando su pago</h1>
    <p class="processing-message">Pago aceptado. Redirigiendo a la confirmación...</p>
    <div class="loading-spinner"></div>
    <p>Por favor espere un momento...</p>
  </div>
</body>
</html>
</html>