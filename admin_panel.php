<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Отримуємо кількість товарів
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$product_count = $stmt->fetchColumn();

// Отримуємо кількість замовлень
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$order_count = $stmt->fetchColumn();

// Отримуємо кількість користувачів
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$user_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін Панель</title>
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
        <div class="dashboard" >
            <h1>Вітаємо на адмін панелі!</h1>
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <h2>Кількість товарів</h2>
                    <p><?php echo $product_count; ?></p>
                </div>
                <div class="dashboard-card">
                    <h2>Кількість замовлень</h2>
                    <p><?php echo $order_count; ?></p>
                </div>
                <div class="dashboard-card">
                    <h2>Кількість користувачів</h2>
                    <p><?php echo $user_count; ?></p>
                </div>
            </div>

            <div class="quick-actions">
                <h2>Швидкі дії</h2>
                <ul>
                    <li><a href="add-product.php">Додати новий товар</a></li>
                    <li><a href="manage-orders.php">Переглянути замовлення</a></li>
                    <li><a href="manage-users.php">Керувати користувачами</a></li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>


