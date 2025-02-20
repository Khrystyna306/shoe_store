<?php
session_start();
require_once('db.php'); // Підключення до бази даних

// Перевірка, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Отримання замовлень користувача з бази даних
$sql = "SELECT o.id AS order_id, o.total_price, o.status, o.phone, o.city, o.nova_poshta_branch, o.payment_method, o.created_at
        FROM orders o
        WHERE o.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<style>
        /* Загальні стилі */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f1e1; /* Світло-бежевий фон */
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Стилі заголовка */
        h1 {
            text-align: center;
            color: #4e3b31; /* Темно-бежевий колір для заголовку */
            padding: 20px 0;
            margin: 0;
            font-size: 2em;
        }

        /* Таблиця */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Заголовки таблиці */
        th {
            background-color: #f0e1c1; /* Світло-бежевий фон для заголовків */
            color: #4e3b31;
            padding: 12px;
            font-size: 1.1em;
            text-align: left;
            border-bottom: 2px solid #d1b89e;
        }

        /* Клітинки таблиці */
        td {
            background-color: #fff;
            padding: 12px;
            border-bottom: 1px solid #d1b89e;
            text-align: left;
        }

        /* Стилі для непарних рядків */
        tr:nth-child(odd) td {
            background-color: #faf3e0; /* Легкий бежевий фон для непарних рядків */
        }

        /* Стилі для парних рядків */
        tr:nth-child(even) td {
            background-color: #fff; /* Білий фон для парних рядків */
        }

        /* Стиль для кнопки "Додати в кошик" */
        button {
            background-color: #4e3b31; /* Темно-бежевий фон */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        /* Зміна кольору кнопки при наведенні */
        button:hover {
            background-color: #6a4e3b; /* Світліший відтінок для ефекту наведеного стану */
        }

        /* Стиль для посилання */
        a {
            color: #4e3b31;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Стилі для повідомлень */
        .alert {
            color: #d9534f; /* Червоний колір для помилок */
            text-align: center;
            font-size: 1.2em;
            margin: 20px 0;
        }

        /* Загальний стиль для таблиці замовлень */
        .table-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мої замовлення</title>
</head>
<body>
    <h1>Мої замовлення</h1>
    <?php if ($orders): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Номер замовлення</th>
                    <th>Дата створення</th>
                    <th>Сума</th>
                    <th>Статус</th>
                    <th>Телефон</th>
                    <th>Місто</th>
                    <th>Філія Нової Пошти</th>
                    <th>Метод оплати</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['created_at']; ?></td>
                        <td><?php echo '₴' . number_format($order['total_price'], 2); ?></td>
                        <td><?php echo ucfirst($order['status']); ?></td>
                        <td><?php echo $order['phone']; ?></td>
                        <td><?php echo $order['city']; ?></td>
                        <td><?php echo $order['nova_poshta_branch']; ?></td>
                        <td><?php echo $order['payment_method']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас немає замовлень.</p>
    <?php endif; ?>
</body>
</html>
