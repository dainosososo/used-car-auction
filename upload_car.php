<?php
session_start();

//車輛上架的 HTML 表單，使用者填寫後會提交到 upload_car_process.php

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<!----------------------------------------------------------------------------->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>車輛上架</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        
        /* 頂部標題 */
        .header {
            background: #E69A00;color: black;padding: 10px;text-align: left;font-size: 24px;font-weight: bold;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="number"], input[type="tel"], input[type="file"], textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="tel"]:focus, textarea:focus {
            border-color: #E69A00;
            outline: none;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        button {
            width: 100%;
            background-color: #E69A00;
            color: white;
            padding: 14px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #D68900;
        }

        .footer {
            text-align: center;
            padding: 5px;
            background-color: #E69A00;
            color: black;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
<!-------------------------------------------------------------------------->
</head>
<body>
    <!-- 頂部標題 -->
    <div class="header">邠邠邠二手車拍賣網</div>
    <!-- 頁首 -->
    <div class="header">
        <h2>上架二手車</h2>
    </div>

    <!-- 表單容器 -->
    <div class="container">
        <form action="upload_car_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="brand">品牌：</label>
                <input type="text" id="brand" name="brand" required>
            </div>

            <div class="form-group">
                <label for="model">型號：</label>
                <input type="text" id="model" name="model" required>
            </div>

            <div class="form-group">
                <label for="year">年份：</label>
                <input type="number" id="year" name="year" required>
            </div>

            <div class="form-group">
                <label for="price">價格：</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="description">描述：</label>
                <textarea id="description" name="description" placeholder="輸入車輛描述"></textarea>
            </div>

            <div class="form-group">
                <label for="phone">聯絡電話：</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder="請輸入 10 位數字" required>
            </div>

            <div class="form-group">
                <label for="images">車輛圖片（可選多張）：</label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple>
            </div>

            <button type="submit">上架</button>
        </form>

        <a href="car_list.php">查看已上架車輛</a>
    </div>

    <!-- 底部版權 -->
    <div class="footer">
        <p>&copy; 2025 車輛上架系統</p>
    </div>
</body>
</html>
