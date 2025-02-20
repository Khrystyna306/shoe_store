<?php
// Підключення до бази даних
$host = '127.0.0.1'; // або 'localhost'
$dbname = 'shoe_store';
$username = 'root'; // за умовчанням для XAMPP
$password = ''; // за умовчанням для XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
