<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Отримуємо список користувачів
$stmt = $pdo->query("SELECT id, name, email, created_at FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Видалення користувача
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header("Location: manage-users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Керування користувачами</title>
    <link rel="stylesheet" href="styles_adminpanel.css">
</head>
<body>
    <header>
        <nav>
            <ul>
            <li><a href="admin_panel.php">Головна</a></li>
                <li><a href="add-product.php">Додати товар</a></li>
                <li><a href="manage-products.php">Керувати товарами</a></li>
                <li><a href="manage-orders.php">Замовлення</a></li>
                <li><a href="manage-users.php">Користувачі</a></li>
                <li><a href="admin_logout.php">Вийти</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="dashboard">
            <h1>Керування користувачами</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ім'я користувача</th>
                        <th>Email</th>
                        <th>Дата реєстрації</th>
                        <th>Дія</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td>
                                <a href="manage-users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Ви впевнені?');">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>