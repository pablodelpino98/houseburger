<?php
include '../includes/database.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}
?>
<main class="form-page-container">
    <div class="payment-form">
        <h1>Pago</h1>
        <form id="paymentForm" action="process_payment.php" method="POST">
            <label for="card_number">Número de tarjeta:</label>
            <input type="text" id="card_number" name="card_number" maxlength="19" required>

            <label for="card_name">Nombre del titular:</label>
            <input type="text" id="card_name" name="card_name" required>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" maxlength="3" required>

            <button type="submit" class="btn-menu">Pagar</button>
        </form>
    </div>
</main>

<script>
document.getElementById("paymentForm").addEventListener("submit", function(e) {
    const cardNumber = document.getElementById("card_number").value.trim();
    const cardName = document.getElementById("card_name").value.trim();
    const cvv = document.getElementById("cvv").value.trim();

    const validCard = /^\d{16}$/.test(cardNumber.replace(/\s/g, ""));
    const validName = cardName.length > 0;
    const validCVV = /^\d{3}$/.test(cvv);

    if (!validCard || !validName || !validCVV) {
        alert("Por favor, completa los datos correctamente.");
        e.preventDefault(); // Cancelar el envío si no es válido
    }
});
</script>

<?php include '../includes/footer.php'; ?>
