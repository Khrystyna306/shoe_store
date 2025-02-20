<?php
session_start();
include 'db.php'; // Підключення до бази даних

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["cartCount" => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(["cartCount" => $result['cart_count']]);
?>
