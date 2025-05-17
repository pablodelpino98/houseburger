<?php
include '../includes/header.php';
include '../cart/cart.php';
include '../includes/database.php'; // Aquí se define $pdo (PDO)


// Función para renderizar productos por categoría con PDO
function renderCategory($pdo, $category, $title) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id ASC");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>$title</h2>";
    echo "<div class='product-list'>";

    foreach ($products as $product) {
        $name = htmlspecialchars($product['name']);
        $desc = htmlspecialchars($product['description']);
        $price = number_format($product['price'], 2, ',', '.');
        $image = htmlspecialchars($product['image']);

        echo "<div class='product-card'>
                <img src='../assets/images/$image' alt='$name'>
                <h3>$name</h3>
                <p>$desc</p>
                <span class='price'>{$price} €</span>
              </div>";
    }

    echo "</div>";
}
?>

<main class="menu-page-container">
    <div class="menu-content">
        <h1>Nuestra Carta</h1>
        <a href="order.php" class="order-button">Realizar pedido</a>

        <?php
        renderCategory($pdo, 'hamburguesa', 'Hamburguesas');
        echo "<br><p>El precio no incluye papas ni bebidas. Puede añadir Papas fritas clásicas y un refresco por un suplemento de 2.99€</p>";

        renderCategory($pdo, 'papas', 'Papas fritas');
        renderCategory($pdo, 'nachos', 'Nachos');
        renderCategory($pdo, 'refresco', 'Refrescos');
        renderCategory($pdo, 'cerveza', 'Cervezas');
        ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
