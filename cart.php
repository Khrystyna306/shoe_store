<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Будь ласка, увійдіть у систему, щоб переглянути кошик.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, p.product_name, p.image, p.price, c.quantity, p.size, c.product_id
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обчислення загальної суми та кількості товарів у кошику
$totalPrice = 0;
$totalQuantity = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
    $totalQuantity += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Кошик</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="products-page">
    <div class="cart-container">
        <h2>Ваш кошик</h2>

        <?php if (count($cartItems) > 0): ?>
            <table>
                <tr>
                    <th>Зображення</th>
                    <th>Назва</th>
                    <th>Розмір</th>
                    <th>Кількість</th>
                    <th>Ціна</th>
                    <th>Дії</th>
                </tr>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['image']; ?>" width="50"></td>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['size']; ?></td>
                        <td>
                            <input type="number" value="<?php echo $item['quantity']; ?>" 
                                   class="update-quantity" data-id="<?php echo $item['id']; ?>" min="1">
                        </td>
                        <td>₴<?php echo $item['price'] * $item['quantity']; ?></td>
                        <td>
                            <button class="remove-btn" data-id="<?php echo $item['id']; ?>">Видалити</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <!-- Додано суму та кількість товарів -->
            <div class="cart-summary">
                <p>Загальна кількість товарів: <strong><?php echo $totalQuantity; ?></strong></p>
                <p>Загальна сума: <strong>₴<?php echo number_format($totalPrice, 2); ?></strong></p>
            </div>
           
            <?php if (count($cartItems) > 0): ?>
                <form action="checkout.php" method="POST">
                <?php foreach ($cartItems as $item): ?>
                    <input type="hidden" name="product_id[]" value="<?php echo $item['product_id']; ?>">
                    <input type="hidden" name="product_name[]" value="<?= $item['product_name']; ?>">
                    <input type="hidden" name="price[]" value="<?= $item['price']; ?>">
                    <input type="hidden" name="size[]" value="<?= $item['size']; ?>">
                    <input type="hidden" name="quantity[]" value="<?= $item['quantity']; ?>">
                <?php endforeach; ?>
                <input type="hidden" name="total_price" value="<?= $totalPrice; ?>">

                <button type="submit">Оформити замовлення</button>
            </form>

            <?php endif; ?>

        <?php else: ?>
            <p>Кошик порожній.</p>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
