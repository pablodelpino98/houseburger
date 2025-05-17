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
                <button class='btn-menu add-to-cart'>Añadir al carrito</button>
              </div>";
    }
    echo "</div>";
}
?>

<main class="menu-page-container">
    <div class="menu-content">
        <h1>Realiza tu pedido</h1>

        <?php
        renderOrderCategory($pdo, 'hamburguesa', 'Hamburguesas');
        renderOrderCategory($pdo, 'papas', 'Papas Fritas');
        renderOrderCategory($pdo, 'nachos', 'Nachos');
        renderOrderCategory($pdo, 'refresco', 'Refrescos');
        renderOrderCategory($pdo, 'cerveza', 'Cervezas');
        ?>
    </div>
</main>

<!-- Modal para combos de hamburguesa -->
<div id="modal" class="modal-overlay" style="display: none;">
  <div class="modal-content">
    <p id="modal-message">¿Desea añadir Papas Fritas Clásicas y un refresco por 3,00€?</p>

    <select id="refrescoSelect" style="display: none;">
      <option value="Coca-Cola">Coca-Cola</option>
      <option value="Coca-Cola ZERO">Coca-Cola ZERO</option>
      <option value="Fanta Naranja">Fanta Naranja</option>
    </select>

    <div id="modal-buttons">
      <button id="confirmCombo">Sí, añadir combo</button><br>
      <button id="cancelCombo">No, solo hamburguesa</button>
    </div>

    <button id="closeModal" style="display:none;">Cerrar</button>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
