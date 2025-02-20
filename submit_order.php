<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo "Помилка: користувач не авторизований.";
        exit;
    }

    // Перевірка наявності вибраного продукту
    $product_id = $_POST['product_id'] ?? null;
    if (!$product_id) {
        echo "Помилка: не вибрано продукт.";
        exit;
    }

    $phone = $_POST['phone'] ?? 'Немає телефону';
    $city = $_POST['city'] ?? 'Немає міста';
    $post_office = $_POST['post-office'] ?? 'Немає відділення';
    $payment = $_POST['payment'] ?? 'Невідомий метод';
    $size = $_POST['size'] ?? 'Невідомо';
    $quantity = $_POST['quantity'] ?? 1; // Кількість товару
    $price = $_POST['price'] ?? 0;

    try {
        // Початок транзакції
        $pdo->beginTransaction();

        // Вставка замовлення
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, phone, city, nova_poshta_branch, payment_method, created_at) VALUES (?, ?, 'pending', ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $price * $quantity, $phone, $city, $post_office, $payment]);
        $order_id = $pdo->lastInsertId();

        // Вставка товару в замовлення
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $price]);

        // Підтвердження транзакції
        $pdo->commit();

        header("Location: manage_orders.php");
        exit;
    } catch (PDOException $e) {
        // Відкат транзакції у разі помилки
        $pdo->rollBack();
        echo "Помилка: " . $e->getMessage();
    }
} else {
    header("Location: checkaut.html");
    exit;
}
?>







