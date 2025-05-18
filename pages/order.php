<?php
include '../includes/header.php';
include '../cart/cart.php';
include '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function renderOrderCategory($pdo, $category, $title) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id ASC");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>$title</h2>";
    echo "<div class='product-list'>";
    foreach ($products as $product) {
        $name = htmlspecialchars($product['name']);
        $price = number_format($product['price'], 2, '.', '.');
        $image = htmlspecialchars($product['image']);
        $type = htmlspecialchars($product['category']);
        $product_id = (int)$product['id'];

        echo "<div class='product-card' 
                    data-id='$product_id' 
                    data-type='$type' 
                    data-name='$name' 
                    data-price='{$product['price']}'>
                <img src='../assets/images/$image' alt='$name'>
                <h3>$name</h3>
                <span class='price'>{$price} €</span>
                <button class='btn-menu add-to-cart'>{$GLOBALS['translations']['add_to_cart']}</button>
              </div>";
    }
    echo "</div>";
}
?>

<main class="menu-page-container">
    <div class="menu-content">
        <h1><?= $translations['place_order'] ?? 'Place Your Order' ?></h1>

        <?php
        renderOrderCategory($pdo, 'hamburguesa', $translations['hamburgers'] ?? 'Burgers');
        renderOrderCategory($pdo, 'papas', $translations['fries'] ?? 'Fries');
        renderOrderCategory($pdo, 'nachos', $translations['nachos'] ?? 'Nachos');
        renderOrderCategory($pdo, 'refresco', $translations['soft_drinks'] ?? 'Soft Drinks');
        renderOrderCategory($pdo, 'cerveza', $translations['beers'] ?? 'Beers');
        ?>
    </div>
</main>

<!-- Modal para combos de hamburguesa y mensajes -->
<div id="modal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    <p id="modal-message"><?= $translations['combo_question'] ?? 'Would you like to add Classic Fries and a soft drink for €2.99?' ?></p>

    <select id="refrescoSelect" style="display: none;">
      <option value="Coca-Cola">Coca-Cola</option>
      <option value="Coca-Cola ZERO">Coca-Cola ZERO</option>
      <option value="Fanta Naranja">Fanta Orange</option>
    </select>

    <div id="modal-buttons">
      <button id="confirmCombo"><?= $translations['yes_add_combo'] ?? 'Yes, add combo' ?></button><br>
      <button id="cancelCombo"><?= $translations['no_only_burger'] ?? 'No, just the burger' ?></button>
    </div>

    <button id="closeModal" style="display:none;"><?= $translations['close'] ?? 'Close' ?></button>
  </div>
</div>

<script>
  const translations = {
    combo_question: <?= json_encode($translations['combo_question'] ?? '¿Desea añadir Papas Fritas Clásicas y un Refresco por 2,99€?') ?>,
    yes_add_combo: <?= json_encode($translations['yes_add_combo'] ?? 'Sí, añadir combo') ?>,
    no_only_burger: <?= json_encode($translations['no_only_burger'] ?? 'No, solo la hamburguesa') ?>,
    close: <?= json_encode($translations['close'] ?? 'Cerrar') ?>,
    added_to_cart: <?= json_encode($translations['added_to_cart'] ?? 'Añadido al carrito') ?>
  };
</script>
<script src="../js/script.js"></script>


<?php include '../includes/footer.php'; ?>
