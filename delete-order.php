<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = $_GET['id'];

    // Видаляємо товари, пов'язані з цим замовленням
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Видаляємо саме замовлення
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);

    $_SESSION['success_message'] = "Замовлення успішно видалено.";
} else {
    $_SESSION['error_message'] = "Невірний ID замовлення.";
}

header("Location: manage-orders.php");
exit;
?>
