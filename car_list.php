<?php
session_start();
require 'db.php';

// 若未登入，導向登入
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// 取得登入者資訊
$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "";

// 查詢所有車輛 + 每車一張封面圖片 + 賣家名稱
$stmt = $conn->prepare("
    SELECT car.*, user.username AS seller_name,
           (SELECT image_path FROM car_images WHERE car_id = car.id LIMIT 1) AS cover_image
    FROM car
    JOIN user ON car.user_id = user.id
    ORDER BY car.created_at DESC
");
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>所有車輛</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .header { background: #E69A00; color: black; padding: 10px; font-size: 24px; font-weight: bold; position: relative; }
        .username { position: absolute; right: 120px; top: 15px; font-size: 16px; color: black; }
        .logout-btn { position: absolute; right: 10px; top: 10px; }
        .sidebar { background: #FFD700; width: 10%; height: 100vh; float: left; padding: 15px; }
        .sidebar a { display: block; color: black; font-size: 18px; padding: 8px 0; text-decoration: none; }
        .sidebar a:hover { background: #555; color: white; }
        .content { margin-left: 12%; padding: 20px; }
        .car-card { margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; background: #fff; }
        .car-card img { width: 100%; height: 200px; object-fit: cover; }
        .car-info { padding: 15px; }
        .car-info h5 { margin: 0 0 10px; }
        .price { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        邠邠邠二手車拍賣網
        <span class="username">歡迎，<?= htmlspecialchars($username) ?></span>
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">登出</a>
    </div>

    <div class="sidebar">
        <a href="car_list.php">所有車輛</a>
        <a href="upload_car.php">上架車輛</a>
        <a href="cart.php">購物車 🛒</a>
        <a href="profile.php">個人中心</a>
    </div>

    <div class="content">
        <h2>所有上架的車輛</h2>
        <div class="row">
            <?php if (count($cars) === 0): ?>
                <p>目前沒有車輛上架。</p>
            <?php endif; ?>
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4">
                    <div class="card car-card">
                        <?php if (!empty($car['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($car['cover_image']) ?>" alt="Car Image">
                        <?php else: ?>
                            <img src="uploads/default_car.png" alt="No Image">
                        <?php endif; ?>
                        <div class="car-info">
                            <h5><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h5>
                            <p>年份：<?= htmlspecialchars($car['year']) ?></p>
                            <p>價格：<span class="price"><?= htmlspecialchars($car['price']) ?> 萬</span></p>
                            <p>賣家：<?= htmlspecialchars($car['seller_name']) ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="car_detailcase.php?id=<?= $car['id'] ?>" class="btn btn-primary btn-sm">查看詳情</a>
                                <a href="chat.php?car_id=<?= $car['id'] ?>" class="btn btn-info btn-sm">聯絡賣家</a>
                                <form action="cart_add.php" method="post" style="display:inline;">
                                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">加入購物車</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
