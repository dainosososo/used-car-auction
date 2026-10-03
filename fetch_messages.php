<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo "未登入";
    exit();
}

$user_id = $_SESSION["user_id"];
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;

if ($car_id === 0 || $seller_id === 0) {
    http_response_code(400);
    echo "參數錯誤";
    exit();
}

$stmt = $conn->prepare("SELECT messages.*, user.username FROM messages JOIN user ON messages.sender_id = user.id WHERE car_id = ? AND ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)) ORDER BY created_at ASC");
$stmt->execute([$car_id, $user_id, $seller_id, $seller_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);
