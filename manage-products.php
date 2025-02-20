<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Отримуємо список товарів
$stmt = $pdo->query("SELECT id, product_name, description, price, category_id, size, image, stock FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Керувати товарами</title>
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

    <main>
        <h1>Керування товарами</h1>
        <table>
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Назва</th>
                    <th>Опис</th>
                    <th>Ціна</th>
                    <th>Категорія</th>
                    <th>Розмір</th>
                    <th>Кількість</th>
                    <th>Дія</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Фото товару" width="100"></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?> грн</td>
                        <td><?php echo htmlspecialchars($product['category_id']); ?></td>
                        <td><?php echo htmlspecialchars($product['size']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>">Редагувати</a> |
                            <a href="delete-product.php?id=<?php echo $product['id']; ?>">Видалити</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
