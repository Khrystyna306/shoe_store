<?php
session_start();
include 'db.php'; // Ваш файл для підключення до бази даних

// Перевірка, чи користувач увійшов
if (!isset($_SESSION['user_id'])) {
    echo "0"; // Якщо користувач не увійшов, кошик порожній
    exit();
}

$user_id = $_SESSION['user_id'];

// Отримуємо кількість товарів у кошику
$sql = "SELECT COUNT(*) FROM cart WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$count = $stmt->fetchColumn();

echo $count; // Повертаємо кількість товарів у кошику
?>

