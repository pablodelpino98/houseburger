<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['cart']);

header("Location: ../pages/order.php");
exit();
?>
