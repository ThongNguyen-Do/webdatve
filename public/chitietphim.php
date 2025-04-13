<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}


if (isset($_GET['phim_id'])) {
    $phim_id = $_GET['phim_id'];

    
    $sql = "SELECT * FROM movies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $phim_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
    } else {
        $movie = null;
    }

    $stmt->close();
} else {
    $movie = null;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['date'])) {
    $selected_date = $_POST['date'];

    
    if ($movie) {
        $sql_insert = "INSERT INTO bookings (phim_id, date) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("is", $phim_id, $selected_date);
        
        if ($stmt->execute()) {
            $message = "Đặt vé thành công cho phim: " . htmlspecialchars($movie['title']) . " vào ngày " . htmlspecialchars($selected_date);
        } else {
            $message = "Có lỗi xảy ra khi đặt vé.";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Phim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #141E30, #243B55);
            margin: 0;
            padding: 0;
        }

        .container {
    width: 80%;
    margin: 20px auto;
    padding: 30px;
    background-color: #1e2a38; 
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    color: #f5f5f5; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h1 {
    color: #FFD700; 
    font-size: 2rem;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

.movie-details {
    font-size: 18px;
    margin-top: 20px;
    line-height: 1.6;
}

.movie-details p {
    margin: 10px 0;
}

.movie-details img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin-top: 15px;
    border: 2px solid #444;
}

.not-found {
    text-align: center;
    color: #ff6b6b;
}

footer {
    text-align: center;
    margin-top: 40px;
    font-size: 14px;
    color: #aaa;
}

.booking-form {
    margin-top: 30px;
    text-align: center;
}

.booking-form input[type="date"] {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: none;
    background-color: #2e3c4d;
    color: #f5f5f5;
    margin-bottom: 20px;
}

.booking-form input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

.booking-form button {
    padding: 12px 20px;
    background-color: #FF6347;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

.booking-form button:hover {
    background-color: #e5533d;
}

.success-message {
    color: #4CAF50;
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
    background-color: #1b3b1b;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #4CAF50;
}
.logout {
    display: inline-block;
    margin-top: 30px;
    padding: 10px 20px;
    background-color: #34495e;
    color:  #f5f5f5;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.logout:hover {
    background-color: #2c3e50;
}

    </style>
</head>
<body>

<div class="container">
    <?php if ($movie): ?>
        <h1>Chi Tiết Phim: <?php echo htmlspecialchars($movie['title']); ?></h1>
        <div class="movie-details">
            <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>
            <p><strong>Ngày phát hành:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
            <img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="Poster of <?= htmlspecialchars($movie['title']) ?>">
        </div>

        <!-- Form đặt vé -->
        <div class="booking-form">
            <form method="POST" action="">
                <label for="date">Chọn ngày xem phim:</label>
                <input type="date" id="date" name="date" required>
                <button type="submit">Đặt vé</button>
            </form>
        </div>

        <?php if (isset($message)): ?>
            <div class="success-message">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="not-found">
            <p>Không tìm thấy phim này.</p>
        </div>
    <?php endif; ?>
    <a href="home.php" class="logout">Quay lại trang chủ</a>
</div>



</body>
</html>
