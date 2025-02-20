<?php
session_start();
include 'db.php';

$cart_id = $_POST['cart_id'];
$quantity = $_POST['quantity'];

$sql = "UPDATE cart SET quantity = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$quantity, $cart_id]);
?>
