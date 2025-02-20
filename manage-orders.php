<?php
session_start();
require_once 'db.php';

// Отримуємо всі замовлення разом з ім'ям користувача
$stmt = $pdo->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");

// Перевірка на успішне виконання запиту
if ($stmt) {
    $orders = $stmt->fetchAll();
} else {
    echo "Помилка при отриманні даних.";
}

// Видалення замовлення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = $_POST['delete_order_id'];

    try {
        // Видаляємо елементи з таблиці order_items
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);

        // Видаляємо замовлення з таблиці orders
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);

        echo "Замовлення видалено.";
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    try {
        // Оновлення статусу замовлення
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);

        echo "Статус замовлення оновлено.";
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління замовленнями</title>
    <link rel="stylesheet" href="styles_adminpanel.css">
</head>
<body>

<header>
        <nav>
            <ul>
                <li><a href="admin_panel.php">Головна</a></li>
                <li><a href="add-product.php">Додати товар</a></li>
                <li><a href="manage-products.php">Керувати товарами</a></li>
                <li><a href="manage-orders.php">Замовлення</a></li>
                <li><a href="manage-users.php">Користувачі</a></li>
                <li><a href="admin_logout.php">Вийти</a></li>
            </ul>
        </nav>
    </header>


    <h2>Управління замовленнями</h2>

    <?php if ($orders): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>№ замовлення</th>
                    <th>Користувач</th>
                    <th>Товар</th>
                    <th>Ціна</th>
                    <th>Розмір</th>
                    <th>Кількість</th>
                    <th>Місто</th>
                    <th>Відділення Нової Пошти</th>
                    <th>Телефон</th>
                    <th>Метод оплати</th>
                    <th>Статус</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <?php
                        // Отримуємо товари для кожного замовлення, включаючи розмір з таблиці products
                        $stmt_items = $pdo->prepare("SELECT oi.*, p.product_name AS name, p.size AS product_size 
                                                     FROM order_items oi 
                                                     JOIN products p ON oi.product_id = p.id 
                                                     WHERE oi.order_id = ?");
                        $stmt_items->execute([$order['id']]);
                        $items = $stmt_items->fetchAll();
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td>
                            <?php foreach ($items as $item): ?>
                                <p><?php echo htmlspecialchars($item['name']); ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>₴<?php echo htmlspecialchars($order['total_price']); ?></td>
                        <td>
                            <?php foreach ($items as $item): ?>
                                <p><?php echo htmlspecialchars($item['product_size']); ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($items as $item): ?>
                                <p><?php echo htmlspecialchars($item['quantity']); ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['city']); ?></td>
                        <td><?php echo htmlspecialchars($order['nova_poshta_branch']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <select name="status">
                                    <option value="pending" <?php if ($order['status'] === 'pending') echo 'selected'; ?>>Очікується</option>
                                    <option value="shipped" <?php if ($order['status'] === 'shipped') echo 'selected'; ?>>Відправлено</option>
                                    <option value="delivered" <?php if ($order['status'] === 'delivered') echo 'selected'; ?>>Доставлено</option>
                                </select>
                                <button type="submit">Змінити статус</button>
                            </form>
                        </td>
                        <td>
                            <!-- Форма для видалення замовлення -->
                            <form method="post">
                                <input type="hidden" name="delete_order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <button type="submit" onclick="return confirm('Ви впевнені, що хочете видалити це замовлення?');">Видалити</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Немає замовлень для відображення.</p>
    <?php endif; ?>
</body>
</html>
