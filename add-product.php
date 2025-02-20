<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Отримуємо список категорій
$stmt = $pdo->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка, чи надійшли всі необхідні дані
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category'] ?? '';
    $size = $_POST['size'] ?? '';  // Отримуємо розмір
    $image = $_FILES['image']['name'] ?? '';
    $quantity = $_POST['quantity'] ?? '';  // Отримуємо кількість товару

    if ($name && $description && $price && $category_id && $size && $image && $quantity) {
        // Завантажуємо зображення на сервер
        $target_dir = "images/";
        $new_image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_image_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Перевірка MIME типу файлу
        $imageMimeType = mime_content_type($_FILES['image']['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($imageMimeType, $allowedMimeTypes)) {
            echo "<p>Файл не є допустимим зображенням.</p>";
            exit;
        }

        // Перевірка чи файл завантажено без помилок
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo "<p>Помилка при завантаженні файлу: " . $_FILES['image']['error'] . "</p>";
            exit;
        }

        // Переміщення файлу в потрібну папку
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Додаємо товар до бази даних
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id, size, image, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $category_id, $size, $target_file, $quantity]);

            echo "<p>Товар успішно додано!</p>";
        } else {
            echo "<p>Сталася помилка при завантаженні зображення.</p>";
        }
    } else {
        echo "<p>Будь ласка, заповніть всі поля!</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати товар</title>
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
        <h1>Додати новий товар</h1>
        <form action="add-product.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="name">Назва товару:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label for="description">Опис товару:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div>
                <label for="price">Ціна товару:</label>
                <input type="number" id="price" name="price" required>
            </div>

            <div>
                <label for="category">Категорія товару:</label>
                <select id="category" name="category" required>
                    <option value="">Оберіть категорію</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="size">Розмір товару:</label>
                <input type="text" id="size" name="size" required>
            </div>

            <div>
                <label for="quantity">Кількість товару:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <div>
                <label for="image">Зображення товару:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div>
                <button type="submit">Додати товар</button>
            </div>
        </form>
    </main>
</body>
</html>
