<?php
header("Content-Type: application/json");
require_once "db.php"; // Підключення до бази даних

// Перевіряємо, чи переданий `cart_id`
if (!isset($_POST['cart_id'])) {
    echo json_encode(["success" => false, "message" => "Не передано ID товару"]);
    exit;
}

$cart_id = intval($_POST['cart_id']); // Приводимо до int для безпеки

// Видаляємо товар з кошика
$query = $pdo->prepare("DELETE FROM cart WHERE id = ?");
$result = $query->execute([$cart_id]);

if ($result) {
    echo json_encode(["success" => true, "message" => "Товар видалено"]);
} else {
    echo json_encode(["success" => false, "message" => "Помилка при видаленні товару"]);
}
exit;
?>
