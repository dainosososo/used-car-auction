<?php
session_start();
require 'db.php'; // 連線資料庫

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('請先登入'); window.location.href='login.php';</script>";
    exit();
}

// 取得目前登入的使用者資訊
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, password FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 如果找不到用戶資料，導回登入頁
if (!$user) {
    echo "<script>alert('用戶不存在'); window.location.href='login.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人中心</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
        .container { max-width: 800px; background: white; padding: 20px; border-radius: 8px; box-shadow: 1px 1px 10px rgba(0,0,0,0.1); }
        .sidebar { background: #FFD700;width: 10%;height: 100vh;float: left;padding: 20px;box-sizing: border-box; }
        .sidebar a { color: black; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background: #555; }
        .content { margin-left: 220px; padding: 20px; }
        .user-info { font-size: 18px; margin-top: 20px; }
    </style>
</head>
<body>
    <!-- 頂部標題 -->
    <div class="header">邠邠邠二手車拍賣網</div>

    <!-- 左側導航菜單 -->
    <div class="sidebar">
        <b>功能</b>
        <a href="car_list.php">所有車輛</a>
        <a href="cart.php">購物車🛒</a>
        <a href="change_password.php">變更密碼</a>
    </div>

    <!-- 主要內容 -->
    <div class="content">
        <h2>個人中心</h2>
        <p>目前登入的帳號資訊：</p>

        <div class="container">
            <p class="user-info"><strong>帳號：</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p class="user-info"><strong>密碼：</strong> <?= htmlspecialchars($user['password']) ?></p>
        </div>
    </div>

</body>
</html>
