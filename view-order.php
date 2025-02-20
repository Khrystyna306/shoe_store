<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db.php';

// Отримуємо ID замовлення
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-orders.php");
    exit;
}
$order_id = $_GET['id'];

// Отримуємо загальні дані про замовлення
$stmt = $pdo->prepare("
    SELECT orders.id, users.username, users.email, orders.total_price, orders.status, 
           orders.city, orders.nova_poshta_branch, orders.payment_method, orders.created_at 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    WHERE orders.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

// Отримуємо список товарів у замовленні
$stmt = $pdo->prepare("
    SELECT products.name, products.price, order_items.quantity 
    FROM order_items 
    JOIN products ON order_items.product_id = products.id 
    WHERE order_items.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Деталі замовлення</title>
    <link rel="stylesheet" href="styles_adminpanel.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin_panel.php">Головна</a></li>
                <li><a href="manage-orders.php">Назад</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Деталі замовлення #<?php echo $order['id']; ?></h1>
        <p><strong>Користувач:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        <p><strong>Місто:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
        <p><strong>Відділення Нової Пошти:</strong> <?php echo htmlspecialchars($order['nova_poshta_branch']); ?></p>
        <p><strong>Метод оплати:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        <p><strong>Дата:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

        <h2>Товари у замовленні</h2>
        <table>
            <thead>
                <tr>
                    <th>Назва товару</th>
                    <th>Ціна</th>
                    <th>Кількість</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> грн</td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
