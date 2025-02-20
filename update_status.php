<?php
session_start();
require_once 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Доступ заборонений!";
    exit;
}

if (isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Оновлення статусу замовлення
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

    // Перенаправляємо назад на сторінку управління замовленнями
    header("Location: manage-order.php");
    exit;
} else {
    echo "Помилка: Невірні дані.";
}
?>
