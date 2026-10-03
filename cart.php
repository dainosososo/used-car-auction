<?php
session_start();
require 'db.php'; // 載入資料庫連線

if (!isset($conn) || !$conn) {
    die("資料庫連線失敗，請檢查 db.php");
}

// 過濾購物車內已刪除的車輛
$_SESSION['cart'] = array_filter($_SESSION['cart'], function ($car) use ($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM car WHERE id = ?");
    $stmt->execute([$car['id']]);
    return $stmt->fetchColumn() > 0; // 如果車輛仍存在資料庫，則保留
});

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateTotal() {
            let checkboxes = document.querySelectorAll('.car-checkbox:checked');
            let total = 0;
            checkboxes.forEach(cb => {
                total += parseFloat(cb.dataset.price) || 0; // 防止 NaN
            });
            document.getElementById('totalPrice').innerText = total.toFixed(2) + ' 萬';
            document.getElementById('checkoutBtn').disabled = checkboxes.length === 0;
        }
    </script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .header {
            background: #E69A00; color: black; padding: 10px; text-align: left; font-size: 24px; font-weight: bold;
        }
        .container {
            width: 80%; margin: 30px auto; padding: 20px; background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px;
        }
        .car-card {
            display: flex; align-items: center; padding: 15px; border: 1px solid #ddd; border-radius: 5px;
            margin-bottom: 15px; background: #f9f9f9;
        }
        .car-image {
            width: 100px; height: 75px; object-fit: cover; margin-right: 15px;
        }
        button {
            width: 100%; background-color: #E69A00; color: white; padding: 14px;
            font-size: 16px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px;
        }
        button:hover { background-color: #D68900; }
        .footer {
            text-align: center; padding: 10px; background-color: #E69A00; color: black;
            position: fixed; bottom: 0; width: 100%;
        }
        a { color: #007bff; text-decoration: none; font-size: 14px; display: block; margin-top: 20px; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<!-- 頂部標題 -->
<div class="header">邠邠邠二手車拍賣網</div>
<div class="container">
    <h2 class="text-center">購物車</h2>

    <form action="checkout.php" method="post">
        <div class="car-list">
            <?php if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $car): ?>
                    <?php
                    // 確保 $car['id'] 存在並且是數字
                    if (!isset($car['id']) || !is_numeric($car['id'])) continue;

                    $stmt = $conn->prepare("SELECT image_path FROM car_images WHERE car_id = ? LIMIT 1");
                    $stmt->execute([$car['id']]);
                    $image = $stmt->fetch(PDO::FETCH_ASSOC);
                    $imagePath = $image ? $image['image_path'] : 'default_car.jpg';
                    ?>

                    <div class="car-card">
                        <input type="checkbox" name="selectedCars[]" value="<?= htmlspecialchars($car['id']) ?>" 
                               class="car-checkbox me-3" data-price="<?= htmlspecialchars($car['price']) ?>" onclick="updateTotal()">
                        
                        <img src="<?= htmlspecialchars($imagePath) ?>" class="car-image">
                        
                        <div>
                            <div class="car-title"><?= htmlspecialchars($car['brand'] . " " . $car['model'] . " " . $car['year']) ?></div>
                            <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                            <div class="car-price"><?= htmlspecialchars($car['price']) ?> 萬</div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p>購物車內沒有車輛。</p>
            <?php endif; ?>
        </div>

        <p class="text-center fw-bold" style="font-size: 18px;">總金額：<span id="totalPrice">0 萬</span></p>
        
        <div class="text-center">
            <button type="submit" id="checkoutBtn" class="btn btn-success" disabled>結帳</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="car_list.php" class="btn btn-primary">返回車輛列表</a>
    </div>
</div>

</body>
</html>
