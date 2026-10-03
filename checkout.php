<?php
session_start();
require 'db.php'; // 資料庫連線設定

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['selectedCars'])) {
    $selectedCars = $_POST['selectedCars'];
    $placeholders = implode(',', array_fill(0, count($selectedCars), '?'));

    // 查詢選擇的車輛
    $stmt = $conn->prepare("SELECT * FROM car WHERE id IN ($placeholders)");
    $stmt->execute($selectedCars);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPrice = array_sum(array_column($cars, 'price'));

    // 從資料庫刪除已結帳的車輛
    $stmt = $conn->prepare("DELETE FROM car WHERE id IN ($placeholders)");
    $stmt->execute($selectedCars);

    // 2. 從資料庫刪除已結帳的車輛 (或可改為標記已售出)
    $stmt = $conn->prepare("DELETE FROM car WHERE id IN ($placeholders)");
    $stmt->execute($selectedCars);

} else {
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認結帳</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showCheckoutSuccess() {
            var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            checkoutModal.show();
        }
    </script>
    <style>
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }
    </style>
</head>
<body>
<!-- 頂部標題 -->
<div class="header">邠邠邠二手車拍賣網</div>
<div class="container">
    <h2 class="text-center mt-3">確認結帳</h2>

    <ul class="list-group">
        <?php foreach ($cars as $car): ?>
            <li class="list-group-item">
                <?= htmlspecialchars($car['brand'] . " " . $car['model'] . " " . $car['year']) ?> - 
                <?= htmlspecialchars($car['price']) ?> 萬
            </li>
        <?php endforeach; ?>
    </ul>

    <p class="text-center mt-3" style="font-size: 20px; font-weight: bold;">總金額：<?= $totalPrice ?> 萬</p>

    <div class="text-center mt-3">
        <a href="cart.php" class="btn btn-secondary">取消</a>
        <button class="btn btn-success" onclick="showCheckoutSuccess()">進行結帳</button>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">結帳成功</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>您的結帳已成功！</p>
            </div>
            <div class="modal-footer">
                <a href="car_list.php" class="btn btn-primary">返回首頁</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
