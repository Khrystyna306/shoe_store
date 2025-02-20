<?php
include 'db.php'; // Підключення до бази даних

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $sql = "SELECT id, product_name, description, price, image FROM products WHERE product_name LIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результати пошуку</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Результати пошуку для "<?php echo htmlspecialchars($query); ?>"</h1>
        
        <?php if (count($products) > 0): ?>
            <div class="shoes-container">
                <?php foreach ($products as $product): ?>
                    <div class="shoe-card">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <span class="price">₴<?php echo $product['price']; ?></span>
                        <button class="add-to-cart-btn" data-id="<?php echo $product['id']; ?>">У кошик</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Нічого не знайдено.</p>
        <?php endif; ?>
    </div>
</body>
</html>
