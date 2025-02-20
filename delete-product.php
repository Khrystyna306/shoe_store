<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Перевіряємо, чи переданий параметр ID товару
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Отримуємо шлях до зображення, щоб видалити його з сервера
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Видаляємо файл зображення, якщо він існує
        if (!empty($product['image']) && file_exists($product['image'])) {
            unlink($product['image']);
        }

        // Видаляємо товар з бази даних
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        $_SESSION['success_message'] = "Товар успішно видалено.";
    } else {
        $_SESSION['error_message'] = "Товар не знайдено.";
    }
} else {
    $_SESSION['error_message'] = "Невірний ID товару.";
}

// Повертаємося на сторінку керування товарами
header("Location: manage-products.php");
exit;
?>
