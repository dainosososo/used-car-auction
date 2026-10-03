<?php
session_start();
require 'db.php'; // 資料庫連線

// 如果使用者尚未登入，要求輸入帳號密碼
if (!isset($_SESSION['admin_login'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($username === "testuser" && $password === "123456") {
            $_SESSION['admin_login'] = true;
        } else {
            echo "<script>alert('並非網站擁有者'); window.location.href='car_list.php';</script>";
            exit();
        }
    } else {
        // 顯示登入表單
        ?>
        <!DOCTYPE html>
        <html lang="zh-TW">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>管理員登入</title>
        </head>
        <body>
            <h2>請輸入管理員帳號</h2>
            <form action="delete_car.php" method="post">
                <label>帳號：</label>
                <input type="text" name="username" required>
                <br><br>
                <label>密碼：</label>
                <input type="password" name="password" required>
                <br><br>
                <button type="submit">登入</button>
            </form>
        </body>
        </html>
        <?php
        exit();
    }
}

// 查詢所有車輛資訊
$stmt = $conn->prepare("SELECT * FROM car ORDER BY created_at DESC");
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 處理刪除車輛請求
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id'])) {
    $car_id = intval($_POST['car_id']);

    try {
        // 刪除與該車輛相關的圖片
        $stmt = $conn->prepare("DELETE FROM car_images WHERE car_id = ?");
        $stmt->execute([$car_id]);

        // 刪除車輛資訊
        $stmt = $conn->prepare("DELETE FROM car WHERE id = ?");
        $stmt->execute([$car_id]);

        echo "<script>alert('車輛刪除成功'); window.location.href='delete_car.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo "刪除失敗：" . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>刪除車輛資訊</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
        .car-list { display: flex; flex-wrap: wrap; gap: 20px; }
        .car-card {
            width: 100%; display: flex; align-items: center; background: white; padding: 10px;
            border-radius: 8px; box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
        }
        .car-image { width: 120px; height: 80px; object-fit: cover; border-radius: 5px; }
        .car-info { flex-grow: 1; padding-left: 10px; }
        .car-title { font-size: 16px; font-weight: bold; }
        .car-price { color: #E67E22; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <!-- 頂部標題 -->
    <div class="header">邠邠邠二手車拍賣網</div>
    <h2>管理員專區 - 刪除車輛資訊</h2>
    <p >選擇要刪除的車輛：</p>
    <a href="car_list.php">所有車輛</a>
    <div class="car-list">
        <?php foreach ($cars as $car): ?>
            <div class="car-card">
                <?php
                    $stmt = $conn->prepare("SELECT image_path FROM car_images WHERE car_id = ?");
                    $stmt->execute([$car['id']]);
                    $image = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>

                <img src="<?= htmlspecialchars($image['image_path'] ?? 'default.jpg') ?>" class="car-image">

                <div class="car-info">
                    <div class="car-title"><?= htmlspecialchars($car['brand'] . " " . $car['model'] . " " . $car['year']) ?></div>
                    <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                    <div class="car-price"><?= htmlspecialchars($car['price']) ?> 萬</div>
                </div>

                <form method="POST" action="delete_car.php">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                    <button type="submit" class="btn btn-danger">刪除</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
