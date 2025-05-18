<?php
include '../includes/header.php';
include '../cart/cart.php';
include '../includes/database.php';

// Función para renderizar productos por categoría con PDO
function renderCategory($pdo, $category, $title) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY id ASC");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>$title</h2>";
    echo "<div class='product-list'>";

    $language = $_COOKIE['lang'] ?? 'es';
    $desc_column = $language === 'en' ? 'description_en' : 'description';

    foreach ($products as $product) {
        $name = htmlspecialchars($product['name']);
        $desc = htmlspecialchars($product[$desc_column]);
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
        <h1><?= $translations['menu_title'] ?? 'Our Menu' ?></h1>
        <a href="order.php" class="order-button"><?= $translations['order_button'] ?? 'Place Order' ?></a>

        <?php
        renderCategory($pdo, 'hamburguesa', $translations['hamburgers'] ?? 'Burgers');
        echo "<br><p>" . ($translations['price_note'] ?? 'Price does not include fries or drinks. You can add Classic Fries and a soft drink for an extra €2.99') . "</p>";

        renderCategory($pdo, 'papas', $translations['fries'] ?? 'Fries');
        renderCategory($pdo, 'nachos', $translations['nachos'] ?? 'Nachos');
        renderCategory($pdo, 'refresco', $translations['soft_drinks'] ?? 'Soft Drinks');
        renderCategory($pdo, 'cerveza', $translations['beers'] ?? 'Beers');
        ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
