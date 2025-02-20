<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Будь ласка, увійдіть у систему, щоб оформити замовлення.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Перевірка чи були передані дані кошика
if (!isset($_POST['product_id'])) {
    echo "<p>Немає товарів у кошику для оформлення.</p>";
    exit();
}

// Отримуємо передані дані про кошик
$product_ids = $_POST['product_id'];
$product_names = $_POST['product_name'];
$prices = $_POST['price'];
$sizes = $_POST['size'];
$quantities = $_POST['quantity'];
$total_price = $_POST['total_price'];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5e6cc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .checkout-container {
            background: #fff8e1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #8b5e3b;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #6d4c41;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #d2b48c;
            border-radius: 5px;
            background: #fff;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            background-color: #a67c52;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #8b5e3b;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Оформлення замовлення</h2>

        <form action="process_order.php" method="POST">
            <?php foreach ($product_ids as $key => $product_id): ?>
                <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
                <input type="hidden" name="product_name[]" value="<?= $product_names[$key]; ?>">
                <input type="hidden" name="price[]" value="<?= $prices[$key]; ?>">
                <input type="hidden" name="size[]" value="<?= $sizes[$key]; ?>">
                <input type="hidden" name="quantity[]" value="<?= $quantities[$key]; ?>">
            <?php endforeach; ?>

            <input type="hidden" name="total_price" value="<?= $total_price; ?>">

            <div>
                <label for="phone">Телефон</label>
                <input type="text" name="phone" required placeholder="Ваш телефон">
            </div>
            <div>
                <label for="city">Місто</label>
                <input type="text" name="city" required placeholder="Місто">
            </div>
            <div>
                <label for="nova_poshta_branch">Відділення Нової Пошти</label>
                <input type="text" name="nova_poshta_branch" required placeholder="Відділення Нової Пошти">
            </div>
            <div>
                <label for="payment_method">Спосіб оплати</label>
                <select name="payment_method" required>
                    <option value="Банківська карта">Банківська карта</option>
                    <option value="Готівка при отриманні">Готівка при отриманні</option>
                </select>
            </div>

            <button type="submit">Підтвердити замовлення</button>
        </form>
    </div>
</body>
</html>
