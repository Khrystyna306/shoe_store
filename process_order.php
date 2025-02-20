<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Будь ласка, увійдіть у систему, щоб оформити замовлення.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Отримуємо дані з форми
$product_ids = $_POST['product_id'];
$product_names = $_POST['product_name'];
$prices = $_POST['price'];
$sizes = $_POST['size'];
$quantities = $_POST['quantity'];
$total_price = $_POST['total_price'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$nova_poshta_branch = $_POST['nova_poshta_branch'];
$payment_method = $_POST['payment_method'];

// Формуємо SQL для додавання замовлення в базу даних
$sql = "INSERT INTO orders (user_id, total_price, phone, city, nova_poshta_branch, payment_method)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $user_id,
    $total_price,
    $phone,
    $city,
    $nova_poshta_branch,
    $payment_method
]);

// Отримуємо ID нового замовлення
$order_id = $pdo->lastInsertId();

// Записуємо товари в таблицю order_items
foreach ($product_ids as $key => $product_id) {
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $order_id,
        $product_id,
        $quantities[$key],
        $prices[$key]
    ]);
}

// Оновлюємо кошик (видаляємо товари після оформлення)
foreach ($product_ids as $product_id) {
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id]);
}

echo "<p>Ваше замовлення успішно оформлено! 🚚 З вами зв'яжуться для підтвердження.</p>";
?>
