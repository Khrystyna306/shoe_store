<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Користувач не авторизований"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;

if (!$product_id || !$size) {
    echo json_encode(["success" => false, "message" => "Невірні дані"]);
    exit();
}

// Перевірка, чи є товар цього розміру в кошику
$sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $product_id, $size]);
$existingCartItem = $stmt->fetch();

if ($existingCartItem) {
    // Якщо товар вже є в кошику з тим самим розміром, оновлюємо кількість
    $newQuantity = $existingCartItem['quantity'] + 1;
    $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newQuantity, $existingCartItem['id']]);
} else {
    // Якщо такого товару ще немає, додаємо його в кошик з кількістю 1
    $sql = "INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id, $size]);
}

echo json_encode(["success" => true]);
?>
