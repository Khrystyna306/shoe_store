<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Підключення до бази даних
require_once 'db.php';

// Перевірка, чи передано ID товару для редагування
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Отримуємо дані товару з бази
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo "<p>Товар не знайдено.</p>";
        exit;
    }

    // Обробка форми редагування
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category'] ?? '';
        $size = $_POST['size'] ?? '';
        $image = $_FILES['image']['name'] ?? '';

        if ($name && $description && $price && $category_id && $size) {
            // Обробка завантаження нового зображення
            if ($image) {
                $target_dir = "images/";
                $new_image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $new_image_name;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image_path = $target_file;
            } else {
                // Якщо зображення не завантажене, використовуємо старе
                $image_path = $product['image'];
            }

            // Оновлення даних товару в базі
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, size = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $category_id, $size, $image_path, $product_id]);

            echo "<p>Товар успішно оновлено!</p>";
        } else {
            echo "<p>Будь ласка, заповніть всі поля!</p>";
        }
    }
} else {
    echo "<p>ID товару не передано.</p>";
    exit;
}

// Отримуємо список категорій для випадаючого списку
$stmt = $pdo->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f1e1;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4e3b31;
        }
        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #d1b89e;
            border-radius: 4px;
        }
        button {
            display: block;
            width: 100%;
            background-color: #4e3b31;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #6a4e3b;
        }
        img {
            display: block;
            margin-top: 10px;
            max-width: 100px;
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати товар</title>
</head>
<body>
    <h1>Редагувати товар</h1>
    <form action="edit-product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Назва товару:</label>
            <input type="text" id="product_name" product_name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div>
            <label for="description">Опис товару:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div>
            <label for="price">Ціна товару:</label>
            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>
        </div>
        <div>
            <label for="category">Категорія товару:</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="size">Розмір товару:</label>
            <input type="text" id="size" name="size" value="<?php echo $product['size']; ?>" required>
        </div>
        <div>
            <label for="image">Зображення товару:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <img src="<?php echo $product['image']; ?>" alt="Image" width="100">
        </div>
        <div>
            <button type="submit">Оновити товар</button>
        </div>
    </form>
</body>
</html>
