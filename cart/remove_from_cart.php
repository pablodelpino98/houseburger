<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($data['index'])) {
    $index = $data['index'];
    if (isset($_SESSION['cart'][$index])) {
        array_splice($_SESSION['cart'], $index, 1);
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Índice no encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falta el índice']);
}
