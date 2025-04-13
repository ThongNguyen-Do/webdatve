<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách phim
$sql = "SELECT * FROM movies"; // Giả sử bảng là `movies`
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Phim - Đặt Vé</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .movie-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }

        .movie {
            background: white;
            border-radius: 15px;
            width: 280px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .movie:hover {
            transform: translateY(-10px);
        }

        .movie img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .movie h3 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .movie p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            text-align: justify;
        }

        .btn {
            background-color: #FF6347;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            transition: background 0.3s ease;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #FF4500;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 30px;
            background: #ccc;
            padding: 6px 14px;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
        }

        .logout:hover {
            background: #999;
        }
    </style>
</head>
<body>
    <a href="dangxuat.php" class="logout">Đăng xuất</a>
    <h1>Danh sách phim hiện có</h1>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div style="text-align:center; margin-top: 20px;">
        <a href="admin/movies_manage.php" class="btn">Quản lý phim</a>
    </div>
<?php endif; ?>
    <div class="movie-container">
        <?php
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <div class="movie">
                <!-- Hình ảnh phim -->
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Poster of <?= htmlspecialchars($row['title']) ?>">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <a href="datve.php?phim_id=<?= $row['id'] ?>" class="btn">Đặt vé</a>
            </div>
        <?php
            endwhile;
        else:
            echo "<p>Không có phim nào!</p>";
        endif;

        $conn->close();
        ?>
    </div>
</body>
</html>
