<?php
// Обробка реєстрації користувача
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Підключення до бази даних
    $conn = new mysqli("localhost", "root", "", "shoe_store");

    if ($conn->connect_error) {
        die("❌ Помилка підключення до бази даних: " . $conn->connect_error);
    }

    // Перевірка наявності email у базі
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "❌ Цей email вже зареєстрований! <br>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Хешування паролю
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "✅ Реєстрація успішна! Тепер ви можете увійти.";
            // Перенаправлення на головну сторінку після реєстрації
            header("Location: index.php");
            exit();
        } else {
            echo "❌ Помилка реєстрації: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація користувача</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Реєстрація користувача</h2>
        <form action="register_user.php" method="POST">
            <label for="name">Ім'я:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Зареєструватися</button>
        </form>
    </div>
</body>
</html>
