<?php
require 'db.php'; // 確保資料庫連線

// 確保 testuser 存在
$seller_username = 'testuser';
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->execute([$seller_username]);
$seller = $stmt->fetch();
if (!$seller) {
    die("賣家帳號 'testuser' 不存在，請先建立該用戶！");
}
$user_id = $seller['id'];

// 定義測試車輛品牌與型號
$test_cars = [
    'Ford 福特' => ['Escape', 'Focus', 'Mondeo'],
    'Honda 本田' => ['Fit', 'Accord', 'Civic'],
    'Toyota 豐田' => ['Altis', 'Camry', 'Yaris']
];

// 插入測試車輛資料
foreach ($test_cars as $brand => $models) {
    foreach ($models as $model) {
        $year = rand(2005, 2023);
        $price = rand(50000, 3000000);
        $description = "測試車輛 $brand $model，年份：$year，價格：$price 元";
        $stmt = $conn->prepare("INSERT INTO car (user_id, brand, model, year, price, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $brand, $model, $year, $price, $description]);
    }
}

echo "測試車輛插入成功！";
?>
