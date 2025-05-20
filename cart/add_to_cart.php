<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Validar que hay un ID
    $productId = $product['product_id'] ?? $product['id'] ?? null;
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
        exit;
    }

    // Definir tipo (normal o combo)
    $type = $product['type'] ?? 'normal';

    // Buscar si ya está en el carrito ese mismo producto con ese mismo tipo
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if (
            ($item['product_id'] ?? $item['id']) == $productId &&
            ($item['type'] ?? 'normal') === $type
        ) {
            // Si existe con mismo tipo, aumentar cantidad
            $item['quantity'] = ($item['quantity'] ?? 1) + 1;
            $found = true;
            break;
        }
    }
    unset($item); // romper referencia NECESARIO

    if (!$found) {
        $product['quantity'] = 1;
        $product['type'] = $type;
        $_SESSION['cart'][] = $product;
    }

    // Traducciones Carrito
    echo json_encode(['success' => true, 'cartCount' => count($_SESSION['cart'])]);
}
?>
