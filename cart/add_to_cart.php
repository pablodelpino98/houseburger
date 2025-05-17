<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $product;

    echo json_encode(['success' => true, 'cartCount' => count($_SESSION['cart'])]);
}
?>
