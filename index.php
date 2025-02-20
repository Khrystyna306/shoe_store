<?php
include 'db.php'; // Підключаємо базу

// Отримуємо всі продукти та їх категорії
$sql = "SELECT p.id, p.product_name, p.description, p.price, p.image, p.size, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Отримуємо категорії для навігації
$sqlCategories = "SELECT id, name FROM categories";
$stmtCategories = $pdo->query($sqlCategories);
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин взуття</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
<div class="logo-container">
            <a href="#" class="logo-link">
                <img class="logo-img" src="https://thumbs.dreamstime.com/b/woman-shoe-logo-footwear-icon-lady-boots-shop-symbol-store-fashion-design-girl-boot-boutique-sign-high-heeled-shoes-brand-vector-231999392.jpg" alt="logo">
            </a>
        </div>
    <ul class="nav-list">
        <li><a class="nav-list-link" href="#home">Головна</a></li>
        <li><a class="nav-list-link" href="#all-shoes">Всі</a></li>
        <?php foreach ($categories as $category): ?>
            <li><a class="nav-list-link" href="#category-<?php echo $category['id']; ?>">
                <?php echo $category['name']; ?>
            </a></li>
        <?php endforeach; ?>
        <li><a class="nav-list-link" href="about.html">Про нас</a></li>
        <li>
    <a href="cart.php"> 
        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.5 13h13l2.5-9h-18"></path>
        </svg>
    </a>
</li>
<li>
            <form class="search-form" action="search.php" method="GET">
                <input type="text" name="query" class="search-input" placeholder="Пошук...">
                <button type="submit" class="search-button">🔍</button>
            </form>
        </li>
              <li><a href="orders.php">Мої замовлення</a></li>
        <li>
                <a href="admin_login.php"> 
                    <svg class="man-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M16 16c-1.6-1-3.4-1.5-4-1.5s-2.4.5-4 1.5c-1.6 1-3 2.7-3 4v1h14v-1c0-1.3-1.4-3-3-4z"></path>
                    </svg>
                    Увійти
                </a>
            </li>
          
    </ul>
</div>

<!-- Головна секція -->
<div class="hero-section" id="home">
    <div class="hero-content">
        <h1>Ласкаво просимо до магазину взуття</h1>
        <p>Стильне та якісне взуття для кожного!</p>
    </div>
</div>

<!-- Всі товари -->
<!-- Всі товари -->
<section id="all-shoes" class="shoes-section">
    <h2>Всі моделі взуття</h2>
    <div class="shoes-container">
        <?php foreach ($products as $product): ?>
            <div class="shoe-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
                <h3><?php echo $product['product_name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <p>Категорія: <?php echo $product['category_name']; ?></p>
                <div class="shoe-options">
                    <label>Розмір:</label>
                    <select class="size-select">
                        <?php foreach (explode(',', $product['size']) as $size): ?>
                            <option value="<?php echo trim($size); ?>"><?php echo trim($size); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="price">₴<?php echo $product['price']; ?></span>

                    <!-- Форма для покупки товару -->
                    <button class="add-to-cart-btn" 
                            data-id="<?php echo $product['id']; ?>">У кошик</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- Секції категорій -->
<?php foreach ($categories as $category): ?>
    <section id="category-<?php echo $category['id']; ?>" class="shoes-section">
        <h2><?php echo $category['name']; ?></h2>
        <div class="shoes-container">
            <?php foreach ($products as $product): ?>
                <?php if ($product['category_name'] == $category['name']): ?>
                    <div class="shoe-card">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
                        <h3><?php echo $product['product_name']; ?></h3>
                        <p><?php echo $product['description']; ?></p>
                        <div class="shoe-options">
                            <label>Розмір:</label>
                            <select class="size-select">
                                <?php foreach (explode(',', $product['size']) as $size): ?>
                                    <option value="<?php echo trim($size); ?>"><?php echo trim($size); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="price">₴<?php echo $product['price']; ?></span>

                            <button class="add-to-cart-btn" data-id="<?php echo $product['id']; ?>">У кошик</button>

              
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
    
<?php endforeach; ?>
<section class="contact-info">
        <h2>Контакти</h2>
        <p><strong>Телефон:</strong> <a href="tel:+380935072615">+38 (093) 507-26-15</a></p>
        <p><strong>Email:</strong> <a href="hrystyna.nykolaychuk@kpk-lp.com.ua">hrystyna.nykolaychuk@kpk-lp.com.ua</a></p>
        <p><strong>Адреса:</strong> Київ, вул. Хрещатик, 15</p>
        <p><strong>Графік роботи:</strong> Пн-Нд, 10:00 - 20:00</p>
    </section>
   
<script src="script.js"></script>
</body>
</html>

