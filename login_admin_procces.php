<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Підключення до бази даних
$conn = new mysqli("localhost", "root", "", "shoe_store");

if ($conn->connect_error) {
    die("❌ Помилка підключення до бази даних: " . $conn->connect_error);
}

// Обробка форми для входу
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);  // Видаляємо зайві пробіли з email
    $password = trim($_POST["password"]);  // Видаляємо зайві пробіли з пароля

    // Переконуємося, що дані передані
    if (empty($email) || empty($password)) {
        die("⛔ Всі поля обов'язкові!");
    }

    // Підготовка SQL-запиту для пошуку користувача за email
    $sql = "SELECT id, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("❌ Помилка підготовки запиту: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("❌ Помилка виконання запиту: " . $stmt->error);
    }

    // Перевірка, чи знайдений користувач
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Перевірка пароля
        if ($password === $user["password"]) {
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_email"] = $email;

            // Діагностика сесії
            var_dump($_SESSION); 

            // Перевірка ролі користувача
            if (isset($user["role"]) && $user["role"] === "admin") {
                echo "Адміністратор успішно увійшов. Перенаправляємо на адмін-панель...<br>";
                $_SESSION["admin_logged_in"] = true;
                header("Location: admin_panel.php");
                exit();
            } else {
                echo "Звичайний користувач. Перенаправляємо на головну сторінку...<br>";
                $_SESSION["user_logged_in"] = true;
                header("Location: index.php");
                exit();
            }
        } else {
            echo "❌ Невірний пароль!";
        }
    } else {
        echo "❌ Користувача не знайдено!";
    }

    $stmt->close();
}

$conn->close();
?>





