<?php
session_start();
require 'db.php'; // 載入資料庫連線

// 確保 ID 存在
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "無效的車輛 ID";
    exit();
}

$car_id = intval($_GET['id']);

// 查詢車輛資訊
$stmt = $conn->prepare("SELECT car.*, user.username FROM car JOIN user ON car.user_id = user.id WHERE car.id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    echo "找不到該車輛";
    exit();
}

// 查詢車輛圖片
$stmt = $conn->prepare("SELECT image_path FROM car_images WHERE car_id = ?");
$stmt->execute([$car_id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?> - 車輛詳情</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; }
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
        /* 左側導航欄 */
        .sidebar {
            background: #FFD700;width: 10%;height: 100vh;float: left;padding: 15px;box-sizing: border-box;
        }

        .sidebar a {
            display: block;color: black;font-size: 18px;padding: 8px 0;text-decoration: none;
        }
        /* 主要內容區域 */
        .content {
            width: 80%;float: left;padding: 15px;background: #F5F5F5;height: 100vh;box-sizing: border-box;
        }
        .car-detail { display: flex; gap: 20px; }
        .car-images { flex: 1; }
        .car-info { flex: 1; }
        .car-image { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; }
        .btn-back { margin-top: 20px; }
        /* 底部版權 */
        .footer {
            clear: both;background: #E69A00;color: black;text-align: center; height: 10px; position: fixed;bottom: 0;width: 100%;
        }
    </style>
</head>
<body>
    <!-- 頂部標題 -->
    <div class="header">邠邠邠二手車拍賣網</div>

    <!-- 左側導航菜單 -->
    <div class="sidebar">
        <a href="car_list.php">所有車輛</a>
        <a href="cart.php">購物車🛒</a>
    </div>

    <div class="content">
        <h2><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h2>

        <div class="car-detail">
            <!-- 車輛圖片區 -->
            <div class="car-images">
                <div id="carCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= htmlspecialchars($image['image_path']) ?>" class="car-image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>

            <!-- 車輛資訊區 -->
            <div class="car-info">
                <p><strong>品牌：</strong> <?= htmlspecialchars($car['brand']) ?></p>
                <p><strong>型號：</strong> <?= htmlspecialchars($car['model']) ?></p>
                <p><strong>年份：</strong> <?= htmlspecialchars($car['year']) ?> 年</p>
                <p><strong>價格：</strong> <span class="text-danger"><?= htmlspecialchars($car['price']) ?> 萬</span></p>
                <p><strong>賣家：</strong> <?= htmlspecialchars($car['username']) ?></p>
                <p><strong>描述：</strong></p>
                <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>

                <!-- 加入購物車按鈕 -->
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="cart_ids[]" value="<?= $car['id'] ?>">
                    <button type="submit" class="btn btn-success">加入購物車</button>
                </form>

                <!-- 返回按鈕 -->
                <a href="car_list.php" class="btn btn-secondary btn-back">返回列表</a>
            </div>
        </div>
    </div>
    <!-- 底部版權 -->
    <div class="footer">版權 邠邠邠.com</div>
</body>
</html>
