<!-- order_success.php -->
<?php
include 'db.php';
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    echo "Помилка: відсутній ID замовлення.";
    exit;
}

// Отримання інформації про замовлення
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Помилка: замовлення не знайдено.";
    exit;
}
?>
<h1>Ваше замовлення успішно оформлено!</h1>
<p>Номер замовлення: <?php echo $order['id']; ?></p>
<p>Дата створення: <?php echo $order['created_at']; ?></p>
<!-- Додаткові деталі замовлення -->
