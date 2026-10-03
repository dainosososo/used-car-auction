<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $year = $_POST["year"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $phone = $_POST["phone"]; // 新增聯絡電話欄位

    try {
        // 先插入車輛基本資料
        $stmt = $conn->prepare("INSERT INTO car (user_id, brand, model, year, price, description, phone, created_at) VALUES (:user_id, :brand, :model, :year, :price, :description, :phone, NOW())");
        $stmt->execute([
            "user_id" => $user_id,
            "brand" => $brand,
            "model" => $model,
            "year" => $year,
            "price" => $price,
            "phone" => $phone,
            "description" => $description
        ]);

        // 獲取剛插入的車輛 ID
        $car_id = $conn->lastInsertId();

        // 處理多張圖片上傳
        if (!empty($_FILES['images']['name'][0])) {
            $target_dir = "uploads/";
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $image_name = time() . "_" . basename($_FILES["images"]["name"][$key]);
                $target_file = $target_dir . $image_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // 確保是圖片檔案
                $check = getimagesize($tmp_name);
                if ($check !== false) {
                    if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            // 存入 car_images 資料表
                            $stmt = $conn->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
                            $stmt->execute([$car_id, $target_file]);
                        }
                    }
                }
            }
        }

        echo "<script>alert('車輛上架成功！'); window.location.href='car_list.php';</script>";
    } catch (PDOException $e) {
        die("上架失敗：" . $e->getMessage());
    }
} else {
    header("Location: upload_car.php");
    exit();
}
?>
