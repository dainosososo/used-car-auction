<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

if ($car_id === 0) {
    echo "未提供車輛ID。";
    exit();
}

// 取得賣家資訊
$stmt = $conn->prepare("SELECT user.id AS seller_id, user.username AS seller_name, car.model FROM car JOIN user ON car.user_id = user.id WHERE car.id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    echo "找不到該車輛。";
    exit();
}

$seller_id = $car['seller_id'];

// 發送訊息
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, car_id, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $seller_id, $car_id, $message]);
    }
}

// 取得對話紀錄（雙向）
$stmt = $conn->prepare("SELECT messages.*, user.username FROM messages JOIN user ON messages.sender_id = user.id WHERE car_id = ? AND ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)) ORDER BY created_at ASC");
$stmt->execute([$car_id, $user_id, $seller_id, $seller_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>與 <?= htmlspecialchars($car['seller_name']) ?> 聊天</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box {
            max-width: 700px;
            margin: 30px auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #fefefe;
        }
        .chat-message {
            margin-bottom: 10px;
        }
        .chat-message.me {
            text-align: right;
            color: blue;
        }
        .chat-message.them {
            text-align: left;
            color: green;
        }
    </style>
</head>
<body>
<div class="chat-box">
    <h4>與 <?= htmlspecialchars($car['seller_name']) ?> 聊天 - [<?= htmlspecialchars($car['model']) ?>]</h4>
    <div class="chat-history mb-3">
        <?php foreach ($messages as $msg): ?>
            <div class="chat-message <?= $msg['sender_id'] === $user_id ? 'me' : 'them' ?>">
                <strong><?= htmlspecialchars($msg['username']) ?>:</strong> <?= nl2br(htmlspecialchars($msg['content'])) ?>
                <br><small><?= $msg['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="post" class="d-flex gap-2">
        <input type="text" name="message" class="form-control" placeholder="輸入訊息..." required>
        <button type="submit" class="btn btn-primary">送出</button>
    </form>
</div>
<script>
    function fetchMessages() {
        fetch(`fetch_messages.php?car_id=<?= $car_id ?>&seller_id=<?= $seller_id ?>`)
            .then(res => res.json())
            .then(data => {
                const container = document.querySelector('.chat-history');
                container.innerHTML = '';
                data.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = 'chat-message ' + (msg.sender_id == <?= $user_id ?> ? 'me' : 'them');
                    div.innerHTML = `<strong>${msg.username}:</strong> ${msg.content.replace(/\n/g, '<br>')}<br><small>${msg.created_at}</small>`;
                    container.appendChild(div);
                });
                container.scrollTop = container.scrollHeight;
            });
    }

    setInterval(fetchMessages, 3000); // 每3秒刷新一次訊息
    fetchMessages(); // 一進頁面就先跑一次
</script>

</body>
</html>
