<?php
include '../includes/header.php';
include '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Configuración de paginación
$pedidosPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $pedidosPorPagina;

// Consulta para contar el total de pedidos
$sqlCount = "SELECT COUNT(DISTINCT o.id) as total FROM orders o WHERE o.user_id = ?";
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute([$user_id]);
$totalPedidos = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalPedidos / $pedidosPorPagina);

// Consulta principal
$sql = "
    SELECT 
        o.id AS order_id,
        o.order_date,
        o.total,
        o.delivery_method,
        o.delivery_address,
        p.name AS product_name,
        od.quantity,
        od.price,
        od.is_combo
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    JOIN products p ON od.product_id = p.id
    WHERE o.user_id = :user_id
    ORDER BY o.order_date DESC, o.id DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $pedidosPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar pedidos
$groupedOrders = [];
foreach ($orders as $row) {
    $orderId = $row['order_id'];
    if (!isset($groupedOrders[$orderId])) {
        $groupedOrders[$orderId] = [
            'order_date' => $row['order_date'],
            'total' => $row['total'],
            'delivery_method' => $row['delivery_method'],
            'delivery_address' => $row['delivery_address'],
            'items' => []
        ];
    }
    $groupedOrders[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'is_combo' => $row['is_combo']
    ];
}
?>

<div class="form-page-container">
    <div class="orders-container">
        <h2 style="color: #ffa600; margin-bottom: 30px; text-align: center;"><?= $translations['my_orders'] ?></h2>

        <?php if (empty($groupedOrders)): ?>
            <div class="no-orders">
                <p><?= $translations['no_orders_yet'] ?></p>
                <a href="menu.php" class="btn-menu" style="margin-top: 20px; display: inline-block;"><?= $translations['see_menu'] ?></a>
            </div>
        <?php else: ?>
            <?php foreach ($groupedOrders as $orderId => $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3><?= $translations['order'] ?> #<?= $orderId ?></h3>
                        <div class="order-meta">
                            <span><strong><?= $translations['date'] ?>:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></span>
                            <span><strong><?= $translations['method'] ?>:</strong>
                                <?= $order['delivery_method'] === 'domicilio' 
                                    ? $translations['home_delivery'] 
                                    : $translations['pickup_store'] ?>
                            </span>
                            <?php if ($order['delivery_method'] === 'domicilio'): ?>
                                <span><strong><?= $translations['address'] ?>:</strong> <?= htmlspecialchars($order['delivery_address']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="order-body">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th><?= $translations['product'] ?></th>
                                    <th style="text-align: center;"><?= $translations['quantity'] ?></th>
                                    <th style="text-align: right;"><?= $translations['price'] ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($item['product_name']) ?>
                                            <?php if ($item['is_combo']): ?>
                                                <span class="combo-badge"><?= $translations['combo'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;"><?= $item['quantity'] ?></td>
                                        <td style="text-align: right;"><?= number_format($item['price'], 2) ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="order-total">
                            <strong><?= $translations['order_total'] ?>: <?= number_format($order['total'], 2) ?>€</strong>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Paginación -->
            <div class="pagination" style="margin-top: 30px; display: flex; justify-content: center; gap: 10px;">
                <?php if ($paginaActual > 1): ?>
                    <a href="last_orders.php?pagina=<?= $paginaActual - 1 ?>" class="btn-menu" style="padding: 8px 16px;">&laquo; <?= $translations['previous'] ?></a>
                <?php endif; ?>

                <?php 
                $inicio = max(1, $paginaActual - 2);
                $fin = min($totalPaginas, $paginaActual + 2);

                if ($inicio > 1) {
                    echo '<a href="last_orders.php?pagina=1" class="btn-menu" style="padding: 8px 16px;">1</a>';
                    if ($inicio > 2) echo '<span style="padding: 8px 16px;">...</span>';
                }

                for ($i = $inicio; $i <= $fin; $i++): ?>
                    <a href="last_orders.php?pagina=<?= $i ?>" class="btn-menu <?= $i == $paginaActual ? 'active' : '' ?>" style="padding: 8px 16px; <?= $i == $paginaActual ? 'background-color: #e69500;' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor;

                if ($fin < $totalPaginas) {
                    if ($fin < $totalPaginas - 1) echo '<span style="padding: 8px 16px;">...</span>';
                    echo '<a href="last_orders.php?pagina='.$totalPaginas.'" class="btn-menu" style="padding: 8px 16px;">'.$totalPaginas.'</a>';
                }
                ?>

                <?php if ($paginaActual < $totalPaginas): ?>
                    <a href="last_orders.php?pagina=<?= $paginaActual + 1 ?>" class="btn-menu" style="padding: 8px 16px;"><?= $translations['next'] ?> &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
