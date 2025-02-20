<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_panel.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "🔍 Запит отримано.<br>";  // Додайте це для перевірки, чи спрацьовує форма
    $email = $_POST["email"];
    $password = $_POST["password"];

    echo "🔍 Вхідні дані: Email: $email, Пароль: $password <br>";
    

}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід для адміністратора</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h2>Вхід</h2>
        <form action="login_admin_procces.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Увійти</button>
            <div class="register-link">
                <p>Ще не зареєстровані? <a href="register_user.php">Зареєструватися</a></p>
            </div>
        </form>
    </div>
</body>
</html>
