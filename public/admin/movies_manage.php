<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$notification = "";

// Xử lý thêm phim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO movies (title, description, image_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $image_url);
    if ($stmt->execute()) {
        $notification = "<div class='alert success'> Phim đã được thêm thành công!</div>";
    } else {
        $notification = "<div class='alert error'> Lỗi khi thêm phim!</div>";
    }
    $stmt->close();
}

// Xử lý xóa phim
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM movies WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $notification = "<div class='alert success'> Phim đã được xóa thành công!</div>";
    } else {
        $notification = "<div class='alert error'> Lỗi khi xóa phim!</div>";
    }
    $stmt->close();
}

// Xử lý sửa phim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_movie'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $sql = "UPDATE movies SET title = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $image_url, $id);
    if ($stmt->execute()) {
        $notification = "<div class='alert success'> Phim đã được cập nhật!</div>";
    } else {
        $notification = "<div class='alert error'> Lỗi khi cập nhật phim!</div>";
    }
    $stmt->close();
}

// Lấy danh sách phim
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phim</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #141E30, #243B55);
            color: #fff;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 36px;
        }

        .alert {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
        }

        .alert.success {
            background-color: #4CAF50;
            color: white;
        }

        .alert.error {
            background-color: #f44336;
            color: white;
        }

        .movie-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 40px auto;
            max-width: 1200px;
        }

        .movie {
            background: white;
            color: #333;
            border-radius: 10px;
            width: 280px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .movie:hover {
            transform: scale(1.05);
        }

        .movie img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .movie h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .movie p {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .btn {
            background-color: #1e3c72;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            margin: 5px;
            display: inline-block;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #16305c;
        }

        .add-movie-btn {
            display: block;
            width: fit-content;
            margin: 30px auto;
            background-color: #1e3c72;
            padding: 12px 24px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .add-movie-btn:hover {
            background-color: #218838;
        }
        .logout {
    display: block;
    width: fit-content;
    margin: 10px auto;
    background-color: #dc3545;
    padding: 10px 20px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: background 0.3s ease;
}

.logout:hover {
    background-color: #c82333;
}


    </style>
</head>
<body>

<h1> Quản lý Phim</h1>

<!-- <?= $notification ?> chỉnh lại nếu muốn có thông báo -->


<a href="themphim.php" class="add-movie-btn"> Thêm Phim</a>



<div class="movie-container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="movie">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Poster của <?= htmlspecialchars($row['title']) ?>">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <a href="suaphim.php?id=<?= $row['id'] ?>" class="btn"> Sửa</a>
                <a href="movies_manage.php?delete_id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Bạn chắc chắn muốn xóa phim này?')"> Xóa</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">Không có phim nào!</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
